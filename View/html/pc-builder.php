<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlphaStore - Build Your PC</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/pc-builder.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="pc-builder-mode">

    <?php include 'Component/navbar.php'; ?>

    <div class="pc-container">
        <!-- Hero Section -->
        <div class="pc-hero">
            <h1>Build Your Dream PC</h1>
            <p>Select components manually. Our AI ensures compatibility in real-time and recommends the best parts for your budget.</p>
        </div>

        <!-- Controls -->
        <div class="pc-controls">
            <div class="control-group">
                <label class="control-label">Budget Maximum</label>
                <input type="range" id="budgetRange" min="500" max="5000" step="50" value="1500">
                <div id="budgetValue" style="text-align: center; font-weight: 700; color: var(--accent-cyan);">1500 €</div>
            </div>

            <div class="control-group">
                <label class="control-label">Profil d'Usage</label>
                <select id="usageProfile" class="pc-select">
                    <option value="gaming">Gaming & Performance</option>
                    <option value="workstation">Productivité / Workstation</option>
                    <option value="streaming">Streaming & Multitâche</option>
                    <option value="budget">Optimisation Budget</option>
                </select>
            </div>
        </div>

        <!-- PC Grid -->
        <div class="pc-grid" id="pcGrid">
            <!-- Categories (CPU, GPU, etc.) will be injected here by JS -->
        </div>
    </div>

    <!-- Summary Bar -->
    <div class="pc-summary-bar" id="summaryBar">
        <div class="summary-content">
            <div class="summary-stats">
                <div class="stat-item">
                    <span class="stat-label">Prix Total</span>
                    <span class="stat-value" id="totalPrice">0.00 €</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Performance</span>
                    <span class="stat-value" id="totalScore">0<span>/100</span></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Consommation</span>
                    <span class="stat-value" id="totalTdp">0 W</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Statut Alimentation</span>
                    <span class="stat-label" id="psuStatus" style="color: var(--success); font-weight: 700;">PSU: OK</span>
                </div>
            </div>

            <div class="summary-actions">
                <button class="btn-recommend" id="recommendBtn">
                    <i class="ri-brain-line"></i>
                    🧠 Recommandation IA
                </button>
                <button class="btn-cart-all" id="addToCartAll">
                    🛒 Ajouter au panier
                </button>
            </div>
        </div>
    </div>

    <!-- GA Overlay -->
    <div class="ga-overlay" id="gaOverlay">
        <div class="ga-status">L'IA optimise votre configuration...</div>
        <div class="ga-chart-container">
            <canvas id="gaChart"></canvas>
        </div>
        <p style="color: var(--text-dim);">Convergence de l'Algorithme Génétique</p>
    </div>

    <script src="../javaScript/pc-builder.js"></script>
    <?php include 'Component/chatbot.html'; ?>
    <?php include 'Component/footer.html'; ?>
</body>
</html>
