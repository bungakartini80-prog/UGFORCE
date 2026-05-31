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
    {"model_name": "Facenet", "distance_metric": "cosine", "threshold": 0.40},
]

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

        DeepFace = get_deepface()

        # Menggunakan "opencv" untuk hasil kotak (bounding box) seluruh wajah yang super cepat.
        # Catatan: Jika fps turun/lag saat streaming, ganti "opencv" menjadi "ssd" atau "mediapipe"
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
            
            # Padding opsional 10% agar kotak sedikit lebih lebar dari wajah aslinya
            pad_w = int(fw * 0.1)
            pad_h = int(fh * 0.1)
            
            fx = max(0, fx - pad_w)
            fy = max(0, fy - pad_h)
            fw = min(w - fx, fw + (pad_w * 2))
            fh = min(h - fy, fh + (pad_h * 2))
            
            # Simulasi Loading 1-100% berdasarkan nilai confidence
            loading_progress = min(100, int(conf * 100))

            faces.append({
                "x": fx, "y": fy, "w": fw, "h": fh,
                "confidence": round(float(conf), 3),
                "loading_progress": loading_progress
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
    port = int(os.environ.get("PORT", 5001))
    print("=" * 60)
    print("  UGFORCE Face Detection Server")
    print(f"  Engine : OpenCV Detect + Facenet")
    print(f"  Port   : {port}")
    print("=" * 60)
    app.run(host="0.0.0.0", port=port, debug=False, threaded=True)
