// Resources Create JavaScript

class ResourcesCreate {
    constructor() {
        this.init();
    }

    init() {
        this.setupFormValidation();
        this.setupSpecsPreview();
        this.setupAutoCalculate();
        this.setupFormReset();
    }

    setupFormValidation() {
        const form = document.getElementById('resource-form');

        form.addEventListener('submit', (e) => {
            if (!this.validateForm()) {
                e.preventDefault();
                this.showFormErrors();
            }
        });

        // Validation en temps réel
        ['name', 'cpu', 'ram', 'storage'].forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                input.addEventListener('blur', () => this.validateField(field));
            }
        });
    }

    validateForm() {
        let isValid = true;

        // Valider chaque champ
        isValid = this.validateField('name') && isValid;
        isValid = this.validateField('category_id') && isValid;
        isValid = this.validateField('cpu') && isValid;
        isValid = this.validateField('ram') && isValid;
        isValid = this.validateField('storage') && isValid;

        return isValid;
    }

    validateField(fieldName) {
        const input = document.getElementById(fieldName);
        const value = input.value.trim();

        this.clearError(input);

        switch(fieldName) {
            case 'name':
                if (!value) {
                    this.showError(input, 'Le nom est requis');
                    return false;
                }
                if (value.length < 3) {
                    this.showError(input, 'Minimum 3 caractères');
                    return false;
                }
                break;

            case 'category_id':
                if (!value) {
                    this.showError(input, 'La catégorie est requise');
                    return false;
                }
                break;

            case 'cpu':
            case 'ram':
            case 'storage':
                if (!value) {
                    this.showError(input, 'Ce champ est requis');
                    return false;
                }
                const numValue = parseInt(value);
                if (isNaN(numValue) || numValue <= 0) {
                    this.showError(input, 'Doit être un nombre positif');
                    return false;
                }
                break;
        }

        return true;
    }

    showError(input, message) {
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

    showFormErrors() {
        // Scroll vers la première erreur
        const firstError = document.querySelector('.error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    setupSpecsPreview() {
        const cpuInput = document.getElementById('cpu');
        const ramInput = document.getElementById('ram');
        const storageInput = document.getElementById('storage');

        const updatePreview = () => {
            document.getElementById('cpu-preview').textContent =
                (cpuInput.value || 0) + ' cores';
            document.getElementById('ram-preview').textContent =
                (ramInput.value || 0) + ' GB';
            document.getElementById('storage-preview').textContent =
                (storageInput.value || 0) + ' GB';
        };

        [cpuInput, ramInput, storageInput].forEach(input => {
            input.addEventListener('input', updatePreview);
        });

        updatePreview();
    }

    setupAutoCalculate() {
        // Calcul automatique du coût estimé
        const cpuInput = document.getElementById('cpu');
        const ramInput = document.getElementById('ram');
        const storageInput = document.getElementById('storage');

        const calculateCost = () => {
            const cpu = parseInt(cpuInput.value) || 0;
            const ram = parseInt(ramInput.value) || 0;
            const storage = parseInt(storageInput.value) || 0;

            // Estimation simplifiée
            const cost = (cpu * 10) + (ram * 5) + (storage * 0.1);

            // Mettre à jour l'affichage si un élément existe
            const costElement = document.getElementById('estimated-cost');
            if (!costElement) {
                // Créer l'élément
                const costDiv = document.createElement('div');
                costDiv.id = 'estimated-cost';
                costDiv.className = 'cost-estimate';
                costDiv.innerHTML = `
                    <div class="cost-label">Coût estimé:</div>
                    <div class="cost-value">${cost.toFixed(2)} €/mois</div>
                `;

                const specsSummary = document.querySelector('.specs-summary');
                if (specsSummary) {
                    specsSummary.appendChild(costDiv);
                }
            } else {
                costElement.querySelector('.cost-value').textContent =
                    `${cost.toFixed(2)} €/mois`;
            }
        };

        [cpuInput, ramInput, storageInput].forEach(input => {
            input.addEventListener('input', calculateCost);
        });

        calculateCost();
    }

    setupFormReset() {
        const resetBtn = document.querySelector('button[type="reset"]');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                if (confirm('Réinitialiser tous les champs?')) {
                    // Réinitialiser les previews
                    document.getElementById('cpu-preview').textContent = '0 cores';
                    document.getElementById('ram-preview').textContent = '0 GB';
                    document.getElementById('storage-preview').textContent = '0 GB';

                    // Supprimer l'estimation de coût
                    const costElement = document.getElementById('estimated-cost');
                    if (costElement) {
                        costElement.remove();
                    }
                }
            });
        }
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    new ResourcesCreate();

    // Styles pour les erreurs
    const style = document.createElement('style');
    style.textContent = `
        .error {
            border-color: #ef4444 !important;
        }

        .cost-estimate {
            margin-top: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 0.5rem;
            text-align: center;
            border: 2px solid #10b981;
        }

        .cost-label {
            color: #64748b;
            font-size: 0.875rem;
        }

        .cost-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #10b981;
        }
    `;
    document.head.appendChild(style);
});
