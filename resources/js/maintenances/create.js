/**
 * JavaScript pour le formulaire de création de maintenance
 * Version corrigée et sécurisée
 */

// Attendre que la page soit chargée
document.addEventListener('DOMContentLoaded', function() {

    // 1. Récupérer le formulaire
    const form = document.getElementById('maintenanceForm');
    if (!form) {
        console.log('Formulaire non trouvé');
        return; // Arrêter si pas de formulaire
    }

    // 2. Récupérer les champs importants
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const resourceSelect = document.getElementById('resource_id');

    // 3. Vérifier que les champs existent
    if (!startDateInput || !endDateInput) {
        console.log('Champs de date manquants');
        return;
    }

    // 4. Initialiser les dates
    setupDates(startDateInput, endDateInput);

    // 5. Ajouter les écouteurs d'événements
    startDateInput.addEventListener('change', function() {
        updateEndDate(startDateInput, endDateInput);
    });

    endDateInput.addEventListener('change', function() {
        checkDates(startDateInput, endDateInput);
    });

    form.addEventListener('submit', function(event) {
        if (!validateForm(form, resourceSelect, startDateInput, endDateInput)) {
            event.preventDefault(); // Bloquer l'envoi
        }
    });

    console.log('JS maintenance chargé avec succès');
});

/**
 * Configurer les dates par défaut
 */
function setupDates(startInput, endInput) {
    // Date de demain comme minimum
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);

    // Formater pour input datetime-local
    const minDate = formatDate(tomorrow);

    // Appliquer les contraintes
    startInput.min = minDate;
    endInput.min = minDate;

    // Valeurs par défaut
    if (!startInput.value) {
        startInput.value = minDate;
    }

    if (!endInput.value) {
        const defaultEnd = new Date(tomorrow);
        defaultEnd.setHours(defaultEnd.getHours() + 2);
        endInput.value = formatDate(defaultEnd);
    }
}

/**
 * Mettre à jour la date de fin
 */
function updateEndDate(startInput, endInput) {
    if (startInput.value) {
        // La fin ne peut pas être avant le début
        endInput.min = startInput.value;

        // Si la fin est avant le début, la recaler
        if (endInput.value && endInput.value < startInput.value) {
            const newEnd = new Date(startInput.value);
            newEnd.setHours(newEnd.getHours() + 1);
            endInput.value = formatDate(newEnd);
        }
    }
}

/**
 * Vérifier la validité des dates
 */
function checkDates(startInput, endInput) {
    if (startInput.value && endInput.value) {
        const start = new Date(startInput.value);
        const end = new Date(endInput.value);

        // Vérifier que la fin est après le début
        if (end <= start) {
            alert('Erreur: La date de fin doit être après la date de début.');
            endInput.value = '';
            endInput.focus();
            return false;
        }

        // Vérifier si trop longue (> 30 jours)
        const days = (end - start) / (1000 * 60 * 60 * 24);
        if (days > 30) {
            if (!confirm('Attention: Cette maintenance dure plus de 30 jours. Continuer?')) {
                endInput.value = '';
                endInput.focus();
                return false;
            }
        }
    }
    return true;
}

/**
 * Valider tout le formulaire
 */
function validateForm(form, resourceSelect, startInput, endInput) {
    let isValid = true;

    // Vérifier la ressource
    if (resourceSelect && !resourceSelect.value) {
        alert('Veuillez sélectionner une ressource.');
        resourceSelect.focus();
        isValid = false;
    }

    // Vérifier le titre
    const titleInput = document.getElementById('title');
    if (titleInput && !titleInput.value.trim()) {
        alert('Le titre est obligatoire.');
        titleInput.focus();
        isValid = false;
    }

    // Vérifier le type
    const typeSelect = document.getElementById('type');
    if (typeSelect && !typeSelect.value) {
        alert('Veuillez sélectionner un type de maintenance.');
        typeSelect.focus();
        isValid = false;
    }

    // Vérifier les dates
    if (!checkDates(startInput, endInput)) {
        isValid = false;
    }

    return isValid;
}

/**
 * Formater une date pour input datetime-local
 */
function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${year}-${month}-${day}T${hours}:${minutes}`;
}
