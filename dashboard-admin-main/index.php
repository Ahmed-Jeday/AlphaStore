<?php
/**
 * Point d'entrée principal — Routeur Front Controller
 * Toutes les requêtes passent ici
 */

session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/User.php';

// ── Gestion logout ─────────────────────────────────────────────────────────
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
    session_start(); // repart proprement pour le prochain login
    header('Location: login.php');
    exit;
}

// ── Protection session ─────────────────────────────────────────────────────
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// ── Routage ────────────────────────────────────────────────────────────────
$page   = $_GET['page']   ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

switch ($page) {

    case 'dashboard':
        require_once __DIR__ . '/models/Product.php';
        require_once __DIR__ . '/models/User.php';
        require_once __DIR__ . '/models/Order.php';

        $productModel = new Product();
        $userModel    = new User();
        $orderModel   = new Order();

        $stats = [
            'products'  => $productModel->count(),
            'users'     => $userModel->count(),
            'orders'    => $orderModel->count(),
            'revenue'   => $orderModel->getTotalRevenue(),
        ];
        $recentOrders   = $orderModel->getRecent(5);
        $lowStockAlerts = $productModel->getLowStock(5);
        $topProducts    = $productModel->getTopSelling(5);
        $dailySales     = $orderModel->getDailySales(7);
        $monthlyRevenue = $orderModel->getMonthlyRevenue();

        $pageTitle = 'Tableau de bord';
        require __DIR__ . '/views/dashboard.php';
        break;

    case 'products':
        require_once __DIR__ . '/controllers/ProductController.php';
        $ctrl = new ProductController();
        match($action) {
            'create' => $ctrl->create(),
            'edit'   => $ctrl->edit(),
            'delete' => $ctrl->delete(),
            default  => $ctrl->index(),
        };
        break;

    case 'users':
        require_once __DIR__ . '/controllers/UserController.php';
        $ctrl = new UserController();
        match($action) {
            'toggle' => $ctrl->toggleStatus(),
            'delete' => $ctrl->delete(),
            default  => $ctrl->index(),
        };
        break;

    case 'orders':
        require_once __DIR__ . '/controllers/OrderController.php';
        $ctrl = new OrderController();
        match($action) {
            'show'          => $ctrl->show(),
            'update_status' => $ctrl->updateStatus(),
            default         => $ctrl->index(),
        };
        break;

    case 'stats':
        require_once __DIR__ . '/controllers/StatsController.php';
        (new StatsController())->index();
        break;

    case 'favorites':
        require_once __DIR__ . '/models/Product.php';
        $productModel   = new Product();
        $mostFavorited  = $productModel->getMostFavorited(10);
        $pageTitle      = 'Analyse des Favoris';
        $breadcrumb     = 'Favoris';
        require __DIR__ . '/views/favorites.php';
        break;

    default:
        http_response_code(404);
        $html = @file_get_contents(__DIR__ . "/../View/html/404.html");
        if ($html === false) {
            echo "<h1>404 Not Found</h1>";
        } else {
            // Inject <base> tag to ensure relative assets in 404.html resolve correctly
            $script_name = $_SERVER['SCRIPT_NAME'];
            $project_root = rtrim(dirname(dirname($script_name)), '/\\');
            if ($project_root === '') $project_root = '/';
            else $project_root .= '/';
            
            $base_href = $project_root . "View/html/";
            echo str_replace('<head>', '<head><base href="' . $base_href . '">', $html);
        }
        exit;
}