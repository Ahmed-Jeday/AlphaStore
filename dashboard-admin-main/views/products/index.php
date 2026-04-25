<?php
$pageTitle  = 'Produits';
$breadcrumb = 'Produits';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="content-area">

        <!-- ── En-tête ─────────────────────────────────────── -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-800 mb-0" style="color:#0f172a;">Gestion des Produits</h2>
                <p class="text-muted mb-0" style="font-size:14px;"><?= count($products) ?> produits au total</p>
            </div>
            <a href="index.php?page=products&action=create" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:10px;">
                <i class="bi bi-plus-circle-fill"></i>
                Ajouter un produit
            </a>
        </div>

        <!-- ── Alerte stock faible ───────────────────────────── -->
        <?php if (!empty($lowStock)): ?>
        <div class="alert mb-4" style="background:#fff7ed;border:1px solid #fdba74;border-radius:12px;color:#9a3412;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong><?= count($lowStock) ?> produit(s) avec stock ≤ 5 unités</strong> — Pensez à réapprovisionner.
        </div>
        <?php endif; ?>

        <!-- ── Table des produits ────────────────────────────── -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-box-seam-fill me-2 text-primary"></i>Liste des produits</span>
                    <!-- Barre de recherche rapide -->
                    <input type="text" id="searchInput" class="form-control form-control-sm"
                           placeholder="Rechercher un produit..."
                           style="max-width:220px;border-radius:8px;">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table mb-0" id="productsTable">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th style="width:60px;">Image</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Stock</th>
                            <th>Date</th>
                            <th style="width:120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                                Aucun produit trouvé.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($products as $p): ?>
                        <tr>
                            <td class="text-muted" style="font-size:12px;"><?= $p['id'] ?></td>
                            <td>
                                <?php if (!empty($p['image_path']) && file_exists(__DIR__ . '/../../' . $p['image_path'])): ?>
                                    <img src="../../<?= htmlspecialchars($p['image_path']) ?>"
                                         class="product-img-thumb" alt="">
                                <?php else: ?>
                                    <div class="product-img-placeholder">
                                        <i class="bi bi-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-600"><?= htmlspecialchars($p['name']) ?></span>
                                <?php if (!empty($p['description'])): ?>
                                <div class="text-muted" style="font-size:11px;">
                                    <?= mb_strimwidth(htmlspecialchars($p['description']), 0, 50, '...') ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary" style="font-size:12px;">
                                    <?= htmlspecialchars($p['category_name'] ?? '—') ?>
                                </span>
                            </td>
                            <td class="fw-700"><?= number_format($p['price'], 2, ',', ' ') ?> €</td>
                            <td>
                                <?php
                                $stock = (int)$p['stock'];
                                $cls   = $stock === 0 ? 'stock-zero' : ($stock <= 5 ? 'stock-low' : 'stock-ok');
                                $icon  = $stock === 0 ? 'bi-x-circle-fill' : ($stock <= 5 ? 'bi-exclamation-circle-fill' : 'bi-check-circle-fill');
                                ?>
                                <span class="<?= $cls ?>">
                                    <i class="bi <?= $icon ?> me-1"></i><?= $stock ?>
                                </span>
                            </td>
                            <td class="text-muted" style="font-size:12px;">
                                <?= date('d/m/Y', strtotime($p['created_at'])) ?>
                            </td>
                            <td>
                                <a href="index.php?page=products&action=edit&id=<?= $p['id'] ?>"
                                   class="btn btn-sm btn-outline-primary me-1" title="Modifier" style="border-radius:8px;">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDelete(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['name'])) ?>')"
                                        title="Supprimer" style="border-radius:8px;">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
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

<!-- Modale confirmation suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-700">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash-fill text-danger fs-3"></i>
                </div>
                <p class="mb-0">Voulez-vous vraiment supprimer le produit<br>
                <strong id="deleteName"></strong> ?</p>
                <p class="text-danger mt-2 mb-0" style="font-size:13px;">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2">
                <button class="btn btn-light" data-bs-dismiss="modal" style="border-radius:10px;">Annuler</button>
                <a id="deleteBtn" href="#" class="btn btn-danger" style="border-radius:10px;">
                    <i class="bi bi-trash-fill me-1"></i>Supprimer
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteName').textContent = name;
    document.getElementById('deleteBtn').href = 'index.php?page=products&action=delete&id=' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Recherche rapide côté client
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#productsTable tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
    });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
