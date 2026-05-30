import os

files = [
    r'c:\xampp3\htdocs\ugforce\ugforce\resources\views\dashboard\admin.blade.php',
    r'c:\xampp3\htdocs\ugforce\ugforce\resources\views\dashboard\student.blade.php',
    r'c:\xampp3\htdocs\ugforce\ugforce\resources\views\dashboard\lecturer.blade.php'
]

# 1. Update JS verification to be non-blocking
js_old = r"""                            // ── VERIFICATION FLOW ──
                            addLog('OPENCV: Wajah terdeteksi. Memverifikasi dengan database...', 'warn');
                            currentProgress = 50;
                            progressBar.style.width = '50%';
                            progressText.innerText = 'VERIFYING FACE: 50%';

                            const verifyRes = await fetch('http://127.0.0.1:5001/verify', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({
                                    img1: currentFrameB64,
                                    img2: registeredSignature
                                })
                            });
                            const verifyData = await verifyRes.json();

                            if (verifyData.verified) {
                                clearInterval(detectionInterval);
                                addLog(`SUCCESS: Wajah terverifikasi! Kemiripan: ${verifyData.similarity}%`, 'success');
                                currentProgress = 100;
                                progressBar.style.width = '100%';
                                progressText.innerText = 'VERIFYING FACE: 100%';
                                setTimeout(() => {
                                    triggerUnlock();
                                }, 500);
                            } else {
                                addLog('ERROR: Verifikasi biometrik gagal! Wajah tidak cocok.', 'error');
                                currentProgress = 0;
                                progressBar.style.width = '0%';
                                progressText.innerText = 'VERIFYING FACE: FAIL';
                            }"""

js_new = r"""                            // ── VERIFICATION FLOW ──
                            if (window.isVerifying) return; // Prevent blocking the detection loop
                            window.isVerifying = true;

                            addLog('OPENCV: Wajah terdeteksi. Memverifikasi dengan database...', 'warn');
                            currentProgress = 50;
                            progressBar.style.width = '50%';
                            progressText.innerText = 'VERIFYING FACE: 50%';

                            fetch('http://127.0.0.1:5001/verify', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({
                                    img1: currentFrameB64,
                                    img2: registeredSignature
                                })
                            }).then(res => res.json()).then(verifyData => {
                                if (verifyData.verified) {
                                    clearInterval(detectionInterval);
                                    addLog(`SUCCESS: Wajah terverifikasi! Kemiripan: ${verifyData.similarity}%`, 'success');
                                    currentProgress = 100;
                                    progressBar.style.width = '100%';
                                    progressText.innerText = 'VERIFYING FACE: 100%';
                                    setTimeout(() => {
                                        triggerUnlock();
                                    }, 500);
                                } else {
                                    addLog('ERROR: Verifikasi biometrik gagal! Wajah tidak cocok.', 'error');
                                    currentProgress = 0;
                                    progressBar.style.width = '0%';
                                    progressText.innerText = 'VERIFYING FACE: FAIL';
                                    setTimeout(() => { window.isVerifying = false; }, 1000);
                                }
                            }).catch(err => {
                                console.error(err);
                                window.isVerifying = false;
                            });"""

for file in files:
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()

    # Apply JS replacement
    content = content.replace(js_old, js_new)

    # Initialize window.isVerifying near detectionInterval declaration if not already there
    init_old = "let continuousDetections = 0;"
    init_new = "let continuousDetections = 0;\n        window.isVerifying = false;"
    if "window.isVerifying = false;" not in content:
        content = content.replace(init_old, init_new)

    with open(file, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Updated {file}")


# 2. Update face_server.py to use ArcFace
face_server = r'c:\xampp3\htdocs\ugforce\ugforce\python\face_server.py'
with open(face_server, 'r', encoding='utf-8') as f:
    fs_content = f.read()

python_old = r"""        # Run DeepFace.verify on the tightly cropped, aligned faces
        result = DeepFace.verify(
            img1_path=face1_bgr,
            img2_path=face2_bgr,
            model_name="Facenet512",
            detector_backend="skip",
            enforce_detection=False,
            distance_metric="cosine",
        )

        distance   = float(result.get("distance", 1.0))
        
        # Increase tolerance to 0.38 to handle different clothing, lighting, and expressions
        max_allowed_distance = 0.38"""

python_new = r"""        # Run DeepFace.verify on the tightly cropped, aligned faces
        result = DeepFace.verify(
            img1_path=face1_bgr,
            img2_path=face2_bgr,
            model_name="ArcFace",  # Changed to ArcFace for better pose & detail detection
            detector_backend="skip",
            enforce_detection=False,
            distance_metric="cosine",
        )

        distance   = float(result.get("distance", 1.0))
        
        # Use ArcFace with 0.68 max allowed distance for robust pose-invariant matching
        max_allowed_distance = 0.68"""

# Also update the embed function just in case
python_embed_old = r"""        embeddings = DeepFace.represent(
            img_path=img,
            model_name="Facenet512",
            detector_backend="retinaface",
            enforce_detection=False,
        )"""

python_embed_new = r"""        embeddings = DeepFace.represent(
            img_path=img,
            model_name="ArcFace",
            detector_backend="retinaface",
            enforce_detection=False,
        )"""

fs_content = fs_content.replace(python_old, python_new)
fs_content = fs_content.replace(python_embed_old, python_embed_new)

with open(face_server, 'w', encoding='utf-8') as f:
    f.write(fs_content)
print("Updated face_server.py")
