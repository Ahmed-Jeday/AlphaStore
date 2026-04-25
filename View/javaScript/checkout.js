// Checkout Form Validation Script

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    const sameAsBillingCheckbox = document.getElementById('same-as-billing');
    const shippingFields = document.getElementById('shipping-fields');
    const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
    const creditCardFields = document.getElementById('credit-card-fields');

    // Toggle shipping fields based on checkbox
    sameAsBillingCheckbox.addEventListener('change', function() {
        if (this.checked) {
            shippingFields.style.display = 'none';
            // Clear shipping fields
            document.getElementById('shipping-address').value = '';
            document.getElementById('shipping-city').value = '';
            document.getElementById('shipping-zip').value = '';
            document.getElementById('shipping-country').value = '';
        } else {
            shippingFields.style.display = 'block';
        }
    });

    // Toggle credit card fields based on payment method
    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'credit_card') {
                creditCardFields.style.display = 'block';
            } else {
                creditCardFields.style.display = 'none';
                // Clear credit card fields
                document.getElementById('card-number').value = '';
                document.getElementById('expiry-date').value = '';
                document.getElementById('cvv').value = '';
            }
        });
    });

    // Form validation on submit
    form.addEventListener('submit', function(event) {
        let isValid = true;

        // Clear previous errors
        clearErrors();

        // Validate billing address
        if (!validateRequired('first-name')) isValid = false;
        if (!validateRequired('last-name')) isValid = false;
        if (!validateEmail('email')) isValid = false;
        if (!validateRequired('address')) isValid = false;
        if (!validateRequired('city')) isValid = false;
        if (!validateZip('zip')) isValid = false;
        if (!validateRequired('country')) isValid = false;

        // Validate shipping address if not same as billing
        if (!sameAsBillingCheckbox.checked) {
            if (!validateRequired('shipping-address')) isValid = false;
            if (!validateRequired('shipping-city')) isValid = false;
            if (!validateZip('shipping-zip')) isValid = false;
            if (!validateRequired('shipping-country')) isValid = false;
        }

        // Validate payment method
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!paymentMethod) {
            showError('payment-method-error', 'Please select a payment method.');
            isValid = false;
        } else if (paymentMethod.value === 'credit_card') {
            if (!validateCardNumber('card-number')) isValid = false;
            if (!validateExpiryDate('expiry-date')) isValid = false;
            if (!validateCVV('cvv')) isValid = false;
        }

        if (isValid) {
            // If valid, submit via AJAX
            event.preventDefault();
            
            const formData = new FormData(form);
            formData.append('action', 'place_order');

            const placeOrderBtn = document.getElementById('place-order-btn');
            placeOrderBtn.disabled = true;
            placeOrderBtn.textContent = 'Processing...';

            fetch('../../Controller/OrderController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showSuccessModal(data.order_id);
                } else {
                    alert('Error: ' + (data.message || 'Something went wrong.'));
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.textContent = 'Place Order';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                placeOrderBtn.disabled = false;
                placeOrderBtn.textContent = 'Place Order';
            });
        } else {
            event.preventDefault();
        }
    });

    function showSuccessModal(orderId) {
        const modal = document.getElementById('order-success-modal');
        const orderIdDisplay = document.getElementById('order-id-display');
        if (orderIdDisplay) orderIdDisplay.textContent = '#' + orderId;
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    function validateRequired(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field.value.trim()) {
            showError(fieldId + '-error', 'This field is required.');
            return false;
        }
        return true;
    }

    function validateEmail(fieldId) {
        const field = document.getElementById(fieldId);
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(field.value)) {
            showError(fieldId + '-error', 'Please enter a valid email address.');
            return false;
        }
        return true;
    }

    function validateZip(fieldId) {
        const field = document.getElementById(fieldId);
        const zipRegex = /^\d{5}$/;
        if (!zipRegex.test(field.value)) {
            showError(fieldId + '-error', 'Please enter a valid 5-digit ZIP code.');
            return false;
        }
        return true;
    }

    function validateCardNumber(fieldId) {
        const field = document.getElementById(fieldId);
        const cardRegex = /^\d{16}$/;
        if (!cardRegex.test(field.value.replace(/\s/g, ''))) {
            showError(fieldId + '-error', 'Please enter a valid 16-digit card number.');
            return false;
        }
        return true;
    }

    function validateExpiryDate(fieldId) {
        const field = document.getElementById(fieldId);
        const expiryRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
        if (!expiryRegex.test(field.value)) {
            showError(fieldId + '-error', 'Please enter a valid expiry date (MM/YY).');
            return false;
        }
        // Check if date is in the future
        const [month, year] = field.value.split('/');
        const currentDate = new Date();
        const expiryDate = new Date(2000 + parseInt(year), parseInt(month) - 1);
        if (expiryDate < currentDate) {
            showError(fieldId + '-error', 'Expiry date must be in the future.');
            return false;
        }
        return true;
    }

    function validateCVV(fieldId) {
        const field = document.getElementById(fieldId);
        const cvvRegex = /^\d{3}$/;
        if (!cvvRegex.test(field.value)) {
            showError(fieldId + '-error', 'Please enter a valid 3-digit CVV.');
            return false;
        }
        return true;
    }

    function showError(errorId, message) {
        document.getElementById(errorId).textContent = message;
    }

    function clearErrors() {
        const errors = document.querySelectorAll('.error');
        errors.forEach(error => error.textContent = '');
    }
});