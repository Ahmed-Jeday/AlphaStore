const { GoogleGenerativeAI } = require("@google/generative-ai");
require('dotenv').config();

const genAI = new GoogleGenerativeAI(process.env.API_GEMINI);
const model = genAI.getGenerativeModel({ model: "gemini-pro" });

// 🔹 Cache global (chargé une seule fois)
let cachedContext = null;

// 🔹 Mémoire des conversations (par utilisateur)
const conversations = {}; // { sessionId: [messages...] }

/**
 * Charger les produits UNE SEULE FOIS
 */
async function loadContextOnce() {
    if (cachedContext) return cachedContext;

    const baseUrl = process.env.APP_URL || "alphastore.test";

    try {
        const [resNormal, resTech] = await Promise.all([
            fetch(`${baseUrl}/index.php?action=getProduits`),
            fetch(`${baseUrl}/index.php?action=getTechProduits`)
        ]);

        const products = await resNormal.json();
        const techProducts = await resTech.json();

        const allProducts = [...products, ...techProducts];

        let context = "Catalogue Alpha Store :\n";
        allProducts.forEach(p => {
            const cat = p.category || "Technologie";
            context += `- ${p.name} (${cat}): ${p.price}$ | ${p.description}\n`;
        });

        cachedContext = context;
        return context;

    } catch (err) {
        console.error("Erreur chargement produits:", err);
        return "Catalogue indisponible.";
    }
}

/**
 * Génère réponse avec mémoire conversation
 */
async function generateChatResponse(sessionId, userMessage) {
    try {
        // 1. Charger contexte UNE FOIS
        const context = await loadContextOnce();

        // 2. Initialiser conversation si nouvelle
        if (!conversations[sessionId]) {
            conversations[sessionId] = [
                {
                    role: "user",
                    parts: [{
                        text: `Tu es un assistant de vente.
Voici le catalogue :
${context}

Règles :
- Réponds en français
- Utilise uniquement ces produits`
                    }]
                }
            ];
        }

        // 3. Ajouter message utilisateur
        conversations[sessionId].push({
            role: "user",
            parts: [{ text: userMessage }]
        });

        // 4. Envoyer TOUT l'historique
        const result = await model.generateContent({
            contents: conversations[sessionId]
        });

        const reply = result.response.text();

        // 5. Sauvegarder réponse IA
        conversations[sessionId].push({
            role: "model",
            parts: [{ text: reply }]
        });

        return reply;

    } catch (error) {
        console.error("Erreur chatbot:", error);
        return "Erreur serveur.";
    }
}

module.exports = { generateChatResponse };