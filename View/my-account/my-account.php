<?php


include("../../Controller/AuthController.php");
include("../../Controller/ProfileController.php");
session_start();

// 1. On vérifie si l'utilisateur est bien connecté (via user_id comme dans AuthController)
if (isset($_SESSION["user_id"])) {
    getUserInfo(); // Récupère les infos de l'utilisateur et les stocke dans la session
    getProfileInfo(); // Récupère les infos du profil et les stocke dans la session



   
}



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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Mon Profil | Plant Shop</title>
    <!-- Google Fonts & Font Awesome -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="../css/style_1.css">
    <link rel="stylesheet" href="my-account.css">
    <link rel="stylesheet" href="../css/component/footer.css">
    <link rel="stylesheet" href="avatar.css">
</head>

<body>

<<<<<<< HEAD
<a href="../html/vérifie.html">verifie</a>
=======

>>>>>>> c9b4dfd97ac92a7c1c6cf615116ce52bc0f3ba68

    <!-- top bar with call & promo -->
    <div class="top-bar">
        <span><i class="fas fa-phone-alt"></i> +216 97 181 045</span>
        <span><i class="fas fa-gift"></i> Complete your account and GET 25% OFF for your first order. <span
                class="offer-link">Dont misse it
            </span></span>
    </div>
    <nav class="navbar">
        <div class="nav-left">
            <div class="logo"><a href="index.html"></a>Alpha Store</div>
        </div>
        <div class="nav_container">
            <ul class="nav-links">
                <li><a href="index.html">HOME</a></li>


                <li class="NavItem">
                    <a href="shop.html" class="NavLink nav-link-shop">
                        <span class="NavLinkTitle">SHOP</span>
                    </a>

                    <div class="ItemContent mega-content">
                        <div class="mega-inner">

                            <div class="mega-col">
                                <div class="ItemTitle">Kids</div>
                                <ul>
                                    <li><a href="#">Accessoires</a></li>
                                    <li><a href="#">Denim</a></li>
                                    <li><a href="#">Costumes</a></li>
                                </ul>
                            </div>

                            <div class="mega-col">
                                <div class="ItemTitle">Homme</div>
                                <ul>
                                    <li><a href="#">Accessoires</a></li>
                                    <li><a href="#">Denim</a></li>
                                    <li><a href="#">Costumes</a></li>
                                </ul>
                            </div>

                            <div class="mega-col">
                                <div class="ItemTitle">Femme</div>
                                <ul>
                                    <li><a href="#">Robes</a></li>
                                    <li><a href="#">Bags</a></li>
                                    <li><a href="#">Shoes</a></li>
                                </ul>
                            </div>

                            <div class="mega-col">
                                <div class="ItemTitle">Tech</div>
                                <ul>
                                    <li><a href="#">Smartphone</a></li>
                                    <li><a href="#">Laptop</a></li>
                                    <li><a href="#">Tablet</a></li>
                                </ul>
                            </div>


                            <div class="mega-col">
                                <div class="ItemTitle">Our Ai assistant</div>
                                <div class="shop-card">
                                    <span class="badge">New</span>
                                    <p>Collection 2026</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </li>
                <!-- ✅ FIN NavItem Shop -->

                <li><a href="news.html">NEWS&ACT</a></li>
                <li><a href="#">New Arrivals</a></li>
            </ul>
        </div>

        <div class="nav-right">
            <a href="AboutUs.html" class="nav-link-secondary">About Us</a>
            <a href="contactUs.html" class="nav-link-secondary">Contact Us</a>
            <div class="icon">
                <i class="fas fa-search" id="search-btn"></i>

            </div>

            <form action="" class="search-form">
                <input type="search" id="search-bar" placeholder="search here...">
                <label for="search-bar" class="fas fa-search"></label>
            </form>


            <span class="price">$0.00</span>
            <div class="cart-container">
                <i class="fas fa-shopping-bag"></i>
                <span class="cart-count">0</span>
            </div>
            <a href="signUp.php" id="user_icon" class="user-icon" aria-label="Sign up / log in">
                <i class="fas fa-user"></i>
            </a>
        </div>
    </nav>










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
                    <?php $avatar = "https://i.pravatar.cc/180?img=1"; // default ?>
                            
                            <?php if (isset($_SESSION["user_avatar"]) && !empty($_SESSION["user_avatar"])): ?>
                            <?php $avatar = htmlspecialchars($_SESSION["user_avatar"]); ?>
                            <?php endif ?>
                    <div class="profile-card">

                        <div class="avatar-container" id="avatarContainer">
                            
                                <img id="profileAvatar" class="profile-img" src="<?= $avatar ?>" alt="Avatar principal">

                        </div>  


                        <!-- Sélecteur avec 3 avatars -->
                        <div id="avatarPicker" class="avatar-picker hidden">
                            <div class="picker-title">✨ Choisissez votre avatar ✨</div>
                            <div class="avatar-options">
                                <!-- 3 options distinctes : avatar 1, avatar 2, avatar 3 -->
                                <img class="option-img" data-avatar="https://i.pravatar.cc/180?img=3" src="https://i.pravatar.cc/70?img=3" alt="Avatar nature">
                                <img class="option-img" data-avatar="https://i.pravatar.cc/180?img=7" src="https://i.pravatar.cc/70?img=7" alt="Avatar professionnel">
                                <img class="option-img" data-avatar="https://i.pravatar.cc/180?img=9" src="https://i.pravatar.cc/70?img=9" alt="Avatar artistique">
                            </div>
                        </div>
                    </div>

                    <!-- Petit toast de confirmation -->
                    <div id="toastMsg" class="toast-msg">✓ Avatar changé !</div>
                </div>
                <div class="form-grid">

                    <div class="input-group"><label>First Name *</label>
                        <?php
                        // Si la session existe, on prend la valeur, sinon une chaîne vide.
                        $firstname = $_SESSION['user_name'] ?? '';
                        ?>




                        <input type="text" id="firstName"
                            value="<?= htmlspecialchars($firstname) ?>" required>
                    </div>
                    <div class="input-group"><label>Last Name *</label>
                        <?php
                        // Si la session existe, on prend la valeur, sinon une chaîne vide.
                        $lastname = $_SESSION['user_last_name'] ?? '';
                        ?>



                        <input type="text" id="lastName" value="<?= htmlspecialchars($lastname) ?>" required>
                    </div>
                    <div class="input-group"><label>Email *</label><input type="email" id="email"
                            value="<?= htmlspecialchars($_SESSION['user_email']) ?>" required></div>
                    <div class="input-group">
                        <label for="age">Age *</label>
                        <?php
                        // Si la session existe, on prend la valeur, sinon une chaîne vide.
                        $age = $_SESSION['user_age'] ?? '';
                        ?>

                        <input type="text" id="age" value="<?= htmlspecialchars($age) ?>" required>
                    </div>
                    <div class="input-group"><label>Phone *</label>
                        <?php
                        // Si la session existe, on prend la valeur, sinon une chaîne vide.
                        $phone = $_SESSION['user_phone'] ?? '';
                        ?>



                        <input type="tel" id="phone" value="<?= htmlspecialchars($phone) ?>" required>
                    </div>

                    <div class="input-group"><label>Gender *</label>
                        <?php
                        // Si la session existe, on prend la valeur, sinon une chaîne vide.
                        $gender = $_SESSION['user_gender'] ?? '';
                        ?>

                        <select id="gender" name="gender">
                            <option value="male" <?= ($gender === 'male') ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= ($gender === 'female') ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                </div>
                <button id="updateProfileBtn" style="margin-top: 24px;"><i class="fas fa-save"></i> Update
                    Changes</button>
            </div>

            <!-- section 2 : My Orders -->
            <div id="orders-section" class="section-card">
                <h2><i class="fas fa-package"></i> My Orders (2)</h2>
                <div style="margin-bottom: 20px; display: flex; justify-content: flex-end;"><select>
                        <option>All ▼</option>
                        <option>Recent</option>
                    </select></div>
                <!-- order 1 -->
                <div class="order-item">
                    <div class="order-header">
                        <span class="order-id">#SDGT1254FD</span>
                        <span class="order-total">Total: $150.00</span>
                        <span><i class="fab fa-paypal"></i> PayPal</span>
                        <span>Est. Delivery: 24 October 2024</span>
                    </div>
                    <div class="product-list">
                        <span class="product-tag">🌿 Monstera deliciosa</span>
                        <span class="product-tag">🍃 Calathea Medallion</span>
                        <span class="product-tag">🌱 Nephrolepis exaltata</span>
                        <span class="product-tag">💧 Watermelon Peperomia</span>
                    </div>
                    <div class="order-actions">
                        <button class="small-btn track-order" data-order="SDGT1254FD">Track Order</button>
                        <button class="small-btn invoice-order" data-order="SDGT1254FD">Invoice</button>
                        <button class="small-btn cancel-order" data-order="SDGT1254FD">Cancel Order</button>
                        <span class="badge" style="background:#e0f0da; padding:4px 12px; border-radius:30px;">✔
                            Accepted</span>
                    </div>
                </div>
                <!-- order 2 -->
                <div class="order-item">
                    <div class="order-header">
                        <span class="order-id">#SDGT7412DF</span>
                        <span class="order-total">Total: $24.00</span>
                        <span><i class="fas fa-money-bill-wave"></i> Cash</span>
                        <span>Delivered: 26 October 2024</span>
                    </div>
                    <div class="product-list">
                        <span class="product-tag">🌿 Pepper Face Plant</span>
                    </div>
                    <div class="order-actions">
                        <button class="small-btn add-review" data-order="SDGT7412DF">Add Review</button>
                        <button class="small-btn invoice-order" data-order="SDGT7412DF">Invoice</button>
                        <span class="badge" style="background:#d9f0e3;">✅ Delivered</span>
                    </div>
                </div>
            </div>

            <!-- section 3 : Manage Address -->
            <div id="address-section" class="section-card">
                <h2><i class="fas fa-home"></i> Manage Address</h2>
                <div id="addressListContainer">
                    <!-- existing addresses -->
                    <div class="address-card" data-addr-id="addr1">
                        <div><strong>Leslie Cooper</strong><br>2464 Royal Ln. Mesa, New Jersey 45463</div>
                        <div class="address-actions"><button class="edit-address">Edit</button><button
                                class="delete-address">Delete</button></div>
                    </div>
                    <div class="address-card" data-addr-id="addr2">
                        <div><strong>Leslie Cooper</strong><br>6391 Elgin St. Celina, Delaware 10299</div>
                        <div class="address-actions"><button class="edit-address">Edit</button><button
                                class="delete-address">Delete</button></div>
                    </div>
                </div>
                <hr>
                <h3>Add New Address</h3>
                <div class="form-grid">
                    <div class="input-group"><label>First Name *</label><input id="addrFirstName" placeholder="John">
                    </div>
                    <div class="input-group"><label>Last Name *</label><input id="addrLastName" placeholder="Doe"></div>
                    <div class="input-group full-width"><label>Company (Optional)</label><input id="addrCompany"
                            placeholder="Company Name"></div>
                    <div class="input-group"><label>Country *</label><select id="addrCountry">
                            <option>Select Country ▼</option>
                            <option>USA</option>
                            <option>Canada</option>
                        </select></div>
                    <div class="input-group full-width"><label>Street Address *</label><input id="addrStreet"
                            placeholder="Enter Street Address"></div>
                    <div class="input-group"><label>City *</label><input id="addrCity" placeholder="City"></div>
                    <div class="input-group"><label>State *</label><input id="addrState" placeholder="State"></div>
                    <div class="input-group"><label>Zip Code *</label><input id="addrZip" placeholder="Zip Code"></div>
                    <div class="input-group"><label>Phone *</label><input id="addrPhone" placeholder="Phone Number">
                    </div>
                    <div class="input-group"><label>Email *</label><input id="addrEmail" placeholder="Email Address">
                    </div>
                </div>
                <button id="addAddressBtn">+ Add Address</button>
            </div>

            <!-- section 4 : Payment Method -->
            <div id="payment-section" class="section-card">
                <h2><i class="fas fa-wallet"></i> Payment Methods</h2>
                <div id="paymentMethodsList">
                    <div class="payment-method-item" data-payment="paypal"><span><i class="fab fa-paypal"></i>
                            PayPal</span><button class="link-payment">Link Account</button></div>
                    <div class="payment-method-item" data-payment="visa"><span><i class="fab fa-cc-visa"></i> VISA ****
                            **** 8047</span><button class="delete-payment">Delete</button></div>
                    <div class="payment-method-item" data-payment="google"><span><i class="fab fa-google-pay"></i>
                            Google Pay</span><button class="link-payment">Link Account</button></div>
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
                <label style="display: flex; gap: 8px; margin: 12px 0;"><input type="checkbox"> Save card for future
                    payments</label>
                <button id="addCardBtn">Add Card</button>
            </div>

            <!-- section 5 : Password Manager -->
            <div id="password-section" class="section-card">
                <h2><i class="fas fa-key"></i> Password Manager</h2>
                <div class="form-grid">
                    <div class="input-group full-width"><label>Current Password *</label><input type="password"
                            id="currentPwd" placeholder="Enter current password"></div>
                    <div class="input-group full-width"><label>New Password *</label><input type="password" id="newPwd"
                            placeholder="Enter new password"></div>
                    <div class="input-group full-width"><label>Confirm New Password *</label><input type="password"
                            id="confirmPwd" placeholder="Confirm password"></div>
                </div>
                <button id="updatePasswordBtn">Update Password</button>
                <div style="margin-top: 16px;"><a href="#" style="color:#58855f;">Forget Password?</a></div>
            </div>
        </div>
    </div>

    <div id="footer-placeholder"></div>
    <!-- JavaScript -->
    <script src="my-account.js"></script>
    <script src="../javaScript/main.js"></script>

    <script>
        (function() {
            // ----- ÉLÉMENTS DOM -----
            const profileAvatar = document.getElementById('profileAvatar');
            const avatarPicker = document.getElementById('avatarPicker');
            const avatarOptions = document.querySelectorAll('.option-img');
            const toast = document.getElementById('toastMsg');

            let isPickerOpen = false;

            // ----- FONCTIONS UTILITAIRES -----
            function showPicker() {
                if (avatarPicker.classList.contains('hidden')) {
                    avatarPicker.classList.remove('hidden');
                    isPickerOpen = true;
                }
            }

            function hidePicker() {
                if (!avatarPicker.classList.contains('hidden')) {
                    avatarPicker.classList.add('hidden');
                    isPickerOpen = false;
                }
            }

            function togglePicker() {
                if (isPickerOpen) {
                    hidePicker();
                } else {
                    showPicker();
                }
            }

            function showToast(message) {
                if (!toast) return;
                toast.textContent = message || '✓ Avatar changé !';
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(-50%) scale(1)';
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(-50%) scale(0.95)';
                }, 1200);
            }

            function updateAvatar(newAvatarUrl) {
                if (!newAvatarUrl) return;
                profileAvatar.src = newAvatarUrl;
                // petite animation
                profileAvatar.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    profileAvatar.style.transform = '';
                }, 150);
                hidePicker();
                showToast('✓ Avatar changé !');
            }

            // ----- GESTION DES CLICS SUR LES 3 MINIATURES -----
            avatarOptions.forEach(option => {
                option.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const chosenAvatarUrl = option.getAttribute('data-avatar');
                    if (chosenAvatarUrl) {
                        updateAvatar(chosenAvatarUrl);
                    } else {
                        // fallback (si data-avatar manquant)
                        const fallbackSrc = option.src.replace('/70?', '/180?');
                        updateAvatar(fallbackSrc);
                    }
                });
            });

            // ----- CLIC SUR L'IMAGE PRINCIPALE (user icon) -----
            profileAvatar.addEventListener('click', (e) => {
                e.stopPropagation();
                togglePicker();
            });

            // Empêcher la fermeture quand on clique à l'intérieur du sélecteur
            avatarPicker.addEventListener('click', (e) => {
                e.stopPropagation();
            });

            // ----- FERMER LE SÉLECTEUR SI ON CLIQUE AILLEURS -----
            document.addEventListener('click', (e) => {
                if (isPickerOpen) {
                    const isClickOnAvatar = (e.target === profileAvatar || profileAvatar.contains(e.target));
                    const isClickInsidePicker = avatarPicker.contains(e.target);
                    if (!isClickOnAvatar && !isClickInsidePicker) {
                        hidePicker();
                    }
                }
            });

            // Touche Échap pour fermer
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && isPickerOpen) {
                    hidePicker();
                }
            });

            // Préchargement des 3 avatars pour plus de fluidité
            function preloadImages() {
                const urls = [
                    'https://i.pravatar.cc/180?img=3',
                    'https://i.pravatar.cc/180?img=7',
                    'https://i.pravatar.cc/180?img=9'
                ];
                urls.forEach(url => {
                    const img = new Image();
                    img.src = url;
                });
            }
            preloadImages();

            // Initialisation : panneau masqué
            hidePicker();
        })();
    </script>
    <script src="updateProfile.js"></script>

</body>

</html>