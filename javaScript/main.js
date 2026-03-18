let isLoggedIn = false;

// Using event delegation because user_icon is loaded asynchronously
document.addEventListener("click", function(e) {
  const userIcon = e.target.closest("#user_icon");
  if (userIcon) {
    e.preventDefault();
    if (isLoggedIn) {
      window.location.href = "profile.html";
    } else {
      window.location.href = "signUp.html";
      isLoggedIn = true;
    }
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

// Fetch the navbar and footer relative to the current HTML file
loadComponent('navbar-placeholder', '../Component/navbar.html');
loadComponent('footer-placeholder', '../Component/footer.html');


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
