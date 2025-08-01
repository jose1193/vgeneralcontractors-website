/**
 * CrudManagerModal Date Filters Bridge
 * 
 * This script extends the CrudManagerModal prototype to add date filtering capabilities,
 * creating compatibility with the CrudModalManager implementation.
 */

(function() {
    // Check if CrudManagerModal is available
    if (typeof CrudManagerModal === 'undefined') {
        console.warn('CrudManagerModal not found, date filter bridge cannot be initialized');
        return;
    }

    // Add applyDateFilters method to CrudManagerModal prototype
    CrudManagerModal.prototype.applyDateFilters = function(startDate, endDate) {
        console.log('CrudManagerModal: Applying date filters:', { startDate, endDate });
        
        // Store the date filter values
        this.dateStart = startDate;
        this.dateEnd = endDate;
        
        // Reload data with the new filters
        this.currentPage = 1;
        this.loadEntities();
    };

    // Add clearDateFilters method to CrudManagerModal prototype
    CrudManagerModal.prototype.clearDateFilters = function() {
        console.log('CrudManagerModal: Clearing date filters');
        
        // Clear the date filter values
        this.dateStart = '';
        this.dateEnd = '';
        
        // Reload data without date filters
        this.currentPage = 1;
        this.loadEntities();
    };

    console.log('Date filter bridge initialized for CrudManagerModal');
})();
