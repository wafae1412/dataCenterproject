// Categories Show JavaScript

class CategoryShow {
    constructor() {
        this.categoryId = this.getCategoryId();
        this.init();
    }

    getCategoryId() {
        // Récupère l'ID de la catégorie depuis l'URL
        const path = window.location.pathname.split('/');
        return path[path.length - 1] || '';
    }

    init() {
        this.setupResourceCards();
        this.setupDeleteConfirmation();
        this.setupResourceFilter();
        this.setupStatistics();
        this.setupExport();
        this.setupRealTimeUpdates();
    }

    setupResourceCards() {
        // Animation des cartes de ressources
        const resourceCards = document.querySelectorAll('.resource-card');

        resourceCards.forEach((card, index) => {
            // Animation d'apparition
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';

            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);

            // Interactions
            card.addEventListener('click', (e) => {
                if (!e.target.closest('.resource-actions')) {
                    this.viewResource(card);
                }
            });

            // Effet hover
            card.addEventListener('mouseenter', () => {
                card.style.zIndex = '10';
            });

            card.addEventListener('mouseleave', () => {
                card.style.zIndex = '';
            });
        });
    }

    viewResource(card) {
        // Récupère le nom de la ressource depuis la carte
        const resourceName = card.querySelector('h3').textContent;
        console.log(`Voir ressource: ${resourceName}`);

        // Ici, tu peux ajouter une logique pour afficher plus de détails
        // ou rediriger vers la page de la ressource
    }

    setupDeleteConfirmation() {
        const deleteForm = document.querySelector('form[action*="destroy"]');
        if (!deleteForm) return;

        deleteForm.addEventListener('submit', (e) => {
            const resourceCount = document.querySelectorAll('.resource-card').length;
            let message = 'Êtes-vous sûr de vouloir supprimer cette catégorie ?\n\n';

            if (resourceCount > 0) {
                message += `Attention: ${resourceCount} ressource(s) seront affectée(s).\n`;
                message += 'Cette action est irréversible.';
            }

            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    }

    setupResourceFilter() {
        // Crée un champ de recherche si beaucoup de ressources
        const resourceCards = document.querySelectorAll('.resource-card');
        if (resourceCards.length < 5) return;

        const searchContainer = document.createElement('div');
        searchContainer.className = 'resource-search-container';
        searchContainer.innerHTML = `
            <input type="text"
                   class="resource-search"
                   placeholder="Rechercher une ressource..."
                   aria-label="Rechercher une ressource">
        `;

        const resourcesSection = document.querySelector('.resources-section');
        if (resourcesSection) {
            resourcesSection.insertBefore(searchContainer, resourcesSection.querySelector('.resources-grid'));

            const searchInput = searchContainer.querySelector('.resource-search');

            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();

                resourceCards.forEach(card => {
                    const resourceName = card.querySelector('h3').textContent.toLowerCase();

                    if (resourceName.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    }

    setupStatistics() {
        // Calcule et affiche des statistiques
        const resourceCards = document.querySelectorAll('.resource-card');
        if (resourceCards.length === 0) return;

        let availableCount = 0;
        let reservedCount = 0;
        let maintenanceCount = 0;

        resourceCards.forEach(card => {
            const statusElement = card.querySelector('.resource-status');
            if (statusElement) {
                const status = statusElement.textContent.toLowerCase().trim();

                if (status.includes('disponible')) availableCount++;
                else if (status.includes('réservé')) reservedCount++;
                else if (status.includes('maintenance')) maintenanceCount++;
            }
        });

        // Crée le conteneur de statistiques
        const statsContainer = document.createElement('div');
        statsContainer.className = 'category-stats';
        statsContainer.innerHTML = `
            <h3>Statistiques des ressources</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-label">Total:</span>
                    <span class="stat-value">${resourceCards.length}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Disponibles:</span>
                    <span class="stat-value">${availableCount}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Réservées:</span>
                    <span class="stat-value">${reservedCount}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Maintenance:</span>
                    <span class="stat-value">${maintenanceCount}</span>
                </div>
            </div>
        `;

        const resourcesSection = document.querySelector('.resources-section');
        if (resourcesSection) {
            resourcesSection.insertBefore(statsContainer, resourcesSection.querySelector('.resources-grid'));
        }
    }

    setupExport() {
        // Bouton d'export des données
        const exportBtn = document.createElement('button');
        exportBtn.textContent = 'Exporter les données';
        exportBtn.className = 'btn export';
        exportBtn.setAttribute('aria-label', 'Exporter les données de la catégorie');

        exportBtn.addEventListener('click', () => {
            this.exportCategoryData();
        });

        const categoryActions = document.querySelector('.category-actions');
        if (categoryActions) {
            categoryActions.insertBefore(exportBtn, categoryActions.firstChild);
        }
    }

    exportCategoryData() {
        const categoryData = {
            category: {
                id: this.categoryId,
                name: document.querySelector('.header-info h1').textContent.trim(),
                resourceCount: document.querySelectorAll('.resource-card').length
            },
            resources: this.collectResourcesData(),
            exportDate: new Date().toISOString()
        };

        const jsonData = JSON.stringify(categoryData, null, 2);
        this.downloadJSON(jsonData, `category-${this.categoryId}.json`);

        this.showNotification('Données exportées avec succès', 'success');
    }

    collectResourcesData() {
        const resources = [];

        document.querySelectorAll('.resource-card').forEach(card => {
            const resource = {
                name: card.querySelector('h3').textContent.trim(),
                cpu: card.querySelector('.spec:nth-child(1) .spec-value').textContent.trim(),
                ram: card.querySelector('.spec:nth-child(2) .spec-value').textContent.trim(),
                storage: card.querySelector('.spec:nth-child(3) .spec-value').textContent.trim(),
                status: card.querySelector('.resource-status').textContent.trim()
            };
            resources.push(resource);
        });

        return resources;
    }

    downloadJSON(data, filename) {
        const blob = new Blob([data], { type: 'application/json' });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');

        link.href = url;
        link.download = filename;
        link.click();

        window.URL.revokeObjectURL(url);
    }

    setupRealTimeUpdates() {
        // Simulation de mises à jour en temps réel
        setInterval(() => {
            this.checkForUpdates();
        }, 30000); // Toutes les 30 secondes
    }

    checkForUpdates() {
        console.log('Vérification des mises à jour...');

        // Ici, tu peux ajouter une logique pour vérifier les mises à jour
        // depuis le serveur ou afficher une notification
    }

    showNotification(message, type) {
        // Supprime les notifications existantes
        const existingNotification = document.querySelector('.category-notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // Crée la nouvelle notification
        const notification = document.createElement('div');
        notification.className = `category-notification notification-${type}`;
        notification.textContent = message;
        notification.setAttribute('role', 'alert');
        notification.setAttribute('aria-live', 'assertive');

        document.body.appendChild(notification);

        // Auto-suppression
        setTimeout(() => {
            notification.classList.add('notification-hiding');

            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 3000);
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    const categoryShow = new CategoryShow();
});
