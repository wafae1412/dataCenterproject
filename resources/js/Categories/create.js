// Categories Create JavaScript

class CategoriesCreate {
    constructor() {
        this.init();
    }

    init() {
        this.setupFormValidation();
        this.setupRealTimeValidation();
        this.setupAutoSave();
    }

    setupFormValidation() {
        const form = document.getElementById('category-form');
        const nameInput = document.getElementById('name');

        form.addEventListener('submit', (e) => {
            if (!this.validateForm()) {
                e.preventDefault();
            }
        });

        nameInput.addEventListener('input', () => {
            this.validateName();
        });
    }

    validateForm() {
        const nameValid = this.validateName();
        return nameValid;
    }

    validateName() {
        const nameInput = document.getElementById('name');
        const value = nameInput.value.trim();

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

        this.clearError(nameInput);
        return true;
    }

    showError(input, message) {
        this.clearError(input);

        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;

        input.parentNode.appendChild(errorDiv);
        input.classList.add('error');
    }

    clearError(input) {
        const errorDiv = input.parentNode.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.remove();
        }
        input.classList.remove('error');
    }

    setupRealTimeValidation() {
        const nameInput = document.getElementById('name');

        nameInput.addEventListener('blur', () => {
            this.validateName();
        });
    }

    setupAutoSave() {
        // Sauvegarde automatique du brouillon
        const nameInput = document.getElementById('name');

        nameInput.addEventListener('input', () => {
            this.debounce(() => {
                this.saveDraft();
            }, 1000);
        });
    }

    saveDraft() {
        const name = document.getElementById('name').value;

        if (name) {
            localStorage.setItem('category_draft', name);
            console.log('Brouillon sauvegardé');
        }
    }

    debounce(func, delay) {
        let timeout;
        return function() {
            clearTimeout(timeout);
            timeout = setTimeout(func, delay);
        };
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    new CategoriesCreate();

    // Charger le brouillon si existant
    const draft = localStorage.getItem('category_draft');
    if (draft) {
        document.getElementById('name').value = draft;
    }
});
