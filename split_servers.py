import os
import shutil

base_dir = r"c:\xampp3\htdocs\ugforce\ugforce"
python_dir = os.path.join(base_dir, "python")
face_server_path = os.path.join(python_dir, "face_server.py")
detect_path = os.path.join(python_dir, "face_detect.py")
verify_path = os.path.join(python_dir, "face_verify.py")

with open(face_server_path, "r", encoding="utf-8") as f:
    content = f.read()

# detect server (port 5001)
detect_content = content.replace('port=5001', 'port=5001') # keep 5001
detect_content = detect_content.replace('Engine : DeepFace + RetinaFace + ArcFace', 'Engine : OpenCV Detect (Port 5001)')

# verify server (port 5002)
verify_content = content.replace('port=5001', 'port=5002')
verify_content = verify_content.replace('Engine : DeepFace + RetinaFace + ArcFace', 'Engine : DeepFace Verify (Port 5002)')

with open(detect_path, "w", encoding="utf-8") as f:
    f.write(detect_content)

with open(verify_path, "w", encoding="utf-8") as f:
    f.write(verify_content)

# Update blade files
blade_files = [
    r"resources\views\dashboard\admin.blade.php",
    r"resources\views\dashboard\student.blade.php",
    r"resources\views\dashboard\lecturer.blade.php",
    r"resources\views\auth\register.blade.php",
    r"resources\views\admin\profile.blade.php"
]

for bf in blade_files:
    p = os.path.join(base_dir, bf)
    if os.path.exists(p):
        with open(p, "r", encoding="utf-8") as f:
            b_content = f.read()
        
        # Replace only /verify and /embed ports
        b_content = b_content.replace(':5001/verify', ':5002/verify')
        b_content = b_content.replace(':5001/embed', ':5002/embed')
        
        with open(p, "w", encoding="utf-8") as f:
            f.write(b_content)

print("Split servers and updated blade files successfully.")
