/**
 * GLASSMORPHIC TABLE 2025 - MODERN JAVASCRIPT
 * Enhanced interactive effects with improved performance and UX
 */

class GlassmorphicTable {
    constructor(tableId, options = {}) {
        this.tableId = tableId;
        this.options = {
            managerName: "crudManager",
            sortable: true,
            responsive: true,
            loadingText: "Loading...",
            noDataText: "No records found",
            animationDuration: 300,
            hoverDelay: 50,
            ...options,
        };

        this.table = null;
        this.tableBody = null;
        this.headers = null;
        this.currentSort = { field: null, direction: null };
        this.isLoading = false;
        this.observers = [];
        this.eventListeners = [];

        this.init();
    }

    /**
     * Initialize the table
     */
    init() {
        this.table = document.getElementById(this.tableId);
        if (!this.table) {
            console.error(`Table with ID "${this.tableId}" not found`);
            return;
        }

        this.tableBody = document.getElementById(`${this.tableId}-body`);
        if (!this.tableBody) {
            console.error(
                `Table body with ID "${this.tableId}-body" not found`
            );
            return;
        }

        this.headers = this.table.querySelectorAll(".glassmorphic-sortable");

        this.setupEventListeners();
        this.setupObservers();
        this.enhanceExistingRows();
        this.setupAccessibility();

        // Initialize intersection observer for performance
        this.setupIntersectionObserver();

        console.log(`GlassmorphicTable initialized for ${this.tableId}`);
    }

    /**
     * Setup event listeners with proper cleanup
     */
    setupEventListeners() {
        // Sort functionality
        if (this.options.sortable) {
            this.headers.forEach((header) => {
                const clickHandler = (e) => this.handleSort(e);
                const keyHandler = (e) => this.handleSortKeyboard(e);

                header.addEventListener("click", clickHandler);
                header.addEventListener("keydown", keyHandler);

                this.eventListeners.push({
                    element: header,
                    event: "click",
                    handler: clickHandler,
                });

                this.eventListeners.push({
                    element: header,
                    event: "keydown",
                    handler: keyHandler,
                });
            });
        }

        // Row interactions using event delegation
        const mouseoverHandler = (e) => this.handleRowMouseover(e);
        const mouseoutHandler = (e) => this.handleRowMouseout(e);
        const clickHandler = (e) => this.handleRowClick(e);
        const keyHandler = (e) => this.handleRowKeyboard(e);

        this.tableBody.addEventListener("mouseover", mouseoverHandler);
        this.tableBody.addEventListener("mouseout", mouseoutHandler);
        this.tableBody.addEventListener("click", clickHandler);
        this.tableBody.addEventListener("keydown", keyHandler);

        this.eventListeners.push(
            {
                element: this.tableBody,
                event: "mouseover",
                handler: mouseoverHandler,
            },
            {
                element: this.tableBody,
                event: "mouseout",
                handler: mouseoutHandler,
            },
            { element: this.tableBody, event: "click", handler: clickHandler },
            { element: this.tableBody, event: "keydown", handler: keyHandler }
        );

        // Window resize handler for responsive behavior
        const resizeHandler = () => this.handleResize();
        window.addEventListener("resize", resizeHandler);
        this.eventListeners.push({
            element: window,
            event: "resize",
            handler: resizeHandler,
        });
    }

    /**
     * Setup mutation observer for dynamic content
     */
    setupObservers() {
        const mutationObserver = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === "childList") {
                    this.handleContentChange();
                }
            });
        });

        mutationObserver.observe(this.tableBody, {
            childList: true,
            subtree: true,
        });

        this.observers.push(mutationObserver);
    }

    /**
     * Setup intersection observer for performance optimization
     */
    setupIntersectionObserver() {
        if (!("IntersectionObserver" in window)) return;

        const intersectionObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.enableRowInteractions(entry.target);
                    } else {
                        this.disableRowInteractions(entry.target);
                    }
                });
            },
            {
                root: null,
                rootMargin: "50px",
                threshold: 0.1,
            }
        );

        this.observers.push(intersectionObserver);
    }

    /**
     * Setup accessibility attributes
     */
    setupAccessibility() {
        // Add ARIA attributes to sortable headers
        this.headers.forEach((header) => {
            header.setAttribute("role", "button");
            header.setAttribute("tabindex", "0");
            header.setAttribute("aria-sort", "none");
        });

        // Add ARIA attributes to table
        this.table.setAttribute("role", "table");
        this.table.setAttribute(
            "aria-label",
            "Data table with sorting capabilities"
        );

        // Add row accessibility
        this.enhanceRowAccessibility();
    }

    /**
     * Enhance row accessibility
     */
    enhanceRowAccessibility() {
        const rows = this.tableBody.querySelectorAll(
            "tr:not(#loadingRow):not(#noDataRow)"
        );
        rows.forEach((row, index) => {
            row.setAttribute("role", "row");
            row.setAttribute("tabindex", "0");
            row.setAttribute("aria-rowindex", index + 2); // +2 because header is row 1

            const cells = row.querySelectorAll("td");
            cells.forEach((cell, cellIndex) => {
                cell.setAttribute("role", "gridcell");
                cell.setAttribute("aria-colindex", cellIndex + 1);
            });
        });
    }

    /**
     * Handle sort functionality
     */
    handleSort(event) {
        const header = event.currentTarget;
        const field = header.getAttribute("data-field");

        if (!field) return;

        // Determine new sort direction
        let newDirection = "asc";
        if (this.currentSort.field === field) {
            if (this.currentSort.direction === "asc") {
                newDirection = "desc";
            } else if (this.currentSort.direction === "desc") {
                newDirection = null; // Reset to no sort
            }
        }

        // Update sort state
        this.updateSortState(field, newDirection);

        // Trigger sort in CRUD manager
        this.triggerExternalSort(field, newDirection);
    }

    /**
     * Handle keyboard navigation for sorting
     */
    handleSortKeyboard(event) {
        if (event.key === "Enter" || event.key === " ") {
            event.preventDefault();
            this.handleSort(event);
        }
    }

    /**
     * Update visual sort state
     */
    updateSortState(field, direction) {
        // Reset all headers
        this.headers.forEach((header) => {
            header.classList.remove("sort-asc", "sort-desc");
            header.setAttribute("aria-sort", "none");
        });

        // Update current sort
        this.currentSort = { field, direction };

        // Apply new sort state
        if (direction) {
            const currentHeader = this.table.querySelector(
                `[data-field="${field}"]`
            );
            if (currentHeader) {
                currentHeader.classList.add(`sort-${direction}`);
                currentHeader.setAttribute(
                    "aria-sort",
                    direction === "asc" ? "ascending" : "descending"
                );
            }
        }
    }

    /**
     * Trigger external sort manager
     */
    triggerExternalSort(field, direction) {
        const manager = window[this.options.managerName];
        if (manager && typeof manager.sortBy === "function") {
            manager.sortBy(field, direction || "");
        }
    }

    /**
     * Handle row mouseover with throttling
     */
    handleRowMouseover(event) {
        const row = event.target.closest("tr");
        if (!row || this.isSpecialRow(row)) return;

        // Throttle mouseover events
        if (row.hoverTimeout) {
            clearTimeout(row.hoverTimeout);
        }

        row.hoverTimeout = setTimeout(() => {
            this.applyRowHoverEffect(row);
        }, this.options.hoverDelay);
    }

    /**
     * Handle row mouseout
     */
    handleRowMouseout(event) {
        const row = event.target.closest("tr");
        if (!row || this.isSpecialRow(row)) return;

        // Clear hover timeout
        if (row.hoverTimeout) {
            clearTimeout(row.hoverTimeout);
            row.hoverTimeout = null;
        }

        // Only remove hover effect if row is not active
        if (!row.classList.contains("glassmorphic-active")) {
            this.removeRowHoverEffect(row);
        }
    }

    /**
     * Handle row click
     */
    handleRowClick(event) {
        const row = event.target.closest("tr");
        if (!row || this.isSpecialRow(row)) return;

        // Remove active state from all rows
        const allRows = this.tableBody.querySelectorAll("tr");
        allRows.forEach((r) => r.classList.remove("glassmorphic-active"));

        // Add active state to clicked row
        row.classList.add("glassmorphic-active");

        // Trigger custom event
        this.dispatchRowEvent("rowSelect", row);
    }

    /**
     * Handle keyboard navigation for rows
     */
    handleRowKeyboard(event) {
        const row = event.target.closest("tr");
        if (!row || this.isSpecialRow(row)) return;

        switch (event.key) {
            case "Enter":
            case " ":
                event.preventDefault();
                this.handleRowClick(event);
                break;
            case "ArrowUp":
                event.preventDefault();
                this.navigateRow(row, -1);
                break;
            case "ArrowDown":
                event.preventDefault();
                this.navigateRow(row, 1);
                break;
        }
    }

    /**
     * Navigate between rows with keyboard
     */
    navigateRow(currentRow, direction) {
        const rows = Array.from(
            this.tableBody.querySelectorAll(
                "tr:not(#loadingRow):not(#noDataRow)"
            )
        );
        const currentIndex = rows.indexOf(currentRow);
        const newIndex = currentIndex + direction;

        if (newIndex >= 0 && newIndex < rows.length) {
            rows[newIndex].focus();
        }
    }

    /**
     * Apply hover effect to row
     */
    applyRowHoverEffect(row) {
        if (!row || row.classList.contains("glassmorphic-hover")) return;

        row.classList.add("glassmorphic-hover");

        // Add visual feedback
        row.style.transform = "translateY(-2px)";
        row.style.zIndex = "10";

        // Dispatch hover event
        this.dispatchRowEvent("rowHover", row);
    }

    /**
     * Remove hover effect from row
     */
    removeRowHoverEffect(row) {
        if (!row) return;

        row.classList.remove("glassmorphic-hover");

        // Reset styles
        row.style.transform = "";
        row.style.zIndex = "";

        // Dispatch hover out event
        this.dispatchRowEvent("rowHoverOut", row);
    }

    /**
     * Check if row is special (loading, no data, etc.)
     */
    isSpecialRow(row) {
        return (
            row.id === "loadingRow" ||
            row.id === "noDataRow" ||
            row.classList.contains("glassmorphic-loading-row") ||
            row.classList.contains("glassmorphic-no-data-row")
        );
    }

    /**
     * Enhance existing rows
     */
    enhanceExistingRows() {
        const rows = this.tableBody.querySelectorAll(
            "tr:not(#loadingRow):not(#noDataRow)"
        );
        rows.forEach((row, index) => {
            this.enhanceRow(row, index);
        });
    }

    /**
     * Enhance individual row
     */
    enhanceRow(row, index = 0) {
        if (!row || this.isSpecialRow(row)) return;

        // Add glassmorphic class
        row.classList.add("glassmorphic-row");

        // Add staggered animation delay
        row.style.animationDelay = `${index * 0.05}s`;

        // Setup accessibility
        row.setAttribute("role", "row");
        row.setAttribute("tabindex", "0");
        row.setAttribute("aria-rowindex", index + 2);

        // Process cells
        const cells = row.querySelectorAll("td");
        cells.forEach((cell, cellIndex) => {
            this.enhanceCell(cell, cellIndex);
        });
    }

    /**
     * Enhance individual cell
     */
    enhanceCell(cell, index) {
        if (!cell) return;

        // Add accessibility
        cell.setAttribute("role", "gridcell");
        cell.setAttribute("aria-colindex", index + 1);

        // Process status badges
        this.processStatusBadges(cell);
    }

    /**
     * Process status badges in cells
     */
    processStatusBadges(cell) {
        const statusText = cell.textContent.trim().toLowerCase();
        const statusKeywords = [
            "active",
            "inactive",
            "pending",
            "completed",
            "cancelled",
            "approved",
            "rejected",
        ];

        if (statusKeywords.some((keyword) => statusText.includes(keyword))) {
            this.createStatusBadge(cell, statusText);
        }
    }

    /**
     * Create status badge
     */
    createStatusBadge(cell, status) {
        const badge = document.createElement("span");
        badge.className = `glassmorphic-status-badge ${status}`;
        badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);

        cell.innerHTML = "";
        cell.appendChild(badge);
    }

    /**
     * Handle content changes
     */
    handleContentChange() {
        // Re-enhance rows after content changes
        setTimeout(() => {
            this.enhanceExistingRows();
            this.enhanceRowAccessibility();
        }, 100);
    }

    /**
     * Handle window resize
     */
    handleResize() {
        // Debounce resize handler
        if (this.resizeTimeout) {
            clearTimeout(this.resizeTimeout);
        }

        this.resizeTimeout = setTimeout(() => {
            this.updateResponsiveLayout();
        }, 250);
    }

    /**
     * Update responsive layout
     */
    updateResponsiveLayout() {
        const container = this.table.closest(".glassmorphic-table-container");
        if (!container) return;

        const containerWidth = container.offsetWidth;

        // Apply responsive classes based on width
        if (containerWidth < 640) {
            container.classList.add("glassmorphic-mobile");
        } else {
            container.classList.remove("glassmorphic-mobile");
        }
    }

    /**
     * Enable row interactions
     */
    enableRowInteractions(row) {
        if (!row || this.isSpecialRow(row)) return;
        row.style.pointerEvents = "auto";
    }

    /**
     * Disable row interactions
     */
    disableRowInteractions(row) {
        if (!row || this.isSpecialRow(row)) return;
        row.style.pointerEvents = "none";
    }

    /**
     * Dispatch custom row event
     */
    dispatchRowEvent(eventName, row) {
        const event = new CustomEvent(eventName, {
            detail: { row, tableId: this.tableId },
            bubbles: true,
            cancelable: true,
        });

        row.dispatchEvent(event);
    }

    /**
     * Show loading state
     */
    showLoading() {
        this.isLoading = true;
        const loadingRow = document.getElementById("loadingRow");
        const noDataRow = document.getElementById("noDataRow");

        if (loadingRow) loadingRow.style.display = "table-row";
        if (noDataRow) noDataRow.style.display = "none";

        // Hide other rows
        const dataRows = this.tableBody.querySelectorAll(
            "tr:not(#loadingRow):not(#noDataRow)"
        );
        dataRows.forEach((row) => (row.style.display = "none"));
    }

    /**
     * Hide loading state
     */
    hideLoading() {
        this.isLoading = false;
        const loadingRow = document.getElementById("loadingRow");

        if (loadingRow) loadingRow.style.display = "none";

        // Show data rows
        const dataRows = this.tableBody.querySelectorAll(
            "tr:not(#loadingRow):not(#noDataRow)"
        );
        dataRows.forEach((row) => (row.style.display = "table-row"));
    }

    /**
     * Show no data state
     */
    showNoData() {
        const loadingRow = document.getElementById("loadingRow");
        const noDataRow = document.getElementById("noDataRow");

        if (loadingRow) loadingRow.style.display = "none";
        if (noDataRow) noDataRow.style.display = "table-row";

        // Hide other rows
        const dataRows = this.tableBody.querySelectorAll(
            "tr:not(#loadingRow):not(#noDataRow)"
        );
        dataRows.forEach((row) => (row.style.display = "none"));
    }

    /**
     * Refresh table data
     */
    refresh() {
        this.showLoading();

        // Trigger refresh in external manager
        const manager = window[this.options.managerName];
        if (manager && typeof manager.refresh === "function") {
            manager.refresh();
        }
    }

    /**
     * Destroy table instance
     */
    destroy() {
        // Remove all event listeners
        this.eventListeners.forEach(({ element, event, handler }) => {
            element.removeEventListener(event, handler);
        });

        // Disconnect all observers
        this.observers.forEach((observer) => {
            observer.disconnect();
        });

        // Clear timeouts
        if (this.resizeTimeout) {
            clearTimeout(this.resizeTimeout);
        }

        // Clear references
        this.table = null;
        this.tableBody = null;
        this.headers = null;
        this.eventListeners = [];
        this.observers = [];

        console.log(`GlassmorphicTable destroyed for ${this.tableId}`);
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
        this.updateSortState(field, direction);
    }

    /**
     * Get selected rows
     */
    getSelectedRows() {
        return Array.from(
            this.tableBody.querySelectorAll("tr.glassmorphic-active")
        );
    }

    /**
     * Clear selection
     */
    clearSelection() {
        const activeRows = this.tableBody.querySelectorAll(
            "tr.glassmorphic-active"
        );
        activeRows.forEach((row) =>
            row.classList.remove("glassmorphic-active")
        );
    }
}

// Legacy support functions
function initGlassmorphicTable(tableId, options = {}) {
    return new GlassmorphicTable(tableId, options);
}

function formatStatusBadges(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;

    const statusCells = table.querySelectorAll(".status-cell");
    statusCells.forEach((cell) => {
        const glassmorphicTable = window.glassmorphicTable;
        if (glassmorphicTable) {
            glassmorphicTable.processStatusBadges(cell);
        }
    });
}

// Export for global use
window.GlassmorphicTable = GlassmorphicTable;
window.initGlassmorphicTable = initGlassmorphicTable;
window.formatStatusBadges = formatStatusBadges;
