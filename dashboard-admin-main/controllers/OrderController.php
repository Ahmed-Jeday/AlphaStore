<?php
/**
 * Contrôleur Commandes
 */

require_once __DIR__ . '/../models/Order.php';

class OrderController {
    private Order $model;

    public function __construct() {
        $this->model = new Order();
    }

    public function index(): void {
        $orders = $this->model->getAll();
        require __DIR__ . '/../views/orders/index.php';
    }

    public function show(): void {
        $id    = (int)($_GET['id'] ?? 0);
        $order = $this->model->getById($id);
        if (!$order) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Commande introuvable.'];
            header('Location: index.php?page=orders');
            exit;
        }
        $items = $this->model->getItems($id);
        require __DIR__ . '/../views/orders/show.php';
    }

    public function updateStatus(): void {
        $id     = (int)($_POST['order_id'] ?? 0);
        $status = $_POST['status'] ?? '';
        if ($this->model->updateStatus($id, $status)) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Statut mis à jour.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Erreur mise à jour statut.'];
        }
        header("Location: index.php?page=orders&action=show&id=$id");
        exit;
    }
}
