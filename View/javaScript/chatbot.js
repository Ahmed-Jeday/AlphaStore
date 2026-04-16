
// ---------- PRODUCT SETS ----------
const PRODUCT_SET_1 = [
    { name: "Sundress", price: "$89.99", desc: "Flowy & lightweight", img: "https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=140&h=110&fit=crop" },
    { name: "Floral Midi", price: "$75.00", desc: "Vibrant pattern", img: "https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=140&h=110&fit=crop" },
    { name: "Linen Dress", price: "$69.99", desc: "Breathable eco", img: "https://images.unsplash.com/photo-1525507119028-ed4c629a60a3?w=140&h=110&fit=crop" }
];
const PRODUCT_SET_2 = [
    { name: "Chic Sundress", price: "$94.99", desc: "Elegant silhouette", img: "https://images.unsplash.com/photo-1585487000160-6ebcfceb0d03?w=140&h=110&fit=crop" },
    { name: "Boho Romper", price: "$82.00", desc: "Free-spirited", img: "https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=140&h=110&fit=crop" },
    { name: "Silk Slip", price: "$99.00", desc: "Luxury satin", img: "https://images.unsplash.com/photo-1564257631407-4deb1f99d992?w=140&h=110&fit=crop" }
];
const PRODUCT_SET_3 = [
    { name: "Tropical Maxi", price: "$89.00", desc: "Vacation vibes", img: "https://images.unsplash.com/photo-1539008835657-9e8e9680c956?w=140&h=110&fit=crop" },
    { name: "Crochet Dress", price: "$79.99", desc: "Handmade details", img: "https://images.unsplash.com/photo-1509631179647-0177331693ae?w=140&h=110&fit=crop" },
    { name: "Cotton Shirtdress", price: "$68.50", desc: "Everyday staple", img: "https://images.unsplash.com/photo-1483985988355-763728e1935b?w=140&h=110&fit=crop" }
];

// ----- Bag state (items the user adds via "VIEW ITEM" in chat carousels)
let bagItems = []; // each item: { name, price, img }
function addToBag(productName, productPrice, productImg) {
    bagItems.push({ name: productName, price: productPrice, img: productImg });
    renderCurrentView(); // refresh if bag view is open
}
function removeFromBag(index) {
    bagItems.splice(index, 1);
    renderCurrentView();
}

// ----- Chat messages state
let messages = [];
let isWaitingForAI = false;
let currentView = 'chat'; // 'chat', 'profile', 'bag', 'settings'

// DOM elements (initialized once chatbot component is loaded)
let messagesContainer;
let messageInput;
let sendBtn;
let micBtn;
let widgetCloseBtn;
let toggleBtn;
let widgetPanel;
let sidebarIcons;

function initChatbotElements() {
    messagesContainer = document.getElementById('chatMessagesContainer');
    messageInput = document.getElementById('messageInput');
    sendBtn = document.getElementById('sendButton');
    micBtn = document.getElementById('micButton');
    widgetCloseBtn = document.getElementById('widgetCloseBtn');
    toggleBtn = document.getElementById('chatToggleBtn');
    widgetPanel = document.getElementById('chatbotWidget');
    sidebarIcons = document.querySelectorAll('.sidebar-icon');

    return messagesContainer && messageInput && sendBtn && micBtn && widgetCloseBtn && toggleBtn && widgetPanel;
}

function initChatbot() {
    if (!initChatbotElements()) {
        setTimeout(initChatbot, 100);
        return;
    }

    function setPlaceholder() {
        if (!messageInput.innerText.trim()) {
            messageInput.innerText = 'Type your question...';
            messageInput.style.color = '#94a3b8';
        }
    }

    function closeWidget() { widgetPanel.classList.remove('open'); }
    function openWidget() { widgetPanel.classList.add('open'); }
    function toggleWidget() {
        if (widgetPanel.classList.contains('open')) closeWidget();
        else openWidget();
    }

    sendBtn.addEventListener('click', sendUserMessage);
    micBtn.addEventListener('click', () => alert("🎤 Voice input demo — coming soon. Type your message!"));
    messageInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendUserMessage();
        }
    });
    widgetCloseBtn.addEventListener('click', closeWidget);
    toggleBtn.addEventListener('click', toggleWidget);

    sidebarIcons.forEach(icon => {
        icon.addEventListener('click', () => {
            const view = icon.getAttribute('data-view');
            if (view) switchView(view);
        });
    });

    messageInput.addEventListener('focus', () => {
        if (messageInput.innerText === 'Type your question...') {
            messageInput.innerText = '';
            messageInput.style.color = '#1e293b';
        }
    });
    messageInput.addEventListener('blur', setPlaceholder);
    setPlaceholder();
    initConversation();
    closeWidget();
}

window.addEventListener('load', initChatbot);

// Helper: generate carousel HTML
function generateCarouselHTML(products) {
    if (!products || products.length === 0) return document.createDocumentFragment();
    const container = document.createElement('div');
    container.className = 'product-strip';
    products.forEach(prod => {
        const card = document.createElement('div');
        card.className = 'mini-card';
        card.innerHTML = `
                    <div class="mini-card-img"><img src="${prod.img}" alt="${prod.name}" loading="lazy"></div>
                    <h5>${prod.name}</h5>
                    <div class="price">${prod.price}</div>
                    <p>${prod.desc}</p>
                    <div class="btn-view" data-name="${prod.name}" data-price="${prod.price}" data-img="${prod.img}">VIEW ITEM</div>
                `;
        container.appendChild(card);
    });
    setTimeout(() => {
        container.querySelectorAll('.btn-view').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const name = btn.getAttribute('data-name');
                const price = btn.getAttribute('data-price');
                const img = btn.getAttribute('data-img');
                addToBag(name, price, img);
                alert(`✨ "${name}" added to your bag!`);
            });
        });
    }, 0);
    return container;
}

// Render chat messages
function renderMessages() {
    if (!messagesContainer) return;
    messagesContainer.innerHTML = '';
    messages.forEach(msg => {
        const rowDiv = document.createElement('div');
        rowDiv.className = `message-row ${msg.type}`;
        if (msg.type === 'ai') {
            const avatarDiv = document.createElement('div');
            avatarDiv.className = 'avatar-ai';
            avatarDiv.innerHTML = '<i class="fas fa-robot"></i>';
            rowDiv.appendChild(avatarDiv);
            const bubbleDiv = document.createElement('div');
            bubbleDiv.className = 'bubble';
            if (msg.text) {
                const textSpan = document.createElement('div');
                textSpan.innerText = msg.text;
                bubbleDiv.appendChild(textSpan);
            }
            if (msg.carouselProducts && msg.carouselProducts.length) {
                const carouselElem = generateCarouselHTML(msg.carouselProducts);
                bubbleDiv.appendChild(carouselElem);
            }
            rowDiv.appendChild(bubbleDiv);
        } else {
            const bubbleDiv = document.createElement('div');
            bubbleDiv.className = 'bubble';
            bubbleDiv.innerText = msg.text;
            rowDiv.appendChild(bubbleDiv);
        }
        messagesContainer.appendChild(rowDiv);
    });
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Profile view
function renderProfile() {
    messagesContainer.innerHTML = `
                <div class="info-card">
                    <div class="section-title"><i class="fas fa-user-circle"></i> Your Profile</div>
                    <div class="info-row"><span class="info-label">Name</span><span class="info-value">Emma Watson</span></div>
                    <div class="info-row"><span class="info-label">Email</span><span class="info-value">emma@styleandyou.com</span></div>
                    <div class="info-row"><span class="info-label">Preferred size</span><span class="info-value">S / M</span></div>
                    <div class="info-row"><span class="info-label">Style preferences</span><span class="info-value">Bohemian, Minimalist</span></div>
                    <div class="info-row"><span class="info-label">Member since</span><span class="info-value">2024</span></div>
                </div>

                <div class="update-profile">
                   <i class="fas fa-edit"></i> Update Profile
                </div>
                <div class="back-to-chat" id="backToChatBtn"><i class="fas fa-arrow-left"></i> Back to chat</div>
            `;
    document.getElementById('backToChatBtn')?.addEventListener('click', () => switchView('chat'));
}

// Bag view
function renderBag() {
    if (bagItems.length === 0) {
        messagesContainer.innerHTML = `
                    <div style="text-align:center; padding: 30px 20px; color: #5b6e8c;">
                        <i class="fas fa-shopping-bag" style="font-size: 3rem; opacity: 0.5;"></i>
                        <p style="margin-top: 12px;">Your bag is empty.<br>Add items from the chat recommendations!</p>
                    </div>
                    <div class="back-to-chat" id="backToChatBtn"><i class="fas fa-arrow-left"></i> Back to chat</div>
                `;
        document.getElementById('backToChatBtn')?.addEventListener('click', () => switchView('chat'));
        return;
    }
    let total = 0;
    let itemsHtml = '';
    bagItems.forEach((item, idx) => {
        const priceNum = parseFloat(item.price.replace('$', ''));
        total += priceNum;
        itemsHtml += `
                    <div class="bag-item">
                        <div class="bag-item-img"><img src="${item.img}" alt="${item.name}"></div>
                        <div class="bag-item-details">
                            <div class="bag-item-name">${item.name}</div>
                            <div class="bag-item-price">${item.price}</div>
                        </div>
                        <div class="remove-item" data-index="${idx}"><i class="fas fa-trash-alt"></i></div>
                    </div>
                `;
    });
    messagesContainer.innerHTML = `
                <div class="section-title"><i class="fas fa-shopping-bag"></i> Your Bag (${bagItems.length})</div>
                <div class="info-card" style="padding: 12px;">
                    ${itemsHtml}
                    <div class="total-price">Total: $${total.toFixed(2)}</div>
                </div>
                <div class="back-to-chat" id="backToChatBtn"><i class="fas fa-arrow-left"></i> Back to chat</div>
            `;
    document.querySelectorAll('.remove-item').forEach(el => {
        const idx = parseInt(el.getAttribute('data-index'));
        el.addEventListener('click', () => removeFromBag(idx));
    });
    document.getElementById('backToChatBtn')?.addEventListener('click', () => switchView('chat'));
}

// Settings view with toggles
let notificationsEnabled = true;
let darkMode = false;
function renderSettings() {
    messagesContainer.innerHTML = `
                <div class="section-title"><i class="fas fa-cog"></i> Settings</div>
                <div class="info-card">
                    <div class="setting-option">
                        <span>🔔 Enable notifications</span>
                        <div class="toggle-switch ${notificationsEnabled ? 'active' : ''}" id="notifToggle">
                            <div class="toggle-knob"></div>
                        </div>
                    </div>
                   
                    <div class="setting-option">
                        <span>🤖 AI response speed</span>
                        <select style="padding: 6px 10px; border-radius: 30px; border:1px solid #e2e8f0;">
                            <option>Standard</option>
                            <option>Fast</option>
                        </select>
                    </div>
                </div>
                <div class="back-to-chat" id="backToChatBtn"><i class="fas fa-arrow-left"></i> Back to chat</div>
            `;
    const notifToggle = document.getElementById('notifToggle');
    const darkToggle = document.getElementById('darkToggle');
    if (notifToggle) {
        notifToggle.addEventListener('click', () => {
            notificationsEnabled = !notificationsEnabled;
            renderSettings();
        });
    }
    if (darkToggle) {
        darkToggle.addEventListener('click', () => {
            darkMode = !darkMode;
            document.body.style.background = darkMode ? '#1e293b' : 'linear-gradient(145deg, #e9f0f5 0%, #f1f5f9 100%)';
            renderSettings();
        });
    }
    document.getElementById('backToChatBtn')?.addEventListener('click', () => switchView('chat'));
}

// Main render based on currentView
function renderCurrentView() {
    if (currentView === 'chat') {
        renderMessages();
    } else if (currentView === 'profile') {
        renderProfile();
    } else if (currentView === 'bag') {
        renderBag();
    } else if (currentView === 'settings') {
        renderSettings();
    }
    // update active class on sidebar icons
    sidebarIcons.forEach(icon => {
        const view = icon.getAttribute('data-view');
        if (view === currentView) {
            icon.classList.add('active');
        } else {
            icon.classList.remove('active');
        }
    });
}

function switchView(view) {
    currentView = view;
    renderCurrentView();
}

// Chat AI logic
function addMessage(type, text, carouselProducts = null) {
    messages.push({ type, text, carouselProducts });
    if (currentView === 'chat') renderMessages();
}

async function botReplyAfterUser(userMessageText) {
    if (isWaitingForAI) return;
    isWaitingForAI = true;
    await new Promise(resolve => setTimeout(resolve, 550));
    let replyText = "";
    let chosenProducts = [];
    const lowerMsg = userMessageText.toLowerCase();
    if (lowerMsg.includes("dress") || lowerMsg.includes("summer") || lowerMsg.includes("style") || lowerMsg.includes("look")) {
        replyText = "Aria: I found more gorgeous summer picks! ✨ Check out these trending styles:";
        chosenProducts = PRODUCT_SET_2;
    } else if (lowerMsg.includes("accessorie") || lowerMsg.includes("bag") || lowerMsg.includes("shoe")) {
        replyText = "Aria: Accessories complete any outfit! Here are some chic additions:";
        chosenProducts = PRODUCT_SET_3;
    } else if (lowerMsg.includes("thanks") || lowerMsg.includes("merci")) {
        replyText = "Aria: You're very welcome! Let me know if you'd like more recommendations 💚";
        chosenProducts = [];
    } else {
        replyText = "Aria: I'd love to help you find the perfect outfit. How about these elegant dresses?";
        chosenProducts = PRODUCT_SET_1;
    }
    if (chosenProducts.length > 0) {
        addMessage('ai', replyText, chosenProducts);
    } else {
        addMessage('ai', replyText, null);
    }
    isWaitingForAI = false;
}

function sendUserMessage() {
    if (isWaitingForAI) {
        alert("Aria is thinking... just a moment");
        return;
    }
    let rawText = messageInput.innerText.trim();
    if (rawText === "" || rawText === "Type your question...") return;
    messageInput.innerText = "";
    addMessage('user', rawText);
    // if not in chat view, switch to chat
    if (currentView !== 'chat') switchView('chat');
    botReplyAfterUser(rawText);
}

function initConversation() {
    messages = [];
    addMessage('ai', 'Hello! How can I help you today?', null);
    addMessage('user', "I'm looking for a stylish summer dress under $100.");
    addMessage('ai', "Aria: Absolutely! Check out these top picks:", PRODUCT_SET_1);
}

// Load chatbot only once the component markup exists
window.addEventListener('load', initChatbot);
if (document.readyState === 'complete') initChatbot();
