<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../html/index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carnival Spin</title>
  <link rel="stylesheet" href="spin.css">
</head>
<body>

  <!-- INTRO SCREEN -->
  <div id="container">
    <h1 class="title" id="spin-title"></h1>
    <div id="lottie"></div>
    <div id="enter-container">
      <button id="enter-btn" onclick="start()">✨ Enter</button>
    </div>
  </div>

  <!-- 3D CANVAS -->
  <canvas class="webgl"></canvas>

  <!-- PRIZE TABLE -->
  <div id="prize-table">
    <h3>🎁 Table des Cadeaux</h3>
    <table>
      <thead>
        <tr><th>N°</th><th>Cadeau</th><th>Chance</th></tr>
      </thead>
      <tbody>
        <tr data-num="1"><td>1</td><td>❌ Rien</td><td>20%</td></tr>
        <tr data-num="2"><td>2</td><td>🏷️ -10% Solde</td><td>10%</td></tr>
        <tr data-num="3"><td>3</td><td>🚚 Livraison gratuite</td><td>15%</td></tr>
        <tr data-num="4"><td>4</td><td>🎁 Cadeau mystère</td><td>5%</td></tr>
        <tr data-num="5"><td>5</td><td>❌ Rien</td><td>15%</td></tr>
        <tr data-num="6"><td>6</td><td>🏷️ -20% Solde</td><td>8%</td></tr>
        <tr data-num="7"><td>7</td><td>☕ Café offert</td><td>12%</td></tr>
        <tr data-num="8"><td>8</td><td>🏷️ -5% Solde</td><td>7%</td></tr>
        <tr data-num="9"><td>9</td><td>❌ Rien</td><td>5%</td></tr>
        <tr data-num="10"><td>10</td><td>🏆 Grand Prix !</td><td>3%</td></tr>
      </tbody>
    </table>
    <div id="prize-actions">
      <button id="history-btn">📜 Mon Historique</button>
      <a class="back_home" href="../user_Dashboard/index.php?section=spin">Back to Dashboard</a>
    </div>
  </div>

  <!-- HISTORY PANEL -->
  <div id="history-panel" class="hidden">
    <div id="history-content">
      <div id="history-header">
        <h3>📜 Historique des Gains</h3>
        <button id="close-history">&times;</button>
      </div>
      <div id="history-stats">
        <div class="stat-box">
          <span class="stat-label">Total Spins</span>
          <span class="stat-value" id="stat-total">0</span>
        </div>
        <div class="stat-box">
          <span class="stat-label">Gagnés</span>
          <span class="stat-value" id="stat-wins">0</span>
        </div>
      </div>
      <div id="history-list">
        <!-- Will be populated by JS -->
      </div>
    </div>
  </div>

  <!-- GIFT OVERLAY -->
  <div id="gift-overlay" class="hidden">
    <div id="gift-card">
      <div id="gift-sparkles">
        <span></span><span></span><span></span>
        <span></span><span></span><span></span>
      </div>
      <div id="gift-icon">🎉</div>
      <h2 id="gift-title">Félicitations !</h2>
      <p id="gift-desc">Vous avez gagné :</p>
      <div id="gift-badge"></div>
      <button id="replay-btn" onclick="closeGift()">🔄 Rejouer</button>
    </div>
  </div>

  <script>
    const titleText = "Alphastore wishes you good luck 😊!";
    const titleElement = document.getElementById("spin-title");
    let charIndex = 0;

    function typeTitle() {
      if (charIndex < titleText.length) {
        titleElement.textContent += titleText.charAt(charIndex);
        charIndex++;
        setTimeout(typeTitle, 60);
      }
    }

    window.onload = () => {
      typeTitle();
    };

    let start = () => {
      document.getElementById("container").style.display = "none";
      window.start3 = true;
      if (window.anim) window.anim.stop();
    };
    window.closeGift = () => {
      document.getElementById("gift-overlay").classList.add("hidden");
      // reset for replay
      if (typeof resetSpin === "function") resetSpin();
    };
  </script>
  <script type="module" src="spin.js"></script>
</body>
</html>
