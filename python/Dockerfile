FROM python:3.11-slim

# Set working directory
WORKDIR /app

# Install system dependencies for OpenCV
RUN apt-get update && apt-get install -y --no-install-recommends \
    libglib2.0-0 \
    libgl1-mesa-glx \
    libsm6 \
    libxext6 \
    libxrender1 \
    && rm -rf /var/lib/apt/lists/*

# Copy requirements and install Python dependencies
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy application code
COPY . .

# Pre-download DeepFace model weights during build to optimize RAM and speed at runtime
RUN python download_models.py

# Expose port
EXPOSE 8080

# Set environment variables
ENV TF_CPP_MIN_LOG_LEVEL=3
ENV TF_ENABLE_ONEDNN_OPTS=0

# Start with gunicorn, binding dynamically to the environment PORT
CMD gunicorn face_detect:app --bind 0.0.0.0:$PORT --timeout 120 --workers 1 --threads 2

