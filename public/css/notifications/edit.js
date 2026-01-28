document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    if (startDateInput && endDateInput) {
        // Validation au changement
        startDateInput.addEventListener('change', validateDates);
        endDateInput.addEventListener('change', validateDates);

        // Validation à la soumission
        form.addEventListener('submit', function(e) {
            if (!validateDates()) {
                e.preventDefault();
                alert('La date de fin doit être postérieure à la date de début.');
            }
        });
    }

    function validateDates() {
        if (!startDateInput.value || !endDateInput.value) return true;

        const start = new Date(startDateInput.value);
        const end = new Date(endDateInput.value);

        if (end <= start) {
            endDateInput.setCustomValidity('La date de fin doit être après le début.');
            endDateInput.classList.add('is-invalid');
            return false;
        } else {
            endDateInput.setCustomValidity('');
            endDateInput.classList.remove('is-invalid');
            return true;
        }
    }
});