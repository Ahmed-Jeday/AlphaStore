You are a senior full-stack technical writer specializing in e-commerce systems.

Generate a **complete technical documentation** for "Alpha Store", a multi-sector AI-powered e-commerce platform.

---
CONTEXT:
- Sectors: Tech, Fashion (Adulte & Kids)
- Key AI features: product recommendations (BFS), dynamic pricing (Minimax/Alpha-Beta), virtual try-on (IDM-VTON), chatbot (Groq/Gemini API), image search (Vision API)
- Frontend: Vanilla JS + GSAP animations, Swiper.js, FLIP animations, DM Sans/Playfair typography
- Backend: Node.js / Express.js
- Other: A* logistics routing, price negotiation UI
---

Document the following sections:

1. **Project Overview & Vision** — Mission, competitive positioning vs Jumia/Alibaba/Mytek.
2. **Feature Inventory** — Every feature, its AI/technical method, and user benefit.
3. **Tech Stack & Dependencies** — Full list with role of each tool.
4. **Frontend Architecture** — Page structure, animation system, component breakdown.
5. **Backend Architecture** — Express routes, middleware, API integrations.
6. **AI Modules** — For each AI feature: algorithm used, input/output, integration point, limitations.
7. **Data Models** — Products, Users, Orders, Cart, Pricing Engine state.
8. **API Endpoints** — Method, route, description, request body, response schema.
9. **Environment Variables** — All required keys (Groq, Gemini, Vision API, etc.).
10. **UI/UX Design System** — Typography, colors, animation conventions used.
11. **Deployment Guide** — How to run locally and deploy.
12. **Roadmap & Known Issues** — Planned features and current limitations.

Output clean Markdown with tables and code blocks.