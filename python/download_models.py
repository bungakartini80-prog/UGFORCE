import numpy as np
from deepface import DeepFace

print("--- Downloading ArcFace Model ---")
try:
    # Dummy represent call to trigger ArcFace weight download
    dummy_img = np.zeros((100, 100, 3), dtype=np.uint8)
    DeepFace.represent(img_path=dummy_img, model_name="ArcFace", detector_backend="opencv", enforce_detection=False)
    print("ArcFace Downloaded successfully!")
except Exception as e:
    print(f"Error downloading ArcFace: {e}")

print("--- Downloading Facenet Model ---")
try:
    # Dummy represent call to trigger Facenet weight download
    DeepFace.represent(img_path=dummy_img, model_name="Facenet", detector_backend="opencv", enforce_detection=False)
    print("Facenet Downloaded successfully!")
except Exception as e:
    print(f"Error downloading Facenet: {e}")
