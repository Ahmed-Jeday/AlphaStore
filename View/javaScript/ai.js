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

// ---------- CATALOGUE PRODUITS (fetch depuis le backend) ----------
let productsCatalog = [];

// Éléments DOM
const selectA = document.getElementById('productA-select');
const selectB = document.getElementById('productB-select');
const swapBtn = document.getElementById('swapBtn');
const cardsContainer = document.getElementById('productCardsContainer');
const comparisonBody = document.getElementById('comparisonBody');

let currentProductA = null;
let currentProductB = null;

// Charger les produits depuis le backend
async function loadProducts() {
  try {
    // Détermination d’une URL racine stable pour le backend, même si le fichier est servi depuis View/html
    const appRoot = window.location.origin + window.location.pathname.replace(/\/View\/html\/.*$/, '');
    const apiBase = `${appRoot}/index.php`;

    // On récupère les produits tech et les composants PC pour avoir un catalogue riche
    const [techResponse, pcResponse] = await Promise.all([
      fetch(`${apiBase}?action=getTechProduits`),
      fetch(`${apiBase}?action=getPCComponents`)
    ]);

    if (!techResponse.ok || !pcResponse.ok) {
      throw new Error(`HTTP ${techResponse.status} / ${pcResponse.status}`);
    }

    const techProducts = await techResponse.json();
    const pcComponentsGrouped = await pcResponse.json();

    if (techProducts && techProducts.error) {
      throw new Error(`Backend error: ${techProducts.error}`);
    }
    if (pcComponentsGrouped && pcComponentsGrouped.error) {
      throw new Error(`Backend error: ${pcComponentsGrouped.error}`);
    }
    if (!Array.isArray(techProducts)) {
      throw new Error('Réponse invalide pour les produits tech.');
    }
    if (!Array.isArray(pcComponentsGrouped) && typeof pcComponentsGrouped !== 'object') {
      throw new Error('Réponse invalide pour les composants PC.');
    }

    // Aplatir les composants PC (car ils sont groupés par type par le backend Flask)
    let pcComponents = [];
    if (Array.isArray(pcComponentsGrouped)) {
      pcComponents = pcComponentsGrouped;
    } else if (typeof pcComponentsGrouped === 'object') {
      pcComponents = Object.values(pcComponentsGrouped).flat();
    }

    // Normaliser les données pour le comparateur
    const normalizedTech = techProducts.map(p => ({
      id: `tech_${p.id}`,
      dbId: p.id,
      type: 'tech',
      name: p.name,
      price: parseFloat(p.price),
      rating: 4.5, // Par défaut car non présent en DB
      inStock: parseInt(p.stock) > 0,
      image: p.image_path || 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=300&h=200&fit=crop',
      specs: [
        { key: "Catégorie", value: p.category },
        { key: "Couleur", value: p.color },
        { key: "SKU", value: p.sku },
        { key: "Description", value: p.description ? p.description.substring(0, 100) + '...' : 'N/A' }
      ]
    }));

    const normalizedPC = pcComponents.map(p => {
      let parsedSpecs = [];
      try {
        const specsObj = typeof p.specs === 'string' ? JSON.parse(p.specs) : p.specs;
        if (specsObj) {
          parsedSpecs = Object.entries(specsObj).map(([key, value]) => ({
            key: key.charAt(0).toUpperCase() + key.slice(1),
            value: value.toString()
          }));
        }
      } catch (e) {
        console.error("Error parsing specs for", p.name, e);
      }

      // Ajouter des specs de base
      parsedSpecs.unshift(
        { key: "Marque", value: p.brand },
        { key: "Type", value: p.component_type.toUpperCase() }
      );

      return {
        id: `pc_${p.id}`,
        dbId: p.id,
        type: 'pc',
        name: p.name,
        price: parseFloat(p.price),
        rating: (p.performance_score / 20).toFixed(1), // Simuler un rating basé sur le score de perf
        inStock: parseInt(p.stock) > 0,
        image: p.image_url || 'https://images.unsplash.com/photo-1591799264318-7e6ef8e0b9e9?w=300&h=200&fit=crop',
        specs: parsedSpecs
      };
    });

    productsCatalog = [...normalizedTech, ...normalizedPC];
    
    if (productsCatalog.length >= 2) {
      currentProductA = productsCatalog[0];
      currentProductB = productsCatalog[1];
      init();
    } else {
      comparisonBody.innerHTML = '<tr><td colspan="3" style="text-align:center;">Pas assez de produits pour comparer.</td></tr>';
    }
  } catch (error) {
    console.error("Erreur lors du chargement des produits:", error);
    comparisonBody.innerHTML = '<tr><td colspan="3" style="text-align:center; color: #ef4444;">Erreur de connexion au serveur.</td></tr>';
  }
}

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
  const r = parseFloat(rating);
  const fullStars = Math.floor(r);
  const halfStar = (r % 1) >= 0.5 ? 1 : 0;
  const emptyStars = 5 - fullStars - halfStar;
  let starsHtml = '';
  for (let i = 0; i < fullStars; i++) starsHtml += '<i class="fas fa-star"></i>';
  if (halfStar) starsHtml += '<i class="fas fa-star-half-alt"></i>';
  for (let i = 0; i < emptyStars; i++) starsHtml += '<i class="far fa-star"></i>';
  return `<span class="stars">${starsHtml}</span> <span style="font-size:0.8rem;">(${r})</span>`;
}

// Mettre à jour les cartes aperçu
function updateCards() {
  if (!currentProductA || !currentProductB) return;
  
  const cardHtml = `
    <div class="product-card">
      <div class="card-img">
        <img src="${currentProductA.image}" alt="${currentProductA.name}" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=300&h=200&fit=crop'">
      </div>
      <div class="card-info">
        <h3>${currentProductA.name}</h3>
        <div class="price-badge">${currentProductA.price.toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}</div>
        <div class="rating">${renderStars(currentProductA.rating)}</div>
        <div class="stock ${currentProductA.inStock ? 'in' : 'out'}">
          <i class="fas ${currentProductA.inStock ? 'fa-check-circle' : 'fa-times-circle'}"></i> 
          ${currentProductA.inStock ? 'En stock' : 'Rupture'}
        </div>
      </div>
    </div>
    <div class="product-card">
      <div class="card-img">
        <img src="${currentProductB.image}" alt="${currentProductB.name}" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=300&h=200&fit=crop'">
      </div>
      <div class="card-info">
        <h3>${currentProductB.name}</h3>
        <div class="price-badge">${currentProductB.price.toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}</div>
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
  if (typeof str !== 'string') return str ? str.toString() : '';
  const temp = document.createElement('div');
  temp.innerHTML = str;
  return temp.textContent || temp.innerText || '';
}

// Générer le tableau comparatif
function renderComparisonTable() {
  if (!currentProductA || !currentProductB) return;

  const baseRows = [
    { key: "💰 Prix", getValue: (p) => p.price.toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'}) },
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
    
    // Comparaison intelligente pour surlignage
    const strA = stripHtml(valA);
    const strB = stripHtml(valB);
    const isDifferent = (strA !== strB);
    
    let highlightClass = '';
    if (isDifferent) {
        highlightClass = 'diff-highlight';
    }

    tbodyHtml += `
      <tr class="${highlightClass}">
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
  const idA = selectA.value;
  const idB = selectB.value;
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
  refreshComparison();

  selectA.addEventListener('change', updateFromSelectors);
  selectB.addEventListener('change', updateFromSelectors);
  swapBtn.addEventListener('click', swapProducts);

  // Bouton ALPHA AI
  const aiBtn = document.querySelector('.ai-assistant button');
  if (aiBtn) {
    aiBtn.addEventListener('click', () => {
        if (typeof handleSuggestion === 'function') {
            const query = `Compare ${currentProductA.name} and ${currentProductB.name}`;
            handleSuggestion(query);
            // Faire défiler jusqu'au chatbot si nécessaire
            const chatBox = document.querySelector('.ai-widget-root');
            if (chatBox) chatBox.scrollIntoView({ behavior: 'smooth' });
        }
    });
  }
}

// Démarrer l'application au chargement
document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
});
