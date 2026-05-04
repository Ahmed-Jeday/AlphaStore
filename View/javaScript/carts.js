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
    if (checkout_btn) {
        checkout_btn.addEventListener("click", function() {
            window.location.href = "checkout.php";
        });
    }

    if (smartBudgetBtn && !smartBudgetBtn.dataset.listened) {
        smartBudgetBtn.addEventListener('click', () => {
            window.location.href = "smart-suggestion.php";
        });
        smartBudgetBtn.dataset.listened = "true";
    }


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

    const emptyMsg = cartContent.querySelector('.empty-msg');

    cartCountElements.forEach(countBadge => {
        if (totalQuantity > 0) {
            countBadge.textContent = totalQuantity;
            countBadge.style.display = 'flex';
            if (emptyMsg) emptyMsg.style.display = 'none';
        } else {
            countBadge.style.display = 'none';
            if (emptyMsg) emptyMsg.style.display = 'block';
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

// --- Helper to create cart item element ---
const createCartItemElement = (itemData) => {
    const { img, title, price, id, quantity } = itemData;
    const cartItem = document.createElement('div');
    cartItem.classList.add('cart-item');

    cartItem.innerHTML = `
        <img src="${img}" class="cart-img" alt="${title}">
        <div class="item-details">
            <input type="hidden" class="product-id" value="${id}">
            <h3 class="item-title">${title}</h3>
            <p class="item-price">${price}</p>
            <div class="quantity-controls">
                <button class="decrease-qty" aria-label="Decrease quantity">-</button>
                <span class="quantity">${quantity}</span>
                <button class="increase-qty" aria-label="Increase quantity">+</button>
            </div>
        </div>
        <div class="btn_cart">
            <i class="ri-check-line is_checked active" title="Select for checkout"></i>
            <i class="ri-delete-bin-line cart-remove" title="Remove from cart"></i>
        </div>
    `;

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

    const updateQty = async (newQty) => {
        quantityDisplay.textContent = newQty;
        updatePrice();
        updateCartCount();
        try {
            const formData = new FormData();
            formData.append('productID', id);
            formData.append('quantity', newQty);
            await fetch('../../index.php?action=updateQuantity', {
                method: 'POST',
                body: formData
            });
        } catch (error) {
            console.error('Error updating quantity:', error);
        }
    };

    cartItem.querySelector('.decrease-qty').addEventListener('click', () => {
        let qty = parseInt(quantityDisplay.textContent, 10);
        if (qty > 1) updateQty(qty - 1);
    });

    cartItem.querySelector('.increase-qty').addEventListener('click', () => {
        let qty = parseInt(quantityDisplay.textContent, 10);
        updateQty(qty + 1);
    });

    return cartItem;
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
            const newQty = parseInt(qtyElem.textContent) + quantity;
            qtyElem.textContent = newQty;
            item.querySelector('.is_checked').classList.add('active');
            updatePrice();
            updateCartCount();
            openCart();
            return;
        }
    }

    const cartItemElement = createCartItemElement({ img, title, price, id, quantity });
    cartContent.appendChild(cartItemElement);

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
                const isExternal = item.image_path && (item.image_path.startsWith('http://') || item.image_path.startsWith('https://'));
                const fullImgPath = isExternal ? item.image_path : `../../public/${item.image_path}`;

                const cartItemElement = createCartItemElement({
                    img: fullImgPath,
                    title: item.name,
                    price: `${item.price} DT`,
                    id: item.produit_id,
                    quantity: item.quantite
                });
                cartContent.appendChild(cartItemElement);
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
        // Try different wrappers: .product, .product-card, .co-card, .swiper-slide, etc.
        const productBox = gridBtn.closest(".product") || 
                           gridBtn.closest(".product-card") || 
                           gridBtn.closest(".co-card") ||
                           gridBtn.closest(".swiper-slide");
                           
        if (productBox) {
            const img = productBox.querySelector('img');
            const title = productBox.querySelector('.product-name') || 
                          productBox.querySelector('h3') || 
                          productBox.querySelector('h6') ||
                          productBox.querySelector('.co-card__title');
            const price = productBox.querySelector('.product-price') || 
                          productBox.querySelector('.price-value') ||
                          productBox.querySelector('.co-card__price');
            const idElement = productBox.querySelector('.product-id') || 
                              productBox.querySelector('[value]') || 
                              productBox;

            const productData = {
                img: img ? img.src : '',
                title: title ? title.innerText.trim() : 'Premium Product',
                price: price ? price.innerText.trim() : '0.00 DT',
                id: getElementProductId(idElement) || 'P-' + Math.floor(Math.random() * 1000) // Fallback for demo
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
    // 4. Cart Icon Click (Delegation for reliability)
    if (event.target.closest('.cart-icon')) {
        openCart();
    }
});

// Polyfill/Polling to initialize elements since they are loaded via fetch
let cartLoaded = false;
const pollForElements = setInterval(() => {
    if (initCartElements()) {
        const icons = document.querySelectorAll('.cart-icon');
        // Clear interval if we have the sidebar AND at least one icon
        if (icons.length >= 1 && document.querySelector('.cart')) {
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