/* ═══════════════════════════════════════════════════
   ALPHASTORE DASHBOARD — index.js
═══════════════════════════════════════════════════ */

(function () {
  'use strict';

  /* ─── Page title map ─── */
  const PAGE_TITLES = {
    overview:      'Overview',
    orders:        'Commandes',
    cart:          'Panier',
    wishlist:      'Favoris',
    addresses:     'Adresses',
    payments:      'Paiements',
    profile:       'Profil',
    notifications: 'Notifications',
    security:      'Sécurité',
    support:       'Support',
  };

  /* ─── Header button labels per section ─── */
  const HEADER_LABELS = {
    overview:      { pri: '+ Nouvelle commande', sec: 'Export' },
    orders:        { pri: '+ Nouvelle commande', sec: 'Export' },
    cart:          { pri: 'Passer commande',     sec: 'Vider le panier' },
    wishlist:      { pri: 'Voir boutique',        sec: '' },
    addresses:     { pri: '+ Ajouter adresse',   sec: '' },
    payments:      { pri: '+ Ajouter carte',     sec: '' },
    profile:       { pri: 'Enregistrer',         sec: '' },
    notifications: { pri: 'Tout lire',           sec: '' },
    security:      { pri: '',                    sec: '' },
    support:       { pri: 'Nouveau ticket',      sec: '' },
  };

  /* ─────────────────────────────────────
     NAVIGATION
  ───────────────────────────────────── */
  const navItems    = document.querySelectorAll('.nav-item[data-section]');
  const sections    = document.querySelectorAll('.section');
  const pageTitle   = document.getElementById('pageTitle');
  const headerBtnPri = document.getElementById('headerBtnPri');
  const headerBtnSec = document.getElementById('headerBtnSec');

  function showSection(name) {
    /* Deactivate all */
    navItems.forEach(btn => btn.classList.remove('active'));
    sections.forEach(sec => sec.classList.remove('active'));

    /* Activate target */
    const targetNav = document.querySelector(`.nav-item[data-section="${name}"]`);
    const targetSec = document.getElementById(`sec-${name}`);

    if (targetNav) targetNav.classList.add('active');
    if (targetSec) targetSec.classList.add('active');

    /* Update header */
    pageTitle.textContent = PAGE_TITLES[name] || name;
    const labels = HEADER_LABELS[name] || { pri: '', sec: '' };

    headerBtnPri.textContent = labels.pri;
    headerBtnPri.style.display = labels.pri ? 'inline-flex' : 'none';

    headerBtnSec.textContent = labels.sec;
    headerBtnSec.style.display = labels.sec ? 'inline-flex' : 'none';

    /* Close mobile sidebar */
    closeMobileSidebar();
  }

  navItems.forEach(btn => {
    btn.addEventListener('click', () => showSection(btn.dataset.section));
  });

  /* "See all" shortcut links inside cards */
  document.querySelectorAll('.link-btn[data-goto]').forEach(btn => {
    btn.addEventListener('click', () => showSection(btn.dataset.goto));
  });

  /* ─────────────────────────────────────
     MOBILE SIDEBAR
  ───────────────────────────────────── */
  const sidebar        = document.querySelector('.sidebar');
  const overlay        = document.getElementById('sidebarOverlay');
  const menuToggle     = document.getElementById('menuToggle');

  function openMobileSidebar()  { sidebar.classList.add('open'); overlay.classList.add('visible'); }
  function closeMobileSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('visible'); }

  if (menuToggle) menuToggle.addEventListener('click', openMobileSidebar);
  if (overlay)    overlay.addEventListener('click', closeMobileSidebar);

  /* ─────────────────────────────────────
     CART — quantity controls & total
  ───────────────────────────────────── */
  function recalcCart() {
    let total = 0;
    document.querySelectorAll('.cart-item').forEach(item => {
      const price = parseInt(item.dataset.price, 10) || 0;
      const qty   = parseInt(item.querySelector('.qty-val').textContent, 10) || 1;
      total += price * qty;

      /* Update per-item displayed price */
      item.querySelector('.cart-price').textContent = (price * qty) + ' DT';
    });
    const totalEl = document.getElementById('cartTotal');
    if (totalEl) totalEl.textContent = total + ' DT';
  }

  document.addEventListener('click', function (e) {
    /* Quantity buttons */
    if (e.target.closest('.qty-btn')) {
      const btn  = e.target.closest('.qty-btn');
      const ctrl = btn.closest('.qty-ctrl');
      const val  = ctrl.querySelector('.qty-val');
      let n = parseInt(val.textContent, 10) || 1;

      if (btn.dataset.action === 'inc') n = n + 1;
      if (btn.dataset.action === 'dec') n = Math.max(1, n - 1);

      val.textContent = n;
      recalcCart();
    }

    /* Delete cart item */
    if (e.target.closest('.del-btn')) {
      const item = e.target.closest('.cart-item');
      if (item) {
        item.style.transition = 'opacity 0.2s';
        item.style.opacity = '0';
        setTimeout(() => { item.remove(); recalcCart(); }, 200);
      }
    }
  });

  /* ─────────────────────────────────────
     NOTIFICATIONS — mark all read
  ───────────────────────────────────── */
  const markAllBtn = document.getElementById('markAllRead');
  if (markAllBtn) {
    markAllBtn.addEventListener('click', () => {
      document.querySelectorAll('.notif-row.unread').forEach(r => r.classList.remove('unread'));
      /* Update badge count */
      const notifBadge = document.querySelector('.nav-item[data-section="notifications"] .nav-badge');
      if (notifBadge) notifBadge.style.display = 'none';
    });
  }

  /* ─────────────────────────────────────
     FAQ — accordion toggle
  ───────────────────────────────────── */
  document.addEventListener('click', function (e) {
    const faqBtn = e.target.closest('.faq-q');
    if (!faqBtn) return;

    const item   = faqBtn.closest('.faq-item');
    const icon   = faqBtn.querySelector('i');
    const isOpen = item.classList.contains('open');

    /* Close all */
    document.querySelectorAll('.faq-item').forEach(fi => {
      fi.classList.remove('open');
      const ic = fi.querySelector('.faq-q i');
      if (ic) ic.className = 'ph ph-caret-down';
    });

    /* Toggle clicked */
    if (!isOpen) {
      item.classList.add('open');
      if (icon) icon.className = 'ph ph-caret-up';
    }
  });

  /* ─────────────────────────────────────
     SECURITY — 2FA toggle
  ───────────────────────────────────── */
  const tfaToggle = document.getElementById('toggleTfa');
  if (tfaToggle) {
    tfaToggle.addEventListener('click', () => {
      tfaToggle.classList.toggle('on');
      const desc = tfaToggle.closest('.security-item')?.querySelector('.security-desc');
      if (desc) {
        desc.textContent = tfaToggle.classList.contains('on')
          ? 'Activée via SMS · +216 55 ••• 456'
          : 'Non activée — cliquez pour activer';
      }
    });
  }

  /* ─────────────────────────────────────
     LOGOUT
  ───────────────────────────────────── */
  const logoutBtn = document.querySelector('.logout-btn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', () => {
      if (confirm('Voulez-vous vraiment vous déconnecter ?')) {
        /* redirect to login page */
        /* window.location.href = '/login'; */
        alert('Déconnexion simulée.');
      }
    });
  }

  /* ─────────────────────────────────────
     INIT
  ───────────────────────────────────── */
  showSection('overview');

})();
