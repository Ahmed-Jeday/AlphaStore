
// Using event delegation because user_icon is loaded asynchronously
document.addEventListener("click", function (e) {
  const userIcon = e.target.closest("#user_icon");
  if (userIcon) {
    e.preventDefault();
    
      window.location.href = "../html/signUp.php";
      
    
  }
});

// Function to fetch and inject HTML components safely
function loadComponent(id, file) {
  const placeholder = document.getElementById(id);
  // Only attempt to load if the placeholder exists on the current page
  if (placeholder) {
    fetch(file)
      .then(response => {
        if (!response.ok) throw new Error("Could not fetch " + file);
        return response.text();
      })
      .then(data => {
        placeholder.innerHTML = data;
      })
      .catch(error => console.error('Error loading file:', error));
  }
}


// Remplacer les lignes 34-36 par ceci :
let componentPath = 'Component/';
// Si nous sommes dans le dossier my-account, on remonte d'un cran
if (window.location.pathname.includes('/my-account/')) {
    componentPath = '../html/Component/';
}

loadComponent('navbar-placeholder', componentPath + 'navbar.html');
loadComponent('footer-placeholder', componentPath + 'footer.html');



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

