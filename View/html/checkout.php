
<?php
include __DIR__ . "/../../Controller/OrderController.php";
include __DIR__ . "/../../Controller/CartController.php";



$user_id = $_SESSION['user_id'];
$cartModel = new Cart();
$cartItems = $cartModel->getCart($user_id);

if (empty($cartItems)) {
    // Redirect to cart if empty
    header("Location: index.html"); 
    exit;
}

$total_price = $cartModel->getCartTotal($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - AlphaStore</title>
    <link rel="stylesheet" href="../css/checkout.css">
    <link rel="stylesheet" href="../css/animationOrder.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="checkout-container">
        <header class="checkout-header">
            <a href="index.html" class="back-link">← Back to Store</a>
            <h1>Checkout</h1>
        </header>

        <!-- Multi-step Progress Bar -->
        <div class="checkout-progress">
            <div class="progress-line">
                <div class="progress-line-fill" id="progress-line-fill"></div>
            </div>
            <div class="step active" data-step="1">
                <div class="step-icon">
                    <span class="icon-text">1</span>
                    <span class="check-icon">✓</span>
                </div>
                <span class="step-label">Personal</span>
            </div>
            <div class="step" data-step="2">
                <div class="step-icon">
                    <span class="icon-text">2</span>
                    <span class="check-icon">✓</span>
                </div>
                <span class="step-label">Shipping</span>
            </div>
            <div class="step" data-step="3">
                <div class="step-icon">
                    <span class="icon-text">3</span>
                    <span class="check-icon">✓</span>
                </div>
                <span class="step-label">Payment</span>
            </div>
        </div>

        <form id="checkout-form">
            <div class="checkout-grid">
                <div class="checkout-main">
                    <!-- Step 1: Personal Information -->
                    <div class="form-step active" id="step-1">
                        <div class="section">
                            <h2>Personal Information</h2>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="first-name">First Name *</label>
                                    <input type="text" id="first-name" name="first_name" placeholder="John" required>
                                    <span class="error" id="first-name-error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="last-name">Last Name *</label>
                                    <input type="text" id="last-name" name="last_name" placeholder="Doe" required>
                                    <span class="error" id="last-name-error"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" value="<?php echo $_SESSION['user_email'] ?? ''; ?>" placeholder="john.doe@example.com" required>
                                <span class="error" id="email-error"></span>
                            </div>
                        </div>
                        <div class="step-navigation">
                            <div></div> <!-- Spacer -->
                            <button type="button" class="btn-next" data-next="2">Next Step →</button>
                        </div>
                    </div>

                    <!-- Step 2: Shipping Address -->
                    <div class="form-step" id="step-2">
                        <div class="section">
                            <h2>Billing Address</h2>
                            <div class="form-group">
                                <label for="address">Street Address *</label>
                                <input type="text" id="address" name="address" placeholder="123 Main St" required>
                                <span class="error" id="address-error"></span>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="city">City *</label>
                                    <input type="text" id="city" name="city" placeholder="Tunis" required>
                                    <span class="error" id="city-error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="zip">ZIP Code *</label>
                                    <input type="text" id="zip" name="zip" placeholder="1000" required>
                                    <span class="error" id="zip-error"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="country">Country *</label>
                                <select id="country" name="country" required>
                                    <option value="">Select Country</option>
                                    <option value="TN">Tunisia</option>
                                    <option value="FR">France</option>
                                    <option value="US">United States</option>
                                    <option value="CA">Canada</option>
                                </select>
                                <span class="error" id="country-error"></span>
                            </div>
                        </div>

                        <div class="section">
                            <h2>Shipping Details</h2>
                            <div class="form-group checkbox-group">
                                <input type="checkbox" id="same-as-billing" name="same_as_billing" checked>
                                <label for="same-as-billing">Shipping address same as billing</label>
                            </div>
                            <div id="shipping-fields" style="display: none;">
                                <div class="form-group">
                                    <label for="shipping-address">Shipping Address *</label>
                                    <input type="text" id="shipping-address" name="shipping_address">
                                    <span class="error" id="shipping-address-error"></span>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="shipping-city">City *</label>
                                        <input type="text" id="shipping-city" name="shipping_city">
                                        <span class="error" id="shipping-city-error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="shipping-zip">ZIP Code *</label>
                                        <input type="text" id="shipping-zip" name="shipping_zip">
                                        <span class="error" id="shipping-zip-error"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="shipping-country">Country *</label>
                                    <select id="shipping-country" name="shipping_country">
                                        <option value="">Select Country</option>
                                        <option value="TN">Tunisia</option>
                                        <option value="FR">France</option>
                                        <option value="US">United States</option>
                                        <option value="CA">Canada</option>
                                    </select>
                                    <span class="error" id="shipping-country-error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="step-navigation">
                            <button type="button" class="btn-back" data-back="1">← Back</button>
                            <button type="button" class="btn-next" data-next="3">Next Step →</button>
                        </div>
                    </div>

                    <!-- Step 3: Payment Method -->
                    <div class="form-step" id="step-3">
                        <div class="section">
                            <h2>Payment Method</h2>
                            <div class="payment-options">
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="credit_card" required>
                                    <div class="payment-card-ui">
                                        <div class="payment-icon">💳</div>
                                        <span class="label-text">Credit Card</span>
                                    </div>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="paypal">
                                    <div class="payment-card-ui">
                                        <div class="payment-icon">🅿️</div>
                                        <span class="label-text">PayPal</span>
                                    </div>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="cash">
                                    <div class="payment-card-ui">
                                        <div class="payment-icon">💵</div>
                                        <span class="label-text">Cash on Delivery</span>
                                    </div>
                                </label>
                            </div>
                            <span class="error" id="payment-method-error"></span>

                            <div id="credit-card-fields" style="display: none;" class="payment-details-container">
                                <div class="credit-card-preview">
                                    <div class="card-chip"></div>
                                    <div class="card-number-preview">•••• •••• •••• ••••</div>
                                    <div class="card-bottom">
                                        <div class="card-holder">CARD HOLDER</div>
                                        <div class="card-expiry">MM/YY</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="card-number">Card Number *</label>
                                    <input type="text" id="card-number" name="card_number" placeholder="0000 0000 0000 0000">
                                    <span class="error" id="card-number-error"></span>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="expiry-date">Expiry Date *</label>
                                        <input type="text" id="expiry-date" name="expiry_date" placeholder="MM/YY">
                                        <span class="error" id="expiry-date-error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="cvv">CVV *</label>
                                        <input type="text" id="cvv" name="cvv" placeholder="123">
                                        <span class="error" id="cvv-error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step-navigation">
                            <button type="button" class="btn-back" data-back="2">← Back</button>
                            <button type="submit" class="btn-submit" id="place-order-btn">Complete Purchase</button>
                        </div>
                    </div>
                </div>

                <aside class="checkout-sidebar">
                    <div class="section order-summary">
                        <h2>Order Summary</h2>
                        <div class="summary-items">
                            <?php foreach ($cartItems as $item): ?>
                                <div class="summary-item">
                                    <div class="item-info">
                                        <span class="item-name"><?php echo htmlspecialchars($item['name']); ?></span>
                                        <span class="item-qty">x<?php echo $item['quantite']; ?></span>
                                    </div>
                                    <span class="item-price">$<?php echo number_format($item['price'] * $item['quantite'], 2); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="summary-totals">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span>$<?php echo number_format($total_price, 2); ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span>Free</span>
                            </div>
                            <div class="summary-row total">
                                <span>Total</span>
                                <span class="total-amount">$<?php echo number_format($total_price, 2); ?></span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </form>
    </div>


    
    <div class="animation">

    
      <canvas id="scene"></canvas>
      </div>

    <!-- Success Modal -->
    <div id="order-success-modal" class="modal">
        <div class="modal-content">
            <div class="success-icon">✓</div>
            <h2>Order Placed Successfully!</h2>
            <p>Your order <span id="order-id-display"></span> has been received.</p>
            <p>Thank you for shopping with AlphaStore.</p>
            <a href="index.html" class="btn-primary">Continue Shopping</a>
        </div>
    </div>

    <script src="../javaScript/checkout.js"></script>
    <script src="../javaScript/animationOrder.js"></script>
</body>
</html>
