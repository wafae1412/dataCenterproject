// Resources Edit JavaScript 

class ResourcesEdit {
    constructor() {
        this.resourceId = this.getResourceId();
        this.originalValues = {};
        this.init();
    }

    getResourceId() {
        const path = window.location.pathname.split('/');
        return path[path.length - 2] || '';
    }

    init() {
        this.captureOriginalData();
        this.setupFormValidation();
        this.setupFieldValidation();
        this.setupStatusChangeHandler();
        this.setupSpecificationsCalculator();
        this.setupReservationsWarning();
        this.setupDraftSystem();
        this.setupFormReset();
        this.loadSavedDraft();
    }

    captureOriginalData() {
        const form = document.getElementById('resource-edit-form');
        if (!form) return;

        form.querySelectorAll('input, select, textarea').forEach(field => {
            if (field.name) {
                this.originalValues[field.name] = field.value;
            }
        });
    }

    setupFormValidation() {
        const form = document.getElementById('resource-edit-form');
        if (!form) return;

        form.addEventListener('submit', (e) => {
            if (!this.validateAllFields()) {
                e.preventDefault();
                this.focusFirstInvalidField();
            }
        });
    }

    validateAllFields() {
        let isValid = true;

        isValid = this.validateField('name') && isValid;
        isValid = this.validateField('category_id') && isValid;
        isValid = this.validateField('status') && isValid;
        isValid = this.validateNumericField('cpu') && isValid;
        isValid = this.validateNumericField('ram') && isValid;
        isValid = this.validateNumericField('storage') && isValid;

        return isValid;
    }

    validateField(fieldName) {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (!field) return true;

        const value = field.value.trim();
        this.clearFieldError(field);

        if (!value && field.required) {
            this.markFieldError(field, 'Ce champ est obligatoire');
            return false;
        }

        if (fieldName === 'name' && value.length < 3) {
            this.markFieldError(field, 'Minimum 3 caractères');
            return false;
        }

        return true;
    }

    validateNumericField(fieldName) {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (!field) return true;

        const value = field.value.trim();
        this.clearFieldError(field);

        if (!value && field.required) {
            this.markFieldError(field, 'Ce champ est obligatoire');
            return false;
        }

        const numValue = Number(value);
        if (isNaN(numValue) || numValue <= 0) {
            this.markFieldError(field, 'Valeur numérique positive requise');
            return false;
        }

        const constraints = {
            cpu: { min: 1, max: 128 },
            ram: { min: 1, max: 1024 },
            storage: { min: 1, max: 100000 }
        };

        if (constraints[fieldName]) {
            const { min, max } = constraints[fieldName];

            if (numValue < min) {
                this.markFieldError(field, `Minimum: ${min}`);
                return false;
            }

            if (numValue > max) {
                this.markFieldError(field, `Maximum: ${max}`);
                return false;
            }
        }

        return true;
    }

    markFieldError(field, message) {
        this.clearFieldError(field);

        const errorElement = document.createElement('div');
        errorElement.className = 'field-error-message';
        errorElement.textContent = message;
        errorElement.setAttribute('role', 'alert');

        field.parentNode.appendChild(errorElement);
        field.classList.add('field-has-error');
        field.setAttribute('aria-invalid', 'true');
    }

    clearFieldError(field) {
        const existingError = field.parentNode.querySelector('.field-error-message');
        if (existingError) {
            existingError.remove();
        }
        field.classList.remove('field-has-error');
        field.removeAttribute('aria-invalid');
    }

    focusFirstInvalidField() {
        const firstError = document.querySelector('.field-has-error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }

    setupFieldValidation() {
        // Validation en temps réel
        const fieldsToValidate = ['name', 'cpu', 'ram', 'storage'];

        fieldsToValidate.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.addEventListener('blur', () => {
                    if (fieldName === 'cpu' || fieldName === 'ram' || fieldName === 'storage') {
                        this.validateNumericField(fieldName);
                    } else {
                        this.validateField(fieldName);
                    }
                });
            }
        });
    }

    setupStatusChangeHandler() {
        const statusField = document.querySelector('[name="status"]');
        if (!statusField) return;

        const originalStatus = statusField.value;

        statusField.addEventListener('change', () => {
            const newStatus = statusField.value;

            if (newStatus !== originalStatus) {
                this.handleStatusChange(newStatus, originalStatus);
            }
        });
    }

    handleStatusChange(newStatus, oldStatus) {
        const messages = {
            maintenance: 'Mise en maintenance : les réservations seront annulées.',
            disabled: 'Désactivation : la ressource ne sera plus disponible.',
            available: 'Disponible : la ressource pourra être réservée.',
            reserved: 'Réservé : la ressource sera marquée comme occupée.'
        };

        if (messages[newStatus]) {
            const confirmation = `Changement de statut : ${oldStatus} → ${newStatus}\n\n` +
                               `${messages[newStatus]}\n\n` +
                               `Confirmer ce changement ?`;

            if (!confirm(confirmation)) {
                statusField.value = oldStatus;
            }
        }
    }

    setupSpecificationsCalculator() {
        const cpuField = document.getElementById('cpu');
        const ramField = document.getElementById('ram');
        const storageField = document.getElementById('storage');

        if (!cpuField || !ramField || !storageField) return;

        const updateResourceScore = () => {
            const cpu = parseInt(cpuField.value) || 0;
            const ram = parseInt(ramField.value) || 0;
            const storage = parseInt(storageField.value) || 0;

            // Score basé sur les spécifications
            const score = cpu + (ram / 2) + (storage / 1000);
            this.updateScoreDisplay(score);
        };

        [cpuField, ramField, storageField].forEach(field => {
            field.addEventListener('input', updateResourceScore);
        });

        updateResourceScore();
    }

    updateScoreDisplay(score) {
        let scoreElement = document.getElementById('resource-score');

        if (!scoreElement) {
            scoreElement = document.createElement('div');
            scoreElement.id = 'resource-score';
            scoreElement.className = 'resource-score-display';

            const formContainer = document.querySelector('.form-container');
            if (formContainer) {
                formContainer.appendChild(scoreElement);
            }
        }

        let level = 'low';
        if (score > 100) level = 'high';
        else if (score > 50) level = 'medium';

        scoreElement.innerHTML = `
            <div class="score-header">Score des ressources</div>
            <div class="score-bar">
                <div class="score-progress score-level-${level}"
                     style="width: ${Math.min(score, 100)}%"></div>
            </div>
            <div class="score-info">
                <span class="score-label score-label-${level}">Niveau ${level}</span>
                <span class="score-value">${score.toFixed(1)} points</span>
            </div>
        `;
    }

    setupReservationsWarning() {
        const statusField = document.querySelector('[name="status"]');
        const reservationsElement = document.querySelector('.reservations-stats .stat:first-child .stat-value');

        if (!statusField || !reservationsElement) return;

        statusField.addEventListener('change', () => {
            const activeReservations = parseInt(reservationsElement.textContent) || 0;
            const newStatus = statusField.value;

            if (activeReservations > 0 && (newStatus === 'maintenance' || newStatus === 'disabled')) {
                const warning = `${activeReservations} réservation(s) active(s) seront annulée(s).\n` +
                              `Confirmer le changement de statut ?`;

                if (!confirm(warning)) {
                    statusField.value = statusField.dataset.previousValue || 'available';
                }
            }

            statusField.dataset.previousValue = newStatus;
        });
    }

    setupDraftSystem() {
        const form = document.getElementById('resource-edit-form');
        if (!form) return;

        let saveTimeout;

        form.addEventListener('input', () => {
            clearTimeout(saveTimeout);

            saveTimeout = setTimeout(() => {
                this.saveCurrentDraft();
            }, 1000);
        });

        // Protection contre la perte de données
        window.addEventListener('beforeunload', (e) => {
            if (this.hasUnsavedChanges()) {
                e.preventDefault();
                e.returnValue = 'Modifications non enregistrées détectées. Quitter la page ?';
                this.saveCurrentDraft();
            }
        });
    }

    hasUnsavedChanges() {
        const form = document.getElementById('resource-edit-form');
        if (!form) return false;

        let hasChanges = false;

        form.querySelectorAll('input, select, textarea').forEach(field => {
            if (field.name && field.value !== this.originalValues[field.name]) {
                hasChanges = true;
            }
        });

        return hasChanges;
    }

    saveCurrentDraft() {
        if (!this.resourceId) return;

        const formData = {};
        const form = document.getElementById('resource-edit-form');

        form.querySelectorAll('input, select, textarea').forEach(field => {
            if (field.name) {
                formData[field.name] = field.value;
            }
        });

        localStorage.setItem(`draft_resource_${this.resourceId}`, JSON.stringify(formData));
        this.displayMessage('Progression sauvegardée', 'info');
    }

    loadSavedDraft() {
        if (!this.resourceId) return;

        const savedData = localStorage.getItem(`draft_resource_${this.resourceId}`);
        if (!savedData) return;

        try {
            const draft = JSON.parse(savedData);
            const form = document.getElementById('resource-edit-form');

            Object.keys(draft).forEach(fieldName => {
                const field = form.querySelector(`[name="${fieldName}"]`);
                if (field && field.value === this.originalValues[fieldName]) {
                    field.value = draft[fieldName];
                }
            });

            this.displayMessage('Brouillon restauré', 'info');
            localStorage.removeItem(`draft_resource_${this.resourceId}`);
        } catch (error) {
            console.error('Erreur de chargement du brouillon:', error);
        }
    }

    setupFormReset() {
        const resetButton = document.querySelector('button[type="reset"], .btn.reset');
        if (!resetButton) return;

        resetButton.addEventListener('click', () => {
            if (confirm('Réinitialiser toutes les modifications ?')) {
                // Supprimer les éléments dynamiques
                const scoreElement = document.getElementById('resource-score');
                if (scoreElement) {
                    scoreElement.remove();
                }

                // Nettoyer le stockage local
                localStorage.removeItem(`draft_resource_${this.resourceId}`);

                this.displayMessage('Formulaire réinitialisé', 'info');
            }
        });
    }

    displayMessage(text, type) {
        const existingMessage = document.querySelector('.resource-edit-message');
        if (existingMessage) existingMessage.remove();

        const messageElement = document.createElement('div');
        messageElement.className = `resource-edit-message message-${type}`;
        messageElement.textContent = text;
        messageElement.setAttribute('role', 'status');
        messageElement.setAttribute('aria-live', 'polite');

        document.body.appendChild(messageElement);

        setTimeout(() => {
            messageElement.classList.add('message-fading');
            setTimeout(() => {
                if (messageElement.parentNode) {
                    messageElement.remove();
                }
            }, 300);
        }, 3000);
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    new ResourcesEdit();
});
