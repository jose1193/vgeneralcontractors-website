/**
 * Glassmorphic Table Interactive Script
 * Modern ES2025+ Approach
 */

/**
 * Inicializa todos los efectos y la interactividad de la tabla.
 * @param {string} tableId - El ID del elemento <table>.
 */
function initGlassmorphicTable(tableId) {
    const table = document.getElementById(tableId);
    if (!table) {
        console.error(`Tabla con ID "${tableId}" no encontrada.`);
        return;
    }

    const tableBody = document.getElementById(`${tableId}-body`);
    if (!tableBody) {
        console.error(`Cuerpo de tabla con ID "${tableId}-body" no encontrado.`);
        return;
    }

    // 1. Inicializa la funcionalidad de ordenación en las cabeceras.
    initSortFunctionality(table);
    
    // 2. Aplica efectos a las filas existentes y futuras.
    initRowEffects(tableBody);

    // 3. Observa cambios en la tabla para aplicar efectos a nuevas filas (ej. después de una carga de datos).
    observeTableChanges(tableBody);
}

/**
 * Configura los eventos de clic para ordenar las columnas.
 * @param {HTMLElement} table - El elemento <table>.
 */
function initSortFunctionality(table) {
    const sortHeaders = table.querySelectorAll('.sort-header');
    
    sortHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const field = this.dataset.field;
            if (!field) return;

            const isAsc = this.classList.contains('sort-asc');
            const isDesc = this.classList.contains('sort-desc');
            
            // Reinicia la clase de todas las demás cabeceras.
            sortHeaders.forEach(h => {
                h.classList.remove('sort-asc', 'sort-desc');
            });
            
            // Cambia el estado: none -> asc -> desc -> none
            let newSortDirection = '';
            if (!isAsc && !isDesc) {
                this.classList.add('sort-asc');
                newSortDirection = 'asc';
            } else if (isAsc) {
                this.classList.add('sort-desc');
                newSortDirection = 'desc';
            }
            // Si era desc, se quita la clase y queda en neutral.
            
            console.log(`Ordenando por: ${field}, Dirección: ${newSortDirection || 'none'}`);

            // === INTEGRACIÓN ===
            // Aquí llamarías a la función que recarga los datos de tu backend.
            // Ejemplo: window.myCrudManager.sortBy(field, newSortDirection);
        });
    });
}

/**
 * Utiliza delegación de eventos para manejar los efectos de hover en las filas.
 * @param {HTMLElement} tableBody - El elemento <tbody>.
 */
function initRowEffects(tableBody) {
    // Aplica la configuración a las filas ya existentes al cargar.
    setupAllRows(tableBody);

    // Evento para cuando el ratón entra en una fila.
    tableBody.addEventListener('mouseover', event => {
        const row = event.target.closest('tr');
        if (row && row.classList.contains('glassmorphic-table-row')) {
            row.classList.add('is-hovered');
        }
    });

    // Evento para cuando el ratón sale de una fila.
    tableBody.addEventListener('mouseout', event => {
        const row = event.target.closest('tr');
        if (row && row.classList.contains('glassmorphic-table-row')) {
            row.classList.remove('is-hovered');
        }
    });
}


/**
 * Escanea y configura todas las filas dentro del cuerpo de la tabla.
 * Añade la clase principal y el elemento indicador.
 * @param {HTMLElement} tableBody - El elemento <tbody>.
 */
function setupAllRows(tableBody) {
    const rows = tableBody.querySelectorAll('tr');
    
    rows.forEach((row) => {
        // Evita aplicar estilos a la fila de "carga" o "sin datos".
        if (row.querySelectorAll('td').length === 1 && row.querySelector('td').hasAttribute('colspan')) {
            return;
        }

        // Si ya está procesada, no hacer nada.
        if (row.classList.contains('glassmorphic-table-row')) return;
        
        row.classList.add('glassmorphic-table-row');
        
        // Crea y añade el indicador visual a la izquierda de la fila.
        const indicator = document.createElement('div');
        indicator.className = 'glassmorphic-table-row-indicator';
        row.prepend(indicator); // Usa prepend para asegurar que sea el primer hijo.
    });
}


/**
 * Utiliza MutationObserver para detectar cuando se añaden o eliminan filas
 * de la tabla, y reaplica los efectos necesarios. Es muy eficiente.
 * @param {HTMLElement} tableBody - El elemento <tbody> a observar.
 */
function observeTableChanges(tableBody) {
    const observer = new MutationObserver(mutations => {
        for (const mutation of mutations) {
            if (mutation.type === 'childList') {
                // El contenido del tbody ha cambiado, así que reconfiguramos las filas.
                setupAllRows(tableBody);
                break; // Solo necesitamos ejecutarlo una vez por lote de mutaciones.
            }
        }
    });
    
    // Inicia la observación.
    observer.observe(tableBody, { childList: true });
}

// Exporta la función principal para que sea accesible globalmente.
window.initGlassmorphicTable = initGlassmorphicTable;