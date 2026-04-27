<?php
$pageTitle  = 'Utilisateurs';
$breadcrumb = 'Utilisateurs';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="content-area">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-800 mb-0">Gestion des Utilisateurs</h2>
                <p class="text-muted mb-0" style="font-size:14px;"><?= count($users) ?> utilisateurs enregistrés</p>
            </div>
            <!-- Compteurs rapides -->
            <div class="d-flex gap-2">
                <?php
                $activeCount   = count(array_filter($users, fn($u) => $u['is_active']));
                $inactiveCount = count($users) - $activeCount;
                ?>
                <span class="badge px-3 py-2" style="background:#dcfce7;color:#166534;font-size:13px;border-radius:8px;">
                    <i class="bi bi-check-circle-fill me-1"></i><?= $activeCount ?> actifs
                </span>
                <span class="badge px-3 py-2" style="background:#fee2e2;color:#991b1b;font-size:13px;border-radius:8px;">
                    <i class="bi bi-x-circle-fill me-1"></i><?= $inactiveCount ?> inactifs
                </span>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people-fill me-2 text-primary"></i>Liste des utilisateurs</span>
                <input type="text" id="searchInput" class="form-control form-control-sm"
                       placeholder="Rechercher..." style="max-width:220px;border-radius:8px;">
            </div>
            <div class="table-responsive">
                <table class="table mb-0" id="usersTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Utilisateur</th>
                            <th>Email</th>
                            <th>Commandes</th>
                            <th>Total dépensé</th>
                            <th>Statut</th>
                            <th>Inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2"></i>Aucun utilisateur.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($users as $u):
                            if ($u['role'] === 'admin') continue; // ne pas afficher l'admin lui-même
                        ?>
                        <tr>
                            <td class="text-muted" style="font-size:12px;"><?= $u['id'] ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Avatar avec initiale -->
                                    <div style="width:36px;height:36px;border-radius:50%;
                                                background:linear-gradient(135deg,#4f46e5,#7c3aed);
                                                display:flex;align-items:center;justify-content:center;
                                                color:#fff;font-weight:700;font-size:14px;flex-shrink:0;">
                                        <?= strtoupper(mb_substr($u['name'], 0, 1)) ?>
                                    </div>
                                    <span class="fw-600"><?= htmlspecialchars($u['name']) ?></span>
                                </div>
                            </td>
                            <td class="text-muted" style="font-size:13px;"><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <span class="badge bg-light text-dark fw-600"><?= $u['order_count'] ?></span>
                            </td>
                            <td class="fw-600 text-success"><?= number_format($u['total_spent'], 2, ',', ' ') ?> €</td>
                            <td>
                                <?php if ($u['is_active']): ?>
                                <span class="status-badge status-delivered">
                                    <i class="bi bi-check-circle-fill me-1"></i>Actif
                                </span>
                                <?php else: ?>
                                <span class="status-badge status-cancelled">
                                    <i class="bi bi-x-circle-fill me-1"></i>Inactif
                                </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted" style="font-size:12px;">
                                <?= !empty($u['created_at']) ? date('d/m/Y', strtotime($u['created_at'])) : '-' ?>
                            </td>
                            <td>
                                <a href="index.php?page=users&action=toggle&id=<?= $u['id'] ?>"
                                   class="btn btn-sm <?= $u['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?> me-1"
                                   title="<?= $u['is_active'] ? 'Désactiver' : 'Activer' ?>"
                                   style="border-radius:8px;">
                                    <i class="bi <?= $u['is_active'] ? 'bi-pause-circle-fill' : 'bi-play-circle-fill' ?>"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDelete(<?= $u['id'] ?>, '<?= htmlspecialchars(addslashes($u['name'])) ?>')"
                                        style="border-radius:8px;" title="Supprimer">
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

<!-- Modale suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-700">Supprimer l'utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-person-x-fill text-danger fs-3"></i>
                </div>
                <p>Supprimer l'utilisateur <strong id="deleteName"></strong> ?<br>
                <span class="text-muted" style="font-size:13px;">Ses commandes seront également supprimées.</span></p>
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
    document.getElementById('deleteBtn').href = 'index.php?page=users&action=delete&id=' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
