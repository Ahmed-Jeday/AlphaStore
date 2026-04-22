<?php
/**
 * Contrôleur Statistiques
 */

require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/User.php';

class StatsController {
    private Order   $orderModel;
    private Product $productModel;
    private User    $userModel;

    public function __construct() {
        $this->orderModel   = new Order();
        $this->productModel = new Product();
        $this->userModel    = new User();
    }

    public function index(): void {
        // Données pour les graphiques
        $dailySales     = $this->orderModel->getDailySales(30);
        $monthlyRevenue = $this->orderModel->getMonthlyRevenue();
        $topProducts    = $this->productModel->getTopSelling(10);
        $mostFavorited  = $this->productModel->getMostFavorited(5);

        // Totaux pour les KPIs
        $totalRevenue   = $this->orderModel->getTotalRevenue();
        $totalOrders    = $this->orderModel->count();
        $totalUsers     = $this->userModel->count();
        $totalProducts  = $this->productModel->count();

        require __DIR__ . '/../views/stats/index.php';
    }
}
