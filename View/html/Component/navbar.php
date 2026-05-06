<?php 
session_start(); 
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!-- Navbar Fragment -->
<div class="site-header-wrapper">
    <style>
        /* Navbar Styles (Scoped via wrapper if possible, or just global) */
        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background-color: white;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .promo-bar {
            background: linear-gradient(135deg, #1a1a1a 0%, #2c3e50 50%, #1a1a1a 100%);
            color: #fff;
            text-align: center;
            padding: 8px 0;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            text-transform: uppercase;
        }

        .promo-bar a img {
            width: 20px;
            height: 20px;
            vertical-align: middle;
        }

        .top-nav-bar {
            display: flex;
            justify-content: space-between;
            background-color: #f8fafc;
            height: 40px;
            padding: 0 2rem;
        }

        .brand-tabs { display: flex; }
        .tab {
            padding: 0 15px;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            transition: color 0.2s;
        }
        .tab:hover { color: #000; }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .main-nav-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2rem;
            background-color: white;
            border-bottom: 1px solid #f1f5f9;
        }

        .logo {
            font-size: 24px;
            font-weight: 900;
            color: #0f172a;
            text-decoration: none;
            letter-spacing: -1px;
        }

        .main-links { display: flex; gap: 30px; }
        .main-links a {
            text-decoration: none;
            color: #334155;
            font-weight: 600;
            font-size: 14px;
            transition: color 0.2s;
        }
        .main-links a:hover { color: #2563eb; }
        .main-links a.sale { color: #ef4444; }

        .search-container {
            display: flex;
            align-items: center;
            background: #f1f5f9;
            border-radius: 50px;
            padding: 5px 5px 5px 20px;
            width: 300px;
            transition: all 0.3s ease;
        }
        .search-container:focus-within {
            width: 400px;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .search-container input {
            border: none;
            outline: none;
            background: transparent;
            width: 100%;
            font-size: 14px;
        }
        .search-btn {
            background: #0f172a;
            color: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Cart Styles */
        .cart-icon {
            position: relative;
            font-size: 20px;
            cursor: pointer;
            color: #0f172a;
        }
        .cart-item-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            font-size: 10px;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 10px;
            display: none; /* Shown via JS */
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        /* Scrolled State */
        .site-header.scrolled .promo-bar,
        .site-header.scrolled .top-nav-bar {
            display: none;
        }

        /* Cart Sidebar Styles */
        .cart {
            position: fixed;
            top: 0;
            right: -450px;
            width: 400px;
            height: 100vh;
            background: #fff;
            box-shadow: -10px 0 30px rgba(0,0,0,0.1);
            z-index: 10000;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 2rem;
            display: flex;
            flex-direction: column;
            visibility: hidden;
        }

        .cart.active {
            right: 0;
            visibility: visible;
        }

        .cart-title {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 2rem;
            color: #0f172a;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 1rem;
        }

        .cart-content {
            flex-grow: 1;
            overflow-y: auto;
            margin-bottom: 2rem;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 80px 1fr 40px;
            gap: 1rem;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .cart-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-title { font-size: 14px; font-weight: 700; margin: 0; }
        .item-price { font-size: 13px; color: #64748b; margin: 5px 0; }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f1f5f9;
            padding: 5px 10px;
            border-radius: 5px;
            width: fit-content;
        }

        .quantity-controls button {
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 700;
        }

        .btn_cart {
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
        }

        .is_checked { color: #cbd5e1; cursor: pointer; font-size: 20px; transition: color 0.2s; }
        .is_checked.active { color: #22c55e; }
        .cart-remove { color: #ef4444; cursor: pointer; font-size: 20px; }

        .totale {
            border-top: 2px solid #f1f5f9;
            padding-top: 1.5rem;
        }

        .total-title {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            font-size: 18px;
            font-weight: 800;
        }

        .smartBudget-btn {
            width: 100%;
            padding: 12px;
            background: #f1f5f9;
            color: #0f172a;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .empty-msg {
            text-align: center;
            color: #64748b;
            margin-top: 3rem;
            font-style: italic;
        }
    </style>

    <header class="site-header" id="dynamic-header">
        <div class="promo-bar">
           <span>Spin the wheel and try your luck!</span> 
           <a href="../spin/spin.php" aria-label="Spin the fortune wheel">
               <img src="../icon/fortune-wheel.png" alt="">
           </a>
        </div>

        <nav class="top-nav-bar" aria-label="Secondary Navigation">
            <div class="brand-tabs">
                <a href="index.html" class="tab">HOME</a>
                <a href="Adult.html" class="tab">ADULT</a>
                <a href="Kids.html" class="tab">KIDS</a>
                <a href="tech.html" class="tab">TECH</a>
                <a href="smart-budget.html" class="tab">BUDGET</a>
                <a href="pc-builder.php" class="tab" style="color: #2563eb;">🖥️ BUILD PC</a>
            </div>
            
            <div class="user-actions">
                <div class="currency-selector" aria-label="Currency: DT">DT</div>
                <?php if (isset($_SESSION["user_id"])) { ?>
                <a href="../user_Dashboard/index.php" class="action-btn" aria-label="User Dashboard">
                    <i class="ri-user-line"></i>
                </a>
                <?php } else { ?>
                    <a href="login.php" class="tab">LOGIN</a>
                    <a href="signUp.php" class="auth-btn" style="background: #0f172a; color: white; padding: 5px 15px; border-radius: 5px; text-decoration: none; font-size: 11px; font-weight: 700;">REGISTER</a>
                <?php } ?>
            </div>
        </nav>

        <div class="main-nav-bar">
            <a href="index.html" class="logo">AlphaStore</a>
            
            <nav class="main-links" aria-label="Main Navigation">
                <a href="Women1.html">Women</a>
                <a href="Men1.html">Men</a>
                <a href="Kid1.html">Kids</a>
                <a href="tech.html">Technologie</a>
                <a href="AboutUS.html">About Us</a>
                <a href="sale.html" class="sale">SALE</a>
            </nav>

            <div class="header-actions" style="display: flex; align-items: center; gap: 20px;">
                <form class="search-container" action="search.php" method="GET">
                    <input type="text" name="q" placeholder="Search products..." aria-label="Search">
                    <button type="submit" class="search-btn" aria-label="Submit search">
                        <i class="ri-search-line"></i>
                    </button>
                </form>

                <div class="cart-icon" aria-label="Shopping Cart" style="font-size: 24px; position: relative; cursor: pointer;">
                    <i class="ri-shopping-bag-line"></i>
                    <span class="cart-item-count" style="position: absolute; top: -5px; right: -8px; background: #ef4444; color: white; font-size: 10px; min-width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; border: 2px solid white;">0</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Cart Sidebar -->
    <aside class="cart" id="cart-sidebar" aria-labelledby="cart-title">
        <h2 id="cart-title" class="cart-title">Your Cart</h2>
        <div class="cart-content" id="cart-items-container">
            <!-- Items injected by JS -->
            <p class="empty-msg">Your cart is empty.</p>
        </div>
        <div class="totale">
            <div class="total-title">
                <h3>Subtotal:</h3>
                <span class="total-price">0.00 DT</span>
            </div>
            <div class="btn-group" style="display: flex; flex-direction: column; gap: 10px;">
                <button class="smartBudget-btn">Smart BUDGET</button>
                <button class="checkout-btn" style="background: #0f172a; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 700; cursor: pointer;">CHECKOUT</button>
            </div>
            <button id="close-cart" aria-label="Close cart" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
        </div>
    </aside>
</div>