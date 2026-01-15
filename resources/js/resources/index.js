/**
 * Resources Index - JavaScript Module
 */

class ResourcesIndex {
    constructor() {
        this.table = document.getElementById('resources-table');
        this.init();
    }

    init() {
        this.setupFilters();
        this.setupRowInteractions();
        this.setupStatusUpdates();
        this.setupExport();
        this.setupAnimations();
    }

    setupFilters() {
        const categoryFilter = document.getElementById('filter-category');
        const statusFilter = document.getElementById('filter-status');
        const searchFilter = document.getElementById('filter-search');

        if (categoryFilter) {
            categoryFilter.addEventListener('change', () => this.filterResources());
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', () => this.filterResources());
        }

        if (searchFilter) {
            searchFilter.addEventListener('input', () => this.filterResources());
        }
    }

    filterResources() {
        const categoryValue = document.getElementById('filter-category')?.value || '';
        const statusValue = document.getElementById('filter-status')?.value || '';
        const searchValue = document.getElementById('filter-search')?.value.toLowerCase() || '';

        const rows = this.table?.querySelectorAll('tbody tr') || [];

        rows.forEach(row => {
            const matchesCategory = !categoryValue || row.dataset.category === categoryValue;
            const matchesStatus = !statusValue || row.dataset.status === statusValue;
            const matchesSearch = !searchValue || row.textContent.toLowerCase().includes(searchValue);

            row.style.display = matchesCategory && matchesStatus && matchesSearch ? '' : 'none';
        });

        this.updateStats();
    }

    updateStats() {
        // Implémenter la mise à jour des statistiques
        console.log('Stats updated');
    }

    setupRowInteractions() {
        const rows = this.table?.querySelectorAll('tbody tr') || [];

        rows.forEach(row => {
            row.addEventListener('click', (e) => {
                if (!e.target.closest('.resource-actions')) {
                    this.viewResource(row.dataset.id);
                }
            });

            row.addEventListener('mouseenter', () => {
                row.classList.add('row-hover');
            });

            row.addEventListener('mouseleave', () => {
                row.classList.remove('row-hover');
            });
        });
    }

    viewResource(resourceId) {
        window.location.href = `/resources/${resourceId}`;
    }

    setupStatusUpdates() {
        // Implémenter les mises à jour de statut
    }

    setupExport() {
        const exportBtn = document.getElementById('export-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportData());
        }
    }

    exportData() {
        console.log('Exporting resources data...');
        // Implémenter l'export
    }

    setupAnimations() {
        // Animation d'entrée
        const rows = this.table?.querySelectorAll('tbody tr') || [];

        rows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
            row.classList.add('fade-in-up');
        });
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    new ResourcesIndex();
});
