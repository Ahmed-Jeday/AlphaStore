<?php
$pageTitle  = 'Commandes';
$breadcrumb = 'Commandes';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';

// Compter les statuts
$statusCounts = [];
foreach ($orders as $o) {
    $statusCounts[$o['status']] = ($statusCounts[$o['status']] ?? 0) + 1;
}
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="content-area">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-800 mb-0">Gestion des Commandes</h2>
                <p class="text-muted mb-0" style="font-size:14px;"><?= count($orders) ?> commandes au total</p>
            </div>
            <!-- Filtres rapides statuts -->
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-sm filter-btn active" data-filter="all"
                        style="border-radius:8px;background:#0f172a;color:#fff;font-size:12px;">
                    Tout (<?= count($orders) ?>)
                </button>
                <?php
                $statusLabels = [
                    'pending'    => ['label'=>'En attente',  'class'=>'status-pending'],
                    'processing' => ['label'=>'En cours',    'class'=>'status-processing'],
                    'shipped'    => ['label'=>'Expédié',     'class'=>'status-shipped'],
                    'delivered'  => ['label'=>'Livré',       'class'=>'status-delivered'],
                    'cancelled'  => ['label'=>'Annulé',      'class'=>'status-cancelled'],
                ];
                foreach ($statusLabels as $key => $info):
                    if (isset($statusCounts[$key])): ?>
                <button class="btn btn-sm filter-btn status-badge <?= $info['class'] ?>"
                        data-filter="<?= $key ?>" style="font-size:12px;">
                    <?= $info['label'] ?> (<?= $statusCounts[$key] ?>)
                </button>
                <?php endif; endforeach; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-receipt-cutoff me-2 text-primary"></i>Liste des commandes
            </div>
            <div class="table-responsive">
                <table class="table mb-0" id="ordersTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Articles</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-receipt fs-1 d-block mb-2"></i>Aucune commande.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                        <tr data-status="<?= $order['status'] ?>">
                            <td>
                                <span class="fw-700 text-primary">#<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?></span>
                            </td>
                            <td>
                                <div class="fw-600" style="font-size:14px;"><?= htmlspecialchars($order['user_name']) ?></div>
                                <div class="text-muted" style="font-size:11px;"><?= htmlspecialchars($order['user_email']) ?></div>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?= $order['items_count'] ?> article(s)</span>
                            </td>
                            <td class="fw-700"><?= number_format($order['total'], 2, ',', ' ') ?> €</td>
                            <td>
                                <span class="status-badge status-<?= $order['status'] ?>">
                                    <?= $statusLabels[$order['status']]['label'] ?? $order['status'] ?>
                                </span>
                            </td>
                            <td class="text-muted" style="font-size:12px;">
                                <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                            </td>
                            <td>
                                <a href="index.php?page=orders&action=show&id=<?= $order['id'] ?>"
                                   class="btn btn-sm btn-outline-primary" style="border-radius:8px;" title="Voir détails">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
// Filtrage par statut
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.classList.remove('active');
            b.style.opacity = '0.7';
        });
        this.classList.add('active');
        this.style.opacity = '1';

        const filter = this.dataset.filter;
        document.querySelectorAll('#ordersTable tbody tr').forEach(row => {
            row.style.display = (filter === 'all' || row.dataset.status === filter) ? '' : 'none';
        });
    });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
