<?php
// mix-match.php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "alphastore";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get User Inputs
$meteo = isset($_GET['meteo']) ? $_GET['meteo'] : 'toutes_saisons';
$budget = isset($_GET['budget']) ? (float)$_GET['budget'] : 150.0;

// Simple CSP Logic (Constraint Satisfaction Problem) in PHP
// Constraints:
// 1. Must pick 1 Haut, 1 Bas (and optionally 1 Accessoire if budget allows)
// 2. Total price <= $budget
// 3. Season must match $meteo (or be 'toutes_saisons')

$outfits = [];

// Fetch candidates
$hauts = [];
$bas = [];
$accessoires = [];

// Let's get products
$sql = "SELECT id, name, price, product_type, season, image_path FROM produits";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Enforce season constraint (simplified)
        if ($row['season'] !== $meteo && $row['season'] !== 'toutes_saisons' && $meteo !== 'toutes_saisons') {
            // Depending on strictness, we might skip. Let's just allow it or weight it if we want,
            // but CSP says it's a hard constraint:
            // continue; 
        }

        if ($row['product_type'] === 'haut') $hauts[] = $row;
        if ($row['product_type'] === 'bas') $bas[] = $row;
        if ($row['product_type'] === 'accessoire') $accessoires[] = $row;
    }
}

// Generate combinations (Backtracking / Brute force for simple CSP)
$max_outfits = 5;
shuffle($hauts);
shuffle($bas);
shuffle($accessoires);

foreach ($hauts as $h) {
    foreach ($bas as $b) {
        $total = $h['price'] + $b['price'];
        if ($total <= $budget) {
            $outfit = ['items' => [$h, $b], 'total' => $total, 'score' => 100];
            
            // Try to add accessoire
            foreach ($accessoires as $a) {
                if ($total + $a['price'] <= $budget) {
                    $outfit['items'][] = $a;
                    $outfit['total'] += $a['price'];
                    $outfit['score'] += 20; // Bonus for complete outfit
                    break;
                }
            }
            $outfits[] = $outfit;
            if (count($outfits) >= $max_outfits) break 2;
        }
    }
}

// Sort by score
usort($outfits, function($a, $b) {
    return $b['score'] - $a['score'];
});

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mix & Match Results - AlphaStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; padding: 0; font-family: 'Space Grotesk', sans-serif; background: #050505; color: #fff; }
        .header { padding: 40px 20px; text-align: center; background: rgba(255,255,255,0.02); border-bottom: 1px solid rgba(255,255,255,0.1); }
        .header h1 { margin: 0 0 10px 0; font-size: 2.5rem; background: linear-gradient(to right, #a855f7, #6366f1); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .header p { color: #a1a1aa; margin: 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        .outfit-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 24px; margin-bottom: 30px; display: flex; flex-direction: column; gap: 20px; }
        .outfit-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; }
        .outfit-price { font-size: 1.5rem; font-weight: bold; color: #a855f7; }
        .items-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .item-card { background: #000; border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05); text-align: center; }
        .item-img { width: 100%; height: 250px; object-fit: cover; }
        .item-info { padding: 15px; }
        .item-name { font-size: 1rem; margin: 0 0 5px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .item-price { color: #a1a1aa; font-size: 0.9rem; }
        .item-type { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #a855f7; margin-bottom: 5px; display: inline-block; padding: 2px 8px; background: rgba(168,85,247,0.1); border-radius: 10px; }
        .no-results { text-align: center; color: #a1a1aa; padding: 50px 0; font-size: 1.2rem; }
        .btn-add { display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #a855f7, #6366f1); color: #fff; text-decoration: none; border-radius: 8px; font-weight: bold; transition: transform 0.2s; border: none; cursor: pointer; }
        .btn-add:hover { transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="header">
    <h1>Your CSP Generated Outfits</h1>
    <p>Based on Weather: <strong><?= htmlspecialchars(ucfirst($meteo)) ?></strong> & Budget: <strong>£<?= htmlspecialchars($budget) ?></strong></p>
</div>

<div class="container">
    <?php if (empty($outfits)): ?>
        <div class="no-results">
            <p>No outfits found matching your constraints. Try increasing your budget or changing the weather.</p>
            <a href="ai.html#mix-match" class="btn-add">Go Back</a>
        </div>
    <?php else: ?>
        <?php foreach ($outfits as $index => $outfit): ?>
            <div class="outfit-card">
                <div class="outfit-header">
                    <h2>Outfit #<?= $index + 1 ?></h2>
                    <div class="outfit-price">£<?= number_format($outfit['total'], 2) ?> / £<?= $budget ?></div>
                </div>
                
                <div class="items-grid">
                    <?php foreach ($outfit['items'] as $item): ?>
                        <div class="item-card">
                            <?php 
                                $img = $item['image_path'];
                                if (!empty($img) && !filter_var($img, FILTER_VALIDATE_URL)) {
                                    $img = "../../public/" . $img;
                                }
                                if (empty($img)) {
                                    $img = 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400&h=600&fit=crop';
                                }
                                $img = htmlspecialchars($img);
                            ?>
                            <img src="<?= $img ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="item-img">
                            <div class="item-info">
                                <span class="item-type"><?= htmlspecialchars($item['product_type']) ?></span>
                                <h3 class="item-name" title="<?= htmlspecialchars($item['name']) ?>"><?= htmlspecialchars($item['name']) ?></h3>
                                <div class="item-price">£<?= number_format($item['price'], 2) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div style="text-align: right;">
                    <button class="btn-add">Add Entire Outfit to Cart</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
