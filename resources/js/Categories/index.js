/**
 * Categories Index JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Categories Index JS loaded');

    // Confirmation pour la suppression
    const deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')) {
                e.preventDefault();
            }
        });
    });

    // Animation des lignes
    const tableRows = document.querySelectorAll('.categories-table tbody tr');

    tableRows.forEach((row, index) => {
        // Animation d'apparition
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';

        setTimeout(() => {
            row.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 100);

        // Effet hover
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f1f5f9';
        });

        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });

    // Filtre de recherche (optionnel)
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.placeholder = 'Rechercher une catégorie...';
    searchInput.className = 'category-search';
    searchInput.style.cssText = `
        padding: 10px 15px;
        border: 2px solid #cbd5e1;
        border-radius: 6px;
        margin-bottom: 20px;
        width: 100%;
        max-width: 400px;
        font-size: 16px;
        box-sizing: border-box;
    `;

    // Ajouter la recherche si beaucoup de catégories
    if (tableRows.length > 10) {
        const header = document.querySelector('.categories-header');
        header.appendChild(searchInput);

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            tableRows.forEach(row => {
                const categoryName = row.querySelector('td:nth-child(2) strong').textContent.toLowerCase();
                const categoryId = row.querySelector('td:nth-child(1)').textContent;

                if (categoryName.includes(searchTerm) || categoryId.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
