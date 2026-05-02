(function () {
    const SEASON_LABELS = {
        ete: 'Summer / Hot',
        hiver: 'Winter / Cold',
        mi_saison: 'Spring / Autumn / Mild',
        toutes_saisons: 'All seasons',
    };

    function clampBudget(n) {
        const v = parseFloat(n);
        if (Number.isNaN(v)) return 150;
        return Math.min(1000, Math.max(10, v));
    }

    function readProductId() {
        const hidden = document.getElementById('mix-match-product-id');
        if (hidden && hidden.value.trim()) {
            return hidden.value.trim();
        }
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('product_id') || '';
    }

    function syncUrl(productId, budget, season) {
        const params = new URLSearchParams();
        params.set('product_id', productId);
        params.set('budget', String(budget));
        params.set('meteo', season);
        const path = window.location.pathname;
        window.history.replaceState({}, '', path + '?' + params.toString());
    }

    function updateHeaderLabels(budget, season) {
        const seasonEl = document.getElementById('mix-match-season-label');
        const budgetEl = document.getElementById('mix-match-budget-label');
        if (seasonEl) {
            seasonEl.textContent = SEASON_LABELS[season] || season.replace(/_/g, ' ');
        }
        if (budgetEl) {
            budgetEl.textContent = String(budget);
        }
    }

    function fetchOutfits(productId, budget, season) {
        const container = document.getElementById('outfits-container');
        budget = clampBudget(budget);

        container.innerHTML = `
            <div class="loading">
                <div class="spinner"></div>
                <p>Building your looks—matching season, budget, and pieces from our catalogue…</p>
            </div>
        `;

        fetch('http://localhost:5001/api/mix-match', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
                budget: budget,
                season: season,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.error) {
                    renderError(data.error);
                } else {
                    renderOutfits(data, budget);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                renderError(
                    'Unable to connect to the AI service. Please ensure the Python backend is running.'
                );
            });
    }

    function renderOutfits(outfits, maxBudget) {
        const container = document.getElementById('outfits-container');
        container.innerHTML = '';

        if (!outfits || outfits.length === 0) {
            container.innerHTML = `
                <div class="no-results">
                    <p>No outfits found matching your constraints. Try increasing your budget or changing the season.</p>
                    <a href="ai.html#mix-match" class="btn-add btn-add--outline">Back to style tools</a>
                </div>
            `;
            return;
        }

        outfits.forEach((outfit, index) => {
            const card = document.createElement('div');
            card.className = 'outfit-card';
            card.style.animationDelay = `${index * 0.1}s`;

            let itemsHtml = '';
            outfit.items.forEach((item) => {
                let img = item.image;
                if (img && !img.startsWith('http')) {
                    img = '../../public/' + img;
                }
                if (!img) {
                    img =
                        'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400&h=600&fit=crop';
                }

                itemsHtml += `
                    <div class="item-card">
                        <img src="${img}" alt="${item.name}" class="item-img">
                        <div class="item-info">
                            <span class="item-type">${item.product_type}</span>
                            <h3 class="item-name" title="${item.name}">${item.name}</h3>
                            <div class="item-price">£${item.price.toFixed(2)}</div>
                        </div>
                    </div>
                `;
            });

            card.innerHTML = `
                <div class="outfit-header">
                    <h2>Outfit #${index + 1}</h2>
                    <div class="outfit-price">£${outfit.total.toFixed(2)} / £${maxBudget}</div>
                </div>
                <div class="items-grid">
                    ${itemsHtml}
                </div>
                <div class="mix-match-card-actions">
                    <button class="btn-add" onclick="addOutfitToCart('${outfit.items.map((i) => i.id).join(',')}')">Add Entire Outfit to Cart</button>
                </div>
            `;
            container.appendChild(card);
        });
    }

    function renderError(message) {
        const container = document.getElementById('outfits-container');
        container.innerHTML = `
            <div class="no-results">
                <p>${message}</p>
                <a href="ai.html#mix-match" class="btn-add btn-add--outline">Back to style tools</a>
            </div>
        `;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const productId = readProductId();
        const urlParams = new URLSearchParams(window.location.search);
        const budgetInput = document.getElementById('mix-match-budget-input');
        const seasonSelect = document.getElementById('mix-match-meteo');
        const applyBtn = document.getElementById('mix-match-apply');

        let budget = urlParams.get('budget');
        if (budgetInput && budgetInput.value !== '') {
            budget = budgetInput.value;
        } else if (budget == null || budget === '') {
            budget = 150;
        }
        budget = clampBudget(budget);
        if (budgetInput) {
            budgetInput.value = String(budget);
        }

        let season = urlParams.get('meteo') || 'toutes_saisons';
        if (seasonSelect && seasonSelect.value) {
            season = seasonSelect.value;
        }
        if (seasonSelect) {
            seasonSelect.value = season;
        }

        updateHeaderLabels(budget, season);

        if (!productId) {
            renderError(
                'No product was selected. Open a product, use Mix & Match, or add ?product_id=… to the URL.'
            );
            return;
        }

        fetchOutfits(productId, budget, season);

        function applyFilters() {
            const b = clampBudget(budgetInput ? budgetInput.value : budget);
            const s = seasonSelect ? seasonSelect.value : season;
            if (budgetInput) {
                budgetInput.value = String(b);
            }
            updateHeaderLabels(b, s);
            syncUrl(productId, b, s);
            fetchOutfits(productId, b, s);
        }

        if (applyBtn) {
            applyBtn.addEventListener('click', applyFilters);
        }
    });
})();

function addOutfitToCart(productIds) {
    alert('Adding items to cart: ' + productIds);
}
