<?php
/**
 * Contrôleur Utilisateurs
 */

require_once __DIR__ . '/../models/User.php';

class UserController {
    private User $model;

    public function __construct() {
        $this->model = new User();
    }

    public function index(): void {
        $users = $this->model->getAll();
        require __DIR__ . '/../views/users/index.php';
    }

    public function toggleStatus(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($this->model->toggleStatus($id)) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Statut mis à jour.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Erreur lors de la mise à jour.'];
        }
        header('Location: index.php?page=users');
        exit;
    }

    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($this->model->delete($id)) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Utilisateur supprimé.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Suppression impossible.'];
        }
        header('Location: index.php?page=users');
        exit;
    }
}
