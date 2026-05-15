document.addEventListener('DOMContentLoaded', () => {
    // State management
    const state = {
        selected: {},        // { cpu: {id, price, tdp, performance_score...}, ... }
        validDomains: {},    // { cpu: [{component, ok, reason}, ...], ... }
        budget: 1500,
        usageProfile: 'gaming',
        isCalculating: false
    };

    // DOM Elements
    const budgetInput = document.getElementById('budgetRange');
    const budgetValueDisplay = document.getElementById('budgetValue');
    const profileSelect = document.getElementById('usageProfile');
    const sectionsContainer = document.getElementById('pcGrid');
    const summaryBar = document.getElementById('summaryBar');
    const totalPriceEl = document.getElementById('totalPrice');
    const totalTdpEl = document.getElementById('totalTdp');
    const totalScoreEl = document.getElementById('totalScore');
    const psuStatusEl = document.getElementById('psuStatus');
    const recommendBtn = document.getElementById('recommendBtn');
    const addToCartBtn = document.getElementById('addToCartAll');
    const gaOverlay = document.getElementById('gaOverlay');
    const gaChartCtx = document.getElementById('gaChart').getContext('2d');

    let gaChart = null;

    // Initialize
    async function init() {
        budgetValueDisplay.textContent = `${state.budget} €`;
        await fetchAndRender();
        setupEventListeners();
    }

    // Event Listeners
    function setupEventListeners() {
        budgetInput.addEventListener('input', (e) => {
            state.budget = parseInt(e.target.value);
            budgetValueDisplay.textContent = `${state.budget} €`;
            updateCSP();
        });

        profileSelect.addEventListener('change', (e) => {
            state.usageProfile = e.target.value;
        });

        recommendBtn.addEventListener('click', runGA);
        addToCartBtn.addEventListener('click', addAllToCart);
    }

    // Fetch all components and initial CSP
    async function fetchAndRender() {
        try {
            const response = await fetch('../../index.php?action=getPCComponents');
            const data = await response.json();
            
            // data is { cpu: [...], gpu: [...], ... }
            renderGrid(data);
            await updateCSP();
        } catch (err) {
            console.error('Failed to load components:', err);
        }
    }

    function renderGrid(groupedComponents) {
        sectionsContainer.innerHTML = '';
        const types = ['cpu', 'gpu', 'motherboard', 'ram', 'psu', 'storage', 'case'];
        
        types.forEach(type => {
            const section = document.createElement('div');
            section.className = 'pc-category-section';
            section.innerHTML = `
                <div class="category-header">
                    <h2>${type.toUpperCase()}</h2>
                    <span class="selected-badge" id="badge-${type}">SELECTED</span>
                </div>
                <div class="component-list" id="list-${type}">
                    <!-- Components injected here -->
                </div>
            `;
            sectionsContainer.appendChild(section);
            
            const list = section.querySelector('.component-list');
            const components = groupedComponents[type] || [];
            
            components.forEach(comp => {
                const card = createCompCard(comp);
                list.appendChild(card);
            });
        });
    }

    function createCompCard(comp) {
        const card = document.createElement('div');
        card.className = 'comp-card';
        card.id = `comp-${comp.id}`;
        card.dataset.id = comp.id;
        card.dataset.type = comp.component_type;
        
        const img = comp.image_url ? comp.image_url : 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=200&h=200&fit=crop';
        
        card.innerHTML = `
            <img src="${img}" class="comp-img" alt="${comp.name}">
            <div class="comp-info">
                <div class="comp-name">${comp.name}</div>
                <div class="comp-price">${comp.price.toFixed(2)} €</div>
                <div class="comp-reason"></div>
            </div>
        `;
        
        card.addEventListener('click', () => toggleSelection(comp));
        return card;
    }

    async function toggleSelection(comp) {
        const type = comp.component_type;
        
        if (state.selected[type] && state.selected[type].id === comp.id) {
            delete state.selected[type];
        } else {
            state.selected[type] = comp;
        }
        
        updateUI();
        await updateCSP();
    }

    async function updateCSP() {
        try {
            const response = await fetch('../../index.php?action=filterPCComponents', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    selected: state.selected,
                    budget: state.budget
                })
            });
            
            const annotatedDomains = await response.json();
            state.validDomains = annotatedDomains;
            applyCSPToUI();
        } catch (err) {
            console.error('CSP failed:', err);
        }
    }

    function applyCSPToUI() {
        const allCards = document.querySelectorAll('.comp-card');
        allCards.forEach(card => {
            const id = parseInt(card.dataset.id);
            const type = card.dataset.type;
            const domain = state.validDomains[type] || [];
            const info = domain.find(item => item.component.id === id);
            
            if (info) {
                if (info.ok) {
                    card.classList.remove('incompatible');
                    card.querySelector('.comp-reason').textContent = '';
                } else {
                    card.classList.add('incompatible');
                    card.classList.remove('selected');
                    card.querySelector('.comp-reason').textContent = info.reason;
                }
            }
        });
        updateSummary();
    }

    function updateUI() {
        // Update selection states
        document.querySelectorAll('.comp-card').forEach(card => {
            const type = card.dataset.type;
            const id = parseInt(card.dataset.id);
            if (state.selected[type] && state.selected[type].id === id) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
        
        // Update badges
        ['cpu', 'gpu', 'motherboard', 'ram', 'psu', 'storage', 'case'].forEach(type => {
            const badge = document.getElementById(`badge-${type}`);
            if (state.selected[type]) {
                badge.classList.add('active');
                badge.textContent = 'SELECTED';
            } else {
                badge.classList.remove('active');
            }
        });
    }

    function updateSummary() {
        let total = 0;
        let tdp = 0;
        let score = 0;
        let count = 0;
        
        Object.values(state.selected).forEach(comp => {
            total += parseFloat(comp.price);
            tdp += parseInt(comp.tdp || 0);
            score += parseInt(comp.performance_score || 0);
            count++;
        });

        totalPriceEl.textContent = `${total.toFixed(2)} €`;
        totalTdpEl.textContent = `${tdp} W`;
        totalScoreEl.textContent = count > 0 ? Math.round(score / count) : 0;
        
        const psu = state.selected['psu'];
        if (psu) {
            const margin = Math.round(((parseInt(psu.wattage) / (tdp * 1.2 || 1)) - 1) * 100);
            psuStatusEl.textContent = `${psu.wattage}W (${margin}% margin)`;
            psuStatusEl.style.color = margin < 10 ? 'var(--danger)' : 'var(--success)';
        } else {
            psuStatusEl.textContent = 'Not selected';
            psuStatusEl.style.color = 'var(--text-muted)';
        }

        if (count > 0) {
            summaryBar.classList.add('visible');
        } else {
            summaryBar.classList.remove('visible');
        }
    }

    // Genetic Algorithm
    async function runGA() {
        if (state.isCalculating) return;
        state.isCalculating = true;
        
        gaOverlay.classList.add('active');
        initGAChart();

        try {
            const response = await fetch('../../index.php?action=getPCRecommendation', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    selected: state.selected,
                    budget: state.budget,
                    usage_profile: state.usageProfile
                })
            });
            
            const result = await response.json();
            animateGA(result);
        } catch (err) {
            console.error('GA failed:', err);
            gaOverlay.classList.remove('active');
        } finally {
            state.isCalculating = false;
        }
    }

    function initGAChart() {
        if (gaChart) gaChart.destroy();
        gaChart = new Chart(gaChartCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Fitness Convergence',
                    data: [],
                    borderColor: '#4f46e5',
                    borderWidth: 3,
                    pointRadius: 0,
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(79, 70, 229, 0.05)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { display: false },
                    y: { 
                        grid: { color: 'rgba(0,0,0,0.05)' }, 
                        ticks: { color: '#64748b', font: { family: 'Inter' } } 
                    }
                },
                plugins: { legend: { display: false } }
            }
        });
    }

    function animateGA(result) {
        const history = result.fitness_history;
        let i = 0;
        const step = Math.ceil(history.length / 50);
        
        const interval = setInterval(() => {
            if (i >= history.length) {
                clearInterval(interval);
                setTimeout(() => {
                    gaOverlay.classList.remove('active');
                    applyRecommendation(result.recommended);
                }, 1000);
                return;
            }
            
            gaChart.data.labels.push(i);
            gaChart.data.datasets[0].data.push(history[i]);
            gaChart.update('none');
            i += step;
        }, 30);
    }

    function applyRecommendation(recommended) {
        // Clear old recommendations
        document.querySelectorAll('.comp-card.recommended').forEach(c => c.classList.remove('recommended'));
        
        Object.keys(recommended).forEach(type => {
            const comp = recommended[type];
            const card = document.getElementById(`comp-${comp.id}`);
            if (card) {
                card.classList.add('recommended');
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }

    async function addAllToCart() {
        const ids = Object.values(state.selected).map(c => c.id);
        if (ids.length === 0) return;

        addToCartBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Ajout en cours...';
        addToCartBtn.disabled = true;

        for (const id of ids) {
            const formData = new FormData();
            formData.append('productID', id);
            formData.append('quantity', 1);
            
            await fetch('../../index.php?action=addToCart', {
                method: 'POST',
                body: formData
            });
        }

        addToCartBtn.textContent = 'Terminé !';
        alert('Tous les composants ont été ajoutés au panier !');
        window.location.href = 'index.html';
    }

    init();
});
