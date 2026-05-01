document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const anchorId = urlParams.get('product_id');
    const budget = urlParams.get('budget') || 150;
    const season = urlParams.get('meteo') || 'toutes_saisons';

    if (anchorId) {
        fetchOutfits(anchorId, budget, season);
    }

    function fetchOutfits(productId, budget, season) {
        const container = document.getElementById('outfits-container');
        container.innerHTML = `
            <div class="loading">
                <div class="spinner"></div>
                <p>Generating matching outfits with CSP algorithm...</p>
            </div>
        `;

        fetch('http://localhost:5001/api/mix-match', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
                budget: parseFloat(budget),
                season: season
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                renderError(data.error);
            } else {
                renderOutfits(data, budget);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            renderError('Unable to connect to the AI service. Please ensure the Python backend is running.');
        });
    }

    function renderOutfits(outfits, maxBudget) {
        const container = document.getElementById('outfits-container');
        container.innerHTML = '';

        if (!outfits || outfits.length === 0) {
            container.innerHTML = `
                <div class="no-results">
                    <p>No outfits found matching your constraints. Try increasing your budget or changing the weather.</p>
                    <a href="ai.html#mix-match" class="btn-add">Go Back</a>
                </div>
            `;
            return;
        }

        outfits.forEach((outfit, index) => {
            const card = document.createElement('div');
            card.className = 'outfit-card';
            card.style.animationDelay = `${index * 0.1}s`;

            let itemsHtml = '';
            outfit.items.forEach(item => {
                let img = item.image;
                if (img && !img.startsWith('http')) {
                    img = "../../public/" + img;
                }
                if (!img) {
                    img = 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400&h=600&fit=crop';
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
                <div style="text-align: right; margin-top: 20px;">
                    <button class="btn-add" onclick="addOutfitToCart('${outfit.items.map(i => i.id).join(',')}')">Add Entire Outfit to Cart</button>
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
                <a href="ai.html#mix-match" class="btn-add">Go Back</a>
            </div>
        `;
    }
});

function addOutfitToCart(productIds) {
    // Logic to add multiple products to cart
    // For now, redirect to first product as a placeholder or call cart API
    alert('Adding items to cart: ' + productIds);
}
