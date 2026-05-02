<?php
// mix-match.php

$meteo = isset($_GET['meteo']) ? preg_replace('/[^a-z_]/i', '', $_GET['meteo']) : 'toutes_saisons';
if ($meteo === '') {
    $meteo = 'toutes_saisons';
}
$budgetRaw = isset($_GET['budget']) ? $_GET['budget'] : 150;
$budget = max(10, min(1000, (float)$budgetRaw));
$productId = isset($_GET['product_id']) ? htmlspecialchars((string)$_GET['product_id'], ENT_QUOTES, 'UTF-8') : '';
$seasonLabels = [
    'ete' => 'Summer / Hot',
    'hiver' => 'Winter / Cold',
    'mi_saison' => 'Spring / Autumn',
    'toutes_saisons' => 'All seasons',
];
$seasonLabel = $seasonLabels[$meteo] ?? ucfirst(str_replace('_', ' ', $meteo));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mix &amp; Match — Alpha Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/mix-match.css">
</head>
<body class="mix-match-page">

<div class="mix-match-bg" aria-hidden="true"></div>



<section class="mix-match-hero" aria-labelledby="mix-match-title">
    <p class="mix-match-brand-line">
        <span class="mix-match-brand-line__lead"><strong>Alpha Store</strong> helps you</span>
        <span class="mix-match-brand-line__rest">dress with confidence—balanced outfits, real prices, and pieces that belong in your wardrobe.</span>
    </p>

    <p class="mix-match-eyebrow">Outfit studio</p>
    <h1 id="mix-match-title" class="mix-match-title">Mix &amp; match</h1>
    <p class="mix-match-lead">Tell us the season and budget; we suggest full looks around your chosen item, using rules our stylists and algorithms agree on.</p>

    <ul class="mix-match-trust" aria-label="What we check">
        <li><span class="mix-match-trust__icon" aria-hidden="true">✓</span> Budget limit</li>
        <li><span class="mix-match-trust__icon" aria-hidden="true">✓</span> Season fit</li>
        <li><span class="mix-match-trust__icon" aria-hidden="true">✓</span> Stock &amp; catalogue</li>
    </ul>

    <div class="mix-match-current">
        <span class="mix-match-chip">
            <span class="mix-match-chip-label">Season</span>
            <strong id="mix-match-season-label"><?= htmlspecialchars($seasonLabel) ?></strong>
        </span>
        <span class="mix-match-chip">
            <span class="mix-match-chip-label">Budget</span>
            <strong>£<span id="mix-match-budget-label"><?= htmlspecialchars((string)$budget) ?></span></strong>
        </span>
    </div>

    <div class="mix-match-controls">
        <input type="hidden" id="mix-match-product-id" value="<?= $productId ?>">
        <div class="mix-match-field">
            <label for="mix-match-meteo">Season / weather</label>
            <select id="mix-match-meteo" name="meteo">
                <option value="ete" <?= $meteo === 'ete' ? 'selected' : '' ?>>Summer / Hot</option>
                <option value="hiver" <?= $meteo === 'hiver' ? 'selected' : '' ?>>Winter / Cold</option>
                <option value="mi_saison" <?= $meteo === 'mi_saison' ? 'selected' : '' ?>>Spring / Autumn / Mild</option>
                <option value="toutes_saisons" <?= $meteo === 'toutes_saisons' ? 'selected' : '' ?>>All seasons</option>
            </select>
        </div>
        <div class="mix-match-field">
            <label for="mix-match-budget-input">Budget (£)</label>
            <input type="number" id="mix-match-budget-input" name="budget" min="10" max="1000" step="1" value="<?= htmlspecialchars((string)$budget) ?>">
        </div>
        <button type="button" id="mix-match-apply" class="btn-add mix-match-apply-btn">Update looks</button>
    </div>
</section>

<main class="mix-match-main">
    <div class="mix-match-main-inner">
        <header class="mix-match-results-intro">
            <h2 class="mix-match-results-intro__title">Your curated looks</h2>
            <p class="mix-match-results-intro__text">Each suggestion is built to work as a set—so you spend less time pairing and more time wearing what you love.</p>
        </header>
        <div id="outfits-container" class="mix-match-outfits">
            <div class="loading">
                <div class="spinner"></div>
                <p>Preparing your outfits…</p>
            </div>
        </div>
    </div>
</main>

<footer class="mix-match-footer">
    <p><strong>Alpha Store</strong> — fashion that fits your life, not just your feed.</p>
</footer>

<script src="../javaScript/mix-match.js"></script>
</body>
</html>
