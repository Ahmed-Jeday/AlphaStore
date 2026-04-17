// ============================================
// ALPHA STORE - JavaScript
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // NAVBAR SCROLL EFFECT
    // ==========================================
    const navbar = document.getElementById('navbar');
    let lastScroll = 0;

    function handleNavbarScroll() {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 50) {
            navbar.classList.add('bg-charcoal-950/90', 'nav-blur', 'shadow-lg');
            navbar.classList.remove('bg-transparent');
        } else {
            navbar.classList.remove('bg-charcoal-950/90', 'nav-blur', 'shadow-lg');
            navbar.classList.add('bg-transparent');
        }
        
        lastScroll = currentScroll;
    }

    window.addEventListener('scroll', handleNavbarScroll);

    // ==========================================
    // INTERSECTION OBSERVER - REVEAL ANIMATIONS
    // ==========================================
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, observerOptions);

    // Observe all elements with 'reveal' class
    document.querySelectorAll('.reveal').forEach((el) => {
        observer.observe(el);
    });

    // ==========================================
    // SMOOTH SCROLL FOR ANCHOR LINKS
    // ==========================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const target = document.querySelector(targetId);
            
            if (target) {
                const offsetTop = target.offsetTop - 80; // Account for fixed navbar
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ==========================================
    // PARALLAX EFFECT ON HERO IMAGE
    // ==========================================
    const heroImage = document.querySelector('header img');
    
    function handleParallax() {
        if (!heroImage) return;
        
        const scrolled = window.pageYOffset;
        const windowHeight = window.innerHeight;
        
        if (scrolled < windowHeight) {
            heroImage.style.transform = `scale(1.05) translateY(${scrolled * 0.5}px)`;
        }
    }

    // Throttled scroll listener for parallax
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                handleParallax();
                ticking = false;
            });
            ticking = true;
        }
    });

    // ==========================================
    // ADD TO CART BUTTON INTERACTION
    // ==========================================
    const cartButtons = document.querySelectorAll('button');
    
    cartButtons.forEach(btn => {
        if (btn.textContent.includes('Ajouter au panier')) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                
                const originalText = this.textContent;
                
                // Visual feedback
                this.textContent = 'Ajouté !';
                this.classList.add('bg-cognac-700', 'text-white');
                
                // Reset after 2 seconds
                setTimeout(() => {
                    this.textContent = originalText;
                    this.classList.remove('bg-cognac-700', 'text-white');
                }, 2000);
                
                // Update cart counter (optional visual feedback)
                updateCartCounter();
            });
        }
    });

    // ==========================================
    // CART COUNTER UPDATE
    // ==========================================
    let cartCount = 3; // Initial count from HTML
    
    function updateCartCounter() {
        cartCount++;
        const counter = document.querySelector('.absolute.-top-1.-right-1');
        if (counter) {
            counter.textContent = cartCount;
            
            // Animate counter
            counter.style.transform = 'scale(1.3)';
            setTimeout(() => {
                counter.style.transform = 'scale(1)';
            }, 200);
        }
    }

    // ==========================================
    // NEWSLETTER FORM HANDLING
    // ==========================================
    const newsletterForm = document.querySelector('form');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = this.querySelector('input[type="email"]');
            const submitBtn = this.querySelector('button[type="submit"]');
            
            if (emailInput && emailInput.value) {
                // Simulate API call
                const originalText = submitBtn.textContent;
                submitBtn.textContent = '...';
                submitBtn.disabled = true;
                
                setTimeout(() => {
                    alert('Merci pour votre inscription ! Vous recevrez bientôt nos actualités.');
                    emailInput.value = '';
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }, 1000);
            }
        });
    }

    // ==========================================
    // IMAGE LAZY LOADING (Optional enhancement)
    // ==========================================
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    observer.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // ==========================================
    // MOBILE MENU TOGGLE (Basic implementation)
    // ==========================================
    const mobileMenuBtn = document.querySelector('.md\\\\:hidden button');
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            // Toggle mobile menu (can be expanded)
            alert('Menu mobile - À implémenter selon vos besoins');
        });
    }

    // ==========================================
    // SEARCH BUTTON INTERACTION
    // ==========================================
    const searchBtn = document.querySelector('button svg[viewBox="0 0 24 24"]')?.parentElement;
    
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            const searchTerm = prompt('Rechercher un produit:');
            if (searchTerm) {
                console.log('Recherche:', searchTerm);
                // Implement search logic here
            }
        });
    }

    console.log('Alpha Store - JavaScript loaded successfully');
});