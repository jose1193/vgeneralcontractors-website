/**
 * Glassmorphic Table JavaScript
 * Handles interactive effects for the glassmorphic table component
 */

/**
 * Initialize the glassmorphic table effects
 * @param {string} tableId - The ID of the table element
 */
function initGlassmorphicTable(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    const tableBody = document.getElementById(`${tableId}-body`);
    if (!tableBody) return;

    // Add enhanced table appearance for dark background
    enhanceTableAppearance(table);

    // Initialize sort functionality if it exists
    initSortFunctionality(table);
    
    // Initialize row effects
    initRowEffects(tableBody);

    // Re-initialize when table content changes (for CRUD operations)
    observeTableChanges(tableBody);
}

/**
 * Initialize sort functionality for table headers
 * @param {HTMLElement} table - The table element
 */
function initSortFunctionality(table) {
    const sortHeaders = table.querySelectorAll('.sort-header');
    
    sortHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const field = this.getAttribute('data-field');
            let currentSort = this.classList.contains('sort-asc') ? 'asc' : 
                             this.classList.contains('sort-desc') ? 'desc' : '';
            
            // Reset all headers
            sortHeaders.forEach(h => {
                h.classList.remove('sort-asc', 'sort-desc');
            });
            
            // Set new sort direction
            let newSort = '';
            if (currentSort === '') {
                newSort = 'asc';
                this.classList.add('sort-asc');
            } else if (currentSort === 'asc') {
                newSort = 'desc';
                this.classList.add('sort-desc');
            }
            
            // Trigger sort in CRUD manager if it exists
            if (typeof window.crudManager !== 'undefined' && window.crudManager.sortBy) {
                window.crudManager.sortBy(field, newSort);
            }
        });
    });
}

/**
 * Initialize hover and active effects for table rows
 * @param {HTMLElement} tableBody - The table body element
 */
function initRowEffects(tableBody) {
    // Remove existing event listeners (if any)
    const existingRows = tableBody.querySelectorAll('tr:not(#loadingRow)');
    existingRows.forEach(row => {
        row.classList.remove('glassmorphic-table-row');
        const existingIndicator = row.querySelector('.glassmorphic-table-row-indicator');
        if (existingIndicator) {
            existingIndicator.remove();
        }
    });

    // Add event delegation for current and future rows
    tableBody.addEventListener('mouseover', handleRowMouseOver);
    tableBody.addEventListener('mouseout', handleRowMouseOut);
    tableBody.addEventListener('click', handleRowClick);

    // Initialize existing rows
    setupExistingRows(tableBody);
}

/**
 * Setup existing rows with glassmorphic effects
 * @param {HTMLElement} tableBody - The table body element
 */
function setupExistingRows(tableBody) {
    const rows = tableBody.querySelectorAll('tr:not(#loadingRow)');
    
    rows.forEach((row, index) => {
        row.classList.add('glassmorphic-table-row');
        
        const indicator = document.createElement('div');
        indicator.className = 'glassmorphic-table-row-indicator';
        row.style.position = 'relative';
        row.appendChild(indicator);
        
        row.style.transitionDelay = `${index * 0.03}s`;
    });
}

/**
 * Handle mouseover event for table rows
 * @param {Event} event - The mouseover event
 */
function handleRowMouseOver(event) {
    const row = event.target.closest('tr');
    if (row && !row.id.includes('loadingRow')) {
        row.classList.add('glassmorphic-table-row');
        
        if (!row.querySelector('.glassmorphic-table-row-indicator')) {
            const indicator = document.createElement('div');
            indicator.className = 'glassmorphic-table-row-indicator';
            row.style.position = 'relative';
            row.appendChild(indicator);
        }
        
        row.style.transform = 'scale(1.005)';
        row.style.backgroundColor = 'rgba(0, 0, 0, 0.95)';
        row.style.boxShadow = '0 0 20px rgba(138, 43,

        function enhanceTableAppearance(table) {
            const headers = table.querySelectorAll('th');
            headers.forEach(header => {
                header.style.borderBottom = '1px solid var(--border-color)';
                header.style.color = 'var(--header-text)';
                header.style.fontWeight = '500';
                header.style.textTransform = 'uppercase';
                header.style.letterSpacing = '0.05em';
                header.style.padding = '12px 16px';
                header.style.fontSize = '0.75rem';
                header.style.textAlign = 'center';
                header.style.transition = 'color 0.3s ease, background-color 0.3s ease';
                // Removed: header.style.filter = 'blur(0.5px)';
            });
        
            const cells = table.querySelectorAll('td');
            cells.forEach(cell => {
                cell.style.padding = '12px 16px';
                cell.style.color = 'var(--text-color)';
                cell.style.fontSize = '0.875rem';
                cell.style.textAlign = 'center';
                cell.style.transition = 'color 0.3s ease, background-color 0.3s ease';
                cell.style.overflowWrap = 'break-word'; // Added for long text
            });
        
            table.style.textShadow = '0 0 10px rgba(255, 255, 255, 0.2)';
            const tbody = table.querySelector('tbody');
            if (tbody) {
                tbody.style.backgroundColor = 'var(--row-bg)';
                // Removed: tbody.style.filter = 'blur(0.5px)';
            }
        }