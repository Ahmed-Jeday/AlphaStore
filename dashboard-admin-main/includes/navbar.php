<nav class="topnav">
    <div class="d-flex align-items-center gap-3">
        <!-- Toggle mobile -->
        <button class="btn btn-light btn-sm d-md-none" id="sidebarToggle">
            <i class="bi bi-list fs-5"></i>
        </button>

        <div>
            <h1 class="page-title"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h1>
            <?php if (!empty($breadcrumb)): ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="index.php" class="text-decoration-none text-secondary">Accueil</a>
                    </li>
                    <li class="breadcrumb-item active text-secondary">
                        <?= htmlspecialchars($breadcrumb) ?>
                    </li>
                </ol>
            </nav>
            <?php endif; ?>
        </div>
    </div>

    <div class="admin-info">
        <!-- Notification stock faible -->
        <button class="btn btn-light btn-sm position-relative" title="Alertes">
            <i class="bi bi-bell-fill"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:9px;">!</span>
        </button>

        <!-- Admin dropdown -->
        <div class="dropdown">
            <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center gap-2"
                    data-bs-toggle="dropdown">
                <div class="admin-avatar">
                    <?= strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)) ?>
                </div>
                <span class="d-none d-md-inline fw-600">
                    <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?>
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:12px;">
                <li>
                    <span class="dropdown-item-text text-muted small">
                        <?= htmlspecialchars($_SESSION['admin_email'] ?? '') ?>
                    </span>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="index.php?action=logout">
                        <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Flash message -->
<?php if (!empty($_SESSION['flash'])): ?>
<div class="flash-message" id="flashMsg">
    <div class="alert alert-<?= $_SESSION['flash']['type'] ?> alert-dismissible shadow-sm mb-0" role="alert">
        <i class="bi bi-<?= $_SESSION['flash']['type'] === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill' ?> me-2"></i>
        <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php unset($_SESSION['flash']); endif; ?>

<script>
// Auto-dismiss flash after 4s
setTimeout(() => {
    const f = document.getElementById('flashMsg');
    if (f) f.style.opacity = '0', setTimeout(() => f.remove(), 300);
}, 4000);

// Sidebar mobile toggle
document.getElementById('sidebarToggle')?.addEventListener('click', () => {
    document.querySelector('.sidebar').classList.toggle('open');
});
</script>
