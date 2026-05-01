









<?php 
session_start();
require_once("../../Controller/CartController.php");

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    
    <link rel="stylesheet" href="../css/style_1.css">
    <link rel="stylesheet" href="../css/shop/shop_1.css">
    <link rel="stylesheet" href="../css/component/shop_animation.css">
     <link rel="stylesheet" href="../css/component/footer.css">
     <link rel="stylesheet" href="../css/home.css">
     <style>
        .try-on-btn:hover {
            background-color: #444 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .try-on-btn:active {
            transform: translateY(0);
        }
     </style>
</head>
<body>

    <header >
        <div id="navbar-placeholder"></div>



    </header>
      <div id="chatbot"></div>
    


<section class="hero">
  <div class="head">
    <h2>SHOP</h2>

    <div class="controls">
      <button id="prev" class="nav-btn" aria-label="Prev">‹</button>
      <button id="next" class="nav-btn" aria-label="Next">›</button>
    </div>
  </div>

  <div class="slider">
    <div class="track" id="track">

     
        <article class="project-card" active>
        <img class="project-card__bg" src="../img/young-handsome-man-isolated-dark-background.jpg" alt="Men's premium jacket background" loading="lazy">
        <div class="project-card__content">
          <img class="project-card__thumb" src="../img/still-life-rendering-jackets-display.jpg">
          <div>
            <h3 class="project-card__title">Men’s Jackets</h3>
            <p class="project-card__desc">Bold, rugged & refined — urban outerwear for the modern man.</p>
            <button class="project-card__btn">Shop now →</button>
          </div>
        </div>
      </article>

      <article class="project-card">
        <img class="project-card__bg" src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=800&q=80" alt="Elegant women's dress background" loading="lazy">
        <div class="project-card__content">
          <img class="project-card__thumb" src="../img/young-woman-beautiful-red-dress.jpg" alt="Women's dress thumb">
          <div>
            <h3 class="project-card__title">Silhouette Dresses</h3>
            <p class="project-card__desc">Effortless elegance, timeless cuts for every occasion.</p>
            <button class="project-card__btn">Discover →</button>
          </div>
        </div>
      </article>

        <!-- SLIDE 4 - LUXURY WATCHES (MEN & WOMEN) -->
      <article class="project-card">
        <img class="project-card__bg" src="https://images.unsplash.com/photo-1523170335258-f5ed11844a49?w=800&q=80" alt="Luxury watch background" loading="lazy">
        <div class="project-card__content">
          <img class="project-card__thumb" src="https://images.unsplash.com/photo-1523170335258-f5ed11844a49?w=200&h=200&fit=crop" alt="watch thumb">
          <div>
            <h3 class="project-card__title">Timepieces</h3>
            <p class="project-card__desc">Sophisticated watches for him & her — modern legacy.</p>
            <button class="project-card__btn">View collection →</button>
          </div>
        </div>
      </article>

      <!-- SLIDE 5 - ACTIVEWEAR / PERFORMANCE GEAR -->
      <article class="project-card">
        <img class="project-card__bg" src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&q=80" alt="Activewear performance background" loading="lazy">
        <div class="project-card__content">
          <img class="project-card__thumb" src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=200&h=200&fit=crop" alt="sportswear thumb">
          <div>
            <h3 class="project-card__title">Active DNA</h3>
            <p class="project-card__desc">Breathable, adaptive gear — train without limits.</p>
            <button class="project-card__btn">Shop active →</button>
          </div>
        </div>
      </article>

      <article class="project-card">
        <img class="project-card__bg" src="https://cdn-front.freepik.com/home/anon-rvmp/professionals/art-directors.webp" alt="">
        <div class="project-card__content">
          <img class="project-card__thumb" src="https://cdn-front.freepik.com/home/anon-rvmp/professionals/img-art.webp?w=480" alt="">
          <div>
            <h3 class="project-card__title">Ai </h3>
            <p class="project-card__desc">Ai help you to choose.</p>
            <button class="project-card__btn">Details</button>
          </div>
        </div>
      </article>

    </div>
  </div>

  <div class="dots" id="dots"></div>
</section>



<div class="product-filter-container">
    <!-- FILTER TOP BAR -->
    <div class="filter-top-bar">
        <ul class="category-tabs" id="categoryTabs">
            <li><a href="#" data-cat="all" class="active-tab">All Products</a></li>
            <li><a href="#" data-cat="women">Women</a></li>
            <li><a href="#" data-cat="men">Men</a></li>
            <li><a href="#" data-cat="bag">Bag</a></li>
            <li><a href="#" data-cat="shoes">Shoes</a></li>
            <li><a href="#" data-cat="watches">Watches</a></li>
        </ul>
        <div class="action-buttons">
            <button id="toggle-filter-btn" class="action-btn">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button class="action-btn" id="searchTriggerBtn">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </div>

    <!-- FILTER PANEL (slide-out) -->
    <div id="filter-dropdown-panel" class="filter-panel">
        <div class="filter-column">
            <h4 class="filter-title">Sort By</h4>
            <ul class="filter-list" id="sortList">
                <li><a href="#" data-sort="default">Default</a></li>
                <li><a href="#" data-sort="price_low">Price: Low to High</a></li>
                <li><a href="#" data-sort="price_high">Price: High to Low</a></li>
            </ul>
        </div>
        <div class="filter-column">
            <h4 class="filter-title">Price</h4>
            <ul class="filter-list" id="priceFilterList">
                <li><a href="#" data-price="all" class="active-filter">All</a></li>
                <li><a href="#" data-price="0-50">0.00 DT - 50.00 DT</a></li>
                <li><a href="#" data-price="50-100">50.00 DT - 100.00 DT</a></li>
                <li><a href="#" data-price="100-200">100.00 DT - 200.00 DT</a></li>
            </ul>
        </div>
        <div class="filter-column">
            <h4 class="filter-title">Color</h4>
            <ul class="filter-list">
                <li><a href="#"><span class="color-indicator bg-black"></span> Black</a></li>
                <li><a href="#"><span class="color-indicator bg-blue"></span> Blue</a></li>
            </ul>
        </div>
    </div>

    <!-- PRODUCT GRID -->
    <p class="message" id="noProductMsg"></p>
    <div class="product-grid" id="productGrid"></div>

    <div class="load-more-container">
        <button class="load-more-btn" id="loadMoreBtn">LOAD MORE</button>
    </div>
</div>


 <!-- QUICK VIEW MODAL OVERLAY -->
    <div class="modal-overlay" id="quickViewModal">
        <div class="modal-container">
            <button class="modal-close-btn" id="closeModalBtn">&times;</button>

            <div class="modal-content">
                
                <div class="modal-gallery">
                    <div class="gallery-thumbnails">
                        <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?q=80&w=150&auto=format&fit=crop" alt="Thumb 1" class="active-thumb img1 " >
                        <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?q=80&w=150&auto=format&fit=crop" alt="Thumb 2" class="img2">
                        <img src="https://images.unsplash.com/photo-1543163521-1bf539c55dd2?q=80&w=150&auto=format&fit=crop" alt="Thumb 3" class="img3">
                    </div>
                    
                    <div class="gallery-main">
                        <button class="nav-arrow left-arrow"><i class="fas fa-chevron-left"></i></button>
                        <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?q=80&w=500&auto=format&fit=crop" alt="Main Product Image" class="main-image">
                        <button class="nav-arrow right-arrow"><i class="fas fa-chevron-right"></i></button>
                        <button class="expand-btn" onclick="toggleExpand()"><i class="fas fa-expand"></i></button>
                    </div>
                </div>

                <div class="modal-details">
                    <h2 class="product-title">Lightweight Jacket</h2>
                    <p class="product-price">58.79 DT</p>
                    
                    <p class="product-description">
                        Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.
                    </p>

                    <div class="product-options">
                        <div class="option-row">
                            <label>Size</label>
                            <div class="select-wrapper">
                                <select>
                                    <option>Choose an option</option>
                                    <option>Size S</option>
                                    <option>Size M</option>
                                    <option>Size L</option>
                                    <option>Size XL</option>
                                </select>
                            </div>
                        </div>
                        <div class="option-row">
                            <label>Color</label>
                            <div class="select-wrapper">
                                <select>
                                    <option>Choose an option</option>
                                    <option>Red</option>
                                    <option>Blue</option>
                                    <option>White</option>
                                    <option>Grey</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="add-to-cart-wrapper">
                        <div class="quantity-selector">
                            <button class="qty-btn minus-btn">-</button>
                            <input type="number" class="qty-input" value="1" min="1">
                            <button class="qty-btn plus-btn">+</button>
                        </div>
                        <button class="add-cart-btn" >ADD TO CART</button>
                    </div>

                    <button class="try-on-btn" id="quickViewTryOnBtn" style="margin-top: 15px; width: 100%; height: 45px; background-color: #222; color: white; border: none; font-family: 'Poppins', sans-serif; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.3s ease; border-radius: 4px;">
                        <i class="fas fa-magic"></i> VIRTUAL TRY ON
                    </button>

                    <div class="social-actions">
                        <i class="far fa-heart heart-icon" data-id="${p.id}"></i>
                        <div class="social-links">
                            <a href="#" ><i class="fab fa-facebook-f"></i></a>
                            <a href="#" ><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-google-plus-g"></i></a>
                        </div>
                    </div>


                    <div class="more_info">
                        <div class="id" hidden value="" ></div>

                
                       
                          <a href="#" class="view-more-link">VIEW MORE</a>
                
                       

                        

                    </div>
                </div>

            </div>
        </div>
    </div>

  

    <div id="footer-placeholder"></div>




<script src="../javaScript/index.js"></script>
<script src="../javaScript/main.js"></script>




  <script> 

  //filter
let currentCategory = 'Homme,Femme';
let currentSort = 'default';
let currentPriceRange = 'all';

async function chargerProduits() {
    console.log('Chargement des produits...', {currentCategory, currentSort, currentPriceRange});
    const productGrid = document.getElementById('productGrid');
    
    let url = `../../index.php?action=getProduitByCategory&category=${currentCategory}&sortBy=${currentSort}`;
    
    if (currentPriceRange !== 'all') {
        const [min, max] = currentPriceRange.split('-');
        url += `&minPrice=${min}&maxPrice=${max}`;
    }
   
    try {
        const response = await fetch(url);
        const produits = await response.json();
        console.log('Produits chargés:', produits);

        productGrid.innerHTML = '';

        if (produits.length === 0) {
            productGrid.innerHTML = '<p class="message">No products found matching your criteria.</p>';
            return;
        }

        produits.forEach(product => {
            const card = createProductCard(product);
            productGrid.appendChild(card);
        });

    } catch (error) {
        console.error('Erreur:', error);
        productGrid.innerHTML = '<p class="error">Erreur lors du chargement des produits.</p>';
    }
}

  async function toggleFavorite(productId,heartElement){

            try{

           

            const response = await fetch("../../index.php?action=toggleFavorite",{
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: "product_id=" + productId,
            });
            const data = await response.json();

            if (data.status == 'not_logged_in')
            {
                alert("You need to be logged in to add a product to your favorites");
                heartElement.classList.remove("fas");
                heartElement.classList.add("far");
                return;
            }

            //mise a jour 
            if(data.status === "added"){
                heartElement.classList.remove("far");
                heartElement.classList.add("fas");
                heartElement.classList.add("liked");
            }else{
                heartElement.classList.remove("fas");
                heartElement.classList.add("far");
                heartElement.classList.remove("liked");
            }
        }catch(error){
            console.error("Error toggling favorite:", error);
            alert("An error occurred while toggling the favorite");
         }
  }

document.querySelectorAll('.category-tabs a').forEach(tab => {
    tab.addEventListener('click', function (e) {
        e.preventDefault();

        document.querySelectorAll('.category-tabs a').forEach(t =>
            t.classList.remove('active-tab')
        );

        this.classList.add('active-tab');

        const cat = this.getAttribute('data-cat');
       

        if (cat === 'all') {
            currentCategory = 'Homme,Femme';
        } else if (cat === 'men') {
            currentCategory = 'Homme';
        } else if (cat === 'women') {
            currentCategory = 'Femme';
        }

        chargerProduits();
    });
});

chargerProduits();

function getNextImages(path) {
    const match = path.match(/^(.*_)(\d+)(\.[^.]+)$/);

    if (!match) {
        console.log("Format de chemin invalide");
        return [];
    }

    const prefix = match[1];   // product_images/product1/produit_
    const number = parseInt(match[2]); // 2
    const extension = match[3]; // .jpg

    return [
        `${prefix}${number + 1}${extension}`,
        `${prefix}${number + 2}${extension}`
    ];
}



function createProductCard(p) {
  const continaire = document.getElementById('productGrid');

    const card = document.createElement('div');
    card.classList.add('product-item');
    card.dataset.id = p.id;
    card.dataset.category = p.category;
    
    // Chemin vers les images dans public
    const isExternal = p.image_path && (p.image_path.startsWith('http://') || p.image_path.startsWith('https://'));
    const fullImagePath = isExternal ? p.image_path : `../../public/${p.image_path}`;
   const pathNextimages = getNextImages(p.image_path);
   const fullImagePath1 = fullImagePath;
   const fullImagePath2 = pathNextimages.length > 0 ? (isExternal ? pathNextimages[0] : `../../public/${pathNextimages[0]}`) : fullImagePath;
   const fullImagePath3 = pathNextimages.length > 0 ? (isExternal ? pathNextimages[1] : `../../public/${pathNextimages[1]}`) : fullImagePath;

    card.innerHTML = `
        <div class="product-img-block">
            <img src="${fullImagePath}" alt="${p.name}" onerror="this.src='../img/placeholder.jpg'">
            <div hidden value = "${p.id}" class="product-id"></div>
            <button class="quick-view-btn">Quick View</button>
        </div>
        <div class="product-info-block">
            <div class="product-text">
                <a href="product_details.php?id=${p.id}" class="product-title">${p.name}</a>
                <span class="product-price">${p.price} DT</span>
            </div> 
            <div class="product-actions-icons" style="display: flex; gap: 12px; align-items: center;">
                <i class="far fa-heart heart-icon" data-id="${p.id}"></i>
                <i class="fas fa-shopping-bag add-cart" title="Add to Cart" style="cursor: pointer; color: #aaa; font-size: 1.3rem; transition: color 0.3s;"></i>
            </div>
        </div>
    `;

    // Gestion du clic sur le coeur
    const heart = card.querySelector('.heart-icon');
    if (heart) {
        if (p.is_favorite) {
            heart.classList.remove('far');
            heart.classList.add('fas');
            heart.classList.add('liked');
        }

       heart.addEventListener('click',async function(e){
        e.stopPropagation();
        await toggleFavorite(p.id,this);
        p.is_favorite=!p.is_favorite;
       })
    }

    // Gestion du Quick View
    const quickViewBtn = card.querySelector('.quick-view-btn');
    if (quickViewBtn) {
        quickViewBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const modal = document.getElementById('quickViewModal');
            if (modal) {
                const titleElem = modal.querySelector('.product-title');
                const priceElem = modal.querySelector('.product-price');
                const descElem = modal.querySelector('.product-description');
                const mainImg = modal.querySelector('.main-image');

                const img1 = modal.querySelector('.img1');
                const img2 = modal.querySelector('.img2');
                const img3 = modal.querySelector('.img3');
                const idElem = modal.querySelector('.id');

                if (titleElem) titleElem.textContent = p.name;
                if (priceElem) priceElem.textContent = `${p.price} DT`;
                if (descElem) descElem.textContent = p.description || "Aucune description disponible.";
                if (mainImg) mainImg.src = fullImagePath;
                if (img1) img1.src = fullImagePath1;
                if (img2) img2.src = fullImagePath2;
                if (img3) img3.src = fullImagePath3;
                
                if (idElem) idElem.setAttribute('value', p.id);

                const modalHeart = modal.querySelector('.heart-icon');
                if (modalHeart) {
                    if (p.is_favorite) {
                        modalHeart.classList.replace('far', 'fas');
                        modalHeart.classList.add('liked');
                    } else {
                        modalHeart.classList.replace('fas', 'far');
                        modalHeart.classList.remove('liked');
                    }
                }

                const viewMoreLink = modal.querySelector('.view-more-link');
                if (viewMoreLink) {
                    viewMoreLink.href = `product_details.php?id=${p.id}`;
                }

                const tryOnBtn = modal.querySelector('#quickViewTryOnBtn');
                if (tryOnBtn) {
                    tryOnBtn.onclick = () => {
                        window.location.href = `ai.html?productImage=${encodeURIComponent(fullImagePath)}`;
                    };
                }

                currentImageIndex = 0;
                const thumbnails = modal.querySelectorAll('.gallery-thumbnails img');
                thumbnails.forEach(t => t.classList.remove('active-thumb'));
                if (img1) img1.classList.add('active-thumb');
                
                modal.classList.add('active');
            }
        });
    }

    return card;
}

// Logique de fermeture de la modale
document.addEventListener('DOMContentLoaded', () => {
    const closeModalBtn = document.getElementById('closeModalBtn');
    const modal = document.getElementById('quickViewModal');
    
    if (closeModalBtn && modal) {
        closeModalBtn.addEventListener('click', () => {
            modal.classList.remove('active');
        });

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.remove('active');
            }
        });
    }
    
    const modalHeart = document.querySelector('#quickViewModal .heart-icon');
    if (modalHeart) {
        modalHeart.addEventListener('click', function() {
            const productId = document.querySelector('#quickViewModal .id').getAttribute('value');
            
            // On déclenche le toggle
            toggleFavorite(productId, this).then(() => {
                // Synchronisation : mettre à jour le cœur sur la grille principale aussi
                const gridHeart = document.querySelector(`.product-item[data-id="${productId}"] .heart-icon`);
                if (gridHeart) {
                    if (this.classList.contains('fas')) {
                        gridHeart.classList.replace('far', 'fas');
                        gridHeart.classList.add('liked');
                    } else {
                        gridHeart.classList.replace('fas', 'far');
                        gridHeart.classList.remove('liked');
                    }
                }
            });
        });
    }
    });






let currentImageIndex = 0;
const modalImages = [];
function updateModalGallery(index) {
    const modal = document.getElementById('quickViewModal');
    const mainImg = modal.querySelector('.main-image');
    const thumbnails = modal.querySelectorAll('.gallery-thumbnails img');
    
    if (thumbnails.length > 0) {
        // Reset active class
        thumbnails.forEach(thumb => thumb.classList.remove('active-thumb'));
        
        // Add active class to current thumb
        thumbnails[index].classList.add('active-thumb');
        
        // Update main image source
        mainImg.src = thumbnails[index].src;
        currentImageIndex = index;
    }
}


// navigation quick view
document.addEventListener('DOMContentLoaded', () => {
    const leftArrow = document.querySelector('.left-arrow');
    const rightArrow = document.querySelector('.right-arrow');
    const modal = document.getElementById('quickViewModal');
    
    if (leftArrow && rightArrow) {
        leftArrow.addEventListener('click', () => {
            const thumbnails = modal.querySelectorAll('.gallery-thumbnails img');
            let newIndex = currentImageIndex - 1;
            if (newIndex < 0) newIndex = thumbnails.length - 1;
            updateModalGallery(newIndex);
        });

        rightArrow.addEventListener('click', () => {
            const thumbnails = modal.querySelectorAll('.gallery-thumbnails img');
            let newIndex = (currentImageIndex + 1) % thumbnails.length;
            updateModalGallery(newIndex);
        });
    }

    // Add click events to thumbnails themselves
    modal.addEventListener('click', (e) => {
        if (e.target.tagName === 'IMG' && e.target.parentElement.classList.contains('gallery-thumbnails')) {
            const thumbnails = Array.from(modal.querySelectorAll('.gallery-thumbnails img'));
            const index = thumbnails.indexOf(e.target);
            if (index !== -1) {
                updateModalGallery(index);
            }
        }
    });
});

//zomm quick view
function toggleExpand() {
    const modal = document.getElementById('quickViewModal');
    const mainImg = modal.querySelector('.main-image');
    
    if (!mainImg) return;

    if (mainImg.style.transform === "scale(2.5)") {
        mainImg.style.transform = "scale(1)";
        mainImg.style.cursor = "zoom-in";
    } else {
        mainImg.style.transform = "scale(2.5)";
        mainImg.style.cursor = "zoom-out";
    }
}



// filter logic
document.addEventListener('DOMContentLoaded', () => {
    const filterBtn = document.getElementById('toggle-filter-btn');
    const filterPanel = document.getElementById('filter-dropdown-panel');

    if (filterBtn && filterPanel) {
        filterBtn.addEventListener('click', () => {
            filterPanel.classList.toggle('show-panel');
        });
    }

    // Sort links
    document.querySelectorAll('#sortList a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('#sortList a').forEach(l => l.classList.remove('active-filter'));
            this.classList.add('active-filter');
            currentSort = this.getAttribute('data-sort');
            chargerProduits();
        });
    });

    // Price links
    document.querySelectorAll('#priceFilterList a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('#priceFilterList a').forEach(l => l.classList.remove('active-filter'));
            this.classList.add('active-filter');
            currentPriceRange = this.getAttribute('data-price');
            chargerProduits();
        });
    });
});




</script>
<script src="../javaScript/carts.js"></script>




    
</body>
</html>