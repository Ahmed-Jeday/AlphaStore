import os
import shutil
import replicate
from flask import Blueprint, request, jsonify
from werkzeug.utils import secure_filename
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

# Create a Flask Blueprint
replicate_bp = Blueprint('replicate_bp', __name__)

# Configuration (matching app.py structure)
UPLOAD_FOLDER = os.path.join(os.getcwd(), "uploads")
OUTPUT_FOLDER = os.path.join(os.getcwd(), "outputs")
ALLOWED_EXT = {"png", "jpg", "jpeg", "webp"}

os.makedirs(UPLOAD_FOLDER, exist_ok=True)
os.makedirs(OUTPUT_FOLDER, exist_ok=True)

def allowed(filename):
    return "." in filename and filename.rsplit(".", 1)[1].lower() in ALLOWED_EXT

# Initialize Replicate client
replicate_token = os.getenv("REPLICATE_API_TOKEN")
if not replicate_token:
    replicate_token = os.getenv("API_r") # Fallback to API_r

replicate_model = os.getenv("REPLICATE_MODEL", "cuuupid/idm-vton")
replicate_client = replicate.Client(api_token=replicate_token)

@replicate_bp.route('/api/tryon-replicate', methods=['POST'])
def tryon_replicate():
    try:
        # 1. Get images from the frontend request (handling both JSON and Multipart)
        if request.is_json:
            person_image_url = request.json.get('person_image') 
            garment_image_url = request.json.get('garment_image')
            garment_description = request.json.get('description', "short sleeve t-shirt")
            
            if not person_image_url or not garment_image_url:
                return jsonify({"error": "Missing required images"}), 400
            
            # Replicate can take URLs directly
            input_data = {
                "crop": True,
                "steps": 30,
                "category": "upper_body",
                "garm_img": garment_image_url,
                "human_img": person_image_url,
                "garment_des": garment_description
            }
        else:
            # Handle multipart/form-data (matching current frontend)
            bg_file = request.files.get("bg_img")
            garm_file = request.files.get("garm_img")
            garment_description = request.form.get("garment_des", "Un vêtement")

            if not bg_file or not garm_file:
                return jsonify({"error": "Les deux images sont requises."}), 400
            
            if not allowed(bg_file.filename) or not allowed(garm_file.filename):
                return jsonify({"error": "Format non supporté."}), 400

            bg_path = os.path.join(UPLOAD_FOLDER, secure_filename(bg_file.filename))
            garm_path = os.path.join(UPLOAD_FOLDER, secure_filename(garm_file.filename))
            
            bg_file.save(bg_path)
            garm_file.save(garm_path)

            input_data = {
                "crop": True,
                "steps": 30,
                "category": "upper_body",
                "garm_img": open(garm_path, "rb"),
                "human_img": open(bg_path, "rb"),
                "garment_des": garment_description
            }

        # 2. Call the IDM-VTON model on Replicate
        print(f"[REPLICATE] Calling model {replicate_model} with description: {garment_description}")
        output = replicate_client.run(
            replicate_model,
            input=input_data
        )

        # 3. Handle output
        result_url = output[0] if isinstance(output, list) else output

        return jsonify({
            "status": "success",
            "result_image": result_url,
            "url": result_url # Keeping key name consistent with app.py's "url" if needed
        }), 200

    except Exception as e:
        print(f"[REPLICATE ERROR] {str(e)}")
        return jsonify({"error": str(e)}), 500
