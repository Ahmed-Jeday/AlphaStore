<?php $currentPage = $_GET['page'] ?? 'dashboard'; ?>
<aside class="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-bag-heart-fill"></i></div>
        <div>
            <h5>ShopAdmin</h5>
            <small>Panneau d'administration</small>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-2">
        <div class="sidebar-section">Principal</div>

        <a href="index.php?page=dashboard"
           class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2-fill"></i>
            Tableau de bord
        </a>

        <div class="sidebar-section">Gestion</div>

        <a href="index.php?page=products"
           class="<?= $currentPage === 'products' ? 'active' : '' ?>">
            <i class="bi bi-box-seam-fill"></i>
            Produits
        </a>

        <a href="index.php?page=users"
           class="<?= $currentPage === 'users' ? 'active' : '' ?>">
            <i class="bi bi-people-fill"></i>
            Utilisateurs
        </a>

        <a href="index.php?page=orders"
           class="<?= $currentPage === 'orders' ? 'active' : '' ?>">
            <i class="bi bi-receipt-cutoff"></i>
            Commandes
        </a>

        <div class="sidebar-section">Analyse</div>

        <a href="index.php?page=stats"
           class="<?= $currentPage === 'stats' ? 'active' : '' ?>">
            <i class="bi bi-bar-chart-line-fill"></i>
            Statistiques
        </a>

        <a href="index.php?page=favorites"
           class="<?= $currentPage === 'favorites' ? 'active' : '' ?>">
            <i class="bi bi-heart-fill"></i>
            Favoris
        </a>
    </nav>

    <!-- Version info -->
    <div class="px-4 py-3" style="position:absolute;bottom:0;left:0;right:0;border-top:1px solid rgba(255,255,255,.07);">
        <small class="text-secondary" style="font-size:11px;">ShopAdmin v1.0 — PFA 2025</small>
    </div>
</aside>
