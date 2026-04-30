document.addEventListener('DOMContentLoaded', () => {
    const optimizeBtn = document.getElementById('optimizeBtn');
    const budgetInput = document.getElementById('budget');
    const categorySelect = document.getElementById('category');
    const statusMsg = document.getElementById('statusMsg');
    const resultsGrid = document.getElementById('resultsGrid');
    
    const statPrice = document.getElementById('statPrice');
    const statScore = document.getElementById('statScore');
    const statCount = document.getElementById('statCount');
    const genCountDisplay = document.getElementById('generationCount');

    let chart = initChart();

    optimizeBtn.addEventListener('click', async () => {
        const budget = budgetInput.value;
        const category = categorySelect.value;

        if (!budget || budget <= 0) {
            alert('Veuillez entrer un budget valide.');
            return;
        }

        optimizeBtn.disabled = true;
        optimizeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Calcul en cours...';
        statusMsg.innerText = "L'algorithme génétique explore les combinaisons...";
        resultsGrid.innerHTML = '';

        try {
            const response = await fetch('http://localhost:5001/api/optimize', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ budget, category })
            });

            const data = await response.json();

            if (data.error) {
                statusMsg.innerText = data.error;
                statusMsg.style.color = '#ff4444';
            } else {
                animateOptimization(data);
            }
        } catch (error) {
            console.error('Error:', error);
            statusMsg.innerText = "Erreur de connexion au serveur IA (Flask).";
            statusMsg.style.color = '#ff4444';
        } finally {
            optimizeBtn.disabled = false;
            optimizeBtn.innerHTML = '<i class="fas fa-microchip"></i> Optimiser';
        }
    });

    function initChart() {
        const ctx = document.getElementById('fitnessChart').getContext('2d');
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Fitness (Satisfaction/Prix)',
                    data: [],
                    borderColor: '#ccff00',
                    backgroundColor: 'rgba(204, 255, 0, 0.1)',
                    borderWidth: 2,
                    pointRadius: 0,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { display: false },
                    y: { 
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { color: '#666' }
                    }
                }
            }
        });
    }

    function animateOptimization(data) {
        const history = data.history;
        let currentGen = 0;
        
        // Clear chart
        chart.data.labels = [];
        chart.data.datasets[0].data = [];
        chart.update();

        const interval = setInterval(() => {
            if (currentGen >= history.length) {
                clearInterval(interval);
                displayResults(data);
                return;
            }

            // Update chart bit by bit
            chart.data.labels.push(currentGen);
            chart.data.datasets[0].data.push(history[currentGen]);
            chart.update('none');

            genCountDisplay.innerText = `Génération: ${currentGen + 1}/${history.length}`;
            
            // Randomly update stats for effect
            statPrice.innerText = (Math.random() * data.budget).toFixed(2) + ' €';
            statScore.innerText = Math.floor(Math.random() * 100);
            
            currentGen += 2; // Fast forward
        }, 20);
    }

    function displayResults(data) {
        statPrice.innerText = data.total_price.toFixed(2) + ' €';
        statScore.innerText = data.total_score;
        statCount.innerText = data.best_combination.length;
        statusMsg.innerText = "Optimisation terminée avec succès.";
        statusMsg.style.color = 'var(--accent)';

        resultsGrid.innerHTML = data.best_combination.map(p => `
            <div class="product-card stagger-in">
                <div class="product-img">
                    <img src="${p.image.startsWith('http') ? p.image : '../../uploads/' + p.image}" alt="${p.name}">
                </div>
                <div class="product-info">
                    <div class="product-category">${p.category}</div>
                    <div class="product-name">${p.name}</div>
                    <div class="product-footer">
                        <div class="product-price">${p.price.toFixed(2)} €</div>
                        <div class="score-badge">Score: ${p.score}</div>
                    </div>
                </div>
            </div>
        `).join('');
    }
});
