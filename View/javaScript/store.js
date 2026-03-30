// ============================================================
//  BUY NOW – Shared JS
// ============================================================

document.addEventListener('DOMContentLoaded', () => {

  // --- Active nav link ---
  const page = location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-links a').forEach(a => {
    if (a.getAttribute('href') === page) a.classList.add('active');
  });

  // --- Navbar scroll shadow ---
  const navbar = document.querySelector('.navbar');
  window.addEventListener('scroll', () => {
    navbar?.classList.toggle('scrolled', window.scrollY > 40);
  });

  // --- Hamburger (mobile) ---
  const ham = document.querySelector('.hamburger');
  const navLinks = document.querySelector('.nav-links');
  ham?.addEventListener('click', () => {
    navLinks?.classList.toggle('open');
  });

});
