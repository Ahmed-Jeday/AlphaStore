<?php 
session_start(); 
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link
  href="https://cdn.jsdelivr.net/npm/remixicon@4.9.0/fonts/remixicon.css"
  rel="stylesheet">
    <title>AlphaStore - Dynamic Header</title>
    <style>
       

        /* --- Configuration du Header --- */
        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background-color: white;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            
        }

        /* --- Bandeau Promo (Noir) --- */
        .promo-bar {
            background: linear-gradient(135deg, #1a1a1a 0%, #2c3e50 50%, #1a1a1a 100%);
            color: #fff;
            text-align: center;
            padding: 12px 0;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.4s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            border-bottom: 2px solid rgba(255, 215, 0, 0.3);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            text-transform: uppercase;
        }

        /* Glimmer effect */
        .promo-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(
                to right,
                transparent,
                rgba(255, 255, 255, 0.1),
                transparent
            );
            transition: none;
            animation: glimmer 4s infinite;
        }

        @keyframes glimmer {
            0% { left: -100%; }
            20% { left: 200%; }
            100% { left: 200%; }
        }

        .promo-bar a {
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: rgba(255, 255, 255, 0.1);
            padding: 5px;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.2);
        }

        .promo-bar a:hover {
            transform: scale(1.2) rotate(15deg);
            background: rgba(255, 215, 0, 0.2);
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.4);
        }

        .promo-bar img {
            width: 28px;
            height: 28px;
            filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.5));
            animation: spin-slow 10s linear infinite, pulse-icon 2s infinite ease-in-out;
        }

        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes pulse-icon {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* --- Barre Supérieure (Gris clair) --- */
        .top-nav-bar {
            display: flex;
            justify-content: space-between;
            background-color: #f1f1f1;
            height: 40px;
            transition: all 0.3s ease;
        }

        .brand-tabs {
            display: flex;
        }

        .tab {
            padding: 0 25px;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #222;
            font-size: 13px;
            font-weight: bold;
        }

        .user-actions {
            display: flex;
            align-items: center;
            padding-right: 20px;
            gap: 15px;
        }


        .divider { color: #ccc; margin: 0 5px; }

        .icon { width: 22px; height: 22px; }
        .icon-small { width: 16px; height: 16px; }

        /* --- Navigation Principale (Logo + Liens + Recherche) --- */
        .main-nav-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 25px;
            background-color: white;
            border-bottom: 1px solid #e5e5e5;
            position: relative;
            transition: all 0.4s ease;
        }

        .main-nav-bar .logo {
            font-size: 26px;
            font-weight: 800;
            color: #222;
            padding-right: 15px;
            border-right: 1px solid #222;
        }

        .main-links {
            display: flex;
            gap: 25px;
        }

        .main-links a {
            text-decoration: none;
            color: #222;
            font-weight: 600;
            font-size: 15px;
        }

        .main-links a.sale { color: #d90000; }

        /* --- Barre de recherche --- */
        .search-container {
            display: flex;
            align-items: center;
            border: 1px solid #222;
            border-radius: 30px;
            padding: 3px 3px 3px 20px;
            width: 320px;
            transition: width 0.4s ease;
        }

        .search-container input {
            border: none;
            outline: none;
            flex-grow: 1;
            font-size: 14px;
            background: transparent;
        }

        .search-btn {
            background-color: #222;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        /* Panier seul (caché par défaut) */
        .cart-only {
            display: none;
            position: absolute;
            right: 25px;
        }

        /* =========================================
           ÉVOLUTION AU SCROLL (IMAGE 2 -> IMAGE 1)
           ========================================= */

        .site-header.scrolled {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* Cache les barres du haut */
        .site-header.scrolled .promo-bar,
        .site-header.scrolled .top-nav-bar {
            height: 0;
            opacity: 0;
            overflow: hidden;
            padding: 0;
        }

        /* Cache Logo et Liens centraux */
        .site-header.scrolled .logo,
        .site-header.scrolled .main-links {
            display: none;
        }

        /* Centre la recherche et ajuste la taille */
        .site-header.scrolled .main-nav-bar {
            justify-content: center;
            padding: 10px 0;
        }

        .site-header.scrolled .search-container {
            width: 60%; /* Agrandit la barre comme sur l'image 1 */
            max-width: 800px;
        }

        /* Affiche l'icône du panier à droite */
        .site-header.scrolled .cart-only {
            display: block;
        }

        /* Ajout d'un padding au body pour compenser le header fixe */
        .content-placeholder {
            margin-top: 150px;
            text-align: center;
            padding: 50px;
            color: #888;
        }


.cart-item-count{
    position: absolute;
    top: 0;
    right: -6px;
    background: red;
    color: #fff;
    font-size: 10px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    display: none;
    justify-content: center;
    align-items: center;
}
/* --- Base Cart Container --- */
.cart {
    position: fixed;
    top: 100px;
    right: 20px; /* Change to -100% and use a 'active' class to toggle visibility with JS */
    width: 400px;
    height: 70vh;
    background-color: #fff;
    box-shadow: -2px 0 15px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    display: flex;
    flex-direction: column;
    z-index: 1000;
    border-radius:28px;
    transition: 0.3s ease;
    visibility: hidden;
}

.cart-img{
    object-fit:contain;
    width: 120px;
    height: auto;
    border-radius: 25%;
}

.cart-title {
    text-align: center;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 2rem;
    color: #333;
}

/* --- Cart Content (Scrollable Area) --- */
.cart-content {
    flex-grow: 1;
    overflow-y: auto;
    margin-bottom: 1.5rem;
}

/* --- Individual Item Row --- */
.cart-item {
    display: grid;
    grid-template-columns: 32% 50% 18%;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid #f1f1f1;
    padding-bottom: 1rem;
}

.cart-item img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
}

.item-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.item-title {
    font-size: 1rem;
    text-transform: uppercase;
    font-weight: 500;
}

.item-price {
    font-weight: 600;
    color: #2ecc71;
}

/* --- Quantity Controls --- */
.quantity-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1px solid #ddd;
    width: fit-content;
    padding: 2px 8px;
    border-radius: 4px;
}

.quantity-controls button {
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 1.2rem;
    padding: 0 5px;
}

.quantity {
    font-weight: 500;
}

.btn_cart{
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* --- Remove Icon --- */
.cart-remove {
    font-size: 1.3rem;
    color: #e74c3c;
    cursor: pointer;
    justify-self: flex-end;
}

.cart-remove:hover {
    color: #c0392b;
}

/* --- select icon */
.is_checked {
    color: #ccc;
    font-size: 1.3rem;
    cursor: pointer;
    
    transition: color 0.2s;
}

.is_checked.active {
    color: #2ecc71;
}



/* --- Footer & Total Section --- */
.totale {
    border-top: 2px solid #333;
    padding-top: 1.5rem;
}

.total-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.total-title h3 {
    font-size: 1.1rem;
    font-weight: 600;
}

.total-price {
    font-size: 1.2rem;
    font-weight: 700;
}

.smartBudget-btn{
    display: block;
    width: 100%;
    padding: 12px;
    background-color: #c0392b;
    margin-bottom: 5px;
    border-color: white;
    color: white;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
    border-radius: 4px;
}
.smartBudget-btn:hover {
    background-color: #333;
    border-color: #bc0000ff;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* --- Checkout Button --- */
.checkout-btn {
    display: block;
    width: 100%;
    padding: 12px;
    background-color: #333;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

.checkout-btn:hover {
    background-color: #555;
}

/* --- Close Icon --- */
#close-cart {
    position: absolute;
    top: 1rem;
    right: 0.8rem;
    font-size: 1.8rem;
    cursor: pointer;
    color: #333;
}




.auth-btn {
    background-color: #222;
    color: white;
    border: 1px solid #222;
    border-radius: 4px;
    padding: 6px 16px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.auth-btn::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -60%;
    width: 20%;
    height: 200%;
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(30deg);
    transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
}

.auth-btn:hover::after {
    left: 120%;
}

.auth-btn:hover {
    background-color: #444;
    border-color: #444;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.auth-btn.btn-outline {
    background-color: transparent;
    color: #222;
}

.auth-btn.btn-outline:hover {
    background-color: #222;
    color: white;
    transform: translateY(-1px);
}

.currency-selector {
    display: flex;
    align-items: center;
    font-size: 13px;
    cursor: pointer;
    transition: color 0.3s ease;
}

.currency-selector:hover {
    color: #888;
}

.cart-icon {
    position: relative;
    font-size: 24px;
    color: #333;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.cart-icon:hover {
    transform: scale(1.15);
    color: #000;
}

#signup-btn:hover{
    background-color: white;
    color: #333;
}
    </style>
</head>
<body>

    <header class="site-header" id="site-header">
        <div class="promo-bar">
           <span>Spin the wheel and try your luck!</span> 
           <a href="../spin/spin.html">
               <img src="../icon/fortune-wheel.png" alt="Luck Wheel">
           </a>
        </div>

        <div class="top-nav-bar">
            <div class="brand-tabs">
                <a href="index.html" class="tab">HOME</a>
                <a href="../improve_desgin/homepage1.html" class="tab">ADULT</a>
                <a href="#" class="tab">KIDS</a>
                <a href="tech.html" class="tab">TECH</a>
                <a href="ai.html" class="tab">Ai</a>
                <a href="smart-budget.html" class="tab">BUDGET</a>
            </div>
            
            <div class="user-actions">
                <div class="currency-selector">Dt <span class="divider">|</span></div>
                <?php if (isset($_SESSION["user_id"])) { ?>
                <a href="../user_Dashbord/index.php" class="action-btn">
                    <svg class="icon" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.5" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                </a>
                <?php } else { ?>
                    <a href="login.php">
                        <button class="auth-btn btn-outline">LOGIN</button>
                    </a>
                    <a href="signUp.php">
                        <button class="auth-btn" id="signup-btn" >REGISTER</button>
                    </a>
                <?php } ?>
               <div class="cart-icon">
            <i class="ri-shopping-bag-line"></i>
            <span class="cart-item-count"></span>
        </div>
            </div>
        </div>

        <div class="main-nav-bar">
            <div class="logo">AlphaStore</div>
            
            <nav class="main-links">
                <a href="Women1.html">Women</a>
                <a href="Men1.html">Men</a>
                <a href="Kid1.html">Kids</a>
                <a href="#">Technologie</a>
                <a href="AboutUS.html">AboutUS</a>
                <a href="#" class="sale">SALE</a>
            </nav>

            <div class="search-container">
                <input type="text" placeholder="What are you looking for?">
                <button type="submit" class="search-btn">
                    <svg style="width:18px;height:18px" viewBox="0 0 24 24"><path fill="white" d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                </button>
            </div>

            <div class="cart-only">
               <div class="cart-icon">
            <i class="ri-shopping-bag-line"></i>
            <span class="cart-item-count"></span>
        </div>
            </div>
        </div>
    </header>


     <div class="cart">
        <h2 class="cart-title">Shopping Cart</h2>
        <div class="cart-content">
            

        </div>
        <div class="totale">
            <div class="total-title">
                <h3>Total:</h3>
                <span class="total-price">11.00 DT</span>
            </div>
            <button class="smartBudget-btn">Smart BUDGET</button>
            <button class="checkout-btn">ORDER NOW</button>
            <i class="ri-close-large-fill " id="close-cart"></i>
        </div>
    </div>


   


</body>
</html>