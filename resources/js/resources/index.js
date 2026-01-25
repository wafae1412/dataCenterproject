
// Gestion des filtres - Version cote serveur uniquement

/**
 * Initialiser le script quand la page est chargee
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script ressources charge');

    // Initialiser les filtres
    initFilters();

    // Initialiser les interactions des lignes
    initRowInteractions();
});

/**
 * Initialiser les fonctionnalites de filtrage
 */
function initFilters() {
    // Recuperer les elements du formulaire
    const filterForm = document.getElementById('filter-form');
    const categoryFilter = document.getElementById('filter-category');
    const statusFilter = document.getElementById('filter-status');
    const searchFilter = document.getElementById('filter-search');

    // Verifier que le formulaire existe
    if (!filterForm) {
        console.error('Formulaire de filtres non trouve');
        return;
    }

    console.log('Form trouve, activation des filtres');

    //  FILTRE CATEGORIE
    // Envoyer automatiquement quand la categorie change
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            console.log('Categorie changee:', this.value);
            filterForm.submit();
        });
    }

    //  FILTRE STATUT
    // Envoyer automatiquement quand le statut change
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            console.log('Statut change:', this.value);
            filterForm.submit();
        });
    }

    //  FILTRE RECHERCHE
    // Attendre que l'utilisateur arrete de taper
    if (searchFilter) {
        let searchTimer;
        searchFilter.addEventListener('input', function() {
            // Annuler le timer precedent
            clearTimeout(searchTimer);

            // Lancer un nouveau timer
            searchTimer = setTimeout(function() {
                console.log('Recherche envoyee:', searchFilter.value);
                filterForm.submit();
            }, 600); // 600ms apres la derniere frappe
        });
    }

    //  EMPECHER LE DOUBLON D'ENVOI
    // Eviter que le bouton "Filtrer" cause un double envoi
    const filterButton = document.getElementById('filter-button');
    if (filterButton) {
        filterButton.addEventListener('click', function(e) {
            // Si les filtres deja envoyes via changement, prevenir
            if (window.filterAlreadySent) {
                e.preventDefault();
            }
            window.filterAlreadySent = true;
            setTimeout(() => {
                window.filterAlreadySent = false;
            }, 1000);
        });
    }
}

/**
 * Initialiser les interactions des lignes du tableau
 */
function initRowInteractions() {
    const table = document.getElementById('resources-table');
    if (!table) return;

    const rows = table.querySelectorAll('tbody tr');

    rows.forEach(function(row) {
        // Clic sur la ligne (sauf sur les boutons d'action)
        row.addEventListener('click', function(event) {
            // Verifier si on a clique sur un bouton d'action
            const clickedButton = event.target.closest('.resource-actions');
            if (!clickedButton) {
                // Rediriger vers la page de detail
                const resourceId = row.dataset.id;
                window.location.href = '/resources/' + resourceId;
            }
        });

        // Effet de survol
        row.addEventListener('mouseenter', function() {
            if (row.classList) {
                row.classList.add('row-hover');
            }
        });

        row.addEventListener('mouseleave', function() {
            if (row.classList) {
                row.classList.remove('row-hover');
            }
        });
    });
}

/**
 * Recalculer et mettre a jour les statistiques
 * Cette fonction est appelee apres un filtrage local si necessaire
 */
function updateStats() {
    // Elements des statistiques
    const totalElement = document.querySelector('.stat-total .stat-number');
    const availableElement = document.querySelector('.stat-available .stat-number');
    const reservedElement = document.querySelector('.stat-reserved .stat-number');

    // Si aucun element de statistiques, sortir
    if (!totalElement) return;

    // Compter les lignes visibles
    const table = document.getElementById('resources-table');
    if (!table) return;

    const visibleRows = table.querySelectorAll('tbody tr[style=""]');
    const totalCount = visibleRows.length;

    // Mettre a jour le total
    totalElement.textContent = totalCount;

    // Calculer les statistiques par statut si les elements existent
    if (availableElement || reservedElement) {
        let availableCount = 0;
        let reservedCount = 0;

        visibleRows.forEach(function(row) {
            const status = row.dataset.status;
            if (status === 'available') availableCount++;
            if (status === 'reserved') reservedCount++;
        });

        // Mettre a jour les compteurs
        if (availableElement) availableElement.textContent = availableCount;
        if (reservedElement) reservedElement.textContent = reservedCount;
    }
}

/**
 * Filtrer localement (optionnel - desactive par defaut)
 * Cette fonction peut etre activee si on veut un filtrage rapide sans rechargement
 */
function filterLocal() {
    // Recuperer les valeurs des filtres
    const categoryValue = document.getElementById('filter-category')?.value || '';
    const statusValue = document.getElementById('filter-status')?.value || '';
    const searchValue = document.getElementById('filter-search')?.value.toLowerCase() || '';

    // Recuperer toutes les lignes du tableau
    const table = document.getElementById('resources-table');
    if (!table) return;

    const rows = table.querySelectorAll('tbody tr');

    // Parcourir chaque ligne
    rows.forEach(function(row) {
        // Donnees de la ligne
        const rowCategory = row.dataset.category;
        const rowStatus = row.dataset.status;
        const rowText = row.textContent.toLowerCase();

        // Verifier chaque condition de filtrage
        const matchCategory = !categoryValue || rowCategory === categoryValue;
        const matchStatus = !statusValue || rowStatus === statusValue;
        const matchSearch = !searchValue || rowText.includes(searchValue);

        // Afficher ou cacher la ligne
        const isVisible = matchCategory && matchStatus && matchSearch;
        row.style.display = isVisible ? '' : 'none';
    });

    // Mettre a jour les statistiques
    updateStats();
}

/**
 * Ajouter un indicateur visuel pendant le chargement
 */
function showLoadingIndicator() {
    const filterButton = document.getElementById('filter-button');
    if (filterButton) {
        const originalText = filterButton.textContent;
        filterButton.textContent = 'Chargement...';
        filterButton.disabled = true;

        // Retablir apres 2 secondes au cas ou
        setTimeout(function() {
            filterButton.textContent = originalText;
            filterButton.disabled = false;
        }, 2000);
    }
}

/**
 * Gerer le bouton Reset
 */
function initResetButton() {
    const resetButton = document.querySelector('a[href*="resources.index"]');
    if (resetButton && resetButton.textContent.includes('Reset')) {
        resetButton.addEventListener('click', function(e) {
            // Montrer indicateur de chargement
            showLoadingIndicator();

            // La redirection se fera automatiquement via le lien
            console.log('Reset des filtres');
        });
    }
}

// Initialiser le bouton Reset
document.addEventListener('DOMContentLoaded', function() {
    initResetButton();
});
