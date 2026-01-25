// Resources Show JavaScript - Pur sans CSS interne

class ResourceShow {
    constructor() {
        this.resourceId = this.getResourceId();
        this.statusCheckInterval = null;
        this.notificationTimeout = null;
        this.init();
    }

    getResourceId() {
        const path = window.location.pathname.split('/');
        return path[path.length - 1] || '';
    }

    init() {
        this.setupStatusMonitoring();
        this.setupReservationActions();
        this.setupMaintenanceActions();
        this.setupSpecsVisualization();
        this.setupDeleteConfirmation();
        this.setupRealTimeUpdates();
        this.setupDataExport();
        this.setupResourceInteractions();
    }

    setupStatusMonitoring() {
        const statusBadge = document.querySelector('.status-badge');
        if (!statusBadge) return;

        // Surveillance périodique
        this.statusCheckInterval = setInterval(() => {
            this.checkResourceAvailability();
        }, 30000);

        // Clic sur le badge de statut
        statusBadge.addEventListener('click', (e) => {
            e.preventDefault();
            this.showStatusHistory();
        });

        // Ajouter l'attribut pour l'accessibilité
        statusBadge.setAttribute('role', 'button');
        statusBadge.setAttribute('aria-label', 'Voir l\'historique du statut');
    }

    checkResourceAvailability() {
        console.log('Vérification de la disponibilité de la ressource...');

        // Simulation de vérification
        const statusBadge = document.querySelector('.status-badge');
        if (statusBadge) {
            statusBadge.classList.add('status-checking');
            setTimeout(() => {
                statusBadge.classList.remove('status-checking');
            }, 1000);
        }
    }

    showStatusHistory() {
        // Créer le modal d'historique
        const modal = document.createElement('div');
        modal.className = 'status-history-modal';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-labelledby', 'status-history-title');

        modal.innerHTML = `
            <div class="status-history-content">
                <h3 id="status-history-title">Historique des Statuts</h3>
                <div class="status-history-list">
                    <div class="history-item">
                        <span class="history-time">Aujourd'hui, 10:30</span>
                        <span class="history-status status-available">Disponible</span>
                    </div>
                    <div class="history-item">
                        <span class="history-time">Hier, 15:45</span>
                        <span class="history-status status-reserved">Réservé</span>
                    </div>
                    <div class="history-item">
                        <span class="history-time">05/03/2024</span>
                        <span class="history-status status-maintenance">Maintenance</span>
                    </div>
                </div>
                <button class="btn btn-close-status-history" aria-label="Fermer">Fermer</button>
            </div>
        `;

        document.body.appendChild(modal);

        // Fermer le modal
        const closeBtn = modal.querySelector('.btn-close-status-history');
        closeBtn.addEventListener('click', () => {
            document.body.removeChild(modal);
        });

        // Fermer en cliquant en dehors
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                document.body.removeChild(modal);
            }
        });

        // Fermer avec la touche Échap
        document.addEventListener('keydown', function closeOnEscape(e) {
            if (e.key === 'Escape') {
                document.body.removeChild(modal);
                document.removeEventListener('keydown', closeOnEscape);
            }
        });
    }

    setupReservationActions() {
        // Bouton de réservation
        const reserveBtn = document.querySelector('.btn.reserve');
        if (reserveBtn) {
            reserveBtn.addEventListener('click', (e) => {
                const currentStatus = this.getCurrentStatus();

                if (currentStatus === 'maintenance') {
                    e.preventDefault();
                    this.showAlert('Cette ressource est en maintenance et ne peut pas être réservée.');
                    return;
                }

                if (currentStatus === 'reserved') {
                    e.preventDefault();
                    this.showAlert('Cette ressource est déjà réservée.');
                    return;
                }
            });
        }

        // Actions sur les réservations existantes
        document.querySelectorAll('.reservation-actions .btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (btn.classList.contains('view-reservation')) {
                    e.preventDefault();
                    const reservationRow = btn.closest('tr');
                    this.viewReservationDetails(reservationRow);
                }
            });
        });
    }

    getCurrentStatus() {
        const statusBadge = document.querySelector('.status-badge');
        return statusBadge ? statusBadge.textContent.toLowerCase().trim() : '';
    }

    viewReservationDetails(row) {
        const user = row.cells[0].textContent;
        const startDate = row.cells[1].textContent;
        const endDate = row.cells[2].textContent;
        const status = row.cells[3].textContent;

        const details = `Réservation par: ${user}\n` +
                       `Période: ${startDate} - ${endDate}\n` +
                       `Statut: ${status}`;

        alert(details);
    }

    setupMaintenanceActions() {
        const maintenanceBtn = document.querySelector('a[href*="maintenance/create"]');
        if (maintenanceBtn) {
            maintenanceBtn.addEventListener('click', (e) => {
                const currentStatus = this.getCurrentStatus();

                if (currentStatus === 'reserved') {
                    const confirmMsg = 'Cette ressource est actuellement réservée.\n' +
                                     'Voulez-vous quand même planifier une maintenance?';

                    if (!confirm(confirmMsg)) {
                        e.preventDefault();
                    }
                }
            });
        }
    }

    setupSpecsVisualization() {
        const specsCard = document.querySelector('.specs-card');
        if (!specsCard) return;

        // Vérifier si on a des données à visualiser
        const hasSpecs = document.querySelectorAll('.spec-item').length > 0;
        if (!hasSpecs) return;

        // Créer le bouton de visualisation
        const visualizeBtn = document.createElement('button');
        visualizeBtn.textContent = 'Visualiser les spécifications';
        visualizeBtn.className = 'btn btn-specs-visualization';
        visualizeBtn.setAttribute('aria-label', 'Visualiser le graphique des spécifications');

        visualizeBtn.addEventListener('click', () => {
            this.showSpecsChart();
        });

        specsCard.appendChild(visualizeBtn);
    }

    showSpecsChart() {
        // Récupérer les valeurs des spécifications
        const cpu = this.extractNumberFromElement('.spec-item:nth-child(1) .spec-value');
        const ram = this.extractNumberFromElement('.spec-item:nth-child(2) .spec-value');
        const storage = this.extractNumberFromElement('.spec-item:nth-child(3) .spec-value');

        // Créer le modal du graphique
        const modal = document.createElement('div');
        modal.className = 'specs-chart-modal';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-labelledby', 'specs-chart-title');

        modal.innerHTML = this.generateSpecsChartHTML(cpu, ram, storage);

        document.body.appendChild(modal);

        // Configurer les interactions
        this.setupChartModalInteractions(modal);

        // Animer les barres
        this.animateChartBars(modal);
    }

    extractNumberFromElement(selector) {
        const element = document.querySelector(selector);
        if (!element) return 0;

        const text = element.textContent;
        const numberMatch = text.match(/\d+/);
        return numberMatch ? parseInt(numberMatch[0]) : 0;
    }

    generateSpecsChartHTML(cpu, ram, storage) {
        const cpuPercent = Math.min(cpu * 10, 100);
        const ramPercent = Math.min(ram * 2, 100);
        const storagePercent = Math.min(storage / 1000, 100);

        return `
            <div class="specs-chart-content">
                <h3 id="specs-chart-title">Graphique des Spécifications</h3>
                <div class="chart-container">
                    <div class="chart-item" role="presentation">
                        <div class="chart-label">CPU</div>
                        <div class="chart-bar" aria-label="CPU: ${cpu} cores">
                            <div class="chart-fill chart-fill-cpu"
                                 style="width: ${cpuPercent}%"
                                 role="progressbar"
                                 aria-valuenow="${cpu}"
                                 aria-valuemin="0"
                                 aria-valuemax="128">
                            </div>
                        </div>
                        <div class="chart-value">${cpu} cores</div>
                    </div>
                    <div class="chart-item" role="presentation">
                        <div class="chart-label">RAM</div>
                        <div class="chart-bar" aria-label="RAM: ${ram} GB">
                            <div class="chart-fill chart-fill-ram"
                                 style="width: ${ramPercent}%"
                                 role="progressbar"
                                 aria-valuenow="${ram}"
                                 aria-valuemin="0"
                                 aria-valuemax="1024">
                            </div>
                        </div>
                        <div class="chart-value">${ram} GB</div>
                    </div>
                    <div class="chart-item" role="presentation">
                        <div class="chart-label">Stockage</div>
                        <div class="chart-bar" aria-label="Stockage: ${storage} GB">
                            <div class="chart-fill chart-fill-storage"
                                 style="width: ${storagePercent}%"
                                 role="progressbar"
                                 aria-valuenow="${storage}"
                                 aria-valuemin="0"
                                 aria-valuemax="100000">
                            </div>
                        </div>
                        <div class="chart-value">${storage} GB</div>
                    </div>
                </div>
                <button class="btn btn-close-specs-chart" aria-label="Fermer le graphique">Fermer</button>
            </div>
        `;
    }

    setupChartModalInteractions(modal) {
        const closeBtn = modal.querySelector('.btn-close-specs-chart');
        closeBtn.addEventListener('click', () => {
            document.body.removeChild(modal);
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                document.body.removeChild(modal);
            }
        });

        document.addEventListener('keydown', function closeChartOnEscape(e) {
            if (e.key === 'Escape') {
                document.body.removeChild(modal);
                document.removeEventListener('keydown', closeChartOnEscape);
            }
        });
    }

    animateChartBars(modal) {
        setTimeout(() => {
            const fills = modal.querySelectorAll('.chart-fill');
            fills.forEach(fill => {
                const originalWidth = fill.style.width;
                fill.style.width = '0%';

                setTimeout(() => {
                    fill.style.width = originalWidth;
                }, 100);
            });
        }, 100);
    }

    setupDeleteConfirmation() {
        const deleteForm = document.querySelector('form[action*="destroy"]');
        if (!deleteForm) return;

        deleteForm.addEventListener('submit', (e) => {
            const reservationsCount = document.querySelectorAll('.reservations-table tbody tr').length;
            const maintenancesCount = document.querySelectorAll('.maintenance-item').length;

            let confirmationMessage = 'Êtes-vous sûr de vouloir supprimer cette ressource ?\n\n';

            if (reservationsCount > 0) {
                confirmationMessage += ` ${reservationsCount} réservation(s) seront annulée(s).\n`;
            }

            if (maintenancesCount > 0) {
                confirmationMessage += `  ${maintenancesCount} entrée(s) de maintenance seront supprimée(s).\n`;
            }

            confirmationMessage += '\nCette action est irréversible.';

            if (!confirm(confirmationMessage)) {
                e.preventDefault();
            }
        });
    }

    setupRealTimeUpdates() {
        // Simuler des mises à jour en temps réel
        setInterval(() => {
            this.simulateRealTimeUpdate();
        }, 60000);
    }

    simulateRealTimeUpdate() {
        console.log('Mise à jour des données en temps réel...');

        // Simuler une notification
        const now = new Date();
        const timeString = now.toLocaleTimeString('fr-FR', {
            hour: '2-digit',
            minute: '2-digit'
        });

        this.showNotification(`Dernière mise à jour: ${timeString}`, 'info');
    }

    setupDataExport() {
        const exportBtn = document.createElement('button');
        exportBtn.textContent = 'Exporter les données';
        exportBtn.className = 'btn btn-export-data';
        exportBtn.setAttribute('aria-label', 'Exporter les données de la ressource');

        exportBtn.addEventListener('click', () => {
            this.exportResourceData();
        });

        const resourceActions = document.querySelector('.resource-actions');
        if (resourceActions) {
            resourceActions.insertBefore(exportBtn, resourceActions.firstChild);
        }
    }

    exportResourceData() {
        const resourceData = {
            resource: this.collectResourceInfo(),
            specifications: this.collectSpecifications(),
            reservations: this.collectReservations(),
            maintenances: this.collectMaintenances(),
            exportDate: new Date().toISOString()
        };

        const jsonData = JSON.stringify(resourceData, null, 2);
        this.downloadJSON(jsonData, `resource-${this.resourceId}.json`);

        this.showNotification('Données exportées avec succès', 'success');
    }

    collectResourceInfo() {
        return {
            id: this.resourceId,
            name: document.querySelector('.header-info h1').textContent.trim(),
            category: document.querySelector('.category-badge')?.textContent.trim() || '',
            status: document.querySelector('.status-badge')?.textContent.trim() || '',
            location: document.querySelector('.spec-item:nth-child(4) .spec-value')?.textContent.trim() || ''
        };
    }

    collectSpecifications() {
        const specs = {};

        document.querySelectorAll('.spec-item').forEach(item => {
            const label = item.querySelector('.spec-label').textContent.replace(':', '').trim();
            const value = item.querySelector('.spec-value').textContent.trim();
            specs[label] = value;
        });

        return specs;
    }

    collectReservations() {
        const reservations = [];

        document.querySelectorAll('.reservations-table tbody tr').forEach(row => {
            reservations.push({
                user: row.cells[0].textContent.trim(),
                startDate: row.cells[1].textContent.trim(),
                endDate: row.cells[2].textContent.trim(),
                status: row.cells[3].textContent.trim()
            });
        });

        return reservations;
    }

    collectMaintenances() {
        const maintenances = [];

        document.querySelectorAll('.maintenance-item').forEach(item => {
            maintenances.push({
                date: item.querySelector('.maintenance-date').textContent.trim(),
                description: item.querySelector('.maintenance-desc').textContent.trim()
            });
        });

        return maintenances;
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

    setupResourceInteractions() {
        // Interactions avec les cartes de spécifications
        const specItems = document.querySelectorAll('.spec-item');
        specItems.forEach(item => {
            item.addEventListener('click', () => {
                item.classList.add('spec-item-active');
                setTimeout(() => {
                    item.classList.remove('spec-item-active');
                }, 300);
            });
        });

        // Double-clic sur le nom pour édition rapide
        const resourceName = document.querySelector('.header-info h1');
        if (resourceName) {
            resourceName.addEventListener('dblclick', () => {
                const editUrl = document.querySelector('.btn.edit')?.href;
                if (editUrl) {
                    window.location.href = editUrl;
                }
            });
        }
    }

    showAlert(message) {
        alert(message);
    }

    showNotification(message, type) {
        // Supprimer les notifications existantes
        const existingNotification = document.querySelector('.resource-notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // Créer la nouvelle notification
        const notification = document.createElement('div');
        notification.className = `resource-notification notification-${type}`;
        notification.textContent = message;
        notification.setAttribute('role', 'alert');
        notification.setAttribute('aria-live', 'assertive');

        document.body.appendChild(notification);

        // Auto-suppression après 3 secondes
        this.notificationTimeout = setTimeout(() => {
            notification.classList.add('notification-hiding');

            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 3000);
    }

    // Nettoyage
    destroy() {
        if (this.statusCheckInterval) {
            clearInterval(this.statusCheckInterval);
        }

        if (this.notificationTimeout) {
            clearTimeout(this.notificationTimeout);
        }
    }
}

// Initialisation et nettoyage
document.addEventListener('DOMContentLoaded', () => {
    const resourceShow = new ResourceShow();

    // Nettoyer à la fermeture de la page
    window.addEventListener('beforeunload', () => {
        resourceShow.destroy();
    });
});
