function initGlassmorphicTable(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;
    const tableBody = document.getElementById(`${tableId}-body`);
    if (!tableBody) return;

    // Add main class
    table.classList.add("glassmorphic-table");

    // Initialize sort functionality
    initSortFunctionality(table);

    // Initialize row effects
    initRowEffects(tableBody);

    // Observe changes for dynamic CRUD
    observeTableChanges(tableBody);
}

function initSortFunctionality(table) {
    const sortHeaders = table.querySelectorAll(".sort-header");
    sortHeaders.forEach((header) => {
        header.addEventListener("click", function () {
            const field = this.getAttribute("data-field");
            let currentSort = this.classList.contains("sort-asc")
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
            if (
                typeof window.crudManager !== "undefined" &&
                window.crudManager.sortBy
            ) {
                window.crudManager.sortBy(field, newSort);
            }
        });
    });
}

function initRowEffects(tableBody) {
    tableBody.addEventListener("mouseover", handleRowMouseOver);
    tableBody.addEventListener("mouseout", handleRowMouseOut);
    tableBody.addEventListener("click", handleRowClick);
    setupExistingRows(tableBody);
}

function setupExistingRows(tableBody) {
    const rows = tableBody.querySelectorAll("tr:not(#loadingRow)");
    rows.forEach((row) => {
        row.classList.remove("is-hovered", "is-active");
        row.classList.add("glassmorphic-table-row");
        // Add indicator if not present
        if (!row.querySelector(".glassmorphic-table-row-indicator")) {
            const indicator = document.createElement("div");
            indicator.className = "glassmorphic-table-row-indicator";
            row.style.position = "relative";
            row.appendChild(indicator);
        }
    });
}

function handleRowMouseOver(event) {
    const row = event.target.closest("tr");
    if (row && !row.id.includes("loadingRow")) {
        row.classList.add("is-hovered");
    }
}

function handleRowMouseOut(event) {
    const row = event.target.closest("tr");
    if (row && !row.id.includes("loadingRow")) {
        row.classList.remove("is-hovered");
    }
}

function handleRowClick(event) {
    const row = event.target.closest("tr");
    if (row && !row.id.includes("loadingRow")) {
        const allRows = row.parentElement.querySelectorAll("tr");
        allRows.forEach((r) => r.classList.remove("is-active"));
        row.classList.add("is-active");
    }
}

function observeTableChanges(tableBody) {
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === "childList") {
                setupExistingRows(tableBody);
            }
        });
    });
    observer.observe(tableBody, { childList: true });
}

window.initGlassmorphicTable = initGlassmorphicTable;
