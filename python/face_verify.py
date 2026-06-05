"""
UGFORCE Face Detection Server
Python + OpenCV + DeepFace (RetinaFace detector)
Port: 5001
"""

import base64
import io
import json
import logging
import os
import sys
import time
import cv2
import numpy as np
from flask import Flask, jsonify, request
from flask_cors import CORS
from PIL import Image

if sys.stdout.encoding != 'utf-8':
    sys.stdout.reconfigure(encoding='utf-8')
if sys.stderr.encoding != 'utf-8':
    sys.stderr.reconfigure(encoding='utf-8')

# ── Suppress TF/Keras noise ──────────────────────────────────────────────────
os.environ["TF_CPP_MIN_LOG_LEVEL"] = "3"
os.environ["TF_ENABLE_ONEDNN_OPTS"] = "0"
logging.getLogger("tensorflow").setLevel(logging.ERROR)
logging.getLogger("deepface").setLevel(logging.ERROR)

app = Flask(__name__)
# Mengizinkan frontend (index.html) yang dibuka langsung dari browser untuk mengakses API ini
CORS(app, origins="*") 

# ── Lazy-load DeepFace so first request triggers model download ───────────────
_deepface = None

def get_deepface():
    global _deepface
    if _deepface is None:
        from deepface import DeepFace
        _deepface = DeepFace
    return _deepface

STRICT_FACE_CONFIDENCE = 0.40
STRICT_VERIFIERS = [
    {"model_name": "ArcFace", "distance_metric": "cosine", "threshold": 0.65},
]

# Load Haar Cascade once globally for ultra-fast face detection
face_cascade = None
try:
    cascade_path = os.path.join(cv2.data.haarcascades, 'haarcascade_frontalface_default.xml')
    face_cascade = cv2.CascadeClassifier(cascade_path)
except Exception as e:
    print(f"Error loading Haar Cascade: {e}")

def b64_to_cv2(b64_string: str) -> np.ndarray:
    """Decode base64 image (data:image/jpeg;base64,... or raw) to BGR ndarray."""
    if "," in b64_string:
        b64_string = b64_string.split(",", 1)[1]
    img_bytes = base64.b64decode(b64_string)
    pil_img = Image.open(io.BytesIO(img_bytes)).convert("RGB")
    return cv2.cvtColor(np.array(pil_img), cv2.COLOR_RGB2BGR)

def normalize_face_rgb(face_rgb: np.ndarray) -> np.ndarray:
    face = np.asarray(face_rgb)
    if face.max() <= 1.0:
        face = face * 255
    return face.astype(np.uint8)

def extract_single_face(DeepFace, img: np.ndarray, label: str) -> tuple[np.ndarray, float]:
    faces = DeepFace.extract_faces(
        img_path=img,
        detector_backend="opencv",
        enforce_detection=True,
        align=True,
    )

    confident_faces = [
        face for face in faces
        if float(face.get("confidence", 0.0)) >= STRICT_FACE_CONFIDENCE
    ]

    if len(confident_faces) == 0:
        raise ValueError(f"{label}: wajah tidak terlihat cukup jelas")
    if len(confident_faces) > 1:
        raise ValueError(f"{label}: terdeteksi lebih dari satu wajah")

    confidence = float(confident_faces[0].get("confidence", 0.0))
    face_rgb = normalize_face_rgb(confident_faces[0]["face"])
    face_bgr = cv2.cvtColor(face_rgb, cv2.COLOR_RGB2BGR)
    return face_bgr, confidence

# ─────────────────────────────────────────────────────────────────────────────
# /detect  — detect face bounding box from a frame
# ─────────────────────────────────────────────────────────────────────────────
@app.route("/detect", methods=["POST"])
def detect():
    try:
        data = request.get_json(force=True)
        if not data or "image" not in data:
            return jsonify({"detected": False, "faces": [], "error": "No image"}), 400

        img = b64_to_cv2(data["image"])
        h, w = img.shape[:2]

        if face_cascade is not None and not face_cascade.empty():
            gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
            detected_faces = face_cascade.detectMultiScale(
                gray,
                scaleFactor=1.1,
                minNeighbors=5,
                minSize=(30, 30)
            )
            faces = []
            for (fx, fy, fw, fh) in detected_faces:
                pad_w = int(fw * 0.1)
                pad_h = int(fh * 0.1)
                
                fx_p = max(0, int(fx) - pad_w)
                fy_p = max(0, int(fy) - pad_h)
                fw_p = min(w - fx_p, int(fw) + (pad_w * 2))
                fh_p = min(h - fy_p, int(fh) + (pad_h * 2))

                faces.append({
                    "x": fx_p, "y": fy_p, "w": fw_p, "h": fh_p,
                    "confidence": 0.95,
                    "loading_progress": 95
                })
        else:
            DeepFace = get_deepface()
            results = DeepFace.extract_faces(
                img_path=img,
                detector_backend="opencv",  
                enforce_detection=False,
                align=False,
            )
            faces = []
            for r in results:
                area = r.get("facial_area", {})
                conf = r.get("confidence", 0.0)
                if conf < 0.5:
                    continue
                fx = int(area.get("x", 0))
                fy = int(area.get("y", 0))
                fw = int(area.get("w", 0))
                fh = int(area.get("h", 0))
                if fw < 20 or fh < 20:
                    continue
                pad_w = int(fw * 0.1)
                pad_h = int(fh * 0.1)
                fx_p = max(0, fx - pad_w)
                fy_p = max(0, fy - pad_h)
                fw_p = min(w - fx_p, fw + (pad_w * 2))
                fh_p = min(h - fy_p, fh + (pad_h * 2))
                faces.append({
                    "x": fx_p, "y": fy_p, "w": fw_p, "h": fh_p,
                    "confidence": round(float(conf), 3),
                    "loading_progress": min(100, int(conf * 100))
                })

        return jsonify({"detected": len(faces) > 0, "faces": faces})

    except Exception as e:
        return jsonify({"detected": False, "faces": [], "error": str(e)}), 500

# ─────────────────────────────────────────────────────────────────────────────
# /verify  — compare two face images
# ─────────────────────────────────────────────────────────────────────────────
@app.route("/verify", methods=["POST"])
def verify():
    try:
        data = request.get_json(force=True)
        if not data or "img1" not in data or "img2" not in data:
            return jsonify({"verified": False, "error": "Need img1 and img2"}), 400

        img1 = b64_to_cv2(data["img1"])
        img2 = b64_to_cv2(data["img2"])

        DeepFace = get_deepface()

        try:
            face1_bgr, webcam_confidence = extract_single_face(DeepFace, img1, "Kamera")
            face2_bgr, stored_confidence = extract_single_face(DeepFace, img2, "Data akun")
        except Exception as e:
            return jsonify({
                "verified": False,
                "distance": 1.0,
                "similarity": 0.0,
                "error": str(e),
            }), 200

        checks = []
        for verifier in STRICT_VERIFIERS:
            try:
                result = DeepFace.verify(
                    img1_path=face1_bgr,
                    img2_path=face2_bgr,
                    model_name=verifier["model_name"],
                    detector_backend="skip",
                    enforce_detection=False,
                    distance_metric=verifier["distance_metric"],
                )
                distance = float(result.get("distance", 1.0))
                threshold = float(verifier["threshold"])
                checks.append({
                    "model": verifier["model_name"],
                    "distance": round(distance, 4),
                    "threshold": threshold,
                    "passed": distance <= threshold,
                })
            except Exception as e:
                checks.append({
                    "model": verifier["model_name"],
                    "distance": 1.0,
                    "threshold": verifier["threshold"],
                    "passed": False,
                    "error": str(e),
                })

        verified = any(check["passed"] for check in checks)
        if verified:
            passed_scores = [
                max(0.0, 1.0 - (check["distance"] / check["threshold"]))
                for check in checks if check["passed"]
            ]
            best_passed_score = max(passed_scores) if passed_scores else 0.0
            similarity = round(85.0 + (best_passed_score * 15.0), 1)
        else:
            best_score = max(
                max(0.0, 1.0 - (check["distance"] / check["threshold"]))
                for check in checks
            )
            similarity = round(max(0.0, min(80.0, 80.0 * best_score)), 1)
        distance = min(check["distance"] for check in checks)

        return jsonify({
            "verified": verified,
            "distance": round(distance, 4),
            "similarity": similarity,
            "checks": checks,
            "confidence": {
                "webcam": round(webcam_confidence, 4),
                "stored": round(stored_confidence, 4),
            },
        })

    except Exception as e:
        return jsonify({"verified": False, "distance": 1.0, "similarity": 0.0, "error": str(e)}), 500

# ─────────────────────────────────────────────────────────────────────────────
# /embed  — get face embedding vector
# ─────────────────────────────────────────────────────────────────────────────
@app.route("/embed", methods=["POST"])
def embed():
    try:
        data = request.get_json(force=True)
        if not data or "image" not in data:
            return jsonify({"face_found": False, "error": "No image"}), 400

        img = b64_to_cv2(data["image"])
        DeepFace = get_deepface()

        embeddings = DeepFace.represent(img_path=img, model_name="ArcFace", detector_backend="opencv", enforce_detection=False)

        if not embeddings:
            return jsonify({"face_found": False, "embedding": []})

        emb = embeddings[0].get("embedding", [])
        return jsonify({"face_found": True, "embedding": emb})

    except Exception as e:
        return jsonify({"face_found": False, "embedding": [], "error": str(e)}), 500

@app.route("/health", methods=["GET"])
def health():
    return jsonify({"status": "ok", "engine": "DeepFace+RetinaFace+ArcFace+Facenet", "time": time.time()})

if __name__ == "__main__":
    print("=" * 60)
    print("  UGFORCE Face Detection Server")
    print("  Engine : OpenCV Detect (Port 5001) + Facenet")
    print("  Port   : 5001")
    print("=" * 60)
    app.run(host="0.0.0.0", port=5002, debug=False, threaded=True)
