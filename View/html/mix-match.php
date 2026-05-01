<?php
// mix-match.php

$meteo = isset($_GET['meteo']) ? $_GET['meteo'] : 'toutes_saisons';
$budget = isset($_GET['budget']) ? (float)$_GET['budget'] : 150.0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mix & Match AI - AlphaStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/mix-match.css">
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
    <h1>Your AI Generated Outfits</h1>
    <p>Powered by CSP (Constraint Satisfaction Problem) Algorithm</p>
    <p style="margin-top: 10px; font-size: 0.9rem;">
        Weather: <strong><?= htmlspecialchars(ucfirst($meteo)) ?></strong> | 
        Budget: <strong>£<?= htmlspecialchars($budget) ?></strong>
    </p>
</div>

<div class="container" id="outfits-container">
    <!-- Results will be loaded here via JS -->
    <div class="loading">
        <div class="spinner"></div>
        <p>Initializing AI recommendation engine...</p>
    </div>
</div>

<script src="../javaScript/mix-match.js"></script>
</body>
</html>
