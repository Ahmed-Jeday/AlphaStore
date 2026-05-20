import os
import shutil
from flask import Flask, request, jsonify, send_from_directory, render_template
from flask_cors import CORS
from werkzeug.utils import secure_filename
from gradio_client import Client, handle_file

app = Flask(__name__)
CORS(app) # Enable CORS for all routes

from dotenv import load_dotenv
import os

# Load environment variables
load_dotenv()

# ── Configuration ──────────────────────────────────────────────
API_ID  = "yisol/IDM-VTON"
TOKEN   = os.getenv("API_key_1")
Token_1 = os.getenv("API_key_2")
# creation des dossiers
UPLOAD_FOLDER = os.path.join(os.getcwd(), "uploads")
OUTPUT_FOLDER = os.path.join(os.getcwd(), "outputs")
ALLOWED_EXT   = {"png", "jpg", "jpeg", "webp"}

os.makedirs(UPLOAD_FOLDER, exist_ok=True)
os.makedirs(OUTPUT_FOLDER, exist_ok=True)


def allowed(filename):
    return "." in filename and filename.rsplit(".", 1)[1].lower() in ALLOWED_EXT


@app.route("/")
def index():
    return render_template("index.html")


@app.route("/tryon", methods=["GET", "POST"])
def tryon():
    if request.method == "GET":
        return jsonify({"status": "Server is running", "message": "Use POST to send images"}), 200

    bg_file   = request.files.get("bg_img")
    garm_file = request.files.get("garm_img")

    if not bg_file or not garm_file:
        return jsonify({"error": "Les deux images sont requises."}), 400
    if not allowed(bg_file.filename) or not allowed(garm_file.filename):
        return jsonify({"error": "Format non supporté. Utilisez PNG, JPG ou WEBP."}), 400

    bg_path   = os.path.join(UPLOAD_FOLDER, secure_filename(bg_file.filename))
    garm_path = os.path.join(UPLOAD_FOLDER, secure_filename(garm_file.filename))
    bg_file.save(bg_path)
    garm_file.save(garm_path)

    garment_des     = request.form.get("garment_des", "Un vêtement")
    denoise_steps   = int(request.form.get("denoise_steps", 30))
    seed            = int(request.form.get("seed", 42))
    is_checked      = request.form.get("is_checked", "1") == "1"
    is_checked_crop = request.form.get("is_checked_crop", "0") == "1"

    try:
        print(f"[DEBUG] Initializing Gradio client for API: {API_ID}")
        client = Client(API_ID, token=TOKEN)
        print(f"[DEBUG] Client initialized. Calling predict with files: {bg_path}, {garm_path}")
        result = client.predict(
            dict={"background": handle_file(bg_path), "layers": [], "composite": None},
            garm_img=handle_file(garm_path),
            garment_des=garment_des,
            is_checked=is_checked,
            is_checked_crop=is_checked_crop,
            denoise_steps=denoise_steps,
            seed=seed,
            api_name="/tryon"
        )
        print(f"[DEBUG] Predict call successful. Result: {result}")
        temp_path, _ = result
        out_filename  = "output.jpg"
        out_path      = os.path.join(OUTPUT_FOLDER, out_filename)
        shutil.move(temp_path, out_path)

        return jsonify({
            "filename": out_filename,
            "url": f"http://{request.host}/output/{out_filename}"
        })
    
    except ValueError as e:
        error_msg = str(e)
        print(f"[ERROR] Hugging Face space error: {error_msg}")
        if "CONFIG_ERROR" in error_msg or "invalid state" in error_msg:
            return jsonify({
                "error": "Virtual try-on service is temporarily unavailable (CONFIG_ERROR on Hugging Face)",
                "status": "service_unavailable",
                "message": "The AI model is being repaired. Please try again in a few minutes.",
                "type": "HuggingFaceError"
            }), 503
        return jsonify({"error": error_msg, "type": type(e).__name__}), 500
    
    except Exception as e:
        error_msg = str(e)
        print(f"[ERROR] Unexpected exception: {error_msg}")
        import traceback
        traceback.print_exc()
        return jsonify({"error": error_msg, "type": type(e).__name__}), 500


@app.route("/output/<filename>")
def serve_output(filename):
    return send_from_directory(OUTPUT_FOLDER, filename)

# Register Replicate Blueprint
from replicate_service import replicate_bp
app.register_blueprint(replicate_bp)



if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)