<?php

include("../../Controller/AuthController.php");
include("../../Controller/ProfileController.php");
include("../../model/Order.php");
include("../../model/OrderItem.php");
include("../../model/Cart.php");
include("../../model/Favorite.php");
session_start();

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
    
    $cartModel = new Cart();
    $favModel = new Favorite();

    // Handle cart actions (Simple PHP, no AJAX)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_action'])) {
        $action = $_POST['cart_action'];
        $productId = $_POST['product_id'] ?? null;
        
        if ($action === 'remove' && $productId) {
            $cartModel->removeFromCart($userId, $productId);
        } elseif ($action === 'update' && $productId) {
            $quantity = intval($_POST['quantity'] ?? 1);
            if ($quantity > 0) {
                $cartModel->updateCart($userId, $productId, $quantity);
            } else {
                $cartModel->removeFromCart($userId, $productId);
            }
        } elseif ($action === 'add' && $productId) {
            $cartModel->addToCart($userId, $productId, 1);
        }
        // Refresh to apply changes and avoid form resubmission
        header("Location: index.php?section=cart");
        exit;
    }

    // Fetch user orders
    $orderModel = new Order();
    $user_orders = $orderModel->getOrdersByUserId($userId);
    
    // Fetch cart data
    $cart_items = $cartModel->getCart($userId);
    $cart_total = $cartModel->getCartTotal($userId);
    $cart_count = count($cart_items);

    // Fetch wishlist data
    $fav_items = $favModel->getFavoriteByUser($userId);
    $fav_count = count($fav_items);
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
        <button class="nav-item active" data-section="overview">
          <i class="ph ph-squares-four"></i>
          <span>Overview</span>
        </button>
        <button class="nav-item" data-section="orders">
          <i class="ph ph-package"></i>
          <span>Commandes</span>
          <span class="nav-badge"><?= count($user_orders ?? []) ?></span>
        </button>
        <button class="nav-item" data-section="cart">
          <i class="ph ph-shopping-cart"></i>
          <span>Panier</span>
          <span class="nav-badge"><?= $cart_count ?? 0 ?></span>
        </button>
        <button class="nav-item" data-section="wishlist">
          <i class="ph ph-heart"></i>
          <span>Favoris</span>
          <span class="nav-badge"><?= $fav_count ?? 0 ?></span>
        </button>

        <div class="nav-section">Compte</div>
        <button class="nav-item" data-section="addresses">
          <i class="ph ph-map-pin"></i>
          <span>Adresses</span>
        </button>
        <button class="nav-item" data-section="payments">
          <i class="ph ph-credit-card"></i>
          <span>Paiements</span>
        </button>
        <button class="nav-item" data-section="profile">
          <i class="ph ph-user"></i>
          <span>Profil</span>
        </button>
        <button class="nav-item" data-section="notifications">
          <i class="ph ph-bell"></i>
          <span>Notifications</span>
          <span class="nav-badge">5</span>
        </button>
        <button class="nav-item" data-section="security">
          <i class="ph ph-shield-check"></i>
          <span>Sécurité</span>
        </button>
        <button class="nav-item" data-section="support">
          <i class="ph ph-headset"></i>
          <span>Support</span>
        </button>
      </nav>

      <div class="sidebar-bottom">
        <a href="../html/index.html" class="home-btn">
          <i class="ph ph-house"></i>
          <span>Retour à l'accueil</span>
        </a>
        <button class="logout-btn">
          <i class="ph ph-sign-out"></i>
          <span>Déconnexion</span>
        </button>
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
          <h1 class="page-title" id="pageTitle">Overview</h1>
        </div>
        <div class="header-actions">
          <button class="header-btn secondary" id="headerBtnSec">Export</button>
          <button class="header-btn primary" id="headerBtnPri">+ Nouvelle commande</button>
        </div>
      </header>

      <!-- Scrollable content area -->
      <div class="content-scroll">

        <!-- ─── OVERVIEW ─── -->
        <section class="section active" id="sec-overview">
          <div class="stat-grid">
            <div class="stat-card featured">
              <div class="stat-label">Total dépensé</div>
              <div class="stat-value">2 840 <span class="stat-unit">DT</span></div>
              <div class="stat-sub">Cette année</div>
              <div class="stat-glow"></div>
            </div>
            <div class="stat-card">
              <div class="stat-label">Commandes</div>
              <div class="stat-value">14</div>
              <div class="stat-sub">3 en cours</div>
              <div class="stat-glow"></div>
            </div>
            <div class="stat-card">
              <div class="stat-label">Favoris</div>
              <div class="stat-value"><?= $fav_count ?? 0 ?></div>
              <div class="stat-sub">articles sauvegardés</div>
              <div class="stat-glow"></div>
            </div>
            <div class="stat-card">
              <div class="stat-label">Coupons</div>
              <div class="stat-value">2</div>
              <div class="stat-sub">disponibles</div>
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
                if (!empty($user_orders) && is_array($user_orders)) {
                    // Show only the last 3 orders
                    $recent_orders = array_slice($user_orders, 0, 3);
                    
                    foreach ($recent_orders as $order) {
                        // Determine dot class based on status
                        $dot_class = 'pending';
                        $badge_class = 'pending';
                        $status_text = ucfirst($order['status']);
                        
                        if ($order['status'] === 'delivered') {
                            $dot_class = 'delivered';
                            $badge_class = 'delivered';
                            $status_text = 'Livré';
                        } elseif ($order['status'] === 'shipped') {
                            $dot_class = 'transit';
                            $badge_class = 'transit';
                            $status_text = 'En transit';
                        } elseif ($order['status'] === 'pending') {
                            $dot_class = 'pending';
                            $badge_class = 'pending';
                            $status_text = 'En attente';
                        } elseif ($order['status'] === 'cancelled') {
                            $dot_class = 'cancelled';
                            $badge_class = 'cancelled';
                            $status_text = 'Annulée';
                        } elseif ($order['status'] === 'paid') {
                            $dot_class = 'paid';
                            $badge_class = 'paid';
                            $status_text = 'Payée';
                        }
                        
                        // Get order items to display product names
                        $orderItemModel = new OrderItem();
                        $order_items = $orderItemModel->getItemsByOrderId($order['id']);
                        $product_name = 'Article';
                        
                        if (!empty($order_items) && isset($order_items[0]['product_name'])) {
                            $product_name = $order_items[0]['product_name'];
                        }
                ?>
                <div class="order-row">
                  <div class="order-dot <?php echo $dot_class; ?>"></div>
                  <span class="order-id">#<?php echo htmlspecialchars($order['id']); ?></span>
                  <span class="order-name"><?php echo htmlspecialchars($product_name); ?></span>
                  <span class="badge <?php echo $badge_class; ?>"><?php echo $status_text; ?></span>
                  <span class="order-price"><?php echo htmlspecialchars($order['total_price']); ?> DT</span>
                </div>
                <?php
                    }
                } else {
                    // No orders message
                ?>
                <div style="padding: 20px; text-align: center; color: #999;">
                  <p style="margin: 0; font-size: 14px;">Aucune commande encore. Commencez à acheter!</p>
                </div>
                <?php
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
        <section class="section" id="sec-orders">
          <div class="card-block">
            <div class="card-block-head">
              <i class="ph ph-list"></i>
              <span>Toutes les commandes</span>
            </div>
            <?php
            // Check if user has orders
            if (!empty($user_orders) && is_array($user_orders)) {
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
                  foreach ($user_orders as $order) {
                    // Determine status badge class
                    $status_class = 'pending';
                    $status_text = ucfirst($order['status']);
                    
                    if ($order['status'] === 'delivered') {
                        $status_class = 'delivered';
                        $status_text = 'Livré';
                    } elseif ($order['status'] === 'shipped') {
                        $status_class = 'transit';
                        $status_text = 'En transit';
                    } elseif ($order['status'] === 'pending') {
                        $status_class = 'pending';
                        $status_text = 'En attente';
                    } elseif ($order['status'] === 'cancelled') {
                        $status_class = 'cancelled';
                        $status_text = 'Annulée';
                    } elseif ($order['status'] === 'paid') {
                        $status_class = 'paid';
                        $status_text = 'Payée';
                    }
                    
                    // Format date
                    $date = new DateTime($order['created_at']);
                    $formatted_date = $date->format('d M');
                    
                    // Get order items to display product names
                    $orderItemModel = new OrderItem();
                    $order_items = $orderItemModel->getItemsByOrderId($order['id']);
                    $items_text = '';
                    
                    if (!empty($order_items)) {
                        $items_names = [];
                        foreach ($order_items as $item) {
                            $items_names[] = $item['product_name'] ?? 'Article';
                        }
                        $items_text = implode(', ', array_slice($items_names, 0, 2));
                        if (count($items_names) > 2) {
                            $items_text .= ' +' . (count($items_names) - 2);
                        }
                    } else {
                        $items_text = 'Article(s)';
                    }
                  ?>
                  <tr>
                    <td><strong>#<?php echo htmlspecialchars($order['id']); ?></strong></td>
                    <td><?php echo htmlspecialchars($formatted_date); ?></td>
                    <td><?php echo htmlspecialchars($items_text); ?></td>
                    <td><?php echo htmlspecialchars($order['total_price']); ?> DT</td>
                    <td><span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                    <td><button class="view-btn" onclick="alert('Détails de la commande #<?php echo $order['id']; ?>')">Détails</button></td>
                  </tr>
                  <?php 
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
        <section class="section" id="sec-cart">
          <div class="cart-list">
            <?php if (!empty($cart_items)): ?>
              <?php foreach ($cart_items as $item): ?>
                <div class="cart-item" data-price="<?= htmlspecialchars($item['price']) ?>">
                  <div class="cart-img">
                    <?php 
                    $img_src = $item['image_path'] ?? '';
                    if (!empty($img_src)) {
                        if (strpos($img_src, 'http') !== 0) {
                            $img_src = "../../public/" . $img_src;
                        }
                    ?>
                      <img src="<?= htmlspecialchars($img_src) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                    <?php } else { ?>
                      📦
                    <?php } ?>
                  </div>
                  <div class="cart-info">
                    <div class="cart-name"><?= htmlspecialchars($item['name']) ?></div>
                    <div class="cart-detail">Prix unitaire: <?= htmlspecialchars($item['price']) ?> DT</div>
                  </div>
                  
                  <div class="qty-ctrl">
                    <form method="POST" style="display: flex; align-items: center;">
                      <input type="hidden" name="cart_action" value="update">
                      <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['produit_id']) ?>">
                      
                      <button type="submit" name="quantity" value="<?= $item['quantite'] - 1 ?>" class="qty-btn" <?= $item['quantite'] <= 1 ? 'disabled' : '' ?>>−</button>
                      <span class="qty-val"><?= htmlspecialchars($item['quantite']) ?></span>
                      <button type="submit" name="quantity" value="<?= $item['quantite'] + 1 ?>" class="qty-btn">+</button>
                    </form>
                  </div>
                  
                  <div class="cart-price"><?= $item['price'] * $item['quantite'] ?> DT</div>
                  
                  <form method="POST" onsubmit="return confirm('Supprimer cet article ?');">
                    <input type="hidden" name="cart_action" value="remove">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['produit_id']) ?>">
                    <button type="submit" class="del-btn" title="Supprimer"><i class="ph ph-trash"></i></button>
                  </form>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div style="text-align: center; padding: 40px; color: #666;">
                <i class="ph ph-shopping-cart" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                <p>Votre panier est vide.</p>
                <a href="../html/index.html" class="link-btn" style="margin-top: 15px; display: inline-block;">Continuer mes achats</a>
              </div>
            <?php endif; ?>
          </div>
          
          <?php if (!empty($cart_items)): ?>
          <div class="cart-summary">
            <div class="cart-summary-info">
              <div class="cart-summary-label">Total</div>
              <div class="cart-total-val" id="cartTotal"><?= htmlspecialchars($cart_total) ?> DT</div>
              <div class="cart-summary-sub">Livraison gratuite incluse</div>
            </div>
            <button class="checkout-btn" onclick="alert('Redirection vers le paiement...')">Passer commande →</button>
          </div>
          <?php endif; ?>
        </section>

        <!-- ─── WISHLIST ─── -->
        <section class="section" id="sec-wishlist">
          <div class="wish-grid">
            <?php if (!empty($fav_items)): ?>
              <?php foreach ($fav_items as $fav): ?>
                <div class="wish-card">
                  <div class="wish-img">
                    <?php 
                    $img_src = $fav['image_path'] ?? '';
                    if (!empty($img_src)) {
                        if (strpos($img_src, 'http') !== 0) {
                            $img_src = "../../public/" . $img_src;
                        }
                    ?>
                      <img src="<?= htmlspecialchars($img_src) ?>" alt="<?= htmlspecialchars($fav['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php } else { ?>
                      🎁
                    <?php } ?>
                  </div>
                  <div class="wish-body">
                    <div class="wish-name"><?= htmlspecialchars($fav['name']) ?></div>
                    <div class="wish-price"><?= htmlspecialchars($fav['price']) ?> DT</div>
                    <form method="POST" action="">
                        <input type="hidden" name="cart_action" value="add">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($fav['product_id']) ?>">
                        <button type="submit" class="wish-add">+ Ajouter au panier</button>
                    </form>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #666;">
                <p>Aucun article dans vos favoris.</p>
              </div>
            <?php endif; ?>
          </div>
        </section>

        <!-- ─── ADDRESSES ─── -->
        <section class="section" id="sec-addresses">
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
        <section class="section" id="sec-payments">
          <div class="pay-grid">
            <div class="pay-card featured">
              <div class="pay-icon">💳</div>
              <div class="pay-info">
                <div class="pay-type">Visa</div>
                <div class="pay-num">•••• •••• •••• 4291</div>
              </div>
              <span class="pay-default-badge">Défaut</span>
            </div>
            <div class="pay-card">
              <div class="pay-icon">🏦</div>
              <div class="pay-info">
                <div class="pay-type">Virement Bancaire</div>
                <div class="pay-num">BNA — Compte courant</div>
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
        <section class="section" id="sec-profile">
          <div class="profile-grid">
            <div class="profile-avatar-block">
              <div class="profile-avatar-circle" id="profileAvatarCircle"><?= !empty($firstName) ? strtoupper($firstName[0]) : 'U' ?></div>
              <img id="profileAvatar" src="<?= $avatar ?>" style="display:none;">
              <div class="profile-fullname"><?= htmlspecialchars($firstName . " " . $lastName) ?></div>
              <div class="profile-since">Membre depuis <?= $_SESSION['user_created_at'] ?? '2023' ?></div>
              <button class="change-photo-btn">Changer la photo</button>
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
        <section class="section" id="sec-notifications">
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
        <section class="section" id="sec-security">
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
        <section class="section" id="sec-support">
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
        </section>

      </div><!-- /content-scroll -->
    </main><!-- /main -->
  </div><!-- /app -->

  <!-- Overlay for mobile menu -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <script src="index.js"></script>
  <script src="updateProfile.js"></script>
</body>
</html>
