// ============================================
// MAINTENANCE INDEX - JAVASCRIPT SIMPLE
// ============================================

/**
 * Masquer les messages après 5 secondes
 */
function hideMessages() {
    // Trouver tous les messages
    var messages = document.querySelectorAll('.alert, [class*="message"]');
    
    // Cacher chaque message après 5 secondes
    messages.forEach(function(message) {
        setTimeout(function() {
            message.style.display = 'none';
        }, 5000);
    });
}

/**
 * Confirmer la suppression
 */
function confirmDelete(button) {
    if (confirm('Supprimer cette maintenance ?')) {
        // Trouver le formulaire parent
        var form = button.closest('form');
        if (form) {
            form.submit();
        }
    }
}

/**
 * Filtrer le tableau par statut
 */
function filterByStatus() {
    var select = document.getElementById('status-filter');
    if (!select) return;
    
    var status = select.value;
    var rows = document.querySelectorAll('table tbody tr');
    
    rows.forEach(function(row) {
        // Trouver la cellule statut
        var statusCell = row.cells[6]; // 7ème colonne
        if (!statusCell) return;
        
        var rowStatus = statusCell.textContent.toLowerCase().trim();
        
        // Afficher/masquer selon le filtre
        if (!status || rowStatus.includes(status)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    updateCount();
}

/**
 * Rechercher dans le tableau
 */
function searchTable() {
    var input = document.getElementById('search-input');
    if (!input) return;
    
    var search = input.value.toLowerCase();
    var rows = document.querySelectorAll('table tbody tr');
    
    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        if (text.includes(search)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    updateCount();
}

/**
 * Mettre à jour le compteur
 */
function updateCount() {
    var rows = document.querySelectorAll('table tbody tr:not([style*="none"])');
    var counter = document.getElementById('total-count');
    
    if (counter) {
        counter.textContent = rows.length;
    }
}

/**
 * Trier le tableau
 */
var currentSort = { column: -1, direction: 'asc' };

function sortTable(columnIndex) {
    var table = document.querySelector('table');
    if (!table) return;
    
    var tbody = table.querySelector('tbody');
    var rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Déterminer direction
    if (currentSort.column === columnIndex) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.column = columnIndex;
        currentSort.direction = 'asc';
    }
    
    // Trier
    rows.sort(function(a, b) {
        var aText = a.cells[columnIndex].textContent;
        var bText = b.cells[columnIndex].textContent;
        
        // Pour les nombres (ID)
        if (columnIndex === 0) {
            var aNum = parseInt(aText) || 0;
            var bNum = parseInt(bText) || 0;
            return currentSort.direction === 'asc' ? aNum - bNum : bNum - aNum;
        }
        
        // Pour le texte
        return currentSort.direction === 'asc' 
            ? aText.localeCompare(bText)
            : bText.localeCompare(aText);
    });
    
    // Réinsérer
    rows.forEach(function(row) {
        tbody.appendChild(row);
    });
}

/**
 * Exporter le tableau en CSV
 */
function exportToCSV() {
    var table = document.querySelector('table');
    if (!table) return;
    
    var rows = table.querySelectorAll('tr');
    var csv = [];
    
    rows.forEach(function(row) {
        var rowData = [];
        var cells = row.querySelectorAll('th, td');
        
        cells.forEach(function(cell) {
            rowData.push('"' + cell.textContent.replace(/"/g, '""') + '"');
        });
        
        csv.push(rowData.join(','));
    });
    
    var csvContent = csv.join('\n');
    var blob = new Blob([csvContent], { type: 'text/csv' });
    var url = URL.createObjectURL(blob);
    
    var link = document.createElement('a');
    link.href = url;
    link.download = 'maintenances.csv';
    link.click();
    
    URL.revokeObjectURL(url);
}

/**
 * Initialiser les événements
 */
function initEvents() {
    // Boutons suppression
    var deleteButtons = document.querySelectorAll('.btn-delete, [onclick*="confirmDelete"]');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            confirmDelete(this);
        });
    });
    
    // Filtre statut
    var statusFilter = document.getElementById('status-filter');
    if (statusFilter) {
        statusFilter.addEventListener('change', filterByStatus);
    }
    
    // Recherche
    var searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('keyup', searchTable);
    }
    
    // En-têtes de tableau cliquables
    var headers = document.querySelectorAll('table th');
    headers.forEach(function(header, index) {
        header.addEventListener('click', function() {
            sortTable(index);
        });
    });
}

/**
 * Initialisation au chargement
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page maintenance chargée');
    
    // Cacher messages
    hideMessages();
    
    // Initialiser événements
    initEvents();
    
    // Initialiser compteur
    updateCount();
});