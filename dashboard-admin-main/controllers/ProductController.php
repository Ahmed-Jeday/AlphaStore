<?php
/**
 * Contrôleur Produits
 * Gère les actions CRUD : list, create, edit, delete
 */

require_once __DIR__ . '/../models/Product.php';

class ProductController {
    private Product $model;
    private string $uploadDir = __DIR__ . '/../uploads/products/';
    private string $uploadUrl = 'uploads/products/';

    public function __construct() {
        $this->model = new Product();
    }

    /**
     * Liste tous les produits
     */
    public function index(): void {
        $products  = $this->model->getAll();
        $lowStock  = $this->model->getLowStock();
        $categories = $this->model->getCategories();
        require __DIR__ . '/../views/products/index.php';
    }

    /**
     * Affiche le formulaire d'ajout et traite la soumission
     */
    public function create(): void {
        $categories = $this->model->getCategories();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeInput($_POST);
            $errors = $this->validateProduct($data);

            // Gestion upload image
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imageName = $this->handleImageUpload($_FILES['image']);
                if ($imageName === false) {
                    $errors[] = "Format d'image invalide. Utilisez JPG, PNG ou WebP.";
                } else {
                    $data['image'] = $this->uploadUrl . $imageName;
                }
            } else {
                $data['image'] = null;
            }

            if (empty($errors)) {
                if ($this->model->create($data)) {
                    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Produit ajouté avec succès !'];
                    header('Location: index.php?page=products');
                    exit;
                }
                $errors[] = "Erreur lors de l'ajout du produit.";
            }
        }

        require __DIR__ . '/../views/products/create.php';
    }

    /**
     * Affiche le formulaire d'édition et traite la modification
     */
    public function edit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $product = $this->model->getById($id);
        if (!$product) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Produit introuvable.'];
            header('Location: index.php?page=products');
            exit;
        }

        $categories = $this->model->getCategories();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeInput($_POST);
            $errors = $this->validateProduct($data);

            // Gestion upload image
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imageName = $this->handleImageUpload($_FILES['image']);
                if ($imageName === false) {
                    $errors[] = "Format d'image invalide.";
                } else {
                    // Supprimer ancienne image
                    $this->deleteImage($product['image_path']);
                    $data['image'] = $this->uploadUrl . $imageName;
                }
            } else {
                $data['image'] = $product['image_path']; // garder l'ancienne
            }

            if (empty($errors)) {
                if ($this->model->update($id, $data)) {
                    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Produit modifié avec succès !'];
                    header('Location: index.php?page=products');
                    exit;
                }
                $errors[] = "Erreur lors de la modification.";
            }
        }

        require __DIR__ . '/../views/products/edit.php';
    }

    /**
     * Supprime un produit
     */
    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        $product = $this->model->getById($id);

        if ($product) {
            $this->deleteImage($product['image_path']);
            $this->model->delete($id);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Produit supprimé.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Produit introuvable.'];
        }

        header('Location: index.php?page=products');
        exit;
    }

    // ─── Méthodes privées ──────────────────────────────────────────────────────

    /**
     * Nettoie les données du formulaire
     */
    private function sanitizeInput(array $data): array {
        return [
            'name'        => trim(htmlspecialchars($data['name'] ?? '')),
            'description' => trim(htmlspecialchars($data['description'] ?? '')),
            'price'       => filter_var($data['price'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'stock'       => (int)($data['stock'] ?? 0),
            'category_id' => (int)($data['category_id'] ?? 0),
        ];
    }

    /**
     * Valide les données du produit
     */
    private function validateProduct(array $data): array {
        $errors = [];
        if (empty($data['name'])) $errors[] = "Le nom est obligatoire.";
        if ($data['price'] <= 0)  $errors[] = "Le prix doit être positif.";
        if ($data['stock'] < 0)   $errors[] = "Le stock ne peut pas être négatif.";
        return $errors;
    }

    /**
     * Gère l'upload d'une image produit
     * @return string|false Nom du fichier ou false si erreur
     */
    private function handleImageUpload(array $file): string|false {
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $ext     = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

        // Vérification du type MIME réel
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowed)) return false;
        if ($file['size'] > 5 * 1024 * 1024) return false; // 5 Mo max

        $filename = uniqid('prod_') . '.' . $ext[$mime];
        $dest     = $this->uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) return false;
        return $filename;
    }

    /**
     * Supprime le fichier image d'un produit
     */
    private function deleteImage(?string $imagePath): void {
        if ($imagePath) {
            $fullPath = __DIR__ . '/../' . $imagePath;
            if (file_exists($fullPath)) unlink($fullPath);
        }
    }
}
