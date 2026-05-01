<?php 
include_once "Component/navbar.php"; 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOTRE SUGGESTION POUR VOUS | AlphaStore AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/smart-suggestion.css">
    <script>
        window.userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
    </script>
</head>
<body>
    <div class="noise"></div>

    <div class="suggestion-container">
        <header class="page-header">
            <h1>NOTRE SUGGESTION POUR VOUS</h1>
            <p>Notre IA a analysé vos préférences et votre budget pour composer la sélection idéale.</p>
        </header>

        <div class="suggestion-grid">
            <!-- Left Column: AG Output -->
            <section class="ai-output-section">
                <div class="section-title">
                    <span class="ai-badge">Alpha-GA v2.0</span>
                    <h2>Suggestions Optimisées</h2>
                </div>

                <!-- Budget Control Panel -->
                <div class="budget-control">
                    <div class="input-group">
                        <label for="user-budget">Votre Budget (DT)</label>
                        <input type="number" id="user-budget" value="40" min="10">
                    </div>
                    <button id="update-optimization" class="btn-primary">
                        Optimiser avec mon budget
                    </button>
                </div>

                <!-- Results Summary (Dynamic) -->
                <div id="results-summary" class="results-stats" style="display: none;">
                    <div class="stat-item">
                        <span>Prix Total:</span>
                        <strong id="ai-total-price">0.00 DT</strong>
                    </div>
                    <div class="stat-item">
                        <span>Score IA:</span>
                        <strong id="ai-total-score">0</strong>
                    </div>
                </div>

                <div id="ai-loader" class="loader-container">
                    <div class="loader"></div>
                    <p>L'intelligence artificielle génétique calcule votre panier idéal...</p>
                </div>

                <div id="suggestion-results" class="suggestion-list" style="display: none;">
                    <!-- Results will be injected here -->
                </div>
            </section>

            <!-- Right Column: Current Cart -->
            <aside class="cart-items-section">
                <div class="section-title">
                    <h2>Votre Panier Actuel</h2>
                </div>
                <div id="cart-items-list">
                    <!-- Cart items will be injected here -->
                    <p style="color: var(--text-secondary);">Chargement du panier...</p>
                </div>
                <div class="cart-total">
                    <h4>Total Actuel</h4>
                    <span id="cart-total-price">0.00 DT</span>
                </div>
            </aside>
        </div>
    </div>

    <script src="../javaScript/smart-suggestion.js"></script>
</body>
</html>
