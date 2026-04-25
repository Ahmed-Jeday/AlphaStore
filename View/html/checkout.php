
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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="checkout-container">
        <header class="checkout-header">
            <a href="index.html" class="back-link">← Back to Store</a>
            <h1>Checkout</h1>
        </header>

        <form id="checkout-form">
            <div class="checkout-grid">
                <div class="checkout-main">
                    <div class="section">
                        <h2>Billing Address</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first-name">First Name *</label>
                                <input type="text" id="first-name" name="first_name" required>
                                <span class="error" id="first-name-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="last-name">Last Name *</label>
                                <input type="text" id="last-name" name="last_name" required>
                                <span class="error" id="last-name-error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" value="<?php echo $_SESSION['user_email'] ?? ''; ?>" required>
                            <span class="error" id="email-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="address">Address *</label>
                            <input type="text" id="address" name="address" required>
                            <span class="error" id="address-error"></span>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City *</label>
                                <input type="text" id="city" name="city" required>
                                <span class="error" id="city-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="zip">ZIP Code *</label>
                                <input type="text" id="zip" name="zip" required pattern="\d{5}">
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
                        <h2>Shipping Address</h2>
                        <div class="form-group checkbox-group">
                            <input type="checkbox" id="same-as-billing" name="same_as_billing" checked>
                            <label for="same-as-billing">Same as billing address</label>
                        </div>
                        <div id="shipping-fields" style="display: none;">
                            <div class="form-group">
                                <label for="shipping-address">Address *</label>
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
                                    <input type="text" id="shipping-zip" name="shipping_zip" pattern="\d{5}">
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

                    <div class="section">
                        <h2>Payment Method</h2>
                        <div class="payment-options">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="credit_card" required>
                                <span class="radio-custom"></span>
                                <span class="label-text">Credit Card</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="paypal">
                                <span class="radio-custom"></span>
                                <span class="label-text">PayPal</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cash">
                                <span class="radio-custom"></span>
                                <span class="label-text">Cash on Delivery</span>
                            </label>
                        </div>
                        <span class="error" id="payment-method-error"></span>

                        <div id="credit-card-fields" style="display: none;" class="payment-details">
                            <div class="form-group">
                                <label for="card-number">Card Number *</label>
                                <input type="text" id="card-number" name="card_number" placeholder="0000 0000 0000 0000">
                                <span class="error" id="card-number-error"></span>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="expiry-date">Expiry Date (MM/YY) *</label>
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
                        <button type="submit" class="btn-submit" id="place-order-btn">Place Order</button>
                    </div>
                </aside>
            </div>
        </form>
    </div>

    <!-- Success Modal -->
    <div id="order-success-modal" class="modal">
        <div class="modal-content">
            <div class="success-icon">✓</div>
            <h2>Order Placed Successfully!</h2>
            <p>Your order <span id="order-id-display"></span> has been received.</p>
            <p>Thank you for shopping with AlphaStore.</p>
            <a href="index.php" class="btn-primary">Continue Shopping</a>
        </div>
    </div>

    <script src="../javaScript/checkout.js"></script>
</body>
</html>
