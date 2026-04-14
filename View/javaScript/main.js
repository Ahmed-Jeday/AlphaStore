
  window.addEventListener('scroll', function() {
            const header = document.getElementById('site-header');
            
            // On déclenche l'effet après 50px de scroll
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

// Function to fetch and inject HTML components safely
function loadComponent(id, file) {
  const placeholder = document.getElementById(id);
  if (placeholder) {
    fetch(file)
      .then(response => {
        if (!response.ok) throw new Error("Could not fetch " + file);
        return response.text();
      })
      .then(data => {
        placeholder.innerHTML = data;

        // After loading the navbar, highlight the active tab and attach the scroll effect
        if (id === 'navbar-placeholder') {
          highlightActiveTabs();
          initNavbarScrollEffect();
        }
      })
      .catch(error => console.error('Error loading file:', error));
  }
}

function highlightActiveTabs() {
  const currentPath = window.location.pathname.split('/').pop() || 'index.html';
  const tabs = document.querySelectorAll(".tab");

  tabs.forEach(tab => {
    const tabPath = tab.getAttribute('href').split('/').pop();
    if (tabPath === currentPath) {
      tab.classList.add("active");
    } else {
      tab.classList.remove("active");
    }
  });
}

function initNavbarScrollEffect() {
  const header = document.querySelector('.site-header');
  if (!header) return;

  const updateHeaderState = () => {
    header.classList.toggle('scrolled', window.scrollY > 50);
  };

  window.addEventListener('scroll', updateHeaderState);
  updateHeaderState();
}

// Remplacer les lignes 34-36 par ceci :
let componentPath = 'Component/';
// Si nous sommes dans le dossier my-account, on remonte d'un cran
if (window.location.pathname.includes('/my-account/')) {
  componentPath = '../html/Component/';
}

loadComponent('navbar-placeholder', componentPath + 'navbar.php');
loadComponent('footer-placeholder', componentPath + 'footer.html');
loadComponent('scrollTop', componentPath + 'top.html');


//scroll top
const topBtn = document.getElementById("scrollTop");

// Surveiller le défilement
window.onscroll = function () {
  if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
    // Si on a scrollé plus de 300px
    topBtn.classList.add("show");
  } else {
    // Sinon on cache
    topBtn.classList.remove("show");
  }
};

// Action au clic
topBtn.onclick = function () {
  window.scrollTo({
    top: 0,
    behavior: 'smooth' // Scroll fluide
  });
};


//Search bar 
document.addEventListener('click', (e) => {
  const searchBtn = e.target.closest('#search-btn');
  if (searchBtn) {
    const searchBar = document.querySelector('.search-form');
    if (searchBar) {
      searchBtn.classList.toggle('fa-times');
      searchBar.classList.toggle('active');
    }
  }
});

window.addEventListener('scroll', () => {
  const searchBtn = document.querySelector('#search-btn');
  const searchBar = document.querySelector('.search-form');
  if (searchBtn) searchBtn.classList.remove('fa-times');
  if (searchBar) searchBar.classList.remove('active');
});

//


//heart-icon
const items = document.querySelectorAll('.heart-icon');
const msg = document.querySelector(".mesage")

items.forEach(item => {
  item.addEventListener('click', () => {
    item.classList.toggle('active');
    message.textContent = "Ajouté !";

    setTimeout(() => {
      message.textContent = "";
    }, 2000);


  });
});

