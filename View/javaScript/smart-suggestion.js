document.addEventListener('DOMContentLoaded', async () => {
    const aiLoader = document.getElementById('ai-loader');
    const suggestionResults = document.getElementById('suggestion-results');
    const cartItemsList = document.getElementById('cart-items-list');
    const cartTotalPrice = document.getElementById('cart-total-price');
    const budgetInput = document.getElementById('user-budget');
    const updateBtn = document.getElementById('update-optimization');

    // 1. Fetch Cart Items
    async function loadCart() {
        try {
            const response = await fetch('../../index.php?action=getCart');
            const items = await response.json();

            if (items && Array.isArray(items)) {
                cartItemsList.innerHTML = '';
                let total = 0;

                items.forEach(item => {
                    const isExternal = item.image_path && (item.image_path.startsWith('http://') || item.image_path.startsWith('https://'));
                    const fullImgPath = isExternal ? item.image_path : `../../public/${item.image_path}`;
                    
                    const row = document.createElement('div');
                    row.className = 'cart-item-row';
                    row.innerHTML = `
                        <img src="${fullImgPath}" alt="${item.name}">
                        <div class="cart-item-info">
                            <h5>${item.name}</h5>
                            <p>${item.quantite} x ${item.price} DT</p>
                        </div>
                    `;
                    cartItemsList.appendChild(row);
                    total += parseFloat(item.price) * parseInt(item.quantite);
                });

                cartTotalPrice.textContent = `${total.toFixed(2)} DT`;
                
                // Set initial budget suggestion if input is at default
                if (budgetInput.value == "200") {
                    budgetInput.value = Math.max(Math.ceil(total * 1.2), 100);
                }
                
                return { items, total };
            }
        } catch (error) {
            console.error('Error loading cart:', error);
            cartItemsList.innerHTML = '<p style="color: #e74c3c;">Erreur lors du chargement du panier.</p>';
        }
        return { items: [], total: 0 };
    }

    // 2. Get AI Suggestions
    async function getAISuggestions(customBudget = null) {
        aiLoader.style.display = 'flex';
        suggestionResults.style.display = 'none';
        
        try {
            const budget = customBudget || parseFloat(budgetInput.value);
            
            console.log("Requesting optimization for budget:", budget);

            const response = await fetch('http://localhost:5001/api/optimize', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    budget: budget,
                    category: null,
                    user_id: window.userId
                })
            });

            if (!response.ok) throw new Error('AI Service error');

            const data = await response.json();
            console.log("AI Response:", data);
            
            if (data && data.best_combination && data.best_combination.length > 0) {
                document.getElementById('ai-total-price').textContent = `${parseFloat(data.total_price).toFixed(2)} DT`;
                document.getElementById('ai-total-score').textContent = Math.round(data.total_score);
                document.getElementById('results-summary').style.display = 'flex';
                displaySuggestions(data.best_combination);
            } else {
                document.getElementById('results-summary').style.display = 'none';
                suggestionResults.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-secondary);">Aucune suggestion trouvée pour ce budget. Essayez de l\'augmenter légèrement.</div>';
            }
        } catch (error) {
            console.error('Error fetching AI suggestions:', error);
            document.getElementById('results-summary').style.display = 'none';
            suggestionResults.innerHTML = `<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #e74c3c;">
                Le service IA est hors ligne.<br>
                <small>Vérifiez que "python app.py" est lancé dans "services/ai".</small>
            </div>`;
        } finally {
            aiLoader.style.display = 'none';
            suggestionResults.style.display = 'grid';
        }
    }

    function displaySuggestions(products) {
        suggestionResults.innerHTML = '';
        products.forEach(product => {
            const card = document.createElement('div');
            card.className = 'suggestion-card';
            
            let imgPath = product.image;
            if (!imgPath.startsWith('http')) {
                imgPath = `../../public/${imgPath}`;
            }

            card.innerHTML = `
                <img src="${imgPath}" alt="${product.name}">
                <h4>${product.name}</h4>
                <div class="price">${parseFloat(product.price).toFixed(2)} DT</div>
                <button class="add-to-cart-suggest" data-id="${product.id}" style="margin-top:10px; width:100%; padding:10px; background: var(--accent); border:none; border-radius:8px; cursor:pointer; font-weight:700; color:#000;">Ajouter</button>
            `;
            suggestionResults.appendChild(card);
        });

        document.querySelectorAll('.add-to-cart-suggest').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const id = e.target.dataset.id;
                const formData = new FormData();
                formData.append('productID', id);
                formData.append('quantity', 1);

                try {
                    const res = await fetch('../../index.php?action=addToCart', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await res.json();
                    if (result.status === 'success') {
                        alert('Produit ajouté au panier !');
                        location.reload(); 
                    } else {
                        alert('Erreur: ' + (result.message || 'Impossible d\'ajouter le produit.'));
                    }
                } catch (err) {
                    console.error('Error adding suggestion to cart:', err);
                }
            });
        });
    }

    // Event Listeners
    updateBtn.addEventListener('click', () => getAISuggestions());
    
    // Initial Load
    const { total } = await loadCart();
    await getAISuggestions();
});
