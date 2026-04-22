        // --- TEXT SPLITTING ---
        // Manually split hero text for animation control
        const words = document.querySelectorAll('.hero h1 .word');
        words.forEach(word => {
            const text = word.innerText;
            word.innerHTML = '';
            text.split('').forEach(char => {
                const span = document.createElement('span');
                span.classList.add('char');
                span.innerText = char;
                word.appendChild(span);
            });
        });

        // --- MAGNET & CURSOR ---
        const cursor = document.getElementById('cursor');
        const magneticElements = document.querySelectorAll('.magnetic');

        let mouseX = 0, mouseY = 0;
        let cursorX = 0, cursorY = 0;

        window.addEventListener('mousemove', e => {
            mouseX = e.clientX;
            mouseY = e.clientY;
        });

        function lerp(start, end, factor) {
            return start + (end - start) * factor;
        }

        function animateCursor() {
            cursorX = lerp(cursorX, mouseX, 0.15);
            cursorY = lerp(cursorY, mouseY, 0.15);
            cursor.style.transform = `translate(${cursorX}px, ${cursorY}px) translate(-50%, -50%)`;
            requestAnimationFrame(animateCursor);
        }
        animateCursor();

        magneticElements.forEach(el => {
            el.addEventListener('mousemove', (e) => {
                const rect = el.getBoundingClientRect();
                const centerX = rect.left + rect.width / 2;
                const centerY = rect.top + rect.height / 2;
                const dist = 0.5; // Magnetic strength

                const moveX = (e.clientX - centerX) * dist;
                const moveY = (e.clientY - centerY) * dist;

                el.style.transform = `translate(${moveX}px, ${moveY}px)`;
                cursor.classList.add('magnet');
            });

            el.addEventListener('mouseleave', () => {
                el.style.transform = 'translate(0, 0)';
                cursor.classList.remove('magnet');
            });
        });

        // --- NAVBAR STATE & 3D TILT ---
        const nav = document.querySelector('.brutal-nav');
        let isScrolled = false;

        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                if (!isScrolled) {
                    nav.classList.add('scrolled');
                    isScrolled = true;
                }
            } else {
                if (isScrolled) {
                    nav.classList.remove('scrolled');
                    nav.style.transform = '';
                    isScrolled = false;
                }
            }
        });

        document.addEventListener('mousemove', (e) => {
            if (!isScrolled) return;
            const cx = window.innerWidth / 2;
            const cy = 100; // Pivot near top

            // Subtle tilt
            const rx = (e.clientY - cy) * 0.02;
            const ry = (e.clientX - cx) * 0.02;

            // Constrain
            const clamp = (num, min, max) => Math.min(Math.max(num, min), max);

            nav.style.transform = `translateX(-50%) perspective(1000px) rotateX(${-clamp(rx, -10, 10)}deg) rotateY(${clamp(ry, -10, 10)}deg)`;
        });

        // --- SCROLL VELOCITY SKEW ---
        const content = document.getElementById('scroll-content');
        let currentScroll = 0;
        let targetScroll = 0;
        let skew = 0;

        // Note: For native scroll skewing, we just monitor scroll speed
        // We aren't hijacking scroll here (keeping it native for UX), just adding effect
        let lastScrollTop = 0;

        function scrollLoop() {
            const scrollTop = window.scrollY;
            const velocity = scrollTop - lastScrollTop;
            lastScrollTop = scrollTop;

            // Smooth skew approach
            // Target skew is based on velocity
            // We clamp it to avoid too much distortion
            const maxSkew = 5.0;
            const speed = Math.min(Math.max(velocity * 0.1, -maxSkew), maxSkew);

            // Lerp current skew to target speed
            skew = lerp(skew, speed, 0.1);

            // round to avoid blurry pixel issues if needed, but smooth is better for transform
            if (Math.abs(skew) > 0.01) {
                content.style.transform = `skewY(${skew}deg)`;
            } else {
                content.style.transform = `skewY(0deg)`;
            }

            requestAnimationFrame(scrollLoop);
        }
        scrollLoop();

        // --- HACKER TEXT RE-INIT ---
        const alpha = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        document.querySelectorAll('[data-text]').forEach(link => {
            link.addEventListener('mouseenter', event => {
                let iter = 0;
                const original = event.target.dataset.text;
                clearInterval(event.target.interval);

                event.target.interval = setInterval(() => {
                    event.target.innerText = original.split("")
                        .map((l, i) => {
                            if (i < iter) return original[i];
                            return alpha[Math.floor(Math.random() * 26)]
                        })
                        .join("");

                    if (iter >= original.length) clearInterval(event.target.interval);
                    iter += 1 / 3;
                }, 30);
            });
            link.addEventListener('mouseleave', e => {
                clearInterval(e.target.interval);
                e.target.innerText = e.target.dataset.text;
            });
        });


//compare

// ---------- CATALOGUE PRODUITS (simulation e-commerce) ----------
const productsCatalog = [
  {
    id: 1,
    name: "Phoenix X5",
    price: "599 €",
    rating: 4.5,
    inStock: true,
    image: "https://picsum.photos/id/133/300/200",
    specs: [
      { key: "Écran", value: "6.1″ OLED 120Hz" },
      { key: "Processeur", value: "A17 Bionic" },
      { key: "Stockage", value: "128 Go" },
      { key: "Batterie", value: "3870 mAh" },
      { key: "Appareil photo", value: "48 MP + 12 MP" },
      { key: "Rechargement", value: "25W filaire" }
    ]
  },
  {
    id: 2,
    name: "Stellar X9 Pro",
    price: "799 €",
    rating: 4.8,
    inStock: true,
    image: "https://picsum.photos/id/1/300/200",
    specs: [
      { key: "Écran", value: "6.7″ AMOLED 144Hz" },
      { key: "Processeur", value: "Snapdragon 8 Gen 3" },
      { key: "Stockage", value: "256 Go" },
      { key: "Batterie", value: "5000 mAh" },
      { key: "Appareil photo", value: "200 MP + 50 MP" },
      { key: "Rechargement", value: "65W + 50W sans fil" }
    ]
  },
  {
    id: 3,
    name: "UltraBook Air M3",
    price: "1 299 €",
    rating: 4.9,
    inStock: true,
    image: "https://picsum.photos/id/20/300/200",
    specs: [
      { key: "Écran", value: "13.6″ Liquid Retina" },
      { key: "Processeur", value: "Apple M3 (8 cœurs)" },
      { key: "RAM", value: "16 Go unifiée" },
      { key: "Stockage", value: "512 Go SSD" },
      { key: "Batterie", value: "Jusqu'à 18h" },
      { key: "Poids", value: "1.24 kg" }
    ]
  },
  {
    id: 4,
    name: "ZenPad Elite",
    price: "449 €",
    rating: 4.3,
    inStock: false,
    image: "https://picsum.photos/id/106/300/200",
    specs: [
      { key: "Écran", value: "11″ IPS 90Hz" },
      { key: "Processeur", value: "MediaTek Helio G99" },
      { key: "Stockage", value: "128 Go" },
      { key: "Batterie", value: "8000 mAh" },
      { key: "Stylet", value: "Inclus" },
      { key: "Rechargement", value: "18W" }
    ]
  },
  {
    id: 5,
    name: "Audiopulse Wave",
    price: "129 €",
    rating: 4.2,
    inStock: true,
    image: "https://picsum.photos/id/21/300/200",
    specs: [
      { key: "Type", value: "Casque Bluetooth" },
      { key: "Autonomie", value: "40h" },
      { key: "Réduction bruit", value: "Active (ANC)" },
      { key: "Connectivité", value: "Bluetooth 5.3" },
      { key: "Poids", value: "250g" }
    ]
  }
];

// Éléments DOM
const selectA = document.getElementById('productA-select');
const selectB = document.getElementById('productB-select');
const swapBtn = document.getElementById('swapBtn');
const cardsContainer = document.getElementById('productCardsContainer');
const comparisonBody = document.getElementById('comparisonBody');

let currentProductA = productsCatalog[0];
let currentProductB = productsCatalog[1];

// Remplir les selects avec tous les produits
function populateSelects() {
  const optionsHtml = productsCatalog.map(prod => `<option value="${prod.id}">${prod.name}</option>`).join('');
  selectA.innerHTML = optionsHtml;
  selectB.innerHTML = optionsHtml;
  selectA.value = currentProductA.id;
  selectB.value = currentProductB.id;
}

// Générer des étoiles
function renderStars(rating) {
  const fullStars = Math.floor(rating);
  const halfStar = (rating % 1) >= 0.5 ? 1 : 0;
  const emptyStars = 5 - fullStars - halfStar;
  let starsHtml = '';
  for (let i = 0; i < fullStars; i++) starsHtml += '<i class="fas fa-star"></i>';
  if (halfStar) starsHtml += '<i class="fas fa-star-half-alt"></i>';
  for (let i = 0; i < emptyStars; i++) starsHtml += '<i class="far fa-star"></i>';
  return `<span class="stars">${starsHtml}</span> <span style="font-size:0.8rem;">(${rating})</span>`;
}

// Mettre à jour les cartes aperçu
function updateCards() {
  const cardHtml = `
    <div class="product-card">
      <div class="card-img">
        <img src="${currentProductA.image}" alt="${currentProductA.name}" loading="lazy">
      </div>
      <div class="card-info">
        <h3>${currentProductA.name}</h3>
        <div class="price-badge">${currentProductA.price}</div>
        <div class="rating">${renderStars(currentProductA.rating)}</div>
        <div class="stock ${currentProductA.inStock ? 'in' : 'out'}">
          <i class="fas ${currentProductA.inStock ? 'fa-check-circle' : 'fa-times-circle'}"></i> 
          ${currentProductA.inStock ? 'En stock' : 'Rupture'}
        </div>
      </div>
    </div>
    <div class="product-card">
      <div class="card-img">
        <img src="${currentProductB.image}" alt="${currentProductB.name}" loading="lazy">
      </div>
      <div class="card-info">
        <h3>${currentProductB.name}</h3>
        <div class="price-badge">${currentProductB.price}</div>
        <div class="rating">${renderStars(currentProductB.rating)}</div>
        <div class="stock ${currentProductB.inStock ? 'in' : 'out'}">
          <i class="fas ${currentProductB.inStock ? 'fa-check-circle' : 'fa-times-circle'}"></i> 
          ${currentProductB.inStock ? 'En stock' : 'Rupture'}
        </div>
      </div>
    </div>
  `;
  cardsContainer.innerHTML = cardHtml;
}

// Récupérer la valeur d'une spec
function getSpecValue(product, key) {
  const spec = product.specs.find(s => s.key === key);
  return spec ? spec.value : null;
}

// Nettoyer le HTML pour comparaison
function stripHtml(str) {
  if (typeof str !== 'string') return '';
  const temp = document.createElement('div');
  temp.innerHTML = str;
  return temp.textContent || temp.innerText || '';
}

// Générer le tableau comparatif
function renderComparisonTable() {
  const baseRows = [
    { key: "💰 Prix", getValue: (p) => p.price },
    { key: "⭐ Note", getValue: (p) => renderStars(p.rating) },
    { 
      key: "📦 Disponibilité", 
      getValue: (p) => p.inStock 
        ? '<span class="badge-spec"><i class="fas fa-check-circle" style="color:#15803d;"></i> En stock</span>' 
        : '<span class="badge-spec" style="background:#ffe4e4;"><i class="fas fa-times-circle"></i> Rupture</span>' 
    }
  ];

  const specKeysSet = new Set();
  currentProductA.specs.forEach(spec => specKeysSet.add(spec.key));
  currentProductB.specs.forEach(spec => specKeysSet.add(spec.key));
  const sortedSpecKeys = Array.from(specKeysSet).sort((a, b) => a.localeCompare(b));

  const allRows = [
    ...baseRows,
    ...sortedSpecKeys.map(key => ({
      key: key,
      getValue: (product) => {
        const val = getSpecValue(product, key);
        return val ? val : '<span class="no-data">— non spécifié</span>';
      }
    }))
  ];

  // Update headers
  const headerA = document.querySelector('.product-col-a');
  const headerB = document.querySelector('.product-col-b');
  if (headerA) headerA.textContent = currentProductA.name;
  if (headerB) headerB.textContent = currentProductB.name;

  let tbodyHtml = '';
  for (let row of allRows) {
    const valA = row.getValue(currentProductA);
    const valB = row.getValue(currentProductB);
    const isDifferent = (stripHtml(valA) !== stripHtml(valB));
    const rowClass = isDifferent ? 'diff-highlight' : '';

    tbodyHtml += `
      <tr class="${rowClass}">
        <th>${row.key}</th>
        

        <td class="spec-value spec-col-a">${valA}</td>
        
        <td class="spec-value spec-col-b">${valB}</td>
      </tr>
    `;
  }
  comparisonBody.innerHTML = tbodyHtml;
}

// Mettre à jour l'interface complète
function refreshComparison() {
  updateCards();
  renderComparisonTable();
}

// Changer les produits selon les selects
function updateFromSelectors() {
  const idA = parseInt(selectA.value, 10);
  const idB = parseInt(selectB.value, 10);
  const newProductA = productsCatalog.find(p => p.id === idA);
  const newProductB = productsCatalog.find(p => p.id === idB);
  if (newProductA && newProductB) {
    currentProductA = newProductA;
    currentProductB = newProductB;
    refreshComparison();
  }
}

// Échanger les produits
function swapProducts() {
  const tempId = selectA.value;
  selectA.value = selectB.value;
  selectB.value = tempId;
  updateFromSelectors();
}

// Initialisation
function init() {
  populateSelects();
  const defaultA = productsCatalog.find(p => p.id === parseInt(selectA.value, 10));
  const defaultB = productsCatalog.find(p => p.id === parseInt(selectB.value, 10));
  if (defaultA && defaultB) {
    currentProductA = defaultA;
    currentProductB = defaultB;
  }
  refreshComparison();

  selectA.addEventListener('change', updateFromSelectors);
  selectB.addEventListener('change', updateFromSelectors);
  swapBtn.addEventListener('click', swapProducts);
}

// Démarrer l'application
init();