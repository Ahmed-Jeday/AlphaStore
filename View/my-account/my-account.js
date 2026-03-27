// Section switching
const navItems = document.querySelectorAll('.sidebar-nav .nav-item:not(.logout-item)');
const sections = {
    personal: document.getElementById('personal-section'),
    orders: document.getElementById('orders-section'),
    address: document.getElementById('address-section'),
    payment: document.getElementById('payment-section'),
    password: document.getElementById('password-section')
};

function switchSection(sectionId) {
    Object.values(sections).forEach(sec => sec.classList.remove('active-section'));
    sections[sectionId].classList.add('active-section');
    navItems.forEach(item => item.classList.remove('active'));
    const activeNav = document.querySelector(`.nav-item[data-section="${sectionId}"]`);
    if (activeNav) activeNav.classList.add('active');
}



/* ══════════════════════════════════════
    AVATAR UPLOAD (click to change photo)
 ══════════════════════════════════════ */
const avatarEdit = document.querySelector('.avatar-edit');
const avatarImg = document.querySelector('.avatar-wrap img');

if (avatarEdit && avatarImg) {
    avatarEdit.addEventListener('click', () => {
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.accept = 'image/*';
        fileInput.onchange = (e) => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (ev) => { avatarImg.src = ev.target.result; };
            reader.readAsDataURL(file);
        };
        fileInput.click();
    });
}

navItems.forEach(item => {
    item.addEventListener('click', () => {
        const section = item.getAttribute('data-section');
        if (section && sections[section]) switchSection(section);
    });
});

// Logout functionality
document.getElementById('logoutBtn').addEventListener('click', () => {
    if (confirm('Are you sure you want to log out?')) {
        alert('You have been logged out');
        window.location.href="logout.php"
        
    }
});

// ========== PERSONAL INFORMATION ==========
document.getElementById('updateProfileBtn').addEventListener('click', () => {
    const firstName = document.getElementById('firstName').value;
    const lastName = document.getElementById('lastName').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const gender = document.getElementById('gender').value;

    if (!firstName || !lastName || !email || !phone) {
        alert('Please fill all required fields');
        return;
    }

    // Simulate API call
    console.log('Profile update:', { firstName, lastName, email, phone, gender });
    alert('Profile updated successfully!');
});

// ========== ADDRESS MANAGEMENT ==========
function refreshAddressEvents() {
    // Delete address
    document.querySelectorAll('.delete-address').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const addressCard = e.target.closest('.address-card');
            if (confirm('Are you sure you want to delete this address?')) {
                addressCard.remove();
                alert('Address removed');
            }
        });
    });

    // Edit address
    document.querySelectorAll('.edit-address').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const addressCard = e.target.closest('.address-card');
            const addressText = addressCard.querySelector('div:first-child').innerText;
            alert(`Edit address feature: \n${addressText}\n\nYou can update using the form below.`);
        });
    });
}

// Add new address
document.getElementById('addAddressBtn').addEventListener('click', () => {
    const fName = document.getElementById('addrFirstName').value.trim();
    const lName = document.getElementById('addrLastName').value.trim();
    const street = document.getElementById('addrStreet').value.trim();
    const city = document.getElementById('addrCity').value.trim();
    const state = document.getElementById('addrState').value.trim();
    const zip = document.getElementById('addrZip').value.trim();
    const phone = document.getElementById('addrPhone').value.trim();
    const country = document.getElementById('addrCountry').value;

    if (!fName || !lName || !street || !city || !state || !zip || !phone) {
        alert('Please fill all required fields (First Name, Last Name, Street, City, State, Zip Code, Phone)');
        return;
    }

    const newAddress = document.createElement('div');
    newAddress.className = 'address-card';
    newAddress.innerHTML = `
        <div><strong>${fName} ${lName}</strong><br>${street}, ${city}, ${state} ${zip}<br>${country} | ${phone}</div>
        <div class="address-actions">
            <button class="edit-address">Edit</button>
            <button class="delete-address">Delete</button>
        </div>
    `;

    document.getElementById('addressListContainer').appendChild(newAddress);
    refreshAddressEvents();

    // Clear form
    document.getElementById('addrFirstName').value = '';
    document.getElementById('addrLastName').value = '';
    document.getElementById('addrStreet').value = '';
    document.getElementById('addrCity').value = '';
    document.getElementById('addrState').value = '';
    document.getElementById('addrZip').value = '';
    document.getElementById('addrPhone').value = '';
    document.getElementById('addrEmail').value = '';
    document.getElementById('addrCompany').value = '';

    alert('New address added successfully!');
});

// Initialize address events
refreshAddressEvents();

// ========== PAYMENT METHODS ==========
function refreshPaymentEvents() {
    document.querySelectorAll('.delete-payment').forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (confirm('Remove this payment method?')) {
                e.target.closest('.payment-method-item').remove();
                alert('Payment method removed');
            }
        });
    });

    document.querySelectorAll('.link-payment').forEach(btn => {
        btn.addEventListener('click', () => {
            alert('Link account flow: You would be redirected to the payment provider.');
        });
    });
}

// Add new card
document.getElementById('addCardBtn').addEventListener('click', () => {
    const holder = document.getElementById('cardName').value.trim();
    const number = document.getElementById('cardNumber').value.trim();
    const expiry = document.getElementById('cardExpiry').value.trim();
    const cvv = document.getElementById('cardCvv').value.trim();
    const saveCard = document.getElementById('saveCardCheckbox').checked;

    if (!holder || !number || !expiry || !cvv) {
        alert('Please fill all card details');
        return;
    }

    // Basic card number validation
    const cleanNumber = number.replace(/\s/g, '');
    if (cleanNumber.length < 13 || cleanNumber.length > 19) {
        alert('Please enter a valid card number (13-19 digits)');
        return;
    }

    const last4 = cleanNumber.slice(-4);
    const newCard = document.createElement('div');
    newCard.className = 'payment-method-item';
    newCard.innerHTML = `
        <span><i class="fab fa-cc-visa"></i> ${holder} •••• ${last4} (exp: ${expiry})</span>
        <button class="delete-payment">Delete</button>
    `;

    document.getElementById('paymentMethodsList').appendChild(newCard);
    refreshPaymentEvents();

    // Clear form
    document.getElementById('cardName').value = '';
    document.getElementById('cardNumber').value = '';
    document.getElementById('cardExpiry').value = '';
    document.getElementById('cardCvv').value = '';
    document.getElementById('saveCardCheckbox').checked = false;

    alert(saveCard ? 'Card added and saved for future payments!' : 'Card added successfully!');
});

// Initialize payment events
refreshPaymentEvents();

// ========== PASSWORD MANAGEMENT ==========
document.getElementById('updatePasswordBtn').addEventListener('click', () => {
    const currentPwd = document.getElementById('currentPwd').value;
    const newPwd = document.getElementById('newPwd').value;
    const confirmPwd = document.getElementById('confirmPwd').value;

    if (!currentPwd || !newPwd || !confirmPwd) {
        alert('Please fill all password fields');
        return;
    }

    if (newPwd !== confirmPwd) {
        alert('New password and confirmation do not match');
        return;
    }

    if (newPwd.length < 6) {
        alert('Password must be at least 6 characters long');
        return;
    }

    // Simulate API call
    console.log('Password update request');
    alert('Password updated successfully!');

    // Clear form
    document.getElementById('currentPwd').value = '';
    document.getElementById('newPwd').value = '';
    document.getElementById('confirmPwd').value = '';
});

document.getElementById('forgetPasswordLink').addEventListener('click', (e) => {
    e.preventDefault();
    alert('Password reset link will be sent to your registered email.');
});

// ========== ORDER ACTIONS ==========
document.querySelectorAll('.track-order').forEach(btn => {
    btn.addEventListener('click', (e) => {
        const orderId = btn.getAttribute('data-order');
        alert(`Tracking order ${orderId}: Your package is on the way!`);
    });
});

document.querySelectorAll('.invoice-order').forEach(btn => {
    btn.addEventListener('click', (e) => {
        const orderId = btn.getAttribute('data-order');
        alert(`Downloading invoice for order ${orderId} (PDF demo)`);
    });
});

document.querySelectorAll('.cancel-order').forEach(btn => {
    btn.addEventListener('click', (e) => {
        const orderId = btn.getAttribute('data-order');
        if (confirm(`Are you sure you want to cancel order ${orderId}?`)) {
            alert(`Order ${orderId} cancellation requested.`);
        }
    });
});

document.querySelectorAll('.add-review').forEach(btn => {
    btn.addEventListener('click', (e) => {
        const orderId = btn.getAttribute('data-order');
        alert(`Write a review for order ${orderId} (demo)`);
    });
});

// ========== NEWSLETTER ==========
document.getElementById('subscribeBtn').addEventListener('click', () => {
    const email = document.getElementById('newsletterEmail').value.trim();

    if (!email) {
        alert('Please enter your email address');
        return;
    }

    const emailRegex = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address');
        return;
    }

    alert(`Thanks for subscribing! 25% discount code: GREEN25 will be sent to ${email}`);
    document.getElementById('newsletterEmail').value = '';
});

// ========== TOP BAR OFFER LINK ==========
document.querySelector('.offer-link')?.addEventListener('click', () => {
    alert('Sign up offer: GET 25% OFF on your first order!');
});

// ========== ORDER FILTER (demo) ==========
document.getElementById('orderFilter')?.addEventListener('change', (e) => {
    alert(`Filter orders by: ${e.target.value} (demo)`);
});

// ========== INITIAL ACTIVE SECTION ==========
// Ensure personal section is active on load
switchSection('personal');

