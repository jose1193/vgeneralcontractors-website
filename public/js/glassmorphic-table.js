/**
 * Glassmorphic Table JavaScript
 * Handles interactive effects for the glassmorphic table component
 */
/**
 * Enhance paginator appearance with glassmorphic crystal colors
 * @param {string} paginatorId - The ID of the paginator element
 */
function enhancePaginatorAppearance(paginatorId) {
    const paginator = document.getElementById(paginatorId);
    if (!paginator) return;

    paginator.style.backdropFilter = 'blur(10px)';
    paginator.style.webkitBackdropFilter = 'blur(10px)';
    paginator.style.backgroundColor = 'rgba(255, 255, 255, 0.1)'; // Color cristalino translúcido
    paginator.style.border = '1px solid rgba(255, 255, 255, 0.2)';
    paginator.style.borderRadius = '8px';
    paginator.style.padding = '8px 16px';
    paginator.style.color = 'rgba(255, 255, 255, 0.9)';
    paginator.style.boxShadow = '0 0 15px rgba(255, 255, 255, 0.1)';
}
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

    // Asume que el ID del paginador es 'paginator'
    enhancePaginatorAppearance('paginator');
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
        // Remove existing classes and indicators
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
        // Add glassmorphic class
        row.classList.add('glassmorphic-table-row');
        
        // Add indicator element
        const indicator = document.createElement('div');
        indicator.className = 'glassmorphic-table-row-indicator';
        row.style.position = 'relative';
        row.appendChild(indicator);
        
        // Add subtle animation delay based on row index for staggered effect
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
        
        // Add indicator if it doesn't exist
        if (!row.querySelector('.glassmorphic-table-row-indicator')) {
            const indicator = document.createElement('div');
            indicator.className = 'glassmorphic-table-row-indicator';
            row.style.position = 'relative';
            row.appendChild(indicator);
        }
        
        // Apply black crystal effect
        row.style.transform = 'scale(1.005)';
        row.style.backgroundColor = 'rgba(0, 0, 0, 0.95)';
        row.style.boxShadow = '0 0 20px rgba(138, 43, 226, 0.3)';
        row.style.zIndex = '10';
        row.style.position = 'relative';
        row.style.color = 'rgba(255, 255, 255, 1)';
        row.style.filter = 'blur(0.3px)';
    }
}

/**
 * Handle mouseout event for table rows
 * @param {Event} event - The mouseout event
 */
function handleRowMouseOut(event) {
    const row = event.target.closest('tr');
    if (row && !row.classList.contains('active') && !row.id.includes('loadingRow')) {
        // Reset styles when mouse leaves
        row.style.transform = '';
        row.style.backgroundColor = 'rgba(0, 0, 0, 0.9)';
        row.style.boxShadow = '';
        row.style.zIndex = '';
        row.style.position = '';
        row.style.color = 'rgba(255, 255, 255, 1)';
        row.style.filter = 'blur(0.5px)';
    }
}

/**
 * Handle click event for table rows
 * @param {Event} event - The click event
 */
function handleRowClick(event) {
    const row = event.target.closest('tr');
    if (row && !row.id.includes('loadingRow')) {
        // Remove active class from all rows
        const allRows = row.parentElement.querySelectorAll('tr');
        allRows.forEach(r => r.classList.remove('active'));
        
        // Add active class to clicked row
        row.classList.add('active');
    }
}

/**
 * Observe changes to the table body and reinitialize effects when content changes
 * @param {HTMLElement} tableBody - The table body element
 */
function observeTableChanges(tableBody) {
    // Create a MutationObserver to watch for changes to the table
    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            if (mutation.type === 'childList') {
                // Table content has changed, reinitialize row effects
                setupExistingRows(tableBody);
            }
        });
    });
    
    // Start observing the table body for changes
    observer.observe(tableBody, { childList: true });
}

/**
 * Format status badges in the table
 * @param {string} tableId - The ID of the table element
 */
function formatStatusBadges(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    const statusCells = table.querySelectorAll('.status-cell');
    
    statusCells.forEach(cell => {
        const status = cell.textContent.trim().toLowerCase();
        cell.innerHTML = ''; // Clear the cell
        
        const badge = document.createElement('span');
        badge.className = `status-badge ${status}`;
        badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        
        cell.appendChild(badge);
    });
}

/**
 * Enhance table appearance for dark background with modern 2025 glassmorphic style
 * @param {HTMLElement} table - The table element
 */
function enhanceTableAppearance(table) {
    // Add styles to the table headers
    const headers = table.querySelectorAll('th');
    headers.forEach(header => {
        header.style.borderBottom = '1px solid rgba(255, 255, 255, 0.05)';
        header.style.color = 'rgba(255, 255, 255, 0.9)';
        header.style.fontWeight = '500';
        header.style.textTransform = 'uppercase';
        header.style.letterSpacing = '0.05em';
        header.style.padding = '12px 16px';
        header.style.fontSize = '0.75rem';
        header.style.filter = 'blur(0.5px)';
        header.style.textAlign = 'center';
        header.style.transition = 'color 0.3s ease, background-color 0.3s ease';
    });

    // Add styles to the table cells
    const cells = table.querySelectorAll('td');
    cells.forEach(cell => {
        cell.style.padding = '12px 16px';
        cell.style.color = 'rgba(255, 255, 255, 1)';
        cell.style.fontSize = '0.875rem';
        cell.style.textAlign = 'center';
        cell.style.transition = 'color 0.3s ease, background-color 0.3s ease';
    });

    // Add a subtle text shadow to the entire table
    table.style.textShadow = '0 0 10px rgba(255, 255, 255, 0.2)';
    
    // Apply black crystal effect to the table body
    const tbody = table.querySelector('tbody');
    if (tbody) {
        tbody.style.backgroundColor = 'rgba(0, 0, 0, 0.9)';
        tbody.style.filter = 'blur(0.5px)';
    }
}

// Export functions for global use
window.initGlassmorphicTable = initGlassmorphicTable;
window.formatStatusBadges = formatStatusBadges;