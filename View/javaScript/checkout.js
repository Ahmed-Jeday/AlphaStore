// Checkout Form Validation and Multi-step Logic

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    const sameAsBillingCheckbox = document.getElementById('same-as-billing');
    const shippingFields = document.getElementById('shipping-fields');
    const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
    const creditCardFields = document.getElementById('credit-card-fields');
    
    // Multi-step elements
    const steps = document.querySelectorAll('.step');
    const formSteps = document.querySelectorAll('.form-step');
    const progressLineFill = document.getElementById('progress-line-fill');
    const nextBtns = document.querySelectorAll('.btn-next');
    const backBtns = document.querySelectorAll('.btn-back');

    let currentStep = 1;

    // --- STEP NAVIGATION ---

    nextBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const nextStepNum = parseInt(btn.getAttribute('data-next'));
            if (validateStep(currentStep)) {
                goToStep(nextStepNum);
            }
        });
    });

    backBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const prevStepNum = parseInt(btn.getAttribute('data-back'));
            goToStep(prevStepNum);
        });
    });

    function goToStep(stepNum) {
        // Hide all steps
        formSteps.forEach(step => step.classList.remove('active'));
        // Show target step
        document.getElementById(`step-${stepNum}`).classList.add('active');
        
        // Update Progress Bar
        updateProgressBar(stepNum);
        
        currentStep = stepNum;
        
        // Scroll to top of form
        document.querySelector('.checkout-progress').scrollIntoView({ behavior: 'smooth' });
    }

    function updateProgressBar(stepNum) {
        steps.forEach((step, index) => {
            const stepIdx = index + 1;
            step.classList.remove('active', 'completed');
            
            if (stepIdx < stepNum) {
                step.classList.add('completed');
            } else if (stepIdx === stepNum) {
                step.classList.add('active');
            }
        });

        // Update line fill
        const fillWidth = ((stepNum - 1) / (steps.length - 1)) * 100;
        progressLineFill.style.width = `${fillWidth}%`;
    }

    // --- VALIDATION ---

    function validateStep(stepNum) {
        clearErrors();
        let isValid = true;

        if (stepNum === 1) {
            if (!validateRequired('first-name')) isValid = false;
            if (!validateRequired('last-name')) isValid = false;
            if (!validateEmail('email')) isValid = false;
        } 
        else if (stepNum === 2) {
            if (!validateRequired('address')) isValid = false;
            if (!validateRequired('city')) isValid = false;
            if (!validateZip('zip')) isValid = false;
            if (!validateRequired('country')) isValid = false;

            if (!sameAsBillingCheckbox.checked) {
                if (!validateRequired('shipping-address')) isValid = false;
                if (!validateRequired('shipping-city')) isValid = false;
                if (!validateZip('shipping-zip')) isValid = false;
                if (!validateRequired('shipping-country')) isValid = false;
            }
        }

        return isValid;
    }

    // --- TOGGLE FIELDS ---

    sameAsBillingCheckbox.addEventListener('change', function() {
        if (this.checked) {
            shippingFields.style.display = 'none';
        } else {
            shippingFields.style.display = 'block';
        }
    });

    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'credit_card') {
                creditCardFields.style.display = 'block';
            } else {
                creditCardFields.style.display = 'none';
            }
        });
    });

    // --- CREDIT CARD PREVIEW ---

    const cardNumberInput = document.getElementById('card-number');
    const cardExpiryInput = document.getElementById('expiry-date');
    const cardPreviewNumber = document.querySelector('.card-number-preview');
    const cardPreviewExpiry = document.querySelector('.card-expiry');

    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) formattedValue += ' ';
                formattedValue += value[i];
            }
            e.target.value = formattedValue.substring(0, 19);
            cardPreviewNumber.textContent = formattedValue || '•••• •••• •••• ••••';
        });
    }

    if (cardExpiryInput) {
        cardExpiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/gi, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value.substring(0, 5);
            cardPreviewExpiry.textContent = value || 'MM/YY';
        });
    }

    // --- FINAL FORM SUBMISSION ---

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        let isValid = true;
        clearErrors();

        // Validate final step (Payment)
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
            const formData = new FormData(form);
            formData.append('action', 'place_order');

            const placeOrderBtn = document.getElementById('place-order-btn');
            placeOrderBtn.disabled = true;
            placeOrderBtn.textContent = 'Processing...';

            fetch('../../Controller/OrderController.php', {
                method: 'POST',
                body: formData
            })
            .then(async response => {
                const text = await response.text();
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Server response was not JSON:', text);
                    throw new Error('Server returned an invalid response. Check console for details.');
                }
            })
            .then(data => {
                if (data.status === 'success') {
                    // Mark last step as completed
                    steps[2].classList.add('completed');
                    steps[2].classList.remove('active');
                    progressLineFill.style.width = '100%';
                    showSuccessModal(data.order_id);
                } else {
                    alert('Error: ' + (data.message || 'Something went wrong.'));
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.textContent = 'Complete Purchase';
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                alert('An error occurred: ' + error.message);
                placeOrderBtn.disabled = false;
                placeOrderBtn.textContent = 'Complete Purchase';
            });
        }
    });

    // --- HELPER FUNCTIONS ---

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
        // Basic zip validation (at least 4 digits for Tunisia, 5 for US/FR)
        if (field.value.length < 4) {
            showError(fieldId + '-error', 'Please enter a valid ZIP code.');
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
        const cvvRegex = /^\d{3,4}$/;
        if (!cvvRegex.test(field.value)) {
            showError(fieldId + '-error', 'Please enter a valid CVV.');
            return false;
        }
        return true;
    }

    function showError(errorId, message) {
        const errorEl = document.getElementById(errorId);
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.style.opacity = '1';
        }
    }

    function clearErrors() {
        const errors = document.querySelectorAll('.error');
        errors.forEach(error => {
            error.textContent = '';
            error.style.opacity = '0';
        });
    }
});