/**
 * ============================================================================
 * GLASSMORPHIC TABLE MANAGER
 * Modern JavaScript Class for handling table interactions
 * ============================================================================
 */

class GlassmorphicTableManager {
    constructor(tableId, options = {}) {
        this.tableId = tableId;
        this.options = {
            managerName: 'crudManager',
            sortable: true,
            responsive: true,
            loadingText: 'Cargando datos...',
            noDataText: 'No se encontraron registros',
            ...options
        };
        
        this.table = document.getElementById(tableId);
        this.tbody = document.getElementById(`${tableId}-body`);
        this.loadingRow = document.getElementById(`loading-row-${tableId}`);
        this.noDataRow = document.getElementById(`no-data-row-${tableId}`);
        
        this.currentSort = { field: null, direction: null };
        this.observer = null;
        
        this.init();
    }
    
    /**
     * Initialize the table manager
     */
    init() {
        if (!this.table || !this.tbody) {
            console.error(`Table with ID "${this.tableId}" not found`);
            return;
        }
        
        this.setupSortingHandlers();
        this.setupRowEffects();
        this.setupMutationObserver();
        this.enhanceExistingRows();
        
        console.log(`Glassmorphic Table Manager initialized for: ${this.tableId}`);
    }
    
    /**
     * Setup sorting functionality
     */
    setupSortingHandlers() {
        if (!this.options.sortable) return;
        
        const sortableHeaders = this.table.querySelectorAll('.glassmorphic-th.sortable');
        
        sortableHeaders.forEach(header => {
            header.addEventListener('click', (e) => {
                this.handleSort(e.currentTarget);
            });
        });
    }
    
    /**
     * Handle sorting logic
     */
    handleSort(header) {
        const field = header.dataset.field;
        if (!field) return;
        
        // Reset all other headers
        const allHeaders = this.table.querySelectorAll('.glassmorphic-th.sortable');
        allHeaders.forEach(h => {
            if (h !== header) {
                h.classList.remove('sort-asc', 'sort-desc');
            }
        });
        
        // Determine new sort direction
        let newDirection = 'asc';
        if (header.classList.contains('sort-asc')) {
            newDirection = 'desc';
        } else if (header.classList.contains('sort-desc')) {
            newDirection = '';
        }
        
        // Update header classes
        header.classList.remove('sort-asc', 'sort-desc');
        if (newDirection) {
            header.classList.add(`sort-${newDirection}`);
        }
        
        // Update current sort state
        this.currentSort = { field, direction: newDirection };
        
        // Trigger sort in external manager if exists
        if (window[this.options.managerName] && window[this.options.managerName].sortBy) {
            window[this.options.managerName].sortBy(field, newDirection);
        }
        
        // Add visual feedback
        this.addSortingFeedback(header);
    }
    
    /**
     * Add visual feedback for sorting
     */
    addSortingFeedback(header) {
        header.style.transform = 'scale(0.98)';
        setTimeout(() => {
            header.style.transform = '';
        }, 150);
    }
    
    /**
     * Setup row interaction effects
     */
    setupRowEffects() {
        // Use event delegation for better performance
        this.tbody.addEventListener('mouseenter', this.handleRowMouseEnter.bind(this), true);
        this.tbody.addEventListener('mouseleave', this.handleRowMouseLeave.bind(this), true);
        this.tbody.addEventListener('click', this.handleRowClick.bind(this));
    }
    
    /**
     * Handle row mouse enter
     */
    handleRowMouseEnter(e) {
        const row = e.target.closest('tr.glassmorphic-tr');
        if (row && !row.classList.contains('loading-row') && !row.classList.contains('no-data-row')) {
            this.activateRowHover(row);
        }
    }
    
    /**
     * Handle row mouse leave
     */
    handleRowMouseLeave(e) {
        const row = e.target.closest('tr.glassmorphic-tr');
        if (row && !row.classList.contains('active') && !row.classList.contains('loading-row') && !row.classList.contains('no-data-row')) {
            this.deactivateRowHover(row);
        }
    }
    
    /**
     * Handle row click
     */
    handleRowClick(e) {
        const row = e.target.closest('tr.glassmorphic-tr');
        if (row && !row.classList.contains('loading-row') && !row.classList.contains('no-data-row')) {
            this.selectRow(row);
        }
    }
    
    /**
     * Activate row hover state
     */
    activateRowHover(row) {
        row.classList.add('hovered');
        this.animateRowIndicator(row, true);
    }
    
    /**
     * Deactivate row hover state
     */
    deactivateRowHover(row) {
        row.classList.remove('hovered');
        this.animateRowIndicator(row, false);
    }
    
    /**
     * Select a row
     */
    selectRow(row) {
        // Remove active state from all rows
        const allRows = this.tbody.querySelectorAll('tr.glassmorphic-tr');
        allRows.forEach(r => r.classList.remove('active'));
        
        // Add active state to clicked row
        row.classList.add('active');
        
        // Trigger custom event
        const event = new CustomEvent('rowSelected', {
            detail: { row, tableId: this.tableId }
        });
        document.dispatchEvent(event);
    }
    
    /**
     * Animate row indicator
     */
    animateRowIndicator(row, show) {
        const indicator = row.querySelector('.row-indicator');
        if (indicator) {
            indicator.style.opacity = show ? '1' : '0';
            indicator.style.width = show ? '4px' : '0';
        }
    }
    
    /**
     * Setup mutation observer for dynamic content
     */
    setupMutationObserver() {
        this.observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    this.enhanceNewRows(mutation.addedNodes);
                }
            });
        });
        
        this.observer.observe(this.tbody, {
            childList: true,
            subtree: true
        });
    }
    
    /**
     * Enhance existing rows
     */
    enhanceExistingRows() {
        const rows = this.tbody.querySelectorAll('tr:not(.loading-row):not(.no-data-row)');
        rows.forEach(row => this.enhanceRow(row));
    }
    
    /**
     * Enhance new rows
     */
    enhanceNewRows(nodes) {
        nodes.forEach(node => {
            if (node.nodeType === Node.ELEMENT_NODE && node.tagName === 'TR') {
                this.enhanceRow(node);
            }
        });
    }
    
    /**
     * Enhance a single row
     */
    enhanceRow(row) {
        if (row.classList.contains('loading-row') || row.classList.contains('no-data-row')) {
            return;
        }
        
        // Add glassmorphic classes
        row.classList.add('glassmorphic-tr');
        
        // Add cells classes
        const cells = row.querySelectorAll('td');
        cells.forEach(cell => {
            cell.classList.add('glassmorphic-td');
        });
        
        // Process status badges
        this.processStatusBadges(row);
        
        // Process action buttons
        this.processActionButtons(row);
        
        // Add staggered animation delay
        const index = Array.from(row.parentNode.children).indexOf(row);
        row.style.animationDelay = `${index * 0.05}s`;
    }
    
    /**
     * Process status badges in a row
     */
    processStatusBadges(row) {
        const statusCells = row.querySelectorAll('.status-cell, [data-status]');
        
        statusCells.forEach(cell => {
            const statusText = cell.textContent.trim().toLowerCase();
            const statusValue = cell.dataset.status || statusText;
            
            if (statusText && !cell.querySelector('.status-badge')) {
                const badge = document.createElement('span');
                badge.className = `status-badge ${statusValue}`;
                badge.textContent = this.capitalizeFirst(statusText);
                
                cell.innerHTML = '';
                cell.appendChild(badge);
            }
        });
    }
    
    /**
     * Process action buttons in a row
     */
    processActionButtons(row) {
        const actionCells = row.querySelectorAll('.actions-cell, [data-actions]');
        
        actionCells.forEach(cell => {
            const buttons = cell.querySelectorAll('a, button');
            
            if (buttons.length > 0 && !cell.querySelector('.action-buttons')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'action-buttons';
                
                buttons.forEach(button => {
                    // Add appropriate classes based on button content or data attributes
                    if (button.textContent.includes('Editar') || button.dataset.action === 'edit') {
                        button.classList.add('action-btn', 'edit');
                    } else if (button.textContent.includes('Eliminar') || button.dataset.action === 'delete') {
                        button.classList.add('action-btn', 'delete');
                    } else {
                        button.classList.add('action-btn');
                    }
                    
                    wrapper.appendChild(button);
                });
                
                cell.innerHTML = '';
                cell.appendChild(wrapper);
            }
        });
    }
    
    /**
     * Show loading state
     */
    showLoading() {
        this.hideAllRows();
        this.loadingRow.style.display = '';
        this.addLoadingAnimation();
    }
    
    /**
     * Hide loading state
     */
    hideLoading() {
        this.loadingRow.style.display = 'none';
        this.removeLoadingAnimation();
    }
    
    /**
     * Show no data state
     */
    showNoData() {
        this.hideAllRows();
        this.noDataRow.style.display = '';
    }
    
    /**
     * Hide no data state
     */
    hideNoData() {
        this.noDataRow.style.display = 'none';
    }
    
    /**
     * Hide all special rows
     */
    hideAllRows() {
        this.loadingRow.style.display = 'none';
        this.noDataRow.style.display = 'none';
    }
    
    /**
     * Add loading animation
     */
    addLoadingAnimation() {
        const container = document.getElementById(`${this.tableId}-container`);
        if (container) {
            container.classList.add('loading-state');
        }
    }
    
    /**
     * Remove loading animation
     */
    removeLoadingAnimation() {
        const container = document.getElementById(`${this.tableId}-container`);
        if (container) {
            container.classList.remove('loading-state');
        }
    }
    
    /**
     * Update table data
     */
    updateData(data) {
        // Clear existing data rows
        const dataRows = this.tbody.querySelectorAll('tr:not(.loading-row):not(.no-data-row)');
        dataRows.forEach(row => row.remove());
        
        if (!data || data.length === 0) {
            this.showNoData();
            return;
        }
        
        this.hideAllRows();
        
        // Add new data rows
        data.forEach((rowData, index) => {
            const row = this.createDataRow(rowData, index);
            this.tbody.appendChild(row);
        });
        
        // Enhance new rows
        this.enhanceExistingRows();
    }
    
    /**
     * Create a data row from data object
     */
    createDataRow(data, index) {
        const row = document.createElement('tr');
        row.className = 'glassmorphic-tr';
        
        // This would need to be customized based on your data structure
        // For now, it's a placeholder that assumes data is an object with properties
        Object.values(data).forEach(value => {
            const cell = document.createElement('td');
            cell.className = 'glassmorphic-td';
            cell.textContent = value;
            row.appendChild(cell);
        });
        
        return row;
    }
    
    /**
     * Get current sort state
     */
    getSortState() {
        return { ...this.currentSort };
    }
    
    /**
     * Set sort state programmatically
     */
    setSortState(field, direction) {
        const header = this.table.querySelector(`.glassmorphic-th[data-field="${field}"]`);
        if (header) {
            this.handleSort(header);
        }
    }
    
    /**
     * Refresh table
     */
    refresh() {
        this.showLoading();
        
        // Trigger refresh in external manager if exists
        if (window[this.options.managerName] && window[this.options.managerName].refresh) {
            window[this.options.managerName].refresh();
        }
    }
    
    /**
     * Destroy the table manager
     */
    destroy() {
        if (this.observer) {
            this.observer.disconnect();
        }
        
        // Remove event listeners
        this.tbody.removeEventListener('mouseenter', this.handleRowMouseEnter);
        this.tbody.removeEventListener('mouseleave', this.handleRowMouseLeave);
        this.tbody.removeEventListener('click', this.handleRowClick);
        
        const sortableHeaders = this.table.querySelectorAll('.glassmorphic-th.sortable');
        sortableHeaders.forEach(header => {
            header.removeEventListener('click', this.handleSort);
        });
        
        console.log(`Glassmorphic Table Manager destroyed for: ${this.tableId}`);
    }
    
    /**
     * Get table statistics
     */
    getStats() {
        const totalRows = this.tbody.querySelectorAll('tr:not(.loading-row):not(.no-data-row)').length;
        const visibleRows = this.tbody.querySelectorAll('tr:not(.loading-row):not(.no-data-row):not([style*="display: none"])').length;
        
        return {
            totalRows,
            visibleRows,
            isLoading: this.loadingRow.style.display !== 'none',
            hasNoData: this.noDataRow.style.display !== 'none',
            currentSort: this.currentSort
        };
    }
    
    /**
     * Utility: Capitalize first letter
     */
    capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    
    /**
     * Utility: Add CSS animation class
     */
    addAnimation(element, animationClass, duration = 300) {
        element.classList.add(animationClass);
        setTimeout(() => {
            element.classList.remove(animationClass);
        }, duration);
    }
    
    /**
     * Utility: Debounce function
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

/**
 * ============================================================================
 * LEGACY SUPPORT FUNCTIONS
 * For backward compatibility with existing code
 * ============================================================================
 */

/**
 * Legacy function - Initialize glassmorphic table
 */
function initGlassmorphicTable(tableId, options = {}) {
    const manager = new GlassmorphicTableManager(tableId, options);
    window[`${tableId}Manager`] = manager;
    return manager;
}

/**
 * Legacy function - Format status badges
 */
function formatStatusBadges(tableId) {
    const manager = window[`${tableId}Manager`];
    if (manager) {
        const rows = manager.tbody.querySelectorAll('tr:not(.loading-row):not(.no-data-row)');
        rows.forEach(row => manager.processStatusBadges(row));
    }
}

/**
 * Legacy function - Enhance table appearance
 */
function enhanceTableAppearance(tableId) {
    const manager = window[`${tableId}Manager`];
    if (manager) {
        manager.enhanceExistingRows();
    }
}

// Export for global use
window.GlassmorphicTableManager = GlassmorphicTableManager;
window.initGlassmorphicTable = initGlassmorphicTable;
window.formatStatusBadges = formatStatusBadges;
window.enhanceTableAppearance = enhanceTableAppearance;

/**
 * ============================================================================
 * AUTO-INITIALIZATION
 * Automatically initialize tables with glassmorphic-table class
 * ============================================================================
 */

document.addEventListener('DOMContentLoaded', function() {
    // Auto-initialize tables with the glassmorphic-table class
    const tables = document.querySelectorAll('.glassmorphic-table[data-auto-init="true"]');
    
    tables.forEach(table => {
        const options = {
            managerName: table.dataset.manager || 'crudManager',
            sortable: table.dataset.sortable !== 'false',
            responsive: table.dataset.responsive !== 'false',
            loadingText: table.dataset.loadingText || 'Cargando datos...',
            noDataText: table.dataset.noDataText || 'No se encontraron registros'
        };
        
        new GlassmorphicTableManager(table.id, options);
    });
});

/**
 * ============================================================================
 * UTILITY FUNCTIONS
 * Additional helper functions for table management
 * ============================================================================
 */

/**
 * Batch update multiple tables
 */
function updateMultipleTables(tableIds, data) {
    tableIds.forEach(tableId => {
        const manager = window[`${tableId}Manager`];
        if (manager) {
            manager.updateData(data);
        }
    });
}

/**
 * Get all table managers
 */
function getAllTableManagers() {
    const managers = {};
    Object.keys(window).forEach(key => {
        if (key.endsWith('Manager') && window[key] instanceof GlassmorphicTableManager) {
            managers[key] = window[key];
        }
    });
    return managers;
}

/**
 * Destroy all table managers
 */
function destroyAllTableManagers() {
    const managers = getAllTableManagers();
    Object.values(managers).forEach(manager => {
        if (manager && typeof manager.destroy === 'function') {
            manager.destroy();
        }
    });
}

// Export utilities
window.updateMultipleTables = updateMultipleTables;
window.getAllTableManagers = getAllTableManagers;
window.destroyAllTableManagers = destroyAllTableManagers;