<?php

include("../../Controller/AuthController.php");
include("../../Controller/ProfileController.php");
session_start();

// Verify user is logged in (via user_id from AuthController)
if (isset($_SESSION["user_id"])) {
    getUserInfo(); // Retrieves user info and stores in session
    getProfileInfo(); // Retrieves profile info and stores in session
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
          <span class="nav-badge">3</span>
        </button>
        <button class="nav-item" data-section="cart">
          <i class="ph ph-shopping-cart"></i>
          <span>Panier</span>
          <span class="nav-badge">2</span>
        </button>
        <button class="nav-item" data-section="wishlist">
          <i class="ph ph-heart"></i>
          <span>Favoris</span>
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
              <div class="stat-value">9</div>
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
                <div class="order-row">
                  <div class="order-dot delivered"></div>
                  <span class="order-id">#4821</span>
                  <span class="order-name">Sneakers Air Max</span>
                  <span class="badge delivered">Livré</span>
                  <span class="order-price">189 DT</span>
                </div>
                <div class="order-row">
                  <div class="order-dot transit"></div>
                  <span class="order-id">#4819</span>
                  <span class="order-name">RTX 4060 GPU</span>
                  <span class="badge transit">En transit</span>
                  <span class="order-price">920 DT</span>
                </div>
                <div class="order-row">
                  <div class="order-dot pending"></div>
                  <span class="order-id">#4810</span>
                  <span class="order-name">Mechanical KB</span>
                  <span class="badge pending">En attente</span>
                  <span class="order-price">85 DT</span>
                </div>
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
            <div class="table-wrap">
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
                  <tr>
                    <td><strong>#4821</strong></td>
                    <td>12 Avr</td>
                    <td>Sneakers Air Max</td>
                    <td>189 DT</td>
                    <td><span class="badge delivered">Livré</span></td>
                    <td><button class="view-btn">Détails</button></td>
                  </tr>
                  <tr>
                    <td><strong>#4819</strong></td>
                    <td>10 Avr</td>
                    <td>RTX 4060 GPU</td>
                    <td>920 DT</td>
                    <td><span class="badge transit">En transit</span></td>
                    <td><button class="view-btn">Détails</button></td>
                  </tr>
                  <tr>
                    <td><strong>#4810</strong></td>
                    <td>3 Avr</td>
                    <td>Mechanical KB</td>
                    <td>85 DT</td>
                    <td><span class="badge pending">En attente</span></td>
                    <td><button class="view-btn">Détails</button></td>
                  </tr>
                  <tr>
                    <td><strong>#4805</strong></td>
                    <td>28 Mar</td>
                    <td>USB-C Hub</td>
                    <td>55 DT</td>
                    <td><span class="badge delivered">Livré</span></td>
                    <td><button class="view-btn">Détails</button></td>
                  </tr>
                  <tr>
                    <td><strong>#4780</strong></td>
                    <td>15 Mar</td>
                    <td>Hoodie + Jeans</td>
                    <td>210 DT</td>
                    <td><span class="badge delivered">Livré</span></td>
                    <td><button class="view-btn">Détails</button></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- ─── CART ─── -->
        <section class="section" id="sec-cart">
          <div class="cart-list">
            <div class="cart-item" data-price="189">
              <div class="cart-img">👟</div>
              <div class="cart-info">
                <div class="cart-name">Nike Air Max 270</div>
                <div class="cart-detail">Taille 42 · Blanc/Noir</div>
              </div>
              <div class="qty-ctrl">
                <button class="qty-btn" data-action="dec">−</button>
                <span class="qty-val">1</span>
                <button class="qty-btn" data-action="inc">+</button>
              </div>
              <div class="cart-price">189 DT</div>
              <button class="del-btn" title="Supprimer"><i class="ph ph-trash"></i></button>
            </div>
            <div class="cart-item" data-price="85">
              <div class="cart-img">⌨️</div>
              <div class="cart-info">
                <div class="cart-name">Keychron K2 Keyboard</div>
                <div class="cart-detail">Switch Rouge · Sans fil</div>
              </div>
              <div class="qty-ctrl">
                <button class="qty-btn" data-action="dec">−</button>
                <span class="qty-val">1</span>
                <button class="qty-btn" data-action="inc">+</button>
              </div>
              <div class="cart-price">85 DT</div>
              <button class="del-btn" title="Supprimer"><i class="ph ph-trash"></i></button>
            </div>
          </div>
          <div class="cart-summary">
            <div class="cart-summary-info">
              <div class="cart-summary-label">Total</div>
              <div class="cart-total-val" id="cartTotal">274 DT</div>
              <div class="cart-summary-sub">Livraison gratuite incluse</div>
            </div>
            <button class="checkout-btn">Passer commande →</button>
          </div>
        </section>

        <!-- ─── WISHLIST ─── -->
        <section class="section" id="sec-wishlist">
          <div class="wish-grid">
            <div class="wish-card"><div class="wish-img">💻</div><div class="wish-body"><div class="wish-name">MacBook Air M2</div><div class="wish-price">2 899 DT</div><button class="wish-add">+ Ajouter au panier</button></div></div>
            <div class="wish-card"><div class="wish-img">🎧</div><div class="wish-body"><div class="wish-name">Sony WH-1000XM5</div><div class="wish-price">499 DT</div><button class="wish-add">+ Ajouter au panier</button></div></div>
            <div class="wish-card"><div class="wish-img">📱</div><div class="wish-body"><div class="wish-name">iPhone 15 Pro</div><div class="wish-price">3 199 DT</div><button class="wish-add">+ Ajouter au panier</button></div></div>
            <div class="wish-card"><div class="wish-img">🖥️</div><div class="wish-body"><div class="wish-name">Dell 4K Monitor</div><div class="wish-price">750 DT</div><button class="wish-add">+ Ajouter au panier</button></div></div>
            <div class="wish-card"><div class="wish-img">🕹️</div><div class="wish-body"><div class="wish-name">PS5 Controller</div><div class="wish-price">180 DT</div><button class="wish-add">+ Ajouter au panier</button></div></div>
            <div class="wish-card"><div class="wish-img">⌚</div><div class="wish-body"><div class="wish-name">Apple Watch S9</div><div class="wish-price">1 100 DT</div><button class="wish-add">+ Ajouter au panier</button></div></div>
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
<<<<<<< HEAD
</body>
</html>
=======
  <script src="updateProfile.js"></script>
</body>
</html>
>>>>>>> c9b4dfd97ac92a7c1c6cf615116ce52bc0f3ba68
