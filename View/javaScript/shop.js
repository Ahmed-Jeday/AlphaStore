
document.addEventListener('DOMContentLoaded', function () {
    // ----- PRODUCT DATA (static with categories for filtering) -----
    const productsData = [
        { id: 1, name: "Esprit Ruffle Shirt", price: 16.64, category: "women", img: "https://images.unsplash.com/photo-1554412933-514a83d2f3c8?q=80&w=400&auto=format&fit=crop" },
        { id: 2, name: "Herschel supply", price: 35.31, category: "bag", img: "https://images.unsplash.com/photo-1549298916-b41d501d3772?q=80&w=400&auto=format&fit=crop" },
        { id: 3, name: "Only Check Trouser", price: 25.50, category: "men", img: "https://images.unsplash.com/photo-1506629082955-511b1aa562c8?q=80&w=400&auto=format&fit=crop" },
        { id: 4, name: "Classic Trench Coat", price: 75.00, category: "women", img: "https://images.unsplash.com/photo-1584382296087-9ff2714c1143?q=80&w=400&auto=format&fit=crop" },
        { id: 5, name: "Front Pocket Jumper", price: 34.75, category: "men", img: "https://images.unsplash.com/photo-1543163521-1bf539c55dd2?q=80&w=400&auto=format&fit=crop" },
        { id: 6, name: "Vintage Inspired Classic", price: 93.20, category: "women", img: "https://images.unsplash.com/photo-1524805444758-089113d48a6d?q=80&w=400&auto=format&fit=crop" },
        { id: 7, name: "Stretch Cotton Shirt", price: 52.66, category: "men", img: "https://images.unsplash.com/photo-1503341504253-d2d08aa8f4ef?q=80&w=400&auto=format&fit=crop" },
        { id: 8, name: "Pieces Metallic Printed", price: 18.96, category: "women", img: "https://images.unsplash.com/photo-1510832842233-436cd988290f?q=80&w=400&auto=format&fit=crop" },
        { id: 9, name: "Converse All Star", price: 75.00, category: "shoes", img: "https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?q=80&w=400&auto=format&fit=crop" },
        { id: 10, name: "Femme T-Shirt", price: 25.85, category: "women", img: "https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?q=80&w=400&auto=format&fit=crop" },
        { id: 11, name: "Classic Weekender Bag", price: 63.16, category: "bag", img: "https://images.unsplash.com/photo-1506629082955-511b1aa562c8?q=80&w=400&auto=format&fit=crop" },
        { id: 12, name: "Minimalist Watch", price: 63.15, category: "watches", img: "https://images.unsplash.com/photo-1510832842233-436cd988290f?q=80&w=400&auto=format&fit=crop" }
    ];

    let currentCategory = "all";
    let currentPriceRange = "all";
    let currentSort = "default";
    let visibleProducts = [...productsData];

    const productGrid = document.getElementById("productGrid");
    const noProductMsg = document.getElementById("noProductMsg");

    function renderProducts() {
        let filtered = productsData.filter(p => {
            if (currentCategory !== "all" && p.category !== currentCategory) return false;
            if (currentPriceRange !== "all") {
                const [min, max] = currentPriceRange.split("-").map(Number);
                if (max) { if (p.price < min || p.price > max) return false; }
                else if (currentPriceRange === "200+") { if (p.price < 200) return false; }
            }
            return true;
        });

        if (currentSort === "price_low") filtered.sort((a, b) => a.price - b.price);
        else if (currentSort === "price_high") filtered.sort((a, b) => b.price - a.price);
        else filtered.sort((a, b) => a.id - b.id);

        visibleProducts = filtered;
        if (filtered.length === 0) {
            productGrid.innerHTML = '';
            noProductMsg.classList.add('show');
            return;
        }
        noProductMsg.classList.remove('show');
        productGrid.innerHTML = filtered.map(p => `
                <div class="product-item" data-category="${p.category}" data-id="${p.id}">
                    <div class="product-img-block">
                        <img src="${p.img}" alt="${p.name}">
                        <button class="quick-view-btn" data-id="${p.id}">Quick View</button>
                    </div>
                    <div class="product-info-block">
                        <div class="product-text">
                            <a href="#" class="product-title">${p.name}</a>
                            <span class="product-price">${p.price.toFixed(2)} DT</span>
                        </div>
                        <div class="product-actions">
                            <i class="far fa-heart heart-icon" data-id="${p.id}"></i>
                        </div>
                    </div>
                </div>
            `).join('');
        attachQuickViewEvents();
        attachHeartEvents();
    }

    function attachQuickViewEvents() {
        document.querySelectorAll('.quick-view-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const modal = document.getElementById('quickViewModal');
                if (modal) modal.classList.add('active');
            });
        });
    }

    function attachHeartEvents() {
        document.querySelectorAll('.heart-icon').forEach(heart => {
            heart.addEventListener('click', function (e) {
                e.preventDefault();
                this.classList.toggle('liked');
                if (this.classList.contains('liked')) {
                    this.classList.remove('far');
                    this.classList.add('fas');
                } else {
                    this.classList.remove('fas');
                    this.classList.add('far');
                }
            });
        });
    }

    // category tabs
    document.querySelectorAll('.category-tabs a').forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('.category-tabs a').forEach(t => t.classList.remove('active-tab'));
            tab.classList.add('active-tab');
            currentCategory = tab.dataset.cat;
            renderProducts();
        });
    });

    // price filter
    document.querySelectorAll('#priceFilterList a').forEach(priceLink => {
        priceLink.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('#priceFilterList a').forEach(p => p.classList.remove('active-filter'));
            priceLink.classList.add('active-filter');
            currentPriceRange = priceLink.dataset.price;
            renderProducts();
        });
    });

    // sort
    document.querySelectorAll('#sortList a').forEach(sortLink => {
        sortLink.addEventListener('click', (e) => {
            e.preventDefault();
            currentSort = sortLink.dataset.sort;
            renderProducts();
        });
    });

    // filter panel toggle
    const filterBtn = document.getElementById('toggle-filter-btn');
    const filterPanel = document.getElementById('filter-dropdown-panel');
    if (filterBtn && filterPanel) {
        filterBtn.addEventListener('click', () => {
            filterPanel.classList.toggle('show-panel');
            const icon = filterBtn.querySelector('i');
            if (filterPanel.classList.contains('show-panel')) {
                icon.classList.remove('fa-filter');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-filter');
            }
        });
    }

    // Slider logic (improved)
    const track = document.getElementById('track');
    const prevBtn = document.getElementById('prev');
    const nextBtn = document.getElementById('next');
    const dotsContainer = document.getElementById('dots');
    let slides = Array.from(document.querySelectorAll('.project-card'));
    let currentIndex = 0;
    function updateSlider() {
        const width = slides[0]?.offsetWidth + 20 || 320;
        track.style.transform = `translateX(-${currentIndex * width}px)`;
        if (dotsContainer) {
            dotsContainer.innerHTML = '';
            slides.forEach((_, idx) => {
                const dot = document.createElement('span');
                dot.classList.add('dot');
                if (idx === currentIndex) dot.classList.add('active-dot');
                dot.addEventListener('click', () => { currentIndex = idx; updateSlider(); });
                dotsContainer.appendChild(dot);
            });
        }
    }
    if (prevBtn && nextBtn && track && slides.length) {
        prevBtn.addEventListener('click', () => { if (currentIndex > 0) { currentIndex--; updateSlider(); } });
        nextBtn.addEventListener('click', () => { if (currentIndex < slides.length - 1) { currentIndex++; updateSlider(); } });
        updateSlider();
    }

    // Load More button - adds a smooth alert (feature)
    const loadBtn = document.getElementById('loadMoreBtn');
    if (loadBtn) {
        loadBtn.addEventListener('click', () => {
            window.location.href='../html/store.html'
        });
    }

    // Newsletter signup
    const newsletterForm = document.getElementById('newsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            alert("🎉 Thanks for subscribing! Enjoy 15% off on your next order.");
            newsletterForm.reset();
        });
    }

    // Modal close + quick view modal existing
    const modal = document.getElementById('quickViewModal');
    const closeModal = document.getElementById('closeModalBtn');
    if (closeModal && modal) {
        closeModal.addEventListener('click', () => modal.classList.remove('active'));
        modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.remove('active'); });
    }

    // Quantity selector
    document.querySelectorAll('.minus-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            let input = this.parentElement.querySelector('.qty-input');
            if (input && parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
        });
    });
    document.querySelectorAll('.plus-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            let input = this.parentElement.querySelector('.qty-input');
            if (input) input.value = parseInt(input.value) + 1;
        });
    });

    // Search trigger
    const searchBtn = document.getElementById('searchTriggerBtn');
    if (searchBtn) {
        searchBtn.addEventListener('click', () => {
            let query = prompt("🔍 Search for products (name or category):");
            if (query && query.trim()) {
                const filteredSearch = productsData.filter(p => p.name.toLowerCase().includes(query.toLowerCase()) || p.category.includes(query.toLowerCase()));
                if (filteredSearch.length) {
                    currentCategory = "all";
                    document.querySelectorAll('.category-tabs a').forEach(t => t.classList.remove('active-tab'));
                    document.querySelector('.category-tabs a[data-cat="all"]').classList.add('active-tab');
                    currentPriceRange = "all";
                    renderProductsWithCustom(filteredSearch);
                } else alert("No matching products found.");
            }
        });
    }
    function renderProductsWithCustom(productsArr) {
        if (productsArr.length === 0) { noProductMsg.classList.add('show'); productGrid.innerHTML = ''; return; }
        noProductMsg.classList.remove('show');
        productGrid.innerHTML = productsArr.map(p => `
                <div class="product-item" data-category="${p.category}">
                    <div class="product-img-block"><img src="${p.img}" alt="${p.name}"><button class="quick-view-btn">Quick View</button></div>
                    <div class="product-info-block"><div class="product-text"><a href="#" class="product-title">${p.name}</a><span class="product-price">${p.price.toFixed(2)} DT</span></div><div class="product-actions"><i class="far fa-heart heart-icon"></i></div></div>
                </div>
            `).join('');
        attachQuickViewEvents(); attachHeartEvents();
    }

    renderProducts();
});
