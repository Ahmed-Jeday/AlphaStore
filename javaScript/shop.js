
document.addEventListener('DOMContentLoaded', function() {
    const filterBtn = document.getElementById('toggle-filter-btn');
    const filterPanel = document.getElementById('filter-dropdown-panel');

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
});