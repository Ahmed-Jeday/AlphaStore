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
        const priceElement = item.querySelector('.item-price');
        const quantityElement = item.querySelector('.quantity');
        if (priceElement && quantityElement) {
            const price = parseFloat(priceElement.textContent.replace('$', ''));
            const quantity = parseInt(quantityElement.textContent);
            total += price * quantity;
        }
    });
    totalPriceElement.textContent = `$${total.toFixed(2)}`;
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

// --- Main Add to Cart Function ---
const addToCart = (productData, quantity = 1) => {
    if (!cartContent) {
        if (!initCartElements()) return;
    }

    const { img, title, price, id } = productData;
    if (!id) {
        console.error('addToCart called without a product id', productData);
        return;
    }

    // Check if item already exists in cart
    const cartItems = cartContent.querySelectorAll('.cart-item');
    for (let item of cartItems) {
        const itemId = getElementProductId(item.querySelector('.product-id'));
        if (itemId === id) {
            const qtyElem = item.querySelector('.quantity');
            qtyElem.textContent = parseInt(qtyElem.textContent) + quantity;
            updatePrice();
            updateCartCount();
            openCart();
            return;
        }
    }

    const cartItem = document.createElement('div');
    cartItem.classList.add('cart-item');

    cartItem.innerHTML = `
        <img src="${img}" alt="${title}">
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
        <i class="ri-delete-bin-line cart-remove"></i>
    `;

    cartContent.appendChild(cartItem);

    // --- Add Listeners to NEW elements specifically ---
    
    // 1. Remove Logic
    cartItem.querySelector('.cart-remove').addEventListener('click', () => {
        cartItem.remove();
        updatePrice();
        updateCartCount();
    });

    // 2. Quantity Change Logic
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

    // Initial updates
    updatePrice();
    updateCartCount();
    
    openCart();
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
const pollForElements = setInterval(() => {
    // We check for length > 1 here because user said they have 2 icons now
    if (initCartElements()) {
        const icons = document.querySelectorAll('.cart-icon');
        // If we found both (or at least one if that's all there is), we can stop polling eventually
        // But since content is dynamic, better to keep checking or check for a specific count
        if (icons.length >= 2) {
             clearInterval(pollForElements);
        }
        updatePrice();
        updateCartCount();
    }
}, 500);

document.addEventListener('DOMContentLoaded', () => {
    initCartElements();
    updatePrice();
});