// Variables to hold elements that might be loaded asynchronously
let cartIcons = [], cart, closeCart, cartContent, cartCountElements = [], totalPriceElement;








// Initialization function
const initCartElements = () => {
    cartIcons = document.querySelectorAll('.cart-icon');
    cart = document.querySelector('.cart');
    closeCart = document.querySelector('#close-cart');
    cartContent = document.querySelector('.cart-content');
    cartCountElements = document.querySelectorAll('.cart-item-count');
    totalPriceElement = document.querySelector('.total-price');
    let checkout_btn = document.querySelector('.checkout-btn'); 

    checkout_btn.addEventListener("click", function() {
    window.location.href = "../html/checkout.php";
    });


    if (cartIcons.length > 0 && cart && closeCart) {
        cartIcons.forEach(icon => {
            // Use property to ensure we only attach one listener during polling
            if (!icon.dataset.listened) {
                icon.addEventListener('click', () => {
                    cart.classList.add('active');
                    cart.style.visibility = 'visible';
                });
                icon.dataset.listened = "true";
            }
        });

        if (!closeCart.dataset.listened) {
            closeCart.addEventListener('click', () => {
                cart.classList.remove('active');
                cart.style.visibility = 'hidden';
            });
            closeCart.dataset.listened = "true";
        }
        return true;
    }
    return false;
};

// --- Update Total Price ---
const updatePrice = () => {
    if (!totalPriceElement) return;
    const cartItems = document.querySelectorAll('.cart-item');
    let total = 0;

    cartItems.forEach(item => {
        const checkIcon = item.querySelector('.is_checked');
        if (checkIcon && !checkIcon.classList.contains('active')) return;

        const priceElement = item.querySelector('.item-price');
        const quantityElement = item.querySelector('.quantity');
        if (priceElement && quantityElement) {
            const price = parseFloat(priceElement.textContent.replace('DT', '').trim());
            const quantity = parseInt(quantityElement.textContent);
            total += price * quantity;
        }
    });
    totalPriceElement.textContent = `${total.toFixed(2)} DT`;
};

// --- Update Cart Badge Count ---
const getElementProductId = element => {
    if (!element) return undefined;
    return element.value ?? element.getAttribute('value') ?? element.dataset.id;
};

const updateCartCount = () => {
    if (cartCountElements.length === 0) return;
    const cartItems = document.querySelectorAll('.cart-item');
    let totalQuantity = 0;

    cartItems.forEach(item => {
        const quantityElement = item.querySelector('.quantity');
        if (quantityElement) {
            totalQuantity += parseInt(quantityElement.textContent, 10);
        }
    });

    cartCountElements.forEach(countBadge => {
        if (totalQuantity > 0) {
            countBadge.textContent = totalQuantity;
            countBadge.style.display = 'flex';
        } else {
            countBadge.style.display = 'none';
        }
    });
};

const openCart = () => {
    if (!cart) initCartElements();
    if (cart) {
        cart.classList.add('active');
        cart.style.visibility = 'visible';
    }
};

// --- Main Add to Cart Function ---
const addToCart = async (productData, quantity = 1) => {
    if (!cartContent) {
        if (!initCartElements()) return;
    }

    const { img, title, price, id } = productData;
    if (!id) {
        console.error('addToCart called without a product id', productData);
        return;
    }

    // Sync with backend
    try {
        const formData = new FormData();
        formData.append('productID', id);
        formData.append('quantity', quantity);

        const response = await fetch('../../index.php?action=addToCart', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.status === 'not_logged_in') {
            alert('Please log in to add items to your cart.');
            return;
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
    }

    // Check if item already exists in UI
    const cartItems = cartContent.querySelectorAll('.cart-item');
    for (let item of cartItems) {
        const itemId = getElementProductId(item.querySelector('.product-id'));
        if (itemId === id) {
            const qtyElem = item.querySelector('.quantity');
            qtyElem.textContent = parseInt(qtyElem.textContent) + quantity;
            item.querySelector('.is_checked').classList.add('active');
            updatePrice();
            updateCartCount();
            openCart();
            return;
        }
    }

    const cartItem = document.createElement('div');
    cartItem.classList.add('cart-item');

    cartItem.innerHTML = `
        <img src="${img}" class="cart-img" alt="${title}">
        <div class="item-details">
            <input type="hidden" class="product-id" value="${id}">
            <h3 class="item-title">${title}</h3>
            <p class="item-price">${price}</p>
            <div class="quantity-controls">
                <button class="decrease-qty">-</button>
                <span class="quantity">${quantity}</span>
                <button class="increase-qty">+</button>
            </div>
        </div>
        <i class="ri-check-line is_checked active"></i>
        <i class="ri-delete-bin-line cart-remove"></i>
    `;

    cartContent.appendChild(cartItem);

    // 0. Toggle 'checked' Logic
    cartItem.querySelector('.is_checked').addEventListener('click', () => {
        cartItem.querySelector('.is_checked').classList.toggle('active');
        updatePrice();
    });

    // 1. Remove Logic
    cartItem.querySelector('.cart-remove').addEventListener('click', async () => {
        cartItem.remove();
        updatePrice();
        updateCartCount();

        try {
            const formData = new FormData();
            formData.append('productID', id);
            await fetch('../../index.php?action=removeFromCart', {
                method: 'POST',
                body: formData
            });
        } catch (error) {
            console.error('Error removing from cart:', error);
        }
    });

    // 2. Quantity Change Logic
    const quantityDisplay = cartItem.querySelector('.quantity');

    cartItem.querySelector('.decrease-qty').addEventListener('click', async () => {
        let qty = parseInt(quantityDisplay.textContent, 10);
        if (qty > 1) {
            qty -= 1;
            quantityDisplay.textContent = qty;
            updatePrice();
            updateCartCount();

            try {
                const formData = new FormData();
                formData.append('productID', id);
                formData.append('quantity', qty);
                await fetch('../../index.php?action=updateQuantity', {
                    method: 'POST',
                    body: formData
                });
            } catch (error) {
                console.error('Error updating quantity:', error);
            }
        }
    });

    cartItem.querySelector('.increase-qty').addEventListener('click', async () => {
        let qty = parseInt(quantityDisplay.textContent, 10) + 1;
        quantityDisplay.textContent = qty;
        updatePrice();
        updateCartCount();

        try {
            const formData = new FormData();
            formData.append('productID', id);
            formData.append('quantity', qty);
            await fetch('../../index.php?action=updateQuantity', {
                method: 'POST',
                body: formData
            });
        } catch (error) {
            console.error('Error updating quantity:', error);
        }
    });

    // Initial updates
    updatePrice();
    updateCartCount();

    openCart();
};

const loadCartItems = async () => {
    try {
        const response = await fetch('../../index.php?action=getCart');
        const items = await response.json();

        if (items && Array.isArray(items)) {
            if (!cartContent) initCartElements();
            if (!cartContent) return;

            cartContent.innerHTML = '';
            items.forEach(item => {
                const cartItem = document.createElement('div');
                cartItem.classList.add('cart-item');
                const isExternal = item.image_path && (item.image_path.startsWith('http://') || item.image_path.startsWith('https://'));
                const fullImgPath = isExternal ? item.image_path : `../../public/${item.image_path}`;

                cartItem.innerHTML = `
                    <img src="${fullImgPath}" alt="${item.name}">
                    <div class="item-details">
                        <input type="hidden" class="product-id" value="${item.produit_id}">
                        <h3 class="item-title">${item.name}</h3>
                        <p class="item-price">${item.price} DT</p>
                        <div class="quantity-controls">
                            <button class="decrease-qty">-</button>
                            <span class="quantity">${item.quantite}</span>
                            <button class="increase-qty">+</button>
                        </div>
                    </div>
                    <div class="btn_cart">
                        <i class="ri-check-line is_checked "></i>
                        <i class="ri-delete-bin-line cart-remove"></i>
                    </div>
                `;
                cartContent.appendChild(cartItem);

                // Check Logic
                cartItem.querySelector('.is_checked').addEventListener('click', () => {
                    cartItem.querySelector('.is_checked').classList.toggle('active');
                    updatePrice();
                });

                // Add listeners (same as in addToCart)
                cartItem.querySelector('.cart-remove').addEventListener('click', () => {
                    cartItem.remove();
                    updatePrice();
                    updateCartCount();
                });
                const quantityDisplay = cartItem.querySelector('.quantity');
                cartItem.querySelector('.decrease-qty').addEventListener('click', () => {
                    let qty = parseInt(quantityDisplay.textContent, 10);
                    if (qty > 1) {
                        quantityDisplay.textContent = qty - 1;
                        updatePrice();
                        updateCartCount();
                    }
                });
                cartItem.querySelector('.increase-qty').addEventListener('click', () => {
                    let qty = parseInt(quantityDisplay.textContent, 10);
                    quantityDisplay.textContent = qty + 1;
                    updatePrice();
                    updateCartCount();
                });
            });
            updatePrice();
            updateCartCount();
        }
    } catch (error) {
        console.error('Error loading cart:', error);
    }
};



// --- Global Listener using Event Delegation ---
document.addEventListener('click', event => {
    // 1. Check for "Add to Cart" button in product grid (look for .add-cart or similar)
    const gridBtn = event.target.closest('.add-cart');
    if (gridBtn) {
        const productBox = gridBtn.closest(".product-item");
        if (productBox) {
            const productData = {
                img: productBox.querySelector('img').src,
                title: productBox.querySelector('.product-title').textContent,
                price: productBox.querySelector('.product-price').textContent,
                id: getElementProductId(productBox.querySelector('.product-id'))
            };
            addToCart(productData);
        }
        return;
    }

    // 2. Check for "Add to Cart" button in Quick View Modal
    const modalBtn = event.target.closest('.add-cart-btn');
    if (modalBtn) {
        const modal = event.target.closest('.modal-content');
        if (modal) {
            const qtyInput = modal.querySelector('.qty-input');
            const quantity = qtyInput ? parseInt(qtyInput.value) : 1;
            const idElement = modal.querySelector('.product-id') || modal.querySelector('.id');
            const productData = {
                img: modal.querySelector('.main-image').src,
                title: modal.querySelector('.product-title').textContent,
                price: modal.querySelector('.product-price').textContent,
                id: getElementProductId(idElement)
            };
            addToCart(productData, quantity);
        }
        return;
    }

    // 3. Modal Quantity Buttons
    if (event.target.closest('.plus-btn')) {
        const input = event.target.closest('.quantity-selector').querySelector('.qty-input');
        input.value = parseInt(input.value) + 1;
    }
    if (event.target.closest('.minus-btn')) {
        const input = event.target.closest('.quantity-selector').querySelector('.qty-input');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
});

// Polyfill/Polling to initialize elements since they are loaded via fetch
let cartLoaded = false;
const pollForElements = setInterval(() => {
    if (initCartElements()) {
        const icons = document.querySelectorAll('.cart-icon');
        if (icons.length >= 2) {
            clearInterval(pollForElements);
        }
        updatePrice();
        updateCartCount();
        if (!cartLoaded) {
            loadCartItems();
            cartLoaded = true;
        }
    }
}, 500);

document.addEventListener('DOMContentLoaded', () => {
    initCartElements();
    updatePrice();
    loadCartItems();
});