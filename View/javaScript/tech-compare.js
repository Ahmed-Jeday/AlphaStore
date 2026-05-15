/**
 * tech-compare.js
 * Logic for the Intelligent Product Comparator on tech.html
 */

let compareList = JSON.parse(localStorage.getItem('alpha_compare_list')) || [];

document.addEventListener('DOMContentLoaded', () => {
    initCompareEvents();
    renderCompareBar();
});

function initCompareEvents() {
    // Event delegation for compare icons in the grid
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('compare-icon')) {
            const productId = e.target.dataset.id;
            toggleCompare(productId);
        }
    });

    // Modal Compare Button
    const modalCompareBtn = document.getElementById('modalCompareBtn');
    if (modalCompareBtn) {
        modalCompareBtn.addEventListener('click', () => {
            const productId = document.querySelector('#quickViewModal .id').getAttribute('value');
            toggleCompare(productId);
            // Optionally close the quick view
            // document.getElementById('quickViewModal').classList.remove('active');
        });
    }

    // Clear Button
    document.getElementById('compareClearBtn')?.addEventListener('click', () => {
        compareList = [];
        updateStorageAndUI();
    });

    // Compare Now Button
    document.getElementById('compareNowBtn')?.addEventListener('click', () => {
        if (compareList.length === 2) {
            openCompareModal();
        }
    });

    // Modal Close
    document.getElementById('compareModalClose')?.addEventListener('click', () => {
        document.getElementById('compareModal').classList.remove('active');
    });

    // Close modal on overlay click
    document.getElementById('compareModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'compareModal') {
            document.getElementById('compareModal').classList.remove('active');
        }
    });
}

function toggleCompare(productId) {
    const index = compareList.findIndex(id => id == productId);
    
    if (index > -1) {
        // Remove
        compareList.splice(index, 1);
    } else {
        // Add
        if (compareList.length >= 2) {
            // Replace the oldest one (FIFO)
            compareList.shift();
        }
        compareList.push(productId);
    }

    updateStorageAndUI();
}

function updateStorageAndUI() {
    localStorage.setItem('alpha_compare_list', JSON.stringify(compareList));
    renderCompareBar();
    updateCompareIcons();
}

async function renderCompareBar() {
    const bar = document.getElementById('compareBar');
    if (!bar) return;

    if (compareList.length > 0) {
        bar.classList.add('active');
    } else {
        bar.classList.remove('active');
    }

    const slot1 = document.getElementById('compareSlot1');
    const slot2 = document.getElementById('compareSlot2');
    const nowBtn = document.getElementById('compareNowBtn');

    // Reset slots
    slot1.innerHTML = '<span class="compare-bar__empty">+ Produit 1</span>';
    slot2.innerHTML = '<span class="compare-bar__empty">+ Produit 2</span>';

    // Populate slots
    for (let i = 0; i < compareList.length; i++) {
        const productId = compareList[i];
        const productData = await fetchProductSummary(productId);
        if (productData) {
            const slot = (i === 0) ? slot1 : slot2;
            const isExternal = productData.image_path && (productData.image_path.startsWith('http') );
            const imgPath = isExternal ? productData.image_path : `../../public/${productData.image_path}`;
            
            slot.innerHTML = `
                <div class="compare-bar__product">
                    <img src="${imgPath}" alt="${productData.name}">
                    <span title="${productData.name}">${productData.name}</span>
                    <button class="remove-btn" onclick="event.stopPropagation(); toggleCompare('${productId}')">&times;</button>
                </div>
            `;
        }
    }

    // Enable/Disable button
    if (compareList.length === 2) {
        nowBtn.disabled = false;
        nowBtn.classList.add('ready');
    } else {
        nowBtn.disabled = true;
        nowBtn.classList.remove('ready');
    }
}

async function fetchProductSummary(id) {
    try {
        const response = await fetch(`../../index.php?action=getTechProduitById&id=${id}`);
        return await response.json();
    } catch (e) {
        console.error("Error fetching product summary", e);
        return null;
    }
}

function updateCompareIcons() {
    document.querySelectorAll('.compare-icon').forEach(icon => {
        const id = icon.dataset.id;
        if (compareList.includes(id)) {
            icon.classList.add('active');
        } else {
            icon.classList.remove('active');
        }
    });

    // Update modal button state if open
    const modal = document.getElementById('quickViewModal');
    if (modal && modal.classList.contains('active')) {
        const modalId = modal.querySelector('.id').getAttribute('value');
        const modalBtn = document.getElementById('modalCompareBtn');
        if (modalBtn) {
            if (compareList.includes(modalId)) {
                modalBtn.classList.add('active');
                modalBtn.innerHTML = '<i class="fas fa-check"></i> Dans le comparateur';
            } else {
                modalBtn.classList.remove('active');
                modalBtn.innerHTML = '<i class="fas fa-code-compare"></i> Comparer';
            }
        }
    }
}

async function openCompareModal() {
    const modal = document.getElementById('compareModal');
    const cardsContainer = document.getElementById('compareCards');
    const tableHead = document.getElementById('compareTableHead');
    const tableBody = document.getElementById('compareTableBody');

    modal.classList.add('active');
    cardsContainer.innerHTML = '<div class="compare-loading"><i class="fas fa-spinner fa-spin"></i> Chargement...</div>';
    tableHead.innerHTML = '';
    tableBody.innerHTML = '';

    try {
        const [p1, p2] = await Promise.all([
            fetchProductSummary(compareList[0]),
            fetchProductSummary(compareList[1])
        ]);

        renderCompareContent(p1, p2);
    } catch (e) {
        cardsContainer.innerHTML = '<div class="compare-error">Erreur lors du chargement des données.</div>';
    }
}

function renderCompareContent(p1, p2) {
    const cardsContainer = document.getElementById('compareCards');
    const tableHead = document.getElementById('compareTableHead');
    const tableBody = document.getElementById('compareTableBody');

    const isExternal1 = p1.image_path && p1.image_path.startsWith('http');
    const img1 = isExternal1 ? p1.image_path : `../../public/${p1.image_path}`;
    
    const isExternal2 = p2.image_path && p2.image_path.startsWith('http');
    const img2 = isExternal2 ? p2.image_path : `../../public/${p2.image_path}`;

    cardsContainer.innerHTML = `
        <div class="compare-card">
            <img src="${img1}" alt="${p1.name}">
            <h3>${p1.name}</h3>
            <p class="price">${p1.price} DT</p>
        </div>
        <div class="compare-card">
            <img src="${img2}" alt="${p2.name}">
            <h3>${p2.name}</h3>
            <p class="price">${p2.price} DT</p>
        </div>
    `;

    // Comparison rows
    const rows = [
        { label: 'Prix', val1: `${p1.price} DT`, val2: `${p2.price} DT`, raw1: p1.price, raw2: p2.price },
        { label: 'Catégorie', val1: p1.category_name, val2: p2.category_name },
        { label: 'Couleur', val1: p1.color, val2: p2.color },
        { label: 'Stock', val1: p1.stock > 0 ? 'En stock' : 'Rupture', val2: p2.stock > 0 ? 'En stock' : 'Rupture' },
        { label: 'Description', val1: p1.description, val2: p2.description }
    ];

    tableHead.innerHTML = `<th>Caractéristique</th><th>${p1.name}</th><th>${p2.name}</th>`;

    rows.forEach(row => {
        const tr = document.createElement('tr');
        const isDifferent = row.val1 !== row.val2;
        
        if (isDifferent) tr.classList.add('diff');

        tr.innerHTML = `
            <td class="label">${row.label}</td>
            <td class="${isDifferent ? 'val-diff' : ''}">${row.val1 || '-'}</td>
            <td class="${isDifferent ? 'val-diff' : ''}">${row.val2 || '-'}</td>
        `;
        tableBody.appendChild(tr);
    });
}
