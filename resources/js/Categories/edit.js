// Categories Edit JavaScript 

class CategoriesEdit {
    constructor() {
        this.categoryId = this.extractCategoryId();
        this.init();
    }

    extractCategoryId() {
        const path = window.location.pathname.split('/');
        return path[2] || '';
    }

    init() {
        this.setupFormValidation();
        this.setupRealTimeValidation();
        this.setupResourceCountUpdate();
        this.setupDeleteConfirmation();
        this.setupAutoSave();
        this.loadDraft();
    }

    setupFormValidation() {
        const form = document.getElementById('category-edit-form');
        if (!form) return;

        form.addEventListener('submit', (e) => {
            if (!this.validateForm()) {
                e.preventDefault();
                this.showFormErrors();
            }
        });

        const nameInput = document.getElementById('name');
        if (nameInput) {
            nameInput.addEventListener('input', () => this.validateName());
        }
    }

    validateForm() {
        return this.validateName();
    }

    validateName() {
        const nameInput = document.getElementById('name');
        if (!nameInput) return true;

        const value = nameInput.value.trim();

        this.clearError(nameInput);

        if (!value) {
            this.showError(nameInput, 'Le nom est requis');
            return false;
        }

        if (value.length < 3) {
            this.showError(nameInput, 'Minimum 3 caractères');
            return false;
        }

        if (value.length > 255) {
            this.showError(nameInput, 'Maximum 255 caractères');
            return false;
        }

        return true;
    }

    showError(input, message) {
        this.clearError(input);

        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;

        input.parentNode.appendChild(errorDiv);
        input.classList.add('field-error');
    }

    clearError(input) {
        const errorDiv = input.parentNode.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.remove();
        }
        input.classList.remove('field-error');
    }

    showFormErrors() {
        const firstError = document.querySelector('.field-error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    setupRealTimeValidation() {
        const nameInput = document.getElementById('name');
        if (nameInput) {
            nameInput.addEventListener('blur', () => this.validateName());
        }
    }

    setupResourceCountUpdate() {
        // Animation pour le compteur de ressources
        const resourceCount = document.querySelector('.resource-count h3');
        const nameInput = document.getElementById('name');

        if (resourceCount && nameInput) {
            nameInput.addEventListener('input', () => {
                resourceCount.classList.add('resource-count-update');
                setTimeout(() => {
                    resourceCount.classList.remove('resource-count-update');
                }, 300);
            });
        }
    }

    setupDeleteConfirmation() {
        const deleteForm = document.querySelector('form[action*="destroy"]');
        if (!deleteForm) return;

        deleteForm.addEventListener('submit', (e) => {
            const resourceCount = document.querySelectorAll('.resources-list li').length;

            if (resourceCount > 0) {
                const confirmMessage = `Cette catégorie contient ${resourceCount} ressources. ` +
                                      `Voulez-vous vraiment la supprimer?`;

                if (!confirm(confirmMessage)) {
                    e.preventDefault();
                }
            }
        });
    }

    setupAutoSave() {
        const nameInput = document.getElementById('name');
        if (!nameInput) return;

        let autoSaveTimeout;

        nameInput.addEventListener('input', () => {
            clearTimeout(autoSaveTimeout);

            autoSaveTimeout = setTimeout(() => {
                this.saveDraft();
            }, 2000);
        });

        // Prévenir avant de quitter
        window.addEventListener('beforeunload', (e) => {
            if (this.hasUnsavedChanges()) {
                e.preventDefault();
                e.returnValue = 'Vous avez des modifications non sauvegardées. Quitter?';
            }
        });
    }

    hasUnsavedChanges() {
        const nameInput = document.getElementById('name');
        if (!nameInput) return false;

        return nameInput.value !== nameInput.defaultValue;
    }

    saveDraft() {
        const name = document.getElementById('name').value;
        if (!name || !this.categoryId) return;

        localStorage.setItem(`category_${this.categoryId}_draft`, name);
        this.showNotification('Modifications sauvegardées', 'success');
    }

    loadDraft() {
        if (!this.categoryId) return;

        const draft = localStorage.getItem(`category_${this.categoryId}_draft`);
        if (!draft) return;

        const nameInput = document.getElementById('name');
        if (nameInput && nameInput.value === nameInput.defaultValue) {
            nameInput.value = draft;
            this.showNotification('Brouillon restauré', 'info');
        }

        localStorage.removeItem(`category_${this.categoryId}_draft`);
    }

    showNotification(message, type) {
        // Supprimer les notifications existantes
        const existing = document.querySelector('.edit-notification');
        if (existing) existing.remove();

        // Créer la notification
        const notification = document.createElement('div');
        notification.className = `edit-notification edit-notification-${type}`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Supprimer après 3 secondes
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.add('edit-notification-hide');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 3000);
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    new CategoriesEdit();
});
