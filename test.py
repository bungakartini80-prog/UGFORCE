import numpy as np
import cv2
from deepface import DeepFace

# Create dummy image
img = np.ones((480, 640, 3), dtype=np.uint8) * 128

try:
    res = DeepFace.extract_faces(img, detector_backend="opencv", enforce_detection=False)
    face = res[0]["face"]
    print(f"Type: {face.dtype}, Max: {face.max()}, Min: {face.min()}")
except Exception as e:
    print("Error:", e)
