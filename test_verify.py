import numpy as np
import cv2
from deepface import DeepFace
import traceback

img = np.random.randint(0, 255, (480, 640, 3), dtype=np.uint8)
try:
    res1 = DeepFace.extract_faces(img, detector_backend="opencv", enforce_detection=False)
    face1_rgb = res1[0]["face"]
    face1_bgr = cv2.cvtColor((face1_rgb * 255).astype(np.uint8), cv2.COLOR_RGB2BGR)
    
    result = DeepFace.verify(
        img1_path=face1_bgr,
        img2_path=face1_bgr,
        model_name="ArcFace",
        detector_backend="skip",
        enforce_detection=False,
        distance_metric="cosine",
    )
    print("SUCCESS", result)
except Exception as e:
    traceback.print_exc()
