from flask import Flask, request, jsonify, render_template_string
from flask_cors import CORS
import requests
import json
import urllib.parse

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

# ================== CONFIGURATION ==================
URL = "https://gemini.google.com/_/BardChatUi/data/assistant.lamda.BardFrontendService/StreamGenerate"

HEADERS = {
    "accept": "*/*",
    "content-type": "application/x-www-form-urlencoded;charset=UTF-8",
    "x-same-domain": "1",
    "user-agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36",
    "cookie": ""   # ← COLLE ICI TOUT LE COOKIE qui marche dans Thonny
}

# ================== TON CATALOGUE DE 100 PRODUITS ==================
# Remplace cette liste par tes 100 vrais produits
PRODUCTS = [
    {"id": 1, "name": "Smartphone Samsung Galaxy S25", "price": 899.99, "category": "Téléphonie", "description": "Écran 6.8\", 256Go, caméra 200MP"},
    {"id": 2, "name": "Ordinateur Portable Dell XPS 14", "price": 1499.99, "category": "Informatique", "description": "Intel Core Ultra, 16Go RAM, SSD 1To"},
    # ... Ajoute ici les 98 autres produits
]

# Convertir le catalogue en texte pour Gemini
CATALOG_TEXT = "Catalogue de produits disponible :\n" + "\n".join(
    [f"- {p['name']} | Prix: {p['price']} TND | Catégorie: {p['category']} | {p['description']}" for p in PRODUCTS]
)

# ================== FONCTIONS (identiques à ta version originale) ==================
def build_payload(prompt):
    inner = [
        [prompt, 0, None, None, None, None, 0],
        ["fr-TN"],                    # Changé en français Tunisie
        ["", "", "", None, None, None, None, None, None, ""],
        "", "", None, [0], 1, None, None, 1, 0,
        None, None, None, None, None, [[0]], 0
    ]
    outer = [None, json.dumps(inner)]
    return urllib.parse.urlencode({"f.req": json.dumps(outer)}) + "&"


def parse_response(text):
    text = text.replace(")]}'", "").strip()
    best = ""
    for line in text.splitlines():
        if "wrb.fr" not in line:
            continue
        try:
            data = json.loads(line)
        except:
            continue

        entries = []
        if isinstance(data, list):
            if data and data[0] == "wrb.fr":
                entries = [data]
            else:
                entries = [i for i in data if isinstance(i, list) and i and i[0] == "wrb.fr"]

        for entry in entries:
            try:
                inner = json.loads(entry[2])
                if isinstance(inner, list) and len(inner) > 4 and isinstance(inner[4], list):
                    for c in inner[4]:
                        if isinstance(c, list) and len(c) > 1 and isinstance(c[1], list):
                            txt = "".join([t for t in c[1] if isinstance(t, str)])
                            if len(txt) > len(best):
                                best = txt
            except:
                continue
    return best.strip()


def ask_gemini(user_message):
    try:
        # On injecte le catalogue dans chaque question pour que Gemini le connaisse bien
        full_prompt = f"""
{CATALOG_TEXT}

Tu es un assistant commercial intelligent pour un magasin en Tunisie.
Réponds toujours en français, de façon claire, polie et professionnelle.
Utilise uniquement les produits du catalogue ci-dessus.
Si le produit demandé n'existe pas, dis-le honnêtement.

Question de l'utilisateur : {user_message}
"""

        payload = build_payload(full_prompt)
        res = requests.post(URL, headers=HEADERS, data=payload, timeout=40)

        print(f"[DEBUG] Status Code: {res.status_code}")

        if res.status_code != 200:
            return f"[ERREUR {res.status_code}] Vérifie tes cookies ou l'endpoint."

        reply = parse_response(res.text)
        return reply if reply else "[Aucune réponse analysée]"

    except Exception as e:
        print(f"[ERREUR ask_gemini] {e}")
        return f"[Erreur serveur] {str(e)}"


# ================== INTERFACE WEB ==================
@app.route('/')
def home():
    html = """
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Chatbot Catalogue 100 Produits - Projet Académique</title>
        <style>
            body {font-family: Arial, sans-serif; background:#f4f6f9; margin:0; padding:20px;}
            #chat {max-width: 1000px; margin:auto; background:white; border-radius:15px; box-shadow:0 5px 25px rgba(0,0,0,0.15); overflow:hidden;}
            #header {background:#0d6efd; color:white; padding:20px; text-align:center;}
            #messages {height:70vh; overflow-y:auto; padding:20px;}
            .message {margin:15px 0; padding:14px 18px; border-radius:12px; max-width:85%;}
            .user {background:#0d6efd; color:white; margin-left:auto;}
            .bot {background:#e9ecef; color:black;}
            input {width:100%; padding:16px; font-size:16px; border:none; border-top:1px solid #ddd;}
            button {padding:16px 30px; background:#0d6efd; color:white; border:none; cursor:pointer;}
        </style>
    </head>
    <body>
        <div id="chat">
            <div id="header">
                <h1>🤖 Chatbot Catalogue - 100 Produits</h1>
                <p>Projet Académique | Posez vos questions sur les produits</p>
            </div>
            <div id="messages"></div>
            <div style="display:flex;">
                <input type="text" id="userInput" placeholder="Exemple : Quels sont les smartphones disponibles ? Ou Quel est le prix du Galaxy S25 ?" 
                       onkeypress="if(event.key==='Enter') sendMessage()">
                <button onclick="sendMessage()">Envoyer</button>
            </div>
        </div>

        <script>
            async function sendMessage() {
                const input = document.getElementById('userInput');
                const msg = input.value.trim();
                if (!msg) return;

                addMessage(msg, 'user');
                input.value = '';

                try {
                    const res = await fetch('/chat', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({prompt: msg})
                    });
                    const data = await res.json();
                    addMessage(data.reply, 'bot');
                } catch (err) {
                    addMessage("Erreur de connexion au serveur", 'bot');
                }
            }

            function addMessage(text, sender) {
                const div = document.createElement('div');
                div.className = `message ${sender}`;
                div.textContent = text;
                document.getElementById('messages').appendChild(div);
                div.scrollIntoView({behavior: "smooth"});
            }
        </script>
    </body>
    </html>
    """
    return render_template_string(html)


@app.route('/chat', methods=['POST'])
def chat():
    data = request.get_json(silent=True) or {}
    prompt = data.get('prompt', '').strip()

    if not prompt:
        return jsonify({"reply": "Veuillez poser une question sur les produits."})

    reply = ask_gemini(prompt)
    return jsonify({"reply": reply})


# ================== LANCEMENT ==================
if __name__ == '__main__':
    if not HEADERS["cookie"]:
        print("⚠️  ATTENTION : Tu n'as pas mis ton cookie dans HEADERS !")
    print("🚀 Serveur Flask démarré → http://127.0.0.1:5001")
    print("Assure-toi que le script Thonny marche toujours avant de tester ici.")
    print("Chatbot HTML can access the API at: http://127.0.0.1:5001/chat")
    app.run(debug=True, host='127.0.0.1', port=5001)
