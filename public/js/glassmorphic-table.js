/**
 * Glassmorphic Table JavaScript
 * Handles interactive effects for the glassmorphic table component
 */

/**
 * Initialize the glassmorphic table effects
 * @param {string} tableId - The ID of the table element
 */
const initGlassmorphicTable = (tableId) => {
    const table = document.getElementById(tableId);
    if (!table) return;
    const tableBody = document.getElementById(`${tableId}-body`);
    if (!tableBody) return;

    // Initialize sort functionality if it exists
    initSortFunctionality(table);
    // Initialize row effects
    initRowEffects(tableBody);
    // Re-initialize when table content changes (for CRUD operations)
    observeTableChanges(tableBody);
};

/**
 * Initialize sort functionality for table headers
 * @param {HTMLElement} table - The table element
 */
const initSortFunctionality = (table) => {
    const sortHeaders = table.querySelectorAll(".sort-header");
    sortHeaders.forEach((header) => {
        header.addEventListener("click", function () {
            const field = this.getAttribute("data-field");
            const currentSort = this.classList.contains("sort-asc")
                ? "asc"
                : this.classList.contains("sort-desc")
                ? "desc"
                : "";
            sortHeaders.forEach((h) =>
                h.classList.remove("sort-asc", "sort-desc")
            );
            let newSort = "";
            if (currentSort === "") {
                newSort = "asc";
                this.classList.add("sort-asc");
            } else if (currentSort === "asc") {
                newSort = "desc";
                this.classList.add("sort-desc");
            }
            if (window.crudManager?.sortBy) {
                window.crudManager.sortBy(field, newSort);
            }
        });
    });
};

/**
 * Initialize hover and active effects for table rows
 * @param {HTMLElement} tableBody - The table body element
 */
const initRowEffects = (tableBody) => {
    // Remove existing event listeners (if any)
    tableBody.replaceWith(tableBody.cloneNode(true));
    const newTableBody = document.getElementById(tableBody.id);
    // Add event delegation for current and future rows
    newTableBody.addEventListener("mouseover", handleRowMouseOver);
    newTableBody.addEventListener("mouseout", handleRowMouseOut);
    newTableBody.addEventListener("click", handleRowClick);
    // Initialize existing rows
    setupExistingRows(newTableBody);
};

/**
 * Setup existing rows with glassmorphic effects
 * @param {HTMLElement} tableBody - The table body element
 */
const setupExistingRows = (tableBody) => {
    const rows = tableBody.querySelectorAll("tr:not(#loadingRow)");
    rows.forEach((row, index) => {
        row.classList.add("glassmorphic-table-row");
        if (!row.querySelector(".glassmorphic-table-row-indicator")) {
            const indicator = document.createElement("div");
            indicator.className = "glassmorphic-table-row-indicator";
            row.appendChild(indicator);
        }
        row.style.transitionDelay = `${index * 0.03}s`;
    });
};

/**
 * Handle mouseover event for table rows
 * @param {Event} event - The mouseover event
 */
const handleRowMouseOver = (event) => {
    const row = event.target.closest("tr");
    if (row && !row.id.includes("loadingRow")) {
        row.classList.add("hovered");
    }
};

/**
 * Handle mouseout event for table rows
 * @param {Event} event - The mouseout event
 */
const handleRowMouseOut = (event) => {
    const row = event.target.closest("tr");
    if (
        row &&
        !row.classList.contains("active") &&
        !row.id.includes("loadingRow")
    ) {
        row.classList.remove("hovered");
    }
};

/**
 * Handle click event for table rows
 * @param {Event} event - The click event
 */
const handleRowClick = (event) => {
    const row = event.target.closest("tr");
    if (row && !row.id.includes("loadingRow")) {
        const allRows = row.parentElement.querySelectorAll("tr");
        allRows.forEach((r) => r.classList.remove("active"));
        row.classList.add("active");
    }
};

/**
 * Observe changes to the table body and reinitialize effects when content changes
 * @param {HTMLElement} tableBody - The table body element
 */
const observeTableChanges = (tableBody) => {
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === "childList") {
                setupExistingRows(tableBody);
            }
        });
    });
    observer.observe(tableBody, { childList: true });
};

/**
 * Format status badges in the table
 * @param {string} tableId - The ID of the table element
 */
const formatStatusBadges = (tableId) => {
    const table = document.getElementById(tableId);
    if (!table) return;
    const statusCells = table.querySelectorAll(".status-cell");
    statusCells.forEach((cell) => {
        const status = cell.textContent.trim().toLowerCase();
        cell.innerHTML = "";
        const badge = document.createElement("span");
        badge.className = `status-badge ${status}`;
        badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        cell.appendChild(badge);
    });
};

/**
 * Enhance table appearance for dark background with modern 2025 glassmorphic style
 * @param {HTMLElement} table - The table element
 */
// enhanceTableAppearance is no longer needed; all styles are handled by CSS

// Export functions for global use
window.initGlassmorphicTable = initGlassmorphicTable;
window.formatStatusBadges = formatStatusBadges;
