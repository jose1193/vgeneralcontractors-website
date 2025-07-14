/**
 * Glassmorphic Table JavaScript v2.0
 * Initializes table functionality and observes changes.
 */

function initGlassmorphicTable(config) {
    const tableBody = document.getElementById(`${config.tableId}-body`);
    if (!tableBody) {
        console.error(`Table body with ID '${config.tableId}-body' not found.`);
        return;
    }

    initSortFunctionality(config.tableId);
    initRowInteractions(tableBody);
    observeTableChanges(tableBody);
}

/**
 * Initializes sort functionality for table headers.
 */
function initSortFunctionality(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;

    table.querySelectorAll('.sort-header').forEach(header => {
        header.addEventListener('click', function() {
            const field = this.getAttribute('data-field');
            const isAsc = this.classList.contains('sort-asc');
            const isDesc = this.classList.contains('sort-desc');

            // Reset all other headers
            table.querySelectorAll('.sort-header').forEach(h => {
                if (h !== this) {
                    h.classList.remove('sort-asc', 'sort-desc');
                }
            });

            // Cycle through sort states: none -> asc -> desc -> none
            if (!isAsc && !isDesc) {
                this.classList.add('sort-asc');
            } else if (isAsc) {
                this.classList.remove('sort-asc');
                this.classList.add('sort-desc');
            } else {
                this.classList.remove('sort-desc');
            }

            // Trigger sort in the external CRUD manager
            if (typeof window.crudManager?.sortBy === 'function') {
                const newSortDir = this.classList.contains('sort-asc') ? 'asc' : (this.classList.contains('sort-desc') ? 'desc' : '');
                window.crudManager.sortBy(field, newSortDir);
            }
        });
    });
}


/**
 * Sets up row styling and click interactions using event delegation.
 */
function initRowInteractions(tableBody) {
    tableBody.addEventListener('click', (event) => {
        const row = event.target.closest('tr');
        if (row && row.id !== 'loadingRow') {
            // Remove 'is-active' from any previously active row
            const currentActive = tableBody.querySelector('.is-active');
            if (currentActive) {
                currentActive.classList.remove('is-active');
            }
            // Add 'is-active' to the clicked row
            row.classList.add('is-active');
        }
    });
}

/**
 * Adds the necessary class to new rows added to the table.
 */
function setupNewRows(tableBody) {
    tableBody.querySelectorAll('tr:not(.glassmorphic-table-row):not(#loadingRow)').forEach(row => {
        row.classList.add('glassmorphic-table-row');
        // También podemos formatear los badges aquí si es necesario
        formatBadgesInRow(row);
    });
}

/**
 * Formats status badges within a specific row.
 * El nombre del badge se toma del contenido de la celda.
 */
function formatBadgesInRow(row) {
    // Asume que la celda con el tipo tiene una clase 'type-cell'
    const typeCell = row.querySelector('.type-cell');
    if (typeCell) {
        const typeText = typeCell.textContent.trim();
        typeCell.innerHTML = `<span class="status-badge status-badge-${typeText}">${typeText}</span>`;
    }
}


/**
 * Observes the table body for when new rows are added (e.g., by your CRUD manager).
 */
function observeTableChanges(tableBody) {
    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            if (mutation.addedNodes.length) {
                // When new rows are added, set them up.
                setupNewRows(tableBody);
            }
        });
    });

    observer.observe(tableBody, {
        childList: true
    });

    // Initial setup for any rows that might exist on page load
    setupNewRows(tableBody);
}

// Export a global function for initialization
window.initGlassmorphicTable = initGlassmorphicTable;