<?php
$pageTitle  = 'Modifier le produit';
$breadcrumb = 'Produits / Modifier';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="content-area">
        <div class="row justify-content-center">
            <div class="col-xl-8">

                <div class="d-flex align-items-center gap-3 mb-4">
                    <a href="index.php?page=products" class="btn btn-light btn-sm" style="border-radius:10px;">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <h2 class="fw-800 mb-0">Modifier le produit</h2>
                        <p class="text-muted mb-0" style="font-size:14px;">#<?= $product['id'] ?> — <?= htmlspecialchars($product['name']) ?></p>
                    </div>
                </div>

                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger mb-4" style="border-radius:12px;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Erreurs :</strong>
                    <ul class="mb-0 mt-1">
                        <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="bi bi-pencil-fill me-2 text-warning"></i>Informations du produit
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-600">Nom du produit <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                           value="<?= htmlspecialchars($_POST['name'] ?? $product['name']) ?>" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-600">Description</label>
                                    <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($_POST['description'] ?? $product['description']) ?></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-600">Prix (€) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">€</span>
                                        <input type="number" name="price" class="form-control"
                                               step="0.01" min="0"
                                               value="<?= htmlspecialchars($_POST['price'] ?? $product['price']) ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-600">Stock</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-boxes"></i></span>
                                        <input type="number" name="stock" class="form-control" min="0"
                                               value="<?= htmlspecialchars($_POST['stock'] ?? $product['stock']) ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-600">Catégorie</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">— Aucune catégorie —</option>
                                        <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"
                                            <?= (($_POST['category_id'] ?? $product['category_id']) == $cat['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Image actuelle + nouvel upload -->
                                <div class="col-12">
                                    <label class="form-label fw-600">Image du produit</label>
                                    <?php if (!empty($product['image']) && file_exists(__DIR__ . '/../../' . $product['image'])): ?>
                                    <div class="d-flex align-items-center gap-3 mb-3 p-3"
                                         style="background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;">
                                        <img src="../../<?= htmlspecialchars($product['image']) ?>"
                                             style="width:70px;height:70px;object-fit:cover;border-radius:10px;">
                                        <div>
                                            <div class="fw-600" style="font-size:13px;">Image actuelle</div>
                                            <div class="text-muted" style="font-size:12px;">Uploadez une nouvelle image pour la remplacer</div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <div class="border-2 border-dashed rounded-3 p-4 text-center"
                                         style="border-color:#cbd5e1;background:#f8fafc;cursor:pointer;"
                                         onclick="document.getElementById('imageInput').click()">
                                        <i class="bi bi-cloud-upload fs-2 text-secondary"></i>
                                        <div class="fw-600 mt-2">Cliquez pour changer l'image</div>
                                        <div class="text-muted" style="font-size:12px;">JPG, PNG, WebP — 5 Mo max</div>
                                        <div id="fileNameDisplay" class="text-primary mt-2" style="font-size:13px;display:none;"></div>
                                    </div>
                                    <input type="file" name="image" id="imageInput" class="d-none"
                                           accept="image/jpeg,image/png,image/webp"
                                           onchange="showFileName(this)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="index.php?page=products" class="btn btn-light px-4" style="border-radius:10px;">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-warning px-4" style="border-radius:10px;">
                            <i class="bi bi-check-circle-fill me-2"></i>Enregistrer les modifications
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
function showFileName(input) {
    const el = document.getElementById('fileNameDisplay');
    if (input.files.length > 0) {
        el.textContent = '✓ ' + input.files[0].name;
        el.style.display = 'block';
    }
}
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
