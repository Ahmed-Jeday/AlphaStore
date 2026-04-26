document.addEventListener('DOMContentLoaded', async () => {
    const chatbotDiv = document.getElementById('chatbot');
    if (!chatbotDiv) return;

    try {
        // Obtenir le composant chatbot.html
        const response = await fetch('Component/chatbot.html');
        if (!response.ok) throw new Error('Impossible de charger chatbot.html');
        
        const html = await response.text();
        
        // Extraire le style et le body
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Injecter le CSS
        doc.querySelectorAll('style').forEach(style => {
            document.head.appendChild(style);
        });
        
        // Injecter le HTML
        chatbotDiv.innerHTML = doc.body.innerHTML;
        
        // Initialiser la logique du chatbot une fois le DOM présent
        initChatbotLogic();
    } catch (err) {
        console.error("Erreur lors de l'injection du chatbot :", err);
    }
});

function initChatbotLogic() {
    // ================== CONFIG ==================
    const API_URL = "http://127.0.0.1:5001/chat";
    let messages = [];
    let bagItems = [];
    let currentView = 'chat';

    const messagesContainer = document.getElementById('chatMessagesContainer');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const toggleBtn = document.getElementById('chatToggleBtn');
    const widget = document.getElementById('chatbotWidget');
    const closeBtn = document.getElementById('widgetCloseBtn');

    if (!messagesContainer || !toggleBtn || !widget || !closeBtn) {
        console.error("Éléments du chatbot non trouvés.");
        return;
    }

    // ================== FONCTIONS ==================
    function addMessage(type, text, products = []) {
        messages.push({ type, text, products });
        renderMessages();
    }

    function renderMessages() {
        messagesContainer.innerHTML = '';
        messages.forEach(msg => {
            const row = document.createElement('div');
            row.className = `message-row ${msg.type}`;

            if (msg.type === 'ai') {
                const avatar = document.createElement('div');
                avatar.className = 'avatar-ai';
                avatar.innerHTML = '<i class="fas fa-robot"></i>';
                row.appendChild(avatar);

                const bubble = document.createElement('div');
                bubble.className = 'bubble';
                bubble.innerHTML = `<div>${msg.text}</div>`;

                if (msg.products && msg.products.length > 0) {
                    const strip = document.createElement('div');
                    strip.className = 'product-strip';
                    msg.products.forEach(p => {
                        const card = document.createElement('div');
                        card.className = 'mini-card';
                        card.innerHTML = `
                            <div class="mini-card-img"><img src="${p.img || 'https://via.placeholder.com/140x110?text=Produit'}" alt="${p.name}"></div>
                            <h5>${p.name}</h5>
                            <div class="price">${p.price} TND</div>
                            <button class="btn-view" data-name="${p.name}" data-price="${p.price}">Ajouter au panier</button>
                        `;
                        strip.appendChild(card);
                    });
                    bubble.appendChild(strip);
                }
                row.appendChild(bubble);
            } else {
                const bubble = document.createElement('div');
                bubble.className = 'bubble';
                bubble.textContent = msg.text;
                row.appendChild(bubble);
            }
            messagesContainer.appendChild(row);
        });
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    async function sendMessage() {
        let text = messageInput.innerText.trim();
        if (!text || text === "Posez votre question...") return;

        addMessage('user', text);
        messageInput.innerText = '';

        // Ajout du message "thinking"
        const loadingIndex = messages.length;
        addMessage('ai', 'Alpha réfléchit...', []);

        try {
            const res = await fetch(API_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ prompt: text })
            });

            const data = await res.json();
            const reply = data.reply || "Désolé, je n'ai pas pu obtenir de réponse.";

            // Supprimer le message de chargement
            messages.splice(loadingIndex, 1);

            addMessage('ai', reply, []);

        } catch (err) {
            console.error(err);
            messages.splice(loadingIndex, 1);
            addMessage('ai', "❌ Impossible de se connecter au serveur. Vérifie que le backend Flask tourne sur le port 5001.", []);
        }
    }

    // ================== EVENT LISTENERS ==================
    if (sendButton) sendButton.addEventListener('click', sendMessage);

    if (messageInput) {
        messageInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) { 
                e.preventDefault();
                sendMessage();
            }
        });
    }

    toggleBtn.addEventListener('click', () => widget.classList.toggle('open'));
    closeBtn.addEventListener('click', () => widget.classList.remove('open'));

    // Initialisation
    function initChat() {
        messages = [];
        addMessage('ai', "Bonjour ! Je suis Alpha, votre assistant shopping intelligent.\nJ'ai accès à notre catalogue de 100 produits.\nComment puis-je vous aider aujourd'hui ?", []);
    }

    // Lancement
    initChat();
    widget.classList.remove('open'); // commence fermé
}