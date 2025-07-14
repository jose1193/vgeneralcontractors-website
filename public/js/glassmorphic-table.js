/**
 * Glassmorphic Table JavaScript
 * Handles interactive effects for the glassmorphic table component
 */

/**
 * Initializes the glassmorphic table, setting up sorting functionality.
 * @param {string} tableId - The ID of the table element.
 * @param {Array} columns - The configuration array for the table columns.
 */
function initGlassmorphicTable(tableId, columns) {
    const table = document.getElementById(tableId);
    if (!table) {
        console.error(`Table with id ${tableId} not found.`);
        return;
    }

    // Store columns in a way that's easily accessible by the populate function
    table.dataset.columns = JSON.stringify(columns);

    const headers = table.querySelectorAll('.sort-header');
    headers.forEach(header => {
        header.addEventListener('click', () => {
            const field = header.dataset.field;
            const currentSort = header.classList.contains('sort-asc') ? 'asc' : (header.classList.contains('sort-desc') ? 'desc' : 'none');
            let newSort;

            if (currentSort === 'asc') {
                newSort = 'desc';
            } else if (currentSort === 'desc') {
                newSort = 'asc';
            } else {
                newSort = 'asc';
            }

            // Remove sorting classes from all headers
            headers.forEach(h => {
                h.classList.remove('sort-asc', 'sort-desc');
            });

            // Add new sort class to the clicked header
            header.classList.add(`sort-${newSort}`);

            // Dispatch a custom event to notify that sorting has changed
            // The parent component (e.g., Livewire) can listen for this event
            table.dispatchEvent(new CustomEvent('sort-changed', {
                bubbles: true,
                detail: { field, direction: newSort }
            }));
        });
    });
}

/**
 * Populates the table with data from an API or other source.
 * @param {string} tableId - The ID of the table to populate.
 * @param {Array} data - An array of objects representing the row data.
 */
function populateTable(tableId, data) {
    const table = document.getElementById(tableId);
    if (!table) return;

    const tbody = table.querySelector('tbody');
    const loadingRow = document.getElementById(`loadingRow-${tableId}`);
    const noDataRow = document.getElementById(`noDataRow-${tableId}`);
    const columns = JSON.parse(table.dataset.columns || '[]');

    // Clear existing data rows (but not loading/no-data rows)
    tbody.querySelectorAll('tr:not(.table-state-row)').forEach(row => row.remove());

    if (loadingRow) loadingRow.style.display = 'none';

    if (!data || data.length === 0) {
        if (noDataRow) noDataRow.style.display = 'table-row';
        return;
    }

    if (noDataRow) noDataRow.style.display = 'none';

    data.forEach(item => {
        const row = document.createElement('tr');
        
        columns.forEach(column => {
            const cell = document.createElement('td');
            const cellValue = item[column.field] || 'N/A';

            // Set data-label for responsive view. This is crucial.
            cell.setAttribute('data-label', column.label);

            // Use a renderer if provided, otherwise just set text content
            if (column.render) {
                cell.innerHTML = column.render(cellValue, item);
            } else {
                cell.textContent = cellValue;
            }
            row.appendChild(cell);
        });

        tbody.appendChild(row);
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