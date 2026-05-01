<?php 
session_start();

?>





<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Adult Heavyweight Relaxed T-Shirt</title>
<link rel="stylesheet" href="../css/component/footer.css">
<link rel="stylesheet" href="../css/review.css">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=Playfair+Display:wght@400;500&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --white: #ffffff;
    --off-white: #f7f6f4;
    --light-gray: #e8e6e2;
    --mid-gray: #b0aba3;
    --dark-gray: #3a3833;
    --black: #1a1917;
    --blue-accent: #2b4beb;
    --blue-hover: #1a37d4;
    --docksider: #6b8fc2;
    --sale-red: #c0392b;
    --font-sans: 'DM Sans', sans-serif;
    --font-serif: 'Playfair Display', serif;
  }

  body {
    font-family: var(--font-sans);
    background: var(--white);
    color: var(--black);
    font-size: 14px;
    line-height: 1.5;
  }

  /* ── BREADCRUMB ── */
  .breadcrumb {
    padding: 12px 40px;
    font-size: 12px;
    color: var(--mid-gray);
    border-bottom: 1px solid var(--light-gray);
    display: flex;
    gap: 6px;
    margin-top: 150px;
  }
  .breadcrumb a { color: var(--mid-gray); text-decoration: none; }
  .breadcrumb a:hover { color: var(--black); }
  .breadcrumb span { color: var(--dark-gray); }

  /* ── PRODUCT LAYOUT ── */
  .product-wrapper {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 0;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 40px 60px;
  }

  /* ── GALLERY ── */
  .gallery {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4px;
    padding: 20px 20px 0 0;
  }

  .gallery-main {
    grid-column: 1 / -1;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4px;
  }

  .gallery img {
    width: 100%;
    height: 420px;
    object-fit: cover;
    background: var(--off-white);
    display: block;
    transition: opacity 0.3s ease;
  }

  .gallery img:hover { opacity: 0.92; }

  .gallery-sub img {
    height: 280px;
  }

  /* ── INFO PANEL ── */
  .info-panel {
    padding: 24px 0 0 32px;
    position: sticky;
    top: 20px;
    align-self: start;
  }

  .product-title {
    font-family: var(--font-serif);
    font-size: 26px;
    font-weight: 400;
    line-height: 1.25;
    color: var(--black);
    margin-bottom: 14px;
  }

  .price-row {
    display: flex;
    align-items: baseline;
    gap: 10px;
    margin-bottom: 4px;
  }

  .price-original {
    font-size: 14px;
    color: var(--mid-gray);
    text-decoration: line-through;
  }

  .price-current {
    font-size: 22px;
    font-weight: 600;
    color: var(--black);
  }

  .promo-code {
    font-size: 12px;
    color: var(--dark-gray);
    margin-bottom: 16px;
  }

  .promo-code strong { color: var(--sale-red); }

  /* ── RATINGS ── */
  .ratings {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
  }

  .stars {
    display: flex;
    gap: 2px;
  }

  .star {
    width: 16px;
    height: 16px;
    fill: var(--black);
  }

  .star-empty { fill: none; stroke: var(--mid-gray); stroke-width: 1.5px; }

  .ratings-count {
    font-size: 12px;
    color: var(--blue-accent);
    text-decoration: underline;
    cursor: pointer;
  }

  /* ── DIVIDER ── */
  .divider {
    border: none;
    border-top: 1px solid var(--light-gray);
    margin: 16px 0;
  }

  /* ── COLOR ── */
  .label-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
  }

  .label {
    font-size: 13px;
    font-weight: 500;
    color: var(--dark-gray);
  }

  .label span { color: var(--black); font-weight: 400; }

  .size-guide {
    font-size: 12px;
    color: var(--blue-accent);
    text-decoration: underline;
    cursor: pointer;
  }

  .color-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
    margin-bottom: 18px;
  }

  .color-swatch {
    width: 36px;
    height: 36px;
    border-radius: 3px;
    cursor: pointer;
    border: 1.5px solid transparent;
    transition: border-color 0.15s ease, transform 0.15s ease;
    position: relative;
  }

  .color-swatch:hover { transform: scale(1.08); }
  .color-swatch.active { border-color: var(--black); }

  .color-swatch.stripe {
    background: linear-gradient(135deg, #c9a96e 50%, #6b4c2a 50%);
  }

  /* ── SIZES ── */
  .size-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 6px;
    margin-bottom: 20px;
  }

  .size-btn {
    height: 42px;
    border: 1px solid var(--light-gray);
    background: var(--white);
    font-family: var(--font-sans);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s ease;
    color: var(--black);
  }

  .size-btn:hover {
    border-color: var(--black);
  }

  .size-btn.active {
    background: var(--black);
    color: var(--white);
    border-color: var(--black);
  }

  /* ── ADD TO BAG ── */
  .add-row {
    display: grid;
    grid-template-columns: 90px 1fr;
    gap: 8px;
    margin-bottom: 14px;
  }

  .qty-select {
    height: 50px;
    border: 1px solid var(--light-gray);
    font-family: var(--font-sans);
    font-size: 14px;
    padding: 0 10px;
    background: var(--white);
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' fill='none'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%231a1917' stroke-width='1.5'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    padding-right: 30px;
  }

  .add-btn {
    height: 50px;
    background: var(--blue-accent);
    color: var(--white);
    border: none;
    font-family: var(--font-sans);
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    letter-spacing: 0.01em;
    transition: background 0.2s ease;
  }

  .add-btn:hover { background: var(--blue-hover); }

  /* ── SHIPPING INFO ── */
  .shipping-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-bottom: 20px;
  }

  .shipping-card {
    border: 1px solid var(--light-gray);
    padding: 12px 14px;
  }

  .shipping-card .sh-title {
    font-size: 13px;
    font-weight: 500;
    color: var(--black);
    margin-bottom: 4px;
  }

  .shipping-card p {
    font-size: 12px;
    color: var(--dark-gray);
    line-height: 1.4;
  }

  .shipping-card a {
    color: var(--blue-accent);
    text-decoration: none;
    font-size: 12px;
  }

  /* ── ACCORDION ── */
  .accordion-item {
    border-top: 1px solid var(--light-gray);
  }

  .accordion-item:last-child {
    border-bottom: 1px solid var(--light-gray);
  }

  .accordion-btn {
    width: 100%;
    background: none;
    border: none;
    padding: 14px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    font-family: var(--font-sans);
    font-size: 14px;
    font-weight: 500;
    color: var(--black);
  }

  .accordion-btn svg {
    transition: transform 0.25s ease;
    flex-shrink: 0;
  }

  .accordion-btn.open svg { transform: rotate(180deg); }

  .accordion-body {
    overflow: hidden;
    max-height: 0;
    transition: max-height 0.3s ease, padding 0.3s ease;
  }

  .accordion-body.open {
    max-height: 400px;
    padding-bottom: 14px;
  }

  .accordion-body ul {
    list-style: disc;
    padding-left: 18px;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .accordion-body li {
    font-size: 13px;
    color: var(--dark-gray);
    line-height: 1.5;
  }

  /* ── ALSO VIEWED ── */
  .section-title {
    font-family: var(--font-serif);
    font-size: 22px;
    font-weight: 400;
    margin-bottom: 20px;
    padding-top: 20px;
  }

  .related-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px 40px 60px;
    border-top: 1px solid var(--light-gray);
  }

  .related-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
  }

  .related-card {
    cursor: pointer;
  }

  .related-card img {
    width: 100%;
    aspect-ratio: 3/4;
    object-fit: cover;
    background: var(--off-white);
    margin-bottom: 8px;
    transition: opacity 0.2s ease;
  }

  .related-card:hover img { opacity: 0.85; }

  .dfs-chain {
    margin-bottom: 16px;
    font-size: 14px;
    color: var(--dark-gray);
    line-height: 1.5;
  }

  .related-card .rc-name {
    font-size: 13px;
    color: var(--dark-gray);
    margin-bottom: 4px;
  }

  .related-card .rc-prices {
    display: flex;
    gap: 8px;
    align-items: baseline;
    flex-wrap: wrap;
  }

  .rc-orig {
    font-size: 12px;
    color: var(--mid-gray);
    text-decoration: line-through;
  }

  .rc-sale {
    font-size: 13px;
    font-weight: 600;
    color: var(--black);
  }

  .rc-badge {
    font-size: 11px;
    color: var(--sale-red);
  }

  /* ── REVIEWS ── */
  .reviews-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px 40px 60px;
    border-top: 1px solid var(--light-gray);
  }

  .reviews-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
  }

  .reviews-meta {
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .reviews-avg {
    font-size: 36px;
    font-weight: 600;
    font-family: var(--font-serif);
  }

  .write-review-btn {
    height: 40px;
    padding: 0 20px;
    border: 1px solid var(--black);
    background: var(--white);
    font-family: var(--font-sans);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .write-review-btn:hover {
    background: var(--black);
    color: var(--white);
  }

  .bar-grid {
    display: flex;
    flex-direction: column;
    gap: 5px;
    margin-top: 10px;
  }

  .bar-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: var(--dark-gray);
  }

  .bar-track {
    flex: 1;
    height: 4px;
    background: var(--light-gray);
    border-radius: 2px;
    overflow: hidden;
  }

  .bar-fill {
    height: 100%;
    background: var(--black);
    border-radius: 2px;
  }

  .bar-count {
    width: 30px;
    text-align: right;
    font-size: 12px;
    color: var(--mid-gray);
  }

  .reviews-list {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 28px;
    margin-top: 28px;
  }

  .review-card {
    border-top: 1px solid var(--light-gray);
    padding-top: 16px;
  }

  .review-card .rv-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 6px;
  }

  .rv-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--black);
  }

  .rv-meta {
    font-size: 11px;
    color: var(--mid-gray);
    margin-bottom: 8px;
  }

  .rv-body {
    font-size: 13px;
    color: var(--dark-gray);
    line-height: 1.55;
  }

  /* ── ANIMATIONS ── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .gallery { animation: fadeUp 0.55s ease both; }
  .info-panel { animation: fadeUp 0.55s 0.1s ease both; }

  /* ── RESPONSIVE ── */
  @media (max-width: 900px) {
    .product-wrapper {
      grid-template-columns: 1fr;
      padding: 0 16px 40px;
    }
    .gallery { padding: 16px 0 0; }
    .info-panel { padding: 24px 0 0; position: static; }
    .related-grid { grid-template-columns: repeat(2, 1fr); }
    .reviews-list { grid-template-columns: 1fr; }
    .related-section, .reviews-section { padding: 20px 16px 40px; }
  }
</style>
</head>
<body>

<div id="navbar-placeholder"></div>

<!-- BREADCRUMB -->
<nav class="breadcrumb">
  <a href="#">Men</a>
  <span>›</span>
  <a href="#">T-Shirts</a>
  <span>›</span>
  <span>Adult Heavyweight Relaxed T-Shirt</span>
</nav>
 <div id="scrollTop"></div>

<!-- PRODUCT SECTION -->
<div class="product-wrapper">

  <!-- GALLERY -->
  <div class="gallery">
    <div class="gallery-main">
      <img src="https://www.gap.com/webcontent/0056/714/056/cn56714056.jpg" alt="T-shirt front" onerror="this.style.background='#d8e4f5';this.removeAttribute('src')">
      <img src="https://www.gap.com/webcontent/0056/714/058/cn56714058.jpg" alt="T-shirt full body" onerror="this.style.background='#d0ddef';this.removeAttribute('src')">
    </div>
    <div class="gallery-sub" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:4px;grid-column:1/-1">
      <img src="https://www.gap.com/webcontent/0056/714/059/cn56714059.jpg" alt="Detail 1" onerror="this.style.background='#c8d8ec';this.removeAttribute('src')" style="height:200px">
      <img src="https://www.gap.com/webcontent/0056/714/060/cn56714060.jpg" alt="Detail 2" onerror="this.style.background='#bdd0e8';this.removeAttribute('src')" style="height:200px">
      <img src="https://www.gap.com/webcontent/0056/714/061/cn56714061.jpg" alt="Detail 3" onerror="this.style.background='#b5c8e4';this.removeAttribute('src')" style="height:200px">
    </div>
  </div>

  <!-- INFO PANEL -->
  <aside class="info-panel">

    <h1 class="product-title">Adult Heavyweight Relaxed T-Shirt</h1>

    <div class="price-row">
      <span class="price-original">54.95 DT</span>
      <span class="price-current">49.00 DT</span>
    </div>
    <p class="promo-code">Extra 10% off with code <strong>ADD10</strong></p>

    <div class="ratings" id="productRatings">
      <div class="stars" id="avgStarsContainer"></div>
      <span class="ratings-count" id="totalRatingsCount">241 Ratings</span>
    </div>

    <hr class="divider">

    <!-- COLORS -->
    <div class="label-row">
      <span class="label">Color <span>Docksider blue</span></span>
    </div>
    <div class="color-grid">
      <div class="color-swatch stripe" title="Brown stripe"></div>
      <div class="color-swatch" style="background:#8b6347" title="Brown"></div>
      <div class="color-swatch" style="background:#b8b5ae" title="Gray"></div>
      <div class="color-swatch" style="background:#f0ece4" title="Cream"></div>
      <div class="color-swatch" style="background:#1a2d5a" title="Navy"></div>
      <div class="color-swatch" style="background:#1a1917" title="Black"></div>
      <div class="color-swatch" style="background:#e8d5c0" title="Beige"></div>
      <div class="color-swatch" style="background:#c8d8ec" title="Light blue"></div>
      <div class="color-swatch" style="background:#3d4a30" title="Olive"></div>
      <div class="color-swatch active" style="background:#6b8fc2" title="Docksider blue"></div>
      <div class="color-swatch" style="background:#8a8a85" title="Charcoal"></div>
      <div class="color-swatch" style="background:#2d6654" title="Forest"></div>
      <div class="color-swatch" style="background:#d4778a" title="Pink"></div>
    </div>

    <!-- SIZES -->
    <div class="label-row">
      <span class="label">Size</span>
      <span class="size-guide">Size Guide</span>
    </div>
    <div class="size-grid">
      <button class="size-btn">XS</button>
      <button class="size-btn">S</button>
      <button class="size-btn active">M</button>
      <button class="size-btn">L</button>
      <button class="size-btn">XL</button>
      <button class="size-btn">XXL</button>
    </div>

    <!-- ADD TO BAG -->
    <div class="add-row">
      <input type="number" class="qty-select" min="1" max="100" value="1">
      <button class="add-btn">Add to Bag</button>
    </div>

    <!-- SHIPPING INFO -->
    <div class="shipping-grid">
      <div class="shipping-card">
        <p class="sh-title">Free fast shipping</p>
        <p>on 50 DT+ for Rewards Members.<br><a href="#">Sign in</a> or <a href="#">Join</a></p>
      </div>
      <div class="shipping-card">
        <p class="sh-title">In-store pickup</p>
        <a href="#">Select a Store</a>
      </div>
    </div>

    <!-- ACCORDIONS -->
    <div class="accordion-item">
      <button class="accordion-btn open" onclick="toggleAccordion(this)">
        Product details
        <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M1 1l6 6 6-6" stroke="currentColor" stroke-width="1.5"/>
        </svg>
      </button>
      <div class="accordion-body open">
        <ul>
          <li>Soft, heavyweight jersey cotton relaxed T-shirt.</li>
          <li>Crewneck.</li>
          <li>Short sleeves.</li>
          <li>Product #735031</li>
        </ul>
      </div>
    </div>

    <div class="accordion-item">
      <button class="accordion-btn" onclick="toggleAccordion(this)">
        Size & fit
        <svg width="14" height="8" viewBox="0 0 14 8" fill="none">
          <path d="M1 1l6 6 6-6" stroke="currentColor" stroke-width="1.5"/>
        </svg>
      </button>
      <div class="accordion-body">
        <ul>
          <li>Fit: Men's Relaxed. A straight & easy fit with a relaxed sleeve.</li>
          <li>For a Classic fit, go down one size.</li>
          <li>Hits at the hip.</li>
          <li>Model Sizing: 5'9" wearing a size S; 6'1" wearing a size M.</li>
        </ul>
      </div>
    </div>

    <div class="accordion-item">
      <button class="accordion-btn" onclick="toggleAccordion(this)">
        Fabric & care
        <svg width="14" height="8" viewBox="0 0 14 8" fill="none">
          <path d="M1 1l6 6 6-6" stroke="currentColor" stroke-width="1.5"/>
        </svg>
      </button>
      <div class="accordion-body">
        <ul>
          <li>100% cotton heavyweight jersey.</li>
          <li>Machine wash cold, tumble dry low.</li>
          <li>Do not bleach. Iron on low heat if needed.</li>
        </ul>
      </div>
    </div>

    <div class="accordion-item">
      <button class="accordion-btn" onclick="toggleAccordion(this)">
        Shipping & returns
        <svg width="14" height="8" viewBox="0 0 14 8" fill="none">
          <path d="M1 1l6 6 6-6" stroke="currentColor" stroke-width="1.5"/>
        </svg>
      </button>
      <div class="accordion-body">
        <ul>
          <li>Free standard shipping on orders over 50 DT.</li>
          <li>Free returns within 30 days of purchase.</li>
          <li>Final sale items cannot be returned or exchanged.</li>
        </ul>
      </div>
    </div>

  </aside>
</div>

<!-- ALSO VIEWED -->
<section class="related-section">
  <h2 class="section-title">Produits similaires</h2>
  <div class="dfs-chain" id="dfsChainText">Chargement de la chaîne de découverte...</div>
  <div class="related-grid" id="recommendationsGrid">
    <p>Chargement des recommandations...</p>
  </div>
</section>

<!-- REVIEWS -->
<section class="reviews-section" id="reviewsSection">
  <div class="reviews-header">
    <div class="reviews-meta">
      <span class="reviews-avg" id="avgRatingDisplay">4.4</span>
      <div>
        <div class="stars" id="avgStarsLarge" style="margin-bottom:4px"></div>
        <div style="font-size:12px;color:var(--mid-gray)" id="totalReviewsText">248 Star Ratings</div>
        <div class="bar-grid" id="ratingBars"></div>
      </div>
    </div>
    <button class="write-review-btn" id="writeReviewBtn">Write a Review</button>
  </div>

  <div class="reviews-list" id="dynamicReviewsList"></div>
</section>
<div id="reviewModal" class="modal-overlay">
  <div class="review-modal">
    <button class="modal-close" id="closeModalBtn">&times;</button>
    <h3>Share your thoughts</h3>
    <form id="reviewForm">
      <div class="form-group">
        <label>Your rating *</label>
        <div class="star-rating-input" id="starRatingInput">
          <svg class="star-select" data-value="1" viewBox="0 0 24 24"><polygon points="12,2 15.1,8.3 22,9.2 16.9,14 18.6,20.8 12,17.3 5.4,20.8 7.1,14 2,9.2 8.9,8.3"/></svg>
          <svg class="star-select" data-value="2" viewBox="0 0 24 24"><polygon points="12,2 15.1,8.3 22,9.2 16.9,14 18.6,20.8 12,17.3 5.4,20.8 7.1,14 2,9.2 8.9,8.3"/></svg>
          <svg class="star-select" data-value="3" viewBox="0 0 24 24"><polygon points="12,2 15.1,8.3 22,9.2 16.9,14 18.6,20.8 12,17.3 5.4,20.8 7.1,14 2,9.2 8.9,8.3"/></svg>
          <svg class="star-select" data-value="4" viewBox="0 0 24 24"><polygon points="12,2 15.1,8.3 22,9.2 16.9,14 18.6,20.8 12,17.3 5.4,20.8 7.1,14 2,9.2 8.9,8.3"/></svg>
          <svg class="star-select" data-value="5" viewBox="0 0 24 24"><polygon points="12,2 15.1,8.3 22,9.2 16.9,14 18.6,20.8 12,17.3 5.4,20.8 7.1,14 2,9.2 8.9,8.3"/></svg>
        </div>
        <input type="hidden" id="selectedRating" value="0">
        <span class="error-msg" id="ratingError"></span>
      </div>
      <div class="form-group">
        <label>Review title *</label>
        <input type="text" id="reviewTitle" placeholder="e.g., Great quality">
        <span class="error-msg" id="titleError"></span>
      </div>
      <div class="form-group">
        <label>Your review *</label>
        <textarea id="reviewBody" placeholder="Tell others about this product..."></textarea>
        <span class="error-msg" id="bodyError"></span>
      </div>
      <input type="hidden" id="reviewProductId" value="">
      <div class="form-group">
        <label>Name (optional)</label>
        <input type="text" id="reviewerName" placeholder="Anonymous">
      </div>
      <button type="submit" class="modal-submit">Submit Review</button>
    </form>
  </div>
</div>


  <div id="footer-placeholder"></div>

  <script src="../javaScript/main.js"></script>


<script>
  // Accordion
  function toggleAccordion(btn) {
    const body = btn.nextElementSibling;
    const isOpen = body.classList.contains('open');
    btn.classList.toggle('open', !isOpen);
    body.classList.toggle('open', !isOpen);
  }

  // Size selection
  document.querySelectorAll('.size-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });

  // Color swatch selection
  document.querySelectorAll('.color-swatch').forEach(sw => {
    sw.addEventListener('click', () => {
      document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('active'));
      sw.classList.add('active');
    });
  });
</script>


<script src="../javaScript/review.js"></script>







<script>
    async function chargeProduit() {
        const params = new URLSearchParams(window.location.search);
        const productId = params.get('id');
        const productType = params.get('type');
        
        if (!productId) {
            console.error("Aucun ID de produit spécifié dans l'URL");
            return;
        }

        if (productType === "tech") {
            // Hide size-related elements for tech products
            const labels = document.querySelectorAll('.label-row');
            labels.forEach(label => {
                if (label.innerText.toLowerCase().includes('size')) {
                    label.style.display = 'none';
                }
                if (label.innerText.toLowerCase().includes('color')) {
                    label.style.display = 'none';
                }
            });
            
            const sizeGrid = document.querySelector('.size-grid');
            if (sizeGrid) sizeGrid.style.display = 'none';
            
            const colorGrid = document.querySelector('.color-grid');
            if (colorGrid) colorGrid.style.display = 'none';
            
            const accordions = document.querySelectorAll('.accordion-item');
            accordions.forEach(acc => {
                if (acc.innerText.includes('Size & fit')) {
                    acc.style.display = 'none';
                }
            });
        }

        try {
            let action = "getAllimage";
            if (productType === "tech") {
                action = "getTechProduitDetail";
            }
            
            const response = await fetch(`../../index.php?action=${action}&id=${productId}`);
            const data = await response.json();
            
            if (data && data.length > 0) {
                const product = data[0];
                console.log('Données du produit chargées:', product);
                
                // Mise à jour des textes
                document.querySelector('.product-title').textContent = product.name;
                document.querySelector('.price-current').textContent = `${product.price} DT`;
                
                // Update Breadcrumb
                const breadcrumb = document.querySelector('.breadcrumb');
                if (breadcrumb) {
                    if (productType === "tech") {
                        breadcrumb.innerHTML = `
                            <a href="tech.html">Tech</a>
                            <span>›</span>
                            <a href="#">${product.category || 'Gadgets'}</a>
                            <span>›</span>
                            <span>${product.name}</span>
                        `;
                    } else {
                        breadcrumb.innerHTML = `
                            <a href="men.html">Men</a>
                            <span>›</span>
                            <a href="#">${product.category || 'Clothing'}</a>
                            <span>›</span>
                            <span>${product.name}</span>
                        `;
                    }
                }

                document.title = product.name; // Update browser tab title
                
                if (product.description) {
                    const accordionUl = document.querySelector('.accordion-item:first-of-type .accordion-body ul');
                    if (accordionUl) {
                        if (productType === "tech") {
                            accordionUl.innerHTML = `<li>${product.description}</li>`;
                            if (product.sku) {
                                accordionUl.innerHTML += `<li>Product #${product.sku}</li>`;
                            }
                        } else {
                            const descElem = accordionUl.querySelector('li:first-child');
                            if (descElem) descElem.textContent = product.description;
                        }
                    }
                }

                // Galerie d'images
                const images = product.images || [product.image_path];
                const galleryMain = document.querySelector('.gallery-main');
                const gallerySub = document.querySelector('.gallery-sub');

                if (galleryMain) galleryMain.innerHTML = '';
                if (gallerySub) gallerySub.innerHTML = '';

                images.forEach((imgPath, index) => {
                    const isExternal = imgPath && (imgPath.startsWith('http://') || imgPath.startsWith('https://'));
                    const fullPath = isExternal ? imgPath : `../../public/${imgPath}`;
                    const img = document.createElement('img');
                    img.src = fullPath;
                    img.alt = `${product.name} - image ${index + 1}`;
                    img.onerror = function() {
                        this.style.background = '#f2f2f2';
                        this.removeAttribute('src');
                    };

                    if (index < 2) {
                        // Deux premières images dans la section principale
                        if (galleryMain) galleryMain.appendChild(img);
                    } else if (index < 5) {
                        // Les 3 suivantes dans la section sub
                        img.style.height = '400px';
                        if (gallerySub) gallerySub.appendChild(img);
                    }
                });
            }
        }
        catch(error) {
            console.error('Erreur lors de la récupération du produit:', error);
        }
    }

    chargeProduit();
    loadRecommendations();

    async function loadRecommendations() {
        const params = new URLSearchParams(window.location.search);
        const productId = params.get('id');
        const productType = params.get('type');
        if (!productId) {
            return;
        }

        try {
            const response = await fetch(`../../index.php?action=getRecommendations&id=${productId}${productType ? `&type=${productType}` : ''}`);
            const data = await response.json();
            const grid = document.getElementById('recommendationsGrid');
            const dfsText = document.getElementById('dfsChainText');

            if (!grid) return;
            if (!data || !Array.isArray(data.recommendations)) {
                grid.innerHTML = '<p>Aucune recommandation disponible pour le moment.</p>';
                if (dfsText) dfsText.textContent = '';
                return;
            }

            const recommendations = data.recommendations;
            if (recommendations.length === 0) {
                grid.innerHTML = '<p>Aucune recommandation trouvée.</p>';
            } else {
                grid.innerHTML = recommendations.map(prod => {
                    const isExternal = prod.image_path && (prod.image_path.startsWith('http://') || prod.image_path.startsWith('https://'));
                    const imageUrl = prod.image_path ? (isExternal ? prod.image_path : `../../public/${prod.image_path}`) : 'https://via.placeholder.com/320x420?text=No+Image';
                    const categoryLabel = prod.category ? prod.category : prod.category_id ? prod.category_id : 'Produit';
                    return `
                        <a href="product_details.php?id=${encodeURIComponent(prod.id)}${productType ? `&type=${productType}` : ''}" class="related-card">
                            <img src="${imageUrl}" alt="${escapeHtml(prod.name)}" onerror="this.style.background='#f2f2f2';this.removeAttribute('src')">
                            <p class="rc-name">${escapeHtml(prod.name)}</p>
                            <div class="rc-prices">
                                <span class="rc-sale">${prod.price} DT</span>
                                <span class="rc-badge">${escapeHtml(categoryLabel)}</span>
                            </div>
                        </a>`;
                }).join('');
            }

            if (dfsText) {
                if (Array.isArray(data.dfsChain) && data.dfsChain.length) {
                    dfsText.textContent = 'Chaîne de découverte : ' + data.dfsChain.map(item => item.name).join(' → ');
                } else {
                    dfsText.textContent = 'Chaîne de découverte indisponible pour ce produit.';
                }
            }
        } catch (error) {
            console.error('Erreur de recommandations :', error);
            const grid = document.getElementById('recommendationsGrid');
            if (grid) grid.innerHTML = '<p>Impossible de charger les recommandations.</p>';
        }
    }

    function escapeHtml(text) {
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
</script>

<script src="../javaScript/carts.js"></script>


</body>
</html>