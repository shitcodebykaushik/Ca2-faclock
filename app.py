from flask import Flask, render_template, request, redirect, url_for, session
import os
import cv2
import numpy as np
from deepface import DeepFace
import sqlite3
import time
import hashlib

app = Flask(__name__)
app.secret_key = "faclock_secret"
DATABASE = "users.db"

# Initialize DB with Aadhaar and password
def init_db():
    conn = sqlite3.connect(DATABASE)
    cursor = conn.cursor()
    cursor.execute("""
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT,
            aadhaar TEXT,
            phone TEXT UNIQUE,
            password TEXT,
            face_blob BLOB
        )
    """)
    conn.commit()
    conn.close()

init_db()

# Hash password
def hash_password(password):
    return hashlib.sha256(password.encode()).hexdigest()

# Capture image from webcam
def capture_face_image():
    cap = cv2.VideoCapture(0)
    if not cap.isOpened():
        print("❌ Webcam not accessible.")
        return None

    print("📸 Capturing face in 3 seconds...")
    for i in range(3, 0, -1):
        print(f"{i}...")
        time.sleep(1)

    ret, frame = cap.read()
    cap.release()
    cv2.destroyAllWindows()

    if ret and frame is not None:
        print("✅ Face captured successfully.")
        return frame
    else:
        print("❌ Failed to capture image.")
        return None

# Convert image to binary blob
def image_to_blob(image):
    is_success, buffer = cv2.imencode(".jpg", image)
    return buffer.tobytes() if is_success else None

# Convert blob to image
def blob_to_image(blob):
    return cv2.imdecode(np.frombuffer(blob, np.uint8), cv2.IMREAD_COLOR)

@app.route('/')
def index():
    return redirect(url_for('login'))

@app.route('/signin', methods=['GET', 'POST'])
def signin():
    if request.method == 'POST':
        name = request.form['name']
        aadhaar = request.form['aadhaar']
        phone = request.form['phone']
        password = request.form['password']
        hashed_password = hash_password(password)

        face_img = capture_face_image()
        if face_img is None:
            return "❌ Face capture failed."

        face_blob = image_to_blob(face_img)
        if face_blob is None:
            return "❌ Failed to convert image."

        conn = sqlite3.connect(DATABASE)
        cursor = conn.cursor()
        try:
            cursor.execute("INSERT INTO users (name, aadhaar, phone, password, face_blob) VALUES (?, ?, ?, ?, ?)",
                           (name, aadhaar, phone, hashed_password, face_blob))
            conn.commit()
        except sqlite3.IntegrityError:
            return "❌ Phone number already exists."
        finally:
            conn.close()

        return redirect(url_for('login'))

    return render_template("signin.html")

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        login_type = request.form.get("login_type")

        if login_type == "manual":
            phone = request.form['phone']
            password = request.form['password']
            hashed_password = hash_password(password)

            conn = sqlite3.connect(DATABASE)
            cursor = conn.cursor()
            cursor.execute("SELECT name, aadhaar, phone FROM users WHERE phone=? AND password=?",
                           (phone, hashed_password))
            user = cursor.fetchone()
            conn.close()

            if user:
                session['user'] = {
                    'name': user[0],
                    'aadhaar': user[1],
                    'phone': user[2]
                }
                return redirect(url_for('home'))
            else:
                return "❌ Invalid phone or password."

        elif login_type == "face":
            login_img = capture_face_image()
            if login_img is None:
                return "❌ Face capture failed."

            conn = sqlite3.connect(DATABASE)
            cursor = conn.cursor()
            cursor.execute("SELECT name, aadhaar, phone, face_blob FROM users")
            users = cursor.fetchall()
            conn.close()

            for user in users:
                name, aadhaar, phone, face_blob = user
                stored_img = blob_to_image(face_blob)
                if stored_img is None:
                    continue

                try:
                    result = DeepFace.verify(
                        img1_path=login_img,
                        img2_path=stored_img,
                        model_name="SFace",
                        enforce_detection=True
                    )

                    if result["verified"] and result["distance"] < 0.5:
                        session['user'] = {
                            'name': name,
                            'aadhaar': aadhaar,
                            'phone': phone
                        }
                        print("✅ Face matched. Login successful.")
                        return redirect(url_for('home'))

                except Exception as e:
                    print(f"Verification error: {e}")

            return "❌ Face not matched. Try again."

    return render_template("login.html")

@app.route('/home')
def home():
    if 'user' in session:
        return render_template("home.html", user=session['user'])
    return redirect(url_for('login'))

@app.route('/logout')
def logout():
    session.pop('user', None)
    return redirect(url_for('login'))

if __name__ == '__main__':
    app.run(debug=True)
