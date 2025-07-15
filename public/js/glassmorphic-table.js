/**
 * Modern Glassmorphic Table Manager
 * Handles data rendering, sorting, and UI state for the refactored table.
 */

// Main initialization function
function initGlassmorphicTable(tableId, columns, managerName) {
    const tableManager = new GlassmorphicTableManager(tableId, columns, managerName);
    // Make the manager instance globally accessible if needed
    window[managerName] = tableManager;
    return tableManager;
}

class GlassmorphicTableManager {
    constructor(tableId, columns, managerName) {
        this.tableId = tableId;
        this.columns = columns;
        this.managerName = managerName;
        this.sortState = { field: null, direction: 'none' };

        // DOM Elements
        this.container = document.getElementById(`${tableId}-container`);
        this.table = document.getElementById(tableId);
        this.tableBody = document.getElementById(`${tableId}-body`);
        this.overlay = document.getElementById(`${tableId}-overlay`);
        this.loader = document.getElementById(`${tableId}-loader`);
        this.overlayText = document.getElementById(`${tableId}-overlay-text`);

        if (!this.table || !this.tableBody || !this.overlay) {
            console.error(`[${this.managerName}] Critical elements not found for table:`, tableId);
            return;
        }

        this.initSortHeaders();
        this.showLoading('Initializing...');
    }

    // --- State Management ---
    showLoading(message = 'Loading...') {
        this.overlay.style.display = 'flex';
        this.loader.style.display = 'block';
        this.overlayText.textContent = message;
        this.tableBody.innerHTML = ''; // Clear previous data
    }

    showNoData(message = 'No records found') {
        this.overlay.style.display = 'flex';
        this.loader.style.display = 'none';
        this.overlayText.textContent = message;
        this.tableBody.innerHTML = '';
    }

    showError(message = 'An error occurred') {
        this.showNoData(message); // Reuse no-data style for errors
        // Optionally add error-specific styling
    }

    hideOverlay() {
        this.overlay.style.display = 'none';
    }

    // --- Data Rendering ---
    renderRows(data) {
        if (!data || data.length === 0) {
            this.showNoData();
            return;
        }

        const fragment = document.createDocumentFragment();
        data.forEach(item => {
            const row = this.createRowElement(item);
            fragment.appendChild(row);
        });

        this.tableBody.innerHTML = ''; // Clear for fresh render
        this.tableBody.appendChild(fragment);
        this.hideOverlay();
    }

    createRowElement(item) {
        const row = document.createElement('tr');
        row.className = 'glassmorphic-table-row';
        row.dataset.id = item.id; // Assuming each item has a unique ID

        const indicator = document.createElement('div');
        indicator.className = 'glassmorphic-table-row-indicator';
        row.appendChild(indicator);

        this.columns.forEach(column => {
            const cell = document.createElement('td');
            cell.dataset.label = column.label;
            
            let cellContent = this.getNestedValue(item, column.field);

            // Custom cell rendering logic
            if (column.render) {
                cell.innerHTML = column.render(cellContent, item);
            } else if (column.type === 'status') {
                cell.innerHTML = this.renderStatusBadge(cellContent);
            } else {
                cell.textContent = cellContent;
            }
            
            row.appendChild(cell);
        });

        return row;
    }

    renderStatusBadge(status) {
        if (!status) return '';
        const statusClass = String(status).toLowerCase() === 'active' ? 'active' : 'inactive';
        return `<span class="status-badge ${statusClass}">${status}</span>`;
    }

    // --- Sorting --- 
    initSortHeaders() {
        const headers = this.table.querySelectorAll('.sort-header');
        headers.forEach(header => {
            header.addEventListener('click', () => this.handleSortClick(header));
        });
    }

    handleSortClick(header) {
        const field = header.dataset.field;
        let direction = 'asc';

        if (this.sortState.field === field && this.sortState.direction === 'asc') {
            direction = 'desc';
        } else if (this.sortState.field === field && this.sortState.direction === 'desc') {
            direction = 'none'; // Optional: cycle back to no sort
        } 

        this.sortState = { field, direction };
        this.updateSortIcons();

        // Announce sort event for external listeners (e.g., Livewire)
        const event = new CustomEvent('table-sort', { 
            bubbles: true,
            detail: { ...this.sortState, manager: this.managerName }
        });
        this.container.dispatchEvent(event);
    }

    updateSortIcons() {
        this.table.querySelectorAll('.sort-header').forEach(h => {
            h.classList.remove('sort-asc', 'sort-desc');
            if (h.dataset.field === this.sortState.field) {
                if (this.sortState.direction === 'asc') h.classList.add('sort-asc');
                if (this.sortState.direction === 'desc') h.classList.add('sort-desc');
            }
        });
    }

    // --- Utility ---
    getNestedValue(obj, path) {
        if (!path) return '';
        return path.split('.').reduce((acc, part) => acc && acc[part], obj);
    }
}

// Make the init function globally available
window.initGlassmorphicTable = initGlassmorphicTable;