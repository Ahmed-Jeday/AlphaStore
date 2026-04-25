<?php
$pageTitle  = 'Détail commande #' . str_pad($order['id'], 4, '0', STR_PAD_LEFT);
$breadcrumb = 'Commandes / Détail';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';

$statusLabels = [
    'pending'    => 'En attente',
    'processing' => 'En cours',
    'shipped'    => 'Expédié',
    'delivered'  => 'Livré',
    'cancelled'  => 'Annulé',
];
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="content-area">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="index.php?page=orders" class="btn btn-light btn-sm" style="border-radius:10px;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="flex-grow-1">
                <h2 class="fw-800 mb-0">
                    Commande #<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?>
                </h2>
                <p class="text-muted mb-0" style="font-size:13px;">
                    Passée le <?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?>
                </p>
            </div>
            <!-- Badge statut actuel -->
            <span class="status-badge status-<?= $order['status'] ?>" style="font-size:14px;padding:8px 16px;">
                <?= $statusLabels[$order['status']] ?>
            </span>
        </div>

        <div class="row g-3">
            <!-- Colonne principale -->
            <div class="col-lg-8">

                <!-- Articles commandés -->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="bi bi-cart-fill me-2 text-primary"></i>Articles de la commande
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Qté</th>
                                    <th class="text-end">Prix unitaire</th>
                                    <th class="text-end">Sous-total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($item['product_image']) && file_exists(__DIR__ . '/../../' . $item['product_image'])): ?>
                                                <img src="../../<?= htmlspecialchars($item['product_image']) ?>"
                                                     class="product-img-thumb" alt="">
                                            <?php else: ?>
                                                <div class="product-img-placeholder">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            <?php endif; ?>
                                            <span class="fw-600"><?= htmlspecialchars($item['product_name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark fw-600">× <?= $item['quantity'] ?></span>
                                    </td>
                                    <td class="text-end text-muted"><?= number_format($item['unit_price'], 2, ',', ' ') ?> DT</td>
                                    <td class="text-end fw-700">
                                        <?= number_format($item['quantity'] * $item['unit_price'], 2, ',', ' ') ?> DT
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot style="background:#f8fafc;">
                                <tr>
                                    <td colspan="3" class="text-end fw-700 py-3">TOTAL</td>
                                    <td class="text-end fw-800 text-primary py-3" style="font-size:18px;">
                                        <?= number_format($order['total'], 2, ',', ' ') ?> DT
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Changer statut -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-arrow-repeat me-2 text-warning"></i>Mettre à jour le statut
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="index.php?page=orders&action=update_status">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <div class="row g-3 align-items-end">
                                <div class="col-sm-8">
                                    <label class="form-label fw-600">Nouveau statut</label>
                                    <select name="status" class="form-select">
                                        <?php foreach ($statusLabels as $val => $label): ?>
                                        <option value="<?= $val ?>"
                                            <?= $order['status'] === $val ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <button type="submit" class="btn btn-warning w-100" style="border-radius:10px;">
                                        <i class="bi bi-check-circle-fill me-2"></i>Mettre à jour
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Timeline statuts -->
                        <div class="d-flex align-items-center gap-2 mt-4" style="overflow-x:auto;padding-bottom:4px;">
                            <?php
                            $allStatuses = ['pending','processing','shipped','delivered'];
                            $currentIdx  = array_search($order['status'], $allStatuses);
                            foreach ($allStatuses as $i => $s):
                                $done    = $i <= $currentIdx;
                                $current = $i === $currentIdx;
                            ?>
                            <div class="text-center" style="min-width:90px;">
                                <div style="width:36px;height:36px;border-radius:50%;
                                            background:<?= $done ? '#4f46e5' : '#e2e8f0' ?>;
                                            color:<?= $done ? '#fff' : '#94a3b8' ?>;
                                            display:flex;align-items:center;justify-content:center;
                                            margin:0 auto;font-size:14px;
                                            <?= $current ? 'box-shadow:0 0 0 4px rgba(79,70,229,.2);' : '' ?>">
                                    <i class="bi bi-check<?= $done ? '-lg' : '' ?>"></i>
                                </div>
                                <div style="font-size:11px;margin-top:4px;color:<?= $done ? '#4f46e5' : '#94a3b8' ?>;font-weight:<?= $current ? '700' : '500' ?>;">
                                    <?= $statusLabels[$s] ?>
                                </div>
                            </div>
                            <?php if ($i < count($allStatuses) - 1): ?>
                            <div style="flex:1;height:2px;background:<?= $i < $currentIdx ? '#4f46e5' : '#e2e8f0' ?>;min-width:30px;"></div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Colonne infos client -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-person-fill me-2 text-info"></i>Informations client
                    </div>
                    <div class="card-body p-4">
                        <div style="width:56px;height:56px;border-radius:50%;
                                    background:linear-gradient(135deg,#4f46e5,#7c3aed);
                                    display:flex;align-items:center;justify-content:center;
                                    color:#fff;font-weight:800;font-size:22px;margin-bottom:16px;">
                            <?= strtoupper(mb_substr($order['user_name'], 0, 1)) ?>
                        </div>
                        <div class="fw-700 mb-1" style="font-size:16px;">
                            <?= htmlspecialchars($order['user_name']) ?>
                        </div>
                        <div class="text-muted mb-3" style="font-size:13px;">
                            <?= htmlspecialchars($order['user_email']) ?>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted" style="font-size:13px;">ID commande</span>
                            <span class="fw-600">#<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted" style="font-size:13px;">Montant total</span>
                            <span class="fw-700 text-success"><?= number_format($order['total'], 2, ',', ' ') ?> €</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted" style="font-size:13px;">Date</span>
                            <span class="fw-600" style="font-size:13px;">
                                <?= date('d/m/Y', strtotime($order['created_at'])) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
