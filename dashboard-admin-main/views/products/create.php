<?php
$pageTitle  = 'Ajouter un produit';
$breadcrumb = 'Produits / Ajouter';
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
                        <h2 class="fw-800 mb-0">Ajouter un produit</h2>
                        <p class="text-muted mb-0" style="font-size:14px;">Remplissez le formulaire ci-dessous</p>
                    </div>
                </div>

                <!-- Erreurs -->
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger mb-4" style="border-radius:12px;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Erreurs à corriger :</strong>
                    <ul class="mb-0 mt-1">
                        <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-info-circle me-2 text-primary"></i>Informations générales
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <!-- Nom -->
                                <div class="col-12">
                                    <label class="form-label fw-600">Nom du produit <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                           placeholder="Ex: iPhone 15 Pro"
                                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label class="form-label fw-600">Description</label>
                                    <textarea name="description" class="form-control" rows="3"
                                              placeholder="Décrivez votre produit..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                                </div>

                                <!-- Prix & Stock -->
                                <div class="col-md-6">
                                    <label class="form-label fw-600">Prix (DT) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">DT</span>
                                        <input type="number" name="price" class="form-control"
                                               placeholder="0.00" min="0" step="0.01"
                                               value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-600">Stock <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-boxes"></i></span>
                                        <input type="number" name="stock" class="form-control"
                                               placeholder="0" min="0"
                                               value="<?= htmlspecialchars($_POST['stock'] ?? '0') ?>" required>
                                    </div>
                                </div>

                                <!-- Catégorie -->
                                <div class="col-md-6">
                                    <label class="form-label fw-600">Catégorie</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">— Aucune catégorie —</option>
                                        <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"
                                            <?= (($_POST['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Image upload -->
                                <div class="col-12">
                                    <label class="form-label fw-600">Image du produit</label>
                                    <div class="border-2 border-dashed rounded-3 p-4 text-center"
                                         style="border-color:#cbd5e1;background:#f8fafc;cursor:pointer;"
                                         onclick="document.getElementById('imageInput').click()">
                                        <i class="bi bi-cloud-upload fs-2 text-secondary"></i>
                                        <div class="fw-600 mt-2">Cliquez pour uploader</div>
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

                    <!-- Boutons -->
                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="index.php?page=products" class="btn btn-light px-4" style="border-radius:10px;">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary px-4" style="border-radius:10px;">
                            <i class="bi bi-plus-circle-fill me-2"></i>Ajouter le produit
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
