document.addEventListener('DOMContentLoaded', function() {
    // ---- FILTER PANEL TOGGLE ----
    const filterBtn = document.getElementById('toggle-filter-btn');
    const filterPanel = document.getElementById('filter-dropdown-panel');

    if (filterBtn && filterPanel) {
        filterBtn.addEventListener('click', function() {
            // Basculer la visibilité du panneau
            filterPanel.classList.toggle('show-panel');
            
            // Basculer l'état actif du bouton
            this.classList.toggle('active-btn');

            // Changer l'icône à l'intérieur du bouton
            const icon = this.querySelector('i');
            if (filterPanel.classList.contains('show-panel')) {
                icon.classList.remove('fa-filter');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-filter');
            }
        });
    }

    // ---- QUICK VIEW MODAL ----
    const quickViewModal = document.getElementById('quickViewModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const quickViewBtns = document.querySelectorAll('.quick-view-btn');

    // Open Modal
    quickViewBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            quickViewModal.classList.add('active');
        });
    });

    // Close Modal
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function() {
            quickViewModal.classList.remove('active');
        });
    }

    // Close Modal when clicking outside
    if (quickViewModal) {
        quickViewModal.addEventListener('click', function(e) {
            if (e.target === quickViewModal) {
                quickViewModal.classList.remove('active');
            }
        });
    }

    // ---- MODAL GALLERY ----
    const thumbs = document.querySelectorAll('.gallery-thumbnails img');
    const mainImg = document.querySelector('.gallery-main .main-image');

    thumbs.forEach(thumb => {
        thumb.addEventListener('click', function() {
            // Update active thumbnail
            thumbs.forEach(t => t.classList.remove('active-thumb'));
            this.classList.add('active-thumb');
            
            // Update main image source if needed
            // mainImg.src = this.src; or big version of src
        });
    });

    // ---- QUANTITY SELECTOR ----
    const minusBtns = document.querySelectorAll('.minus-btn');
    const plusBtns = document.querySelectorAll('.plus-btn');

    minusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.qty-input');
            let val = parseInt(input.value);
            if (val > 1) {
                input.value = val - 1;
            }
        });
    });

    plusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.qty-input');
            let val = parseInt(input.value);
            input.value = val + 1;
        });
    });
});