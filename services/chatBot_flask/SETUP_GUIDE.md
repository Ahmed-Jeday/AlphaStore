# Connecting chatbot.html with Flask app.py - Setup Guide

## Overview
This guide explains how to connect your **chatbot.html** (frontend widget in `View/html/Component/`) with your **app.py** (Flask backend in `services/chatBot_flask/`).

## Architecture
```
User Interface (chatbot.html)
         ↓
    HTTP POST Request
         ↓
Flask API (app.py:5001/chat)
         ↓
Gemini API (Google Bard)
```

## Installation & Setup

### Step 1: Install Python Dependencies
Navigate to the Flask app directory and install requirements:

```bash
cd c:\xampp\htdocs\AlphaStore\services\chatBot_flask
pip install -r requirements.txt
```

**Required packages:**
- Flask==2.3.0
- Flask-CORS==4.0.0 (enables cross-origin requests from HTML)
- requests==2.31.0

### Step 2: Configure Gemini Cookie
The Flask app needs a valid Gemini API cookie to work. Edit `app.py` and add your cookie:

```python
HEADERS = {
    "accept": "*/*",
    "content-type": "application/x-www-form-urlencoded;charset=UTF-8",
    "x-same-domain": "1",
    "user-agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36",
    "cookie": "YOUR_COOKIE_HERE"  # ← Replace with your actual cookie
}
```

To get the cookie:
1. Open https://gemini.google.com/ in your browser
2. Open DevTools (F12) → Network tab
3. Send a message to Gemini
4. Find a request and copy the cookie from the Request Headers

### Step 3: Start the Flask Server
```bash
python app.py
```

You should see output like:
```
🚀 Serveur Flask démarré → http://127.0.0.1:5001
Chatbot HTML can access the API at: http://127.0.0.1:5001/chat
```

### Step 4: Test the Connection

#### Method A: Direct API Test
Open a new terminal and test the endpoint:

```bash
# Windows PowerShell
curl -Method POST -Uri "http://127.0.0.1:5001/chat" `
  -ContentType "application/json" `
  -Body '{"prompt":"Hello, what products do you have?"}'
```

#### Method B: Open chatbot.html
1. Navigate to `View/html/Component/chatbot.html` in your browser
2. Or use VS Code Live Server extension
3. Click the chat icon to open the widget
4. Send a message and wait for the AI response

## How It Works

### Frontend (chatbot.html)
When you send a message in the chat widget:
```javascript
fetch('http://127.0.0.1:5001/chat', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ prompt: userMessage })
})
```

### Backend (app.py)
The Flask server receives the request and processes it:
```python
@app.route('/chat', methods=['POST'])
def chat():
    data = request.get_json()
    prompt = data.get('prompt', '').strip()
    reply = ask_gemini(prompt)  # Calls Gemini API
    return jsonify({"reply": reply})
```

## API Endpoint Reference

### POST /chat
**Request:**
```json
{
  "prompt": "What summer dresses do you recommend?"
}
```

**Response:**
```json
{
  "reply": "Based on the catalog, I recommend these summer dresses..."
}
```

## Troubleshooting

### ❌ "Could not connect to Aria" Error
**Solution:**
- Ensure Flask server is running on http://127.0.0.1:5001
- Check if port 5001 is not blocked by firewall
- Verify CORS is enabled (Flask-CORS middleware is installed)

### ❌ "ERREUR 401/403"
**Solution:**
- Your Gemini cookie has expired
- Get a fresh cookie from https://gemini.google.com/
- Update the HEADERS["cookie"] in app.py

### ❌ "[Aucune réponse analysée]"
**Solution:**
- Gemini API response format may have changed
- Check the DEBUG output in Flask console
- Verify your Gemini cookie is still valid

### ✅ Empty Response
**Solution:**
- The AI may not have understood your question
- Try rephrasing in French (the chatbot is configured for French)
- Example: "Quels sont les produits disponibles?" instead of "What products?"

## Modification Guide

### Update Product Catalog (app.py)
Edit the PRODUCTS list in app.py (lines ~20-25):
```python
PRODUCTS = [
    {"id": 1, "name": "Your Product", "price": 99.99, "category": "Category", "description": "Description"},
    # Add more products...
]
```

### Customize Chatbot Prompts (chatbot.html)
Modify the initial conversation in `initConversation()`:
```javascript
function initConversation() {
    messages = [];
    addMessage('ai', 'Your custom greeting here!', null);
}
```

### Change Flask Port
In app.py, change this line:
```python
app.run(debug=True, host='127.0.0.1', port=5001)  # Change 5001 to your port
```

And update the fetch URL in chatbot.html:
```javascript
fetch('http://127.0.0.1:YOUR_PORT/chat', { ... })
```

## File Structure
```
AlphaStore/
├── View/html/Component/
│   └── chatbot.html          ← Frontend widget
├── services/chatBot_flask/
│   ├── app.py                ← Flask backend
│   └── requirements.txt       ← Dependencies
```

## Next Steps
1. Get a valid Gemini cookie and add it to app.py
2. Update the product catalog with your actual products
3. Customize the chatbot prompts and styling
4. Deploy to your server when ready

---

**Questions?** Check the Flask console output for detailed error messages.
