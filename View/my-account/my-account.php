
<?php
session_start();

// 1. On vérifie si l'utilisateur est bien connecté (via user_id comme dans AuthController)
if (isset($_SESSION["user_id"])) {
    
    // 2. On charge la connexion PDO existante
    $pdo = require __DIR__ . "/../../config/Database.php";

    // 3. On récupère les infos de l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION["user_id"]]);
    $user = $stmt->fetch();
    
} else {
    // Redirection vers la page de login si non connecté
    header("Location: ../html/index.html");
    exit;
}
?>





<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Mon Profil | Plant Shop</title>
    <!-- Google Fonts & Font Awesome -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="my-account.css">
    <link rel="stylesheet" href="../css/component/fotter.css">
</head>

<body>

    <!-- top bar with call & promo -->
      <div class="top-bar">
            <span><i class="fas fa-phone-alt"></i> +216 97 181 045</span>
            <span><i class="fas fa-gift"></i> Sign up and GET 25% OFF for your first order. <span
                    class="offer-link">Sign up
                    now</span></span>
        </div>
    <div id="navbar-placeholder"></div>

    


    <div class="container">
        <?php if (isset($user)) : ?>

        <h1 class="salutation>Hello, <?= htmlspecialchars($user["name"] ?? 'Utilisateur') ?></h1>

        <?php endif ;?>
       
        
        <!-- dashboard -->
        <div class="dashboard">
            <!-- side menu -->
            <div class="profile-sidebar">
                <div class="sidebar-nav">
                    <div class="nav-item active" data-section="personal">
                        <i class="fas fa-user-circle"></i> <span>Personal Information</span>
                    </div>
                    <div class="nav-item" data-section="orders">
                        <i class="fas fa-shopping-bag"></i> <span>My Orders</span>
                    </div>
                    <div class="nav-item" data-section="address">
                        <i class="fas fa-map-marker-alt"></i> <span>Manage Address</span>
                    </div>
                    <div class="nav-item" data-section="payment">
                        <i class="fas fa-credit-card"></i> <span>Payment Method</span>
                    </div>
                    <div class="nav-item" data-section="password">
                        <i class="fas fa-lock"></i> <span>Password Manager</span>
                    </div>
                    <div class="nav-item logout-item" id="logoutBtn">
                        <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                    </div>
                </div>
            </div>

            <!-- main dynamic content -->
            <div class="profile-content">
                <!-- section 1 : Personal Information -->
                <div id="personal-section" class="section-card active-section">
                    <h2><i class="fas fa-user-edit"></i> Personal Information</h2>
                    <div class="avatar-wrap">
                        <img src="https://i.pravatar.cc/80?img=47" alt="Profile photo">
                        <div class="avatar-edit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="input-group"><label>First Name *</label><input type="text" id="firstName"
                                value="<?= htmlspecialchars($user["name"] ?? '') ?>"></div>
                        <div class="input-group"><label>Last Name *</label><input type="text" id="lastName"
                                value="Jeday"></div>
                        <div class="input-group"><label>Email *</label>
                        <input type="email" id="email"
                                value="<?= htmlspecialchars($user["email"] ?? '') ?>"></div>
                        <div class="input-group"><label>Phone *</label><input type="tel" id="phone"
                                value="+216 "></div>
                        <div class="input-group"><label>Gender *</label>
                            <select id="gender">
                                <option value="Female" selected>Female</option>
                                <option value="Male">Male</option>

                            </select>
                        </div>
                    </div>
                    <button id="updateProfileBtn"><i class="fas fa-save"></i> Update Changes</button>
                </div>
                <div id="orders-section" class="section-card">
                    <!-- section 2 : My Orders -->
                    <div class="panel" id="tab-orders">
                        <div class="orders-header">
                            <h2>Orders (2)</h2>
                            <div class="sort-wrap">
                                Sort by :
                                <select>
                                    <option>All</option>
                                    <option>Accepted</option>
                                    <option>Delivered</option>
                                </select>
                            </div>
                        </div>

                        <div class="order-card">
                            <div class="order-head">
                                <div class="order-head-col"><span class="label">Order ID</span><span
                                        class="value id">#SDGT1254FD</span></div>
                                <div class="order-head-col"><span class="label">Total Payment</span><span
                                        class="value">$150.00</span></div>
                                <div class="order-head-col"><span class="label">Payment Method</span><span
                                        class="value">Paypal</span></div>
                                <div class="order-head-col"><span class="label">Estimated Delivery D…</span><span
                                        class="value">24 October 2024</span></div>
                            </div>
                            <div class="order-items">
                                <div class="order-item">
                                    <img src="https://images.unsplash.com/photo-1614594975525-e45190c55d0b?w=100&h=100&fit=crop"
                                        alt="Monstera">
                                    <div class="order-item-info">
                                        <div class="name">Monstera deliciosa</div>
                                        <div class="cat">Indoor Plant</div>
                                    </div>
                                </div>
                                <div class="order-item">
                                    <img src="https://images.unsplash.com/photo-1509423350716-97f9360b4e09?w=100&h=100&fit=crop"
                                        alt="Calathea">
                                    <div class="order-item-info">
                                        <div class="name">Calathea Medallion</div>
                                        <div class="cat">Indoor Plant</div>
                                    </div>
                                </div>
                                <div class="order-item">
                                    <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=100&h=100&fit=crop"
                                        alt="Fern">
                                    <div class="order-item-info">
                                        <div class="name">Fephrolepis exaltata</div>
                                        <div class="cat">Indoor Plant</div>
                                    </div>
                                </div>
                            </div>
                            <div class="order-footer">
                                <span class="status-badge status-accepted">✔ Accepted</span>
                                <span style="font-size:13px;color:var(--text-mid)">Your Order has been Accepted</span>
                                <div class="order-actions">
                                    <button class="btn-green" style="padding:9px 18px;font-size:13px">Track
                                        Order</button>
                                    <button class="btn-outline" style="padding:8px 18px;font-size:13px">Invoice</button>
                                    <span class="cancel-link">Cancel Order</span>
                                </div>
                            </div>
                        </div>

                        <div class="order-card">
                            <div class="order-head">
                                <div class="order-head-col"><span class="label">Order ID</span><span
                                        class="value id">#SDGT7412DF</span></div>
                                <div class="order-head-col"><span class="label">Total Payment</span><span
                                        class="value">$24.00</span></div>
                                <div class="order-head-col"><span class="label">Payment Method</span><span
                                        class="value">Cash</span></div>
                                <div class="order-head-col"><span class="label">Delivered Date</span><span
                                        class="value">26
                                        October 2024</span></div>
                            </div>
                            <div class="order-items">
                                <div class="order-item">
                                    <img src="https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=100&h=100&fit=crop"
                                        alt="Pepper Face">
                                    <div class="order-item-info">
                                        <div class="name">Pepper Face Plant</div>
                                        <div class="cat">Indoor Plant</div>
                                    </div>
                                </div>
                            </div>
                            <div class="order-footer">
                                <span class="status-badge status-delivered">✔ Delivered</span>
                                <span style="font-size:13px;color:var(--text-mid)">Your Order has been Delivered</span>
                                <div class="order-actions">
                                    <button class="btn-green" style="padding:9px 18px;font-size:13px">Add
                                        Review</button>
                                    <button class="btn-outline" style="padding:8px 18px;font-size:13px">Invoice</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- section 3 : Manage Address -->
                <div id="address-section" class="section-card">
                    <h2><i class="fas fa-home"></i> Manage Address</h2>
                    <div id="addressListContainer">
                        <div class="address-card" data-addr-id="addr1">
                            <div><strong>Leslie Cooper</strong><br>2464 Royal Ln. Mesa, New Jersey 45463</div>
                            <div class="address-actions">
                                <button class="edit-address">Edit</button>
                                <button class="delete-address">Delete</button>
                            </div>
                        </div>
                        <div class="address-card" data-addr-id="addr2">
                            <div><strong>Leslie Cooper</strong><br>6391 Elgin St. Celina, Delaware 10299</div>
                            <div class="address-actions">
                                <button class="edit-address">Edit</button>
                                <button class="delete-address">Delete</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h3>Add New Address</h3>
                    <div class="form-grid">
                        <div class="input-group"><label>First Name *</label><input id="addrFirstName"
                                placeholder="John"></div>
                        <div class="input-group"><label>Last Name *</label><input id="addrLastName" placeholder="Doe">
                        </div>
                        <div class="input-group full-width"><label>Company (Optional)</label><input id="addrCompany"
                                placeholder="Company Name"></div>
                        <div class="input-group"><label>Country *</label>
                            <select id="addrCountry">
                                <option>Select Country ▼</option>
                                <option>USA</option>
                                <option>Canada</option>
                            </select>
                        </div>
                        <div class="input-group full-width"><label>Street Address *</label><input id="addrStreet"
                                placeholder="Enter Street Address"></div>
                        <div class="input-group"><label>City *</label><input id="addrCity" placeholder="City"></div>
                        <div class="input-group"><label>State *</label><input id="addrState" placeholder="State"></div>
                        <div class="input-group"><label>Zip Code *</label><input id="addrZip" placeholder="Zip Code">
                        </div>
                        <div class="input-group"><label>Phone *</label><input id="addrPhone" placeholder="Phone Number">
                        </div>
                        <div class="input-group"><label>Email *</label><input id="addrEmail"
                                placeholder="Email Address"></div>
                    </div>
                    <button id="addAddressBtn">+ Add Address</button>
                </div>

                <!-- section 4 : Payment Method -->
                <div id="payment-section" class="section-card">
                    <h2><i class="fas fa-wallet"></i> Payment Methods</h2>
                    <div id="paymentMethodsList">
                        <div class="payment-method-item" data-payment="paypal">
                            <span><i class="fab fa-paypal"></i> PayPal</span>
                            <button class="link-payment">Link Account</button>
                        </div>
                        <div class="payment-method-item" data-payment="visa">
                            <span><i class="fab fa-cc-visa"></i> VISA **** **** 8047</span>
                            <button class="delete-payment">Delete</button>
                        </div>
                        <div class="payment-method-item" data-payment="google">
                            <span><i class="fab fa-google-pay"></i> Google Pay</span>
                            <button class="link-payment">Link Account</button>
                        </div>
                    </div>
                    <hr>
                    <h3>Add New Credit/Debit Card</h3>
                    <div class="form-grid">
                        <div class="input-group full-width"><label>Card Holder Name *</label><input id="cardName"
                                placeholder="John Doe"></div>
                        <div class="input-group full-width"><label>Card Number *</label><input id="cardNumber"
                                placeholder="4716 9627 1635 8047"></div>
                        <div class="input-group"><label>Expiry Date *</label><input id="cardExpiry" placeholder="MM/YY">
                        </div>
                        <div class="input-group"><label>CVV *</label><input id="cardCvv" placeholder="000"></div>
                    </div>
                    <label style="display: flex; gap: 8px; margin: 12px 0;">
                        <input type="checkbox" id="saveCardCheckbox"> Save card for future payments
                    </label>
                    <button id="addCardBtn">Add Card</button>
                </div>

                <!-- section 5 : Password Manager -->
                <div id="password-section" class="section-card">
                    <h2><i class="fas fa-key"></i> Password Manager</h2>
                    <div class="form-grid">
                        <div class="input-group full-width"><label>Current Password *</label><input type="password"
                                id="currentPwd" placeholder="Enter current password"></div>
                        <div class="input-group full-width"><label>New Password *</label><input type="password"
                                id="newPwd" placeholder="Enter new password"></div>
                        <div class="input-group full-width"><label>Confirm New Password *</label><input type="password"
                                id="confirmPwd" placeholder="Confirm password"></div>
                    </div>
                    <button id="updatePasswordBtn">Update Password</button>
                    <div style="margin-top: 16px;"><a href="#" id="forgetPasswordLink" style="color:#58855f;">Forget
                            Password?</a></div>
                </div>
            </div>
        </div>

        <!-- features row (Free shipping etc) -->
        <div class="features-row">
            <div class="feature"><i class="fas fa-truck"></i>
                <h4>Free Shipping</h4>
                <p>Free shipping for order above $50</p>
            </div>
            <div class="feature"><i class="fas fa-credit-card"></i>
                <h4>Flexible Payment</h4>
                <p>Multiple secure payment options</p>
            </div>
            <div class="feature"><i class="fas fa-headset"></i>
                <h4>24×7 Support</h4>
                <p>We support online all days.</p>
            </div>
        </div>

        <!-- newsletter -->
        <div class="newsletter">
            <h3>Our Newsletter</h3>
            <p>Subscribe to Our Newsletter to Get Updates on Our Latest Offers</p>
            <p style="font-size:0.9rem;">Get 25% off on your first order just by subscribing to our newsletter</p>
            <div class="newsletter-form">
                <input type="email" id="newsletterEmail" placeholder="Enter Email Address">
                <button id="subscribeBtn">Subscribe</button>
            </div>
        </div>
    </div>

   <div id="footer-placeholder"></div>
    <!-- JavaScript -->
    <script src="my-account.js"></script>
    <script src="../javaScript/main.js"></script>

</body>

</html>