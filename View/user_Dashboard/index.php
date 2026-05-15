<?php

include("../../Controller/AuthController.php");
include("../../Controller/ProfileController.php");
include("../../Controller/OrderController.php");
include("../../Controller/CartController.php");
include("../../Controller/FavoriteController.php");
require_once("../../model/OrderItem.php");
require_once("../../model/SpinHistory.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verify user is logged in (via user_id from AuthController)
if (isset($_SESSION["user_id"])) {
    getUserInfo(); // Retrieves user info and stores in session
    getProfileInfo(); // Retrieves profile info and stores in session
    
    // Extract for easier use in template
    $userId    = $_SESSION['user_id'];
    $firstName = $_SESSION['user_name'] ?? '';
    $lastName  = $_SESSION['user_last_name'] ?? '';
    $email     = $_SESSION['user_email'] ?? '';
    $phone     = $_SESSION['user_phone'] ?? '';
    $age       = $_SESSION['user_age'] ?? '';
    $gender    = $_SESSION['user_gender'] ?? '';
    $avatar    = $_SESSION['user_avatar'] ?? 'https://i.pravatar.cc/180?img=3';

    // Fetch dashboard data
    $user_id = $_SESSION['user_id'];
    
    // Get orders
    $orders = fetchUserOrders($user_id);
    
    // Calculate stats
    $totalSpent = 0;
    $ordersCount = count($orders);
    $pendingOrders = 0;
    foreach ($orders as $order) {
        $totalSpent += $order['total_price'];
        if ($order['status'] == 'pending') $pendingOrders++;
    }
    
    // Get cart items
    $cartModel = new Cart();
    $cartItems = $cartModel->getCart($user_id);
    $cartCount = count($cartItems);
    
    // Get favorites
    $favoriteModel = new Favorite();
    $favorites = $favoriteModel->getFavoriteByUser($user_id);
    $favoritesCount = count($favorites);

    // Get spin status
    $spinHistoryModel = new SpinHistory();
    $hasSpunToday = $spinHistoryModel->hasSpunToday($user_id);
    $userSpinHistory = $spinHistoryModel->getHistoryByUser($user_id, 10);
    
    // Calculate spin stats
    $totalSpins = count($userSpinHistory); // This is only the last 10, maybe we need a separate count? 
    // For now let's just use the history we have or keep it simple.
    
    // Handle cart and spin actions (POST requests)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'update_cart':
                    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
                        updateQuantity($_POST['product_id'], $_POST['quantity']);
                    }
                    break;
                case 'remove_cart':
                    if (isset($_POST['product_id'])) {
                        removeFromCart($_POST['product_id']);
                    }
                    break;
                case 'save_spin':
                    if (isset($_POST['prize_label'])) {
                        // Extra safety check: verify they haven't spun today already
                        if (!$spinHistoryModel->hasSpunToday($user_id)) {
                            $prizeLabel  = $_POST['prize_label'];
                            $prizeNumber = $_POST['prize_number'] ?? 0;
                            $isWin       = $_POST['is_win'] ?? 0;
                            
                            $spinHistoryModel->saveSpin($user_id, $prizeLabel, $prizeNumber, $isWin);
                            header("Location: " . $_SERVER['PHP_SELF'] . "?section=spin&success=spin_saved");
                        } else {
                            header("Location: " . $_SERVER['PHP_SELF'] . "?section=spin&error=already_spun");
                        }
                        exit;
                    }
                    break;
            }
        }
        // Redirect to avoid form resubmission and keep current section
        $sectionParam = '';
        if (isset($_POST['section'])) {
            $sectionParam = '?section=' . urlencode($_POST['section']);
        }
        header("Location: " . $_SERVER['PHP_SELF'] . $sectionParam);
        exit;
    }
    
    // Get active section
    $activeSection = $_GET['section'] ?? 'overview';
    
} else {
    // If not logged in, redirect to login
    header("Location: ../html/index.html");
    exit;
}

// Handle and display session messages
if (isset($_SESSION["message"])) {
    $msg = $_SESSION['message'];
    $color = ($msg === 'success') ? 'green' : 'red';
    $text = '';

    switch ($msg) {
        case 'success':
            $text = 'Mot de passe mis à jour avec succès !';
            break;
        case 'error_mismatch':
            $text = 'Les nouveaux mots de passe ne correspondent pas.';
            break;
        case 'error_current':
            $text = 'Le mot de passe actuel est incorrect.';
            break;
        case 'error_database':
            $text = 'Une erreur est survenue lors de la mise à jour.';
            break;
        default:
            $text = 'Une erreur inconnue est survenue.';
            break;
    }

    echo "<p style='color: $color; font-weight: bold; padding: 10px; background: " . ($color == 'green' ? '#e6ffec' : '#ffe6e6') . "; border-radius: 5px; margin-bottom: 20px; text-align: center;'>$text</p>";
    unset($_SESSION['message']);
}

// Handle URL messages
if (isset($_GET["success"])) {
    $msg = $_GET['success'];
    $text = '';
    switch ($msg) {
        case 'order_placed':
            $text = 'Commande passée avec succès !';
            break;
        case 'added_to_cart':
            $text = 'Article ajouté au panier !';
            break;
        case 'spin_saved':
            $text = 'Votre gain a été enregistré !';
            break;
        default:
            $text = 'Action réussie.';
            break;
    }
    echo "<p style='color: green; font-weight: bold; padding: 10px; background: #e6ffec; border-radius: 5px; margin-bottom: 20px; text-align: center;'>$text</p>";
}

if (isset($_GET["error"])) {
    $msg = $_GET['error'];
    $text = '';
    switch ($msg) {
        case 'cart_empty':
            $text = 'Votre panier est vide.';
            break;
        case 'not_logged_in':
            $text = 'Vous devez être connecté.';
            break;
        case 'order_creation_failed':
            $text = 'Erreur lors de la création de la commande.';
            break;
        case 'order_items_failed':
            $text = 'Erreur lors de l\'ajout des articles.';
            break;
        case 'already_spun':
            $text = 'Vous avez déjà utilisé votre tour aujourd\'hui.';
            break;
        default:
            $text = 'Une erreur est survenue.';
            break;
    }
    echo "<p style='color: red; font-weight: bold; padding: 10px; background: #ffe6e6; border-radius: 5px; margin-bottom: 20px; text-align: center;'>$text</p>";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AlphaStore — Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css">
</head>
<body>

  <div class="app">

    <!-- ═══════════════════════════════════════
         SIDEBAR
    ═══════════════════════════════════════ -->
    <aside class="sidebar">
      <div class="sidebar-top">
        <div class="brand">
          <span class="brand-alpha">Alpha</span>Store
        </div>
        <div class="user-block">
          <div class="user-avatar"><?= !empty($firstName) ? strtoupper($firstName[0]) : 'U' ?></div>
          <div class="user-info">
            <div class="user-name"><?= htmlspecialchars($firstName . " " . $lastName) ?></div>
            <div class="user-email"><?= htmlspecialchars($email) ?></div>
          </div>
        </div>
      </div>

      <nav class="nav">
        <div class="nav-section">Principal</div>
        <a href="?section=overview" class="nav-item <?php echo $activeSection == 'overview' ? 'active' : ''; ?>" data-section="overview">
          <i class="ph ph-squares-four"></i>
          <span>Overview</span>
        </a>
        <a href="?section=orders" class="nav-item <?php echo $activeSection == 'orders' ? 'active' : ''; ?>" data-section="orders">
          <i class="ph ph-package"></i>
          <span>Commandes</span>
          <span class="nav-badge"><?php echo $pendingOrders; ?></span>
        </a>
        <a href="?section=cart" class="nav-item <?php echo $activeSection == 'cart' ? 'active' : ''; ?>" data-section="cart">
          <i class="ph ph-shopping-cart"></i>
          <span>Panier</span>
          <span class="nav-badge"><?php echo $cartCount; ?></span>
        </a>
        <a href="?section=wishlist" class="nav-item <?php echo $activeSection == 'wishlist' ? 'active' : ''; ?>" data-section="wishlist">
          <i class="ph ph-heart"></i>
          <span>Favoris</span>
          <span class="nav-badge"><?php echo $favoritesCount; ?></span>
        </a>
        <a href="?section=spin" class="nav-item <?php echo $activeSection == 'spin' ? 'active' : ''; ?>" data-section="spin">
          <i class="ph ph-spinner-gap"></i>
          <span>Carnival Spin</span>
        </a>

        <div class="nav-section">Compte</div>
        <a href="?section=addresses" class="nav-item <?php echo $activeSection == 'addresses' ? 'active' : ''; ?>" data-section="addresses">
          <i class="ph ph-map-pin"></i>
          <span>Adresses</span>
        </a>
        <a href="?section=payments" class="nav-item <?php echo $activeSection == 'payments' ? 'active' : ''; ?>" data-section="payments">
          <i class="ph ph-credit-card"></i>
          <span>Paiements</span>
        </a>
        <a href="?section=profile" class="nav-item <?php echo $activeSection == 'profile' ? 'active' : ''; ?>" data-section="profile">
          <i class="ph ph-user"></i>
          <span>Profil</span>
        </a>
        <a href="?section=notifications" class="nav-item <?php echo $activeSection == 'notifications' ? 'active' : ''; ?>" data-section="notifications">
          <i class="ph ph-bell"></i>
          <span>Notifications</span>
          <span class="nav-badge">5</span>
        </a>
        <a href="?section=security" class="nav-item <?php echo $activeSection == 'security' ? 'active' : ''; ?>" data-section="security">
          <i class="ph ph-shield-check"></i>
          <span>Sécurité</span>
        </a>
        <a href="?section=support" class="nav-item <?php echo $activeSection == 'support' ? 'active' : ''; ?>" data-section="support">
          <i class="ph ph-headset"></i>
          <span>Support</span>
        </a>
      </nav>

      <div class="sidebar-bottom">
        <a href="../html/index.html" class="home-btn">
          <i class="ph ph-house"></i>
          <span>Retour à l'accueil</span>
        </a>
        <a href="logout.php" class="logout-btn">
          <i class="ph ph-sign-out"></i>
          <span>Déconnexion</span>
        </a>
      </div>
    </aside>

    <!-- ═══════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════ -->
    <main class="main">

      <!-- Header -->
      <header class="content-header">
        <div class="header-left">
          <button class="mobile-menu-btn" id="menuToggle">
            <i class="ph ph-list"></i>
          </button>
          <h1 class="page-title" id="pageTitle"><?php echo ucfirst($activeSection); ?></h1>
        </div>
        <div class="header-actions">
          <button class="header-btn secondary" id="headerBtnSec">Export</button>
          <button class="header-btn primary" id="headerBtnPri">+ Nouvelle commande</button>
        </div>
      </header>

      <!-- Scrollable content area -->
      <div class="content-scroll">

        <!-- ─── OVERVIEW ─── -->
        <section class="section <?php echo $activeSection == 'overview' ? 'active' : ''; ?>" id="sec-overview">
          <div class="stat-grid">
            <div class="stat-card featured">
              <div class="stat-label">Total dépensé</div>
              <div class="stat-value"><?php echo number_format($totalSpent, 0, ',', ' '); ?> <span class="stat-unit">DT</span></div>
              <div class="stat-sub">Cette année</div>
              <div class="stat-glow"></div>
            </div>
            <div class="stat-card">
              <div class="stat-label">Commandes</div>
              <div class="stat-value"><?php echo $ordersCount; ?></div>
              <div class="stat-sub"><?php echo $pendingOrders; ?> en cours</div>
              <div class="stat-glow"></div>
            </div>
            <div class="stat-card">
              <div class="stat-label">Favoris</div>
              <div class="stat-value"><?php echo $favoritesCount; ?></div>
              <div class="stat-sub">articles sauvegardés</div>
              <div class="stat-glow"></div>
            </div>
            <div class="stat-card">
              <div class="stat-label">Panier</div>
              <div class="stat-value"><?php echo $cartCount; ?></div>
              <div class="stat-sub">articles</div>
              <div class="stat-glow"></div>
            </div>
          </div>

          <div class="two-col">
            <div class="card-block">
              <div class="card-block-head">
                <i class="ph ph-package"></i>
                <span>Commandes récentes</span>
                <button class="link-btn" data-goto="orders">voir tout →</button>
              </div>
              <div class="card-block-body">
                <?php
                $recentOrders = array_slice($orders, 0, 3); // Get first 3 orders
                foreach ($recentOrders as $order) {
                    $statusClass = '';
                    $statusText = '';
                    switch ($order['status']) {
                        case 'delivered':
                            $statusClass = 'delivered';
                            $statusText = 'Livré';
                            break;
                        case 'shipped':
                        case 'transit':
                            $statusClass = 'transit';
                            $statusText = 'En transit';
                            break;
                        case 'pending':
                        default:
                            $statusClass = 'pending';
                            $statusText = 'En attente';
                            break;
                    }
                    // Get order items for product name
                    $items = fetchOrderItems($order['id']);
                    $productName = !empty($items) ? $items[0]['name'] : 'Produit';
                    
                    echo '<div class="order-row">
                      <div class="order-dot ' . $statusClass . '"></div>
                      <span class="order-id">#' . $order['id'] . '</span>
                      <span class="order-name">' . htmlspecialchars($productName) . '</span>
                      <span class="badge ' . $statusClass . '">' . $statusText . '</span>
                      <span class="order-price">' . number_format($order['total_price'], 0, ',', ' ') . ' DT</span>
                    </div>';
                }
                if (empty($recentOrders)) {
                    echo '<div class="order-row">Aucune commande trouvée</div>';
                }
                ?>
              </div>
            </div>

            <div class="card-block">
              <div class="card-block-head">
                <i class="ph ph-bell"></i>
                <span>Notifications</span>
                <button class="link-btn" data-goto="notifications">voir tout →</button>
              </div>
              <div class="card-block-body">
                <div class="notif-row">
                  <div class="notif-icon promo"><i class="ph ph-tag"></i></div>
                  <div class="notif-content">
                    <div class="notif-text">Flash sale — 30% off électronique aujourd'hui</div>
                    <div class="notif-time">Il y a 2h</div>
                  </div>
                </div>
                <div class="notif-row">
                  <div class="notif-icon order"><i class="ph ph-truck"></i></div>
                  <div class="notif-content">
                    <div class="notif-text">Commande #4819 en cours de livraison</div>
                    <div class="notif-time">Il y a 5h</div>
                  </div>
                </div>
                <div class="notif-row last">
                  <div class="notif-icon sys"><i class="ph ph-info"></i></div>
                  <div class="notif-content">
                    <div class="notif-text">Mot de passe modifié avec succès</div>
                    <div class="notif-time">Hier</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- ─── ORDERS ─── -->
        <section class="section <?php echo $activeSection == 'orders' ? 'active' : ''; ?>" id="sec-orders">
          <div class="card-block">
            <div class="card-block-head">
              <i class="ph ph-list"></i>
              <span>Toutes les commandes</span>
            </div>
            <?php
            // Check if user has orders
            if (!empty($orders) && is_array($orders)) {
            ?>
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Commande</th>
                    <th>Date</th>
                    <th>Article(s)</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($orders as $order) {
                      $statusClass = '';
                      $statusText = '';
                      switch ($order['status']) {
                          case 'delivered':
                              $statusClass = 'delivered';
                              $statusText = 'Livré';
                              break;
                          case 'shipped':
                          case 'transit':
                              $statusClass = 'transit';
                              $statusText = 'En transit';
                              break;
                          case 'pending':
                          default:
                              $statusClass = 'pending';
                              $statusText = 'En attente';
                              break;
                      }
                      // Get order items for product name
                      $items = fetchOrderItems($order['id']);
                      $productName = !empty($items) ? $items[0]['name'] : 'Produit';
                      
                      echo '<tr>
                        <td><strong>#' . $order['id'] . '</strong></td>
                        <td>' . date('d M', strtotime($order['created_at'])) . '</td>
                        <td>' . htmlspecialchars($productName) . '</td>
                        <td>' . number_format($order['total_price'], 0, ',', ' ') . ' DT</td>
                        <td><span class="badge ' . $statusClass . '">' . $statusText . '</span></td>
                        <td><button class="view-btn">Détails</button></td>
                      </tr>';
                  }
                  if (empty($orders)) {
                      echo '<tr><td colspan="6">Aucune commande trouvée</td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            <?php
            } else {
            ?>
              <div style="text-align: center; padding: 60px 20px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); border-radius: 10px;">
                <div style="font-size: 48px; margin-bottom: 20px;">🛍️</div>
                <h3 style="font-size: 24px; font-weight: 600; color: #333; margin-bottom: 10px;">Pas encore de commandes</h3>
                <p style="font-size: 16px; color: #666; margin-bottom: 20px;">Commençons votre première aventure !</p>
                <a href="../html/index.html" style="display: inline-block; padding: 12px 30px; background: #007AFF; color: white; text-decoration: none; border-radius: 5px; font-weight: 600; transition: background 0.3s;">
                  Découvrir les produits
                </a>
              </div>
            <?php
            }
            ?>
            </div>
          </div>
        </section>

        <!-- ─── CART ─── -->
        <section class="section <?php echo $activeSection == 'cart' ? 'active' : ''; ?>" id="sec-cart">
          <div class="cart-list">
            <?php
            $cartTotal = 0;
            foreach ($cartItems as $item) {
                $itemTotal = $item['price'] * $item['quantite'];
                $cartTotal += $itemTotal;
                echo '<div class="cart-item" data-price="' . $item['price'] . '">
                  <div class="cart-img">' . (!empty($item['image_path']) ? '<img src="' . htmlspecialchars($item['image_path']) . '" alt="' . htmlspecialchars($item['name']) . '">' : '📦') . '</div>
                  <div class="cart-info">
                    <div class="cart-name">' . htmlspecialchars($item['name']) . '</div>
                    <div class="cart-detail">Quantité: ' . $item['quantite'] . '</div>
                  </div>
                  <div class="qty-ctrl">
                    <form method="post" style="display: inline;">
                      <input type="hidden" name="action" value="update_cart">
                      <input type="hidden" name="product_id" value="' . $item['produit_id'] . '">
                      <input type="hidden" name="quantity" value="' . ($item['quantite'] - 1) . '">
                      <input type="hidden" name="section" value="cart">
                      <button type="submit" class="qty-btn" name="dec">−</button>
                    </form>
                    <span class="qty-val">' . $item['quantite'] . '</span>
                    <form method="post" style="display: inline;">
                      <input type="hidden" name="action" value="update_cart">
                      <input type="hidden" name="product_id" value="' . $item['produit_id'] . '">
                      <input type="hidden" name="quantity" value="' . ($item['quantite'] + 1) . '">
                      <input type="hidden" name="section" value="cart">
                      <button type="submit" class="qty-btn" name="inc">+</button>
                    </form>
                  </div>
                  <div class="cart-price">' . number_format($itemTotal, 0, ',', ' ') . ' DT</div>
                  <form method="post" style="display: inline;">
                    <input type="hidden" name="action" value="remove_cart">
                    <input type="hidden" name="product_id" value="' . $item['produit_id'] . '">
                    <input type="hidden" name="section" value="cart">
                    <button type="submit" class="del-btn" title="Supprimer"><i class="ph ph-trash"></i></button>
                  </form>
                </div>';
            }
            if (empty($cartItems)) {
                echo '<div class="cart-item">Votre panier est vide</div>';
            }
            ?>
          </div>
          
          <?php if (!empty($cart_items)): ?>
          <div class="cart-summary">
            <div class="cart-summary-info">
              <div class="cart-summary-label">Total</div>
              <div class="cart-total-val" id="cartTotal"><?php echo number_format($cartTotal, 0, ',', ' '); ?> DT</div>
              <div class="cart-summary-sub">Livraison gratuite incluse</div>
            </div>
            <form method="post" action="../../Controller/OrderController.php" style="display: inline;">
              <input type="hidden" name="action" value="place_order">
              <button type="submit" class="checkout-btn">Passer commande →</button>
            </form>
          </div>
          <?php endif; ?>
        </section>

        <!-- ─── WISHLIST ─── -->
        <section class="section <?php echo $activeSection == 'wishlist' ? 'active' : ''; ?>" id="sec-wishlist">
          <div class="wish-grid">
            <?php
            foreach ($favorites as $fav) {
                echo '<div class="wish-card">
                  <div class="wish-img">' . (!empty($fav['image_path']) ? '<img src="' . htmlspecialchars($fav['image_path']) . '" alt="' . htmlspecialchars($fav['name']) . '">' : '❤️') . '</div>
                  <div class="wish-body">
                    <div class="wish-name">' . htmlspecialchars($fav['name']) . '</div>
                    <div class="wish-price">' . number_format($fav['price'], 0, ',', ' ') . ' DT</div>
                            <form method="post" action="../../Controller/CartController.php" style="display: inline;">
                      <input type="hidden" name="product_id" value="' . $fav['product_id'] . '">
                      <input type="hidden" name="quantity" value="1">
                      <input type="hidden" name="section" value="wishlist">
                      <button type="submit" class="wish-add">+ Ajouter au panier</button>
                    </form>
                  </div>
                </div>';
            }
            if (empty($favorites)) {
                echo '<div class="wish-card">Aucun favori trouvé</div>';
            }
            ?>
          </div>
        </section>

        <!-- ─── ADDRESSES ─── -->
        <section class="section <?php echo $activeSection == 'addresses' ? 'active' : ''; ?>" id="sec-addresses">
          <div class="addr-grid">
            <div class="addr-card default">
              <span class="addr-badge">Défaut</span>
              <div class="addr-name">Ahmed Mzoughi</div>
              <div class="addr-text">12 Rue de la Liberté<br>Hammam Sousse, 4011<br>Tunisie</div>
              <div class="addr-actions">
                <button class="small-btn">Modifier</button>
                <button class="small-btn">Supprimer</button>
              </div>
            </div>
            <div class="addr-card">
              <div class="addr-name">Bureau AlphaStore</div>
              <div class="addr-text">Centre Commercial Azur City<br>Tunis, 1002<br>Tunisie</div>
              <div class="addr-actions">
                <button class="small-btn">Modifier</button>
                <button class="small-btn">Supprimer</button>
                <button class="small-btn">Définir par défaut</button>
              </div>
            </div>
            <button class="addr-add-btn">
              <i class="ph ph-plus-circle"></i>
              <span>Ajouter une adresse</span>
            </button>
          </div>
        </section>

        <!-- ─── PAYMENTS ─── -->
        <section class="section <?php echo $activeSection == 'payments' ? 'active' : ''; ?>" id="sec-payments">
          <div class="pay-grid">
            <div class="pay-card featured">
              <div class="pay-card-top">
                <div class="pay-icon"><i class="ph-fill ph-credit-card"></i></div>
                <span class="pay-default-badge">Défaut</span>
              </div>
              <div class="pay-info">
                <div class="pay-num">•••• •••• •••• 4291</div>
                <div class="pay-type">Visa Signature</div>
              </div>
              <div class="pay-card-chip"></div>
            </div>
            <div class="pay-card">
              <div class="pay-card-top">
                <div class="pay-icon"><i class="ph ph-bank"></i></div>
              </div>
              <div class="pay-info">
                <div class="pay-num">BNA — Compte courant</div>
                <div class="pay-type">Virement Bancaire</div>
              </div>
            </div>
          </div>
          <div class="card-block">
            <div class="card-block-head">
              <i class="ph ph-receipt"></i>
              <span>Historique des paiements</span>
            </div>
            <div class="table-wrap">
              <table class="data-table">
                <thead>
                  <tr><th>Date</th><th>Description</th><th>Méthode</th><th>Montant</th></tr>
                </thead>
                <tbody>
                  <tr><td>12 Avr</td><td>Commande #4821</td><td>Visa ••4291</td><td>189 DT</td></tr>
                  <tr><td>10 Avr</td><td>Commande #4819</td><td>Visa ••4291</td><td>920 DT</td></tr>
                  <tr><td>28 Mar</td><td>Commande #4805</td><td>Virement</td><td>55 DT</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ─── PROFILE ─── -->
        <section class="section <?php echo $activeSection == 'profile' ? 'active' : ''; ?>" id="sec-profile">
          <div class="profile-grid">
            <div class="profile-avatar-block">
              <div class="profile-avatar-circle" id="profileAvatarCircle">
                <?php if ($avatar && strpos($avatar, 'pravatar.cc') === false): ?>
                  <img src="<?= $avatar ?>" alt="Avatar" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                <?php else: ?>
                  <?= !empty($firstName) ? strtoupper($firstName[0]) : 'U' ?>
                <?php endif; ?>
              </div>
              <div class="profile-fullname"><?= htmlspecialchars($firstName . " " . $lastName) ?></div>
              <div class="profile-since">Membre depuis <?= $_SESSION['user_created_at'] ?? '2023' ?></div>
              <button class="change-photo-btn"><i class="ph ph-camera"></i> Changer la photo</button>
            </div>
            <div class="profile-form-block">
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Prénom</label>
                  <input class="form-input" type="text" id="firstName" value="<?= htmlspecialchars($firstName) ?>">
                </div>
                <div class="form-group">
                  <label class="form-label">Nom</label>
                  <input class="form-input" type="text" id="lastName" value="<?= htmlspecialchars($lastName) ?>">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Email</label>
                  <input class="form-input" type="email" id="email" value="<?= htmlspecialchars($email) ?>">
                </div>
                <div class="form-group">
                  <label class="form-label">Téléphone</label>
                  <input class="form-input" type="tel" id="phone" value="<?= htmlspecialchars($phone) ?>">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Age</label>
                  <input class="form-input" type="text" id="age" value="<?= htmlspecialchars($age) ?>">
                </div>
                <div class="form-group">
                  <label class="form-label">Genre</label>
                  <select class="form-input" id="gender">
                    <option value="male" <?= ($gender === 'male') ? 'selected' : '' ?>>Homme</option>
                    <option value="female" <?= ($gender === 'female') ? 'selected' : '' ?>>Femme</option>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group full">
                  <label class="form-label">Mot de passe actuel</label>
                  <input class="form-input" type="password" value="••••••••">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Nouveau mot de passe</label>
                  <input class="form-input" type="password" placeholder="Nouveau...">
                </div>
                <div class="form-group">
                  <label class="form-label">Confirmation</label>
                  <input class="form-input" type="password" placeholder="Confirmer...">
                </div>
              </div>
              <button class="save-btn" id="updateProfileBtn">Enregistrer les modifications</button>
            </div>
          </div>
        </section>

        <!-- ─── NOTIFICATIONS ─── -->
        <section class="section <?php echo $activeSection == 'notifications' ? 'active' : ''; ?>" id="sec-notifications">
          <div class="card-block">
            <div class="card-block-head">
              <i class="ph ph-bell"></i>
              <span>Toutes les notifications</span>
              <button class="link-btn" id="markAllRead">Tout marquer comme lu</button>
            </div>
            <div class="card-block-body notif-feed">
              <div class="notif-row unread">
                <div class="notif-icon promo"><i class="ph ph-tag"></i></div>
                <div class="notif-content">
                  <div class="notif-text">Flash sale — 30% off électronique aujourd'hui seulement</div>
                  <div class="notif-time">Il y a 2h</div>
                </div>
              </div>
              <div class="notif-row unread">
                <div class="notif-icon order"><i class="ph ph-truck"></i></div>
                <div class="notif-content">
                  <div class="notif-text">Commande #4819 en cours de livraison</div>
                  <div class="notif-time">Il y a 5h</div>
                </div>
              </div>
              <div class="notif-row">
                <div class="notif-icon sys"><i class="ph ph-info"></i></div>
                <div class="notif-content">
                  <div class="notif-text">Mot de passe modifié avec succès</div>
                  <div class="notif-time">Hier</div>
                </div>
              </div>
              <div class="notif-row unread">
                <div class="notif-icon promo"><i class="ph ph-gift"></i></div>
                <div class="notif-content">
                  <div class="notif-text">Vous avez 2 coupons qui expirent bientôt</div>
                  <div class="notif-time">Il y a 2 jours</div>
                </div>
              </div>
              <div class="notif-row last">
                <div class="notif-icon order"><i class="ph ph-check-circle"></i></div>
                <div class="notif-content">
                  <div class="notif-text">Commande #4821 livrée — Laissez un avis ?</div>
                  <div class="notif-time">13 Avr</div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- ─── SECURITY ─── -->
        <section class="section <?php echo $activeSection == 'security' ? 'active' : ''; ?>" id="sec-security">
          <div class="security-list">
            <div class="security-item">
              <div class="security-icon active"><i class="ph ph-lock-key-open"></i></div>
              <div class="security-info">
                <div class="security-title">Mot de passe</div>
                <div class="security-desc">Modifié il y a 3 jours</div>
              </div>
              <button class="small-btn">Changer</button>
            </div>
            <div class="security-item">
              <div class="security-icon active"><i class="ph ph-device-mobile"></i></div>
              <div class="security-info">
                <div class="security-title">Authentification à deux facteurs (2FA)</div>
                <div class="security-desc">Activée via SMS · +216 55 ••• 456</div>
              </div>
              <button class="toggle-pill on" id="toggleTfa" title="2FA activé"></button>
            </div>
            <div class="security-item">
              <div class="security-icon"><i class="ph ph-clock-counter-clockwise"></i></div>
              <div class="security-info">
                <div class="security-title">Historique des connexions</div>
                <div class="security-desc">Dernière connexion : Aujourd'hui 14:32 — Tunis, TN · Chrome</div>
              </div>
              <button class="small-btn">Voir tout</button>
            </div>
            <div class="security-item">
              <div class="security-icon"><i class="ph ph-devices"></i></div>
              <div class="security-info">
                <div class="security-title">Sessions actives</div>
                <div class="security-desc">2 sessions connectées</div>
              </div>
              <button class="small-btn">Gérer</button>
            </div>
          </div>
        </section>

        <!-- ─── SUPPORT ─── -->
        <section class="section <?php echo $activeSection == 'support' ? 'active' : ''; ?>" id="sec-support">
          <div class="contact-grid">
            <div class="contact-card">
              <i class="ph ph-chat-dots"></i>
              <span>Chat en direct</span>
            </div>
            <div class="contact-card">
              <i class="ph ph-envelope"></i>
              <span>Envoyer un email</span>
            </div>
            <div class="contact-card">
              <i class="ph ph-phone"></i>
              <span>Nous appeler</span>
            </div>
          </div>
          <div class="card-block" style="margin-bottom: 20px;">
            <div class="card-block-head">
              <i class="ph ph-ticket"></i>
              <span>Mes tickets</span>
              <button class="link-btn">Nouveau ticket</button>
            </div>
            <div class="card-block-body">
              <div class="order-row">
                <div class="order-dot transit"></div>
                <span class="order-id">#T-091</span>
                <span class="order-name">Article manquant dans commande #4810</span>
                <span class="badge transit">Ouvert</span>
              </div>
              <div class="order-row" style="border:none;">
                <div class="order-dot delivered"></div>
                <span class="order-id">#T-088</span>
                <span class="order-name">Demande de retour commande #4780</span>
                <span class="badge delivered">Résolu</span>
              </div>
            </div>
          </div>
          <div class="faq-section">
            <div class="faq-heading">FAQ</div>
            <div class="faq-list">
              <div class="faq-item open">
                <button class="faq-q">
                  <span>Comment suivre ma commande ?</span>
                  <i class="ph ph-caret-up"></i>
                </button>
                <div class="faq-a">Rendez-vous dans Commandes, cliquez sur "Détails" pour voir le suivi en temps réel et la date de livraison estimée.</div>
              </div>
              <div class="faq-item">
                <button class="faq-q">
                  <span>Puis-je retourner un article ?</span>
                  <i class="ph ph-caret-down"></i>
                </button>
                <div class="faq-a">Oui, vous avez 14 jours à partir de la livraison pour initier un retour. Ouvrez un ticket support et sélectionnez "Demande de retour".</div>
              </div>
              <div class="faq-item">
                <button class="faq-q">
                  <span>Comment fonctionnent les coupons ?</span>
                  <i class="ph ph-caret-down"></i>
                </button>
                <div class="faq-a">Entrez votre code coupon lors du paiement. Les coupons peuvent être en pourcentage ou en montant fixe et peuvent avoir une date d'expiration.</div>
              </div>
            </div>
          </div>
        <!-- ─── SPIN ─── -->
        <section class="section <?php echo $activeSection == 'spin' ? 'active' : ''; ?>" id="sec-spin">
          <div class="card-block">
            <div class="card-block-head">
              <i class="ph ph-spinner-gap"></i>
              <span>Carnival Spin</span>
            </div>
            <div class="card-block-body" style="min-height: 600px; display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative;">
              <?php if ($hasSpunToday): ?>
                <div style="text-align: center; padding: 60px 20px; background: rgba(0,0,0,0.05); border-radius: 15px; width: 100%; max-width: 600px; margin-bottom: 30px;">
                  <div style="font-size: 64px; margin-bottom: 20px;">🎡</div>
                  <h3 style="font-size: 28px; font-weight: 700; color: #1a1a1a; margin-bottom: 15px;">Limite quotidienne atteinte</h3>
                  <p style="font-size: 18px; color: #666; margin-bottom: 30px; line-height: 1.6;">
                    Vous avez déjà utilisé votre tour gratuit aujourd'hui. Revenez demain pour retenter votre chance !
                  </p>
                  <div style="padding: 20px; background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); border-radius: 12px; color: #000; font-weight: 700; box-shadow: 0 10px 20px rgba(255, 165, 0, 0.3);">
                    <i class="ph ph-crown" style="font-size: 24px; vertical-align: middle; margin-right: 10px;"></i>
                    Il faut s'abonner en Alpha+ pour des spins illimités !
                  </div>
                </div>
              <?php else: ?>
                <iframe src="../spin/spin.php" style="width: 100%; height: 700px; border: none; border-radius: 15px;" id="spinFrame"></iframe>
                <form id="saveSpinForm" method="POST" style="display: none;">
                  <input type="hidden" name="action" value="save_spin">
                  <input type="hidden" name="prize_label" id="prizeLabelInput">
                  <input type="hidden" name="prize_number" id="prizeNumberInput">
                  <input type="hidden" name="is_win" id="isWinInput">
                  <input type="hidden" name="section" value="spin">
                </form>
                <script>
                  window.addEventListener('message', function(event) {
                    if (event.data.type === 'spin_result') {
                      document.getElementById('prizeLabelInput').value = event.data.prize;
                      document.getElementById('prizeNumberInput').value = event.data.prize_number;
                      document.getElementById('isWinInput').value = event.data.is_win;
                      document.getElementById('saveSpinForm').submit();
                    }
                  });
                </script>
              <?php endif; ?>

              <!-- Spin History Table -->
              <div class="history-container" style="width: 100%; margin-top: 40px;">
                <h4 style="font-size: 20px; font-weight: 600; margin-bottom: 20px; color: #333;">📜 Votre Historique de Gains</h4>
                <?php if (!empty($userSpinHistory)): ?>
                  <table class="data-table">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Cadeau</th>
                        <th>Résultat</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($userSpinHistory as $spin): ?>
                        <tr>
                          <td><?= date('d M Y, H:i', strtotime($spin['created_at'])) ?></td>
                          <td><strong><?= htmlspecialchars($spin['prize_label']) ?></strong></td>
                          <td>
                            <span class="badge <?= $spin['is_win'] ? 'delivered' : 'pending' ?>">
                              <?= $spin['is_win'] ? 'Gagné' : 'Perdu' ?>
                            </span>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                <?php else: ?>
                  <p style="color: #666; font-style: italic;">Aucun spin enregistré pour le moment.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </section>

      </div><!-- /content-scroll -->
    </main><!-- /main -->
  </div><!-- /app -->

  <!-- Overlay for mobile menu -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <!-- Scripts -->
  <script src="index.js"></script>
  <script src="updateProfile.js"></script>

</body>
</html>
