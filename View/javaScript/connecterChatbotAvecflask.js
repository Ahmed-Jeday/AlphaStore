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
    const API_URL = "http://127.0.0.1:5003/chat";
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

    function formatMarkdown(text) {
        if (!text) return '';

        // 1. Gérer les blocs de citation (>)
        text = text.replace(/^> (.*$)/gim, '<blockquote>$1</blockquote>');

        // 2. Gérer les titres (###)
        text = text.replace(/^### (.*$)/gim, '<h4>$1</h4>');

        // 3. Gérer les lignes horizontales (---)
        text = text.replace(/^---$/gim, '<hr>');

        // 4. Détecter et convertir les listes de produits en tableaux
        // Format attendu: * **Nom** : Description — **Prix**
        const lines = text.split('\n');
        let inProductList = false;
        let tableHtml = '<table class="product-table"><thead><tr><th>Produit</th><th>Description</th><th>Prix</th></tr></thead><tbody>';
        let newLines = [];

        for (let i = 0; i < lines.length; i++) {
            let line = lines[i].trim();
            // Supporte — (em-dash), – (en-dash) et - (hyphen)
            let match = /^\*\s+\*\*(.*?)\*\*\s*:\s*(.*?)\s*[—-–-]\s*\*\*(.*?)\*\*$/.exec(line);
            
            if (match) {
                if (!inProductList) {
                    inProductList = true;
                }
                tableHtml += `<tr><td><strong>${match[1]}</strong></td><td>${match[2]}</td><td class="price-tag">${match[3]}</td></tr>`;
            } else {
                if (inProductList) {
                    tableHtml += '</tbody></table>';
                    newLines.push(tableHtml);
                    tableHtml = '<table class="product-table"><thead><tr><th>Produit</th><th>Description</th><th>Prix</th></tr></thead><tbody>';
                    inProductList = false;
                }
                newLines.push(lines[i]); // On garde la ligne originale pour préserver le formatage si ce n'est pas un produit
            }
        }
        if (inProductList) {
            tableHtml += '</tbody></table>';
            newLines.push(tableHtml);
        }
        text = newLines.join('\n');

        // 5. Gérer les listes à puces simples (si pas déjà transformées en tableau)
        text = text.replace(/^\*\s+(?!<table)(.*$)/gim, '<ul><li>$1</li></ul>');
        text = text.replace(/<\/ul>\n<ul>/gim, ''); // Fusionner les ul consécutifs

        // 6. Gérer le gras (**text**)
        text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

        // 7. Gérer les sauts de ligne (restants)
        text = text.replace(/\n/g, '<br>');

        return text;
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
                bubble.innerHTML = `<div>${formatMarkdown(msg.text)}</div>`;

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
            addMessage('ai', "❌ Impossible de se connecter au serveur. Vérifie que le backend Flask tourne sur le port 5003.", []);
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