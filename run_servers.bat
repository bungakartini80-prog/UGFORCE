@echo off
title UGFORCE Server Launcher
echo ====================================================
echo             UGFORCE SERVER LAUNCHER
echo ====================================================
echo.

echo [1/3] Starting Laravel Server (php artisan serve)...
start "Laravel Server (Port 8000)" cmd /k "php artisan serve"

echo [2/3] Starting Face Detection Server (Port 5001)...
start "Face Detection Server (Port 5001)" cmd /k "C:\Users\Administrator\AppData\Local\Programs\Python\Python311\python.exe python/face_detect.py"

echo [3/3] Starting Face Verification Server (Port 5002)...
start "Face Verification Server (Port 5002)" cmd /k "C:\Users\Administrator\AppData\Local\Programs\Python\Python311\python.exe python/face_verify.py"

echo.
echo All servers launched in separate windows!
echo - Laravel: http://127.0.0.1:8000
echo - Face Detection: http://127.0.0.1:5001
echo - Face Verification: http://127.0.0.1:5002
echo.
pause
