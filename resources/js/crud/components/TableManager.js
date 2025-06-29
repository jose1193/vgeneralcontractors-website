/**
 * TableManager - Manejo de tablas de datos
 * Renderiza y manipula las tablas del sistema CRUD
 */
export class TableManager {
    constructor(config = {}) {
        this.tableSelector = config.tableSelector || '#dataTable';
        this.paginationSelector = config.paginationSelector || '#pagination';
        this.headers = config.headers || [];
        this.actions = config.actions || ['edit', 'delete'];
        this.idField = config.idField || 'id';
        this.sortable = config.sortable !== false;
        this.currentSort = {
            field: config.defaultSortField || 'created_at',
            direction: config.defaultSortDirection || 'desc'
        };
        this.onSort = config.onSort || (() => {});
        this.onEdit = config.onEdit || (() => {});
        this.onDelete = config.onDelete || (() => {});
        this.onRestore = config.onRestore || (() => {});
        this.onPageChange = config.onPageChange || (() => {});
    }

    /**
     * Renderizar tabla con datos
     */
    renderTable(data) {
        // Construir selector del tbody, evitando duplicar '-body'
        const tbodySelector = this.tableSelector.endsWith('-body') 
            ? this.tableSelector 
            : `${this.tableSelector}-body`;
            
        const tableBody = document.querySelector(tbodySelector);
        if (!tableBody) {
            console.error('Table body not found:', tbodySelector);
            return;
        }

        const rowsHtml = this.generateTableRows(data.data || data);
        tableBody.innerHTML = rowsHtml;

        // Vincular eventos
        this.bindTableEvents();

        // Renderizar paginación si hay datos de paginación
        if (data.pagination || data.links) {
            this.renderPagination(data);
        }
    }

    /**
     * Generar filas de la tabla
     */
    generateTableRows(data) {
        if (!data || data.length === 0) {
            return this.generateEmptyRow();
        }

        return data.map(row => this.generateTableRow(row)).join('');
    }

    /**
     * Generar HTML de la tabla (solo para casos especiales)
     */
    generateTableHtml(data) {
        if (!data || data.length === 0) {
            return this.generateEmptyTableHtml();
        }

        return `
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        ${this.generateTableHeader()}
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        ${data.map(row => this.generateTableRow(row)).join('')}
                    </tbody>
                </table>
            </div>
        `;
    }

    /**
     * Generar encabezado de tabla
     */
    generateTableHeader() {
        const headerCells = this.headers.map(header => {
            const fieldKey = header.field || header.key;
            const label = header.name || header.label || fieldKey;
            const sortIcon = this.getSortIcon(fieldKey);
            const sortClass = this.sortable && header.sortable !== false ? 'sortable cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700' : '';
            
            return `
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider ${sortClass}" data-sort="${fieldKey}">
                    ${label}
                    ${sortIcon}
                </th>
            `;
        }).join('');

        const actionsHeader = this.actions.length > 0 ? '<th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>' : '';
        
        return `<tr>${headerCells}${actionsHeader}</tr>`;
    }

    /**
     * Generar fila de tabla
     */
    generateTableRow(row) {
        const cells = this.headers.map(header => {
            const value = this.getCellValue(row, header);
            return `<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">${value}</td>`;
        }).join('');

        const actionsCell = this.actions.length > 0 ? this.generateActionsCell(row) : '';
        
        return `<tr class="hover:bg-gray-50 dark:hover:bg-gray-700" data-id="${row.id || row[this.idField] || ''}">${cells}${actionsCell}</tr>`;
    }

    /**
     * Obtener valor de celda
     */
    getCellValue(row, header) {
        // Usar field o key como identificador del campo
        const fieldKey = header.field || header.key;
        
        if (!fieldKey) {
            console.warn('Header missing field/key property:', header);
            return '';
        }
        
        let value = this.getNestedValue(row, fieldKey);
        
        // Aplicar getter personalizado si está definido
        if (header.getter && typeof header.getter === 'function') {
            value = header.getter(row);
        }
        
        // Aplicar formato si está definido
        if (header.format) {
            value = this.formatValue(value, header.format, row);
        }
        
        // Aplicar render personalizado si está definido
        if (header.render && typeof header.render === 'function') {
            value = header.render(value, row);
        }
        
        return value !== null && value !== undefined ? value : '';
    }

    /**
     * Obtener valor anidado de objeto
     */
    getNestedValue(obj, path) {
        if (!path || typeof path !== 'string') {
            console.warn('Invalid path provided to getNestedValue:', path);
            return null;
        }
        
        return path.split('.').reduce((current, key) => {
            return current && current[key] !== undefined ? current[key] : null;
        }, obj);
    }

    /**
     * Formatear valor según tipo
     */
    formatValue(value, format, row) {
        if (!value && value !== 0) return '';
        
        switch (format.type) {
            case 'date':
                return this.formatDate(value, format.pattern);
            case 'currency':
                return this.formatCurrency(value, format.currency);
            case 'number':
                return this.formatNumber(value, format.decimals);
            case 'boolean':
                return this.formatBoolean(value, format.labels);
            case 'badge':
                return this.formatBadge(value, format.variants);
            case 'link':
                return this.formatLink(value, format.url, row);
            case 'image':
                return this.formatImage(value, format.alt);
            default:
                return value;
        }
    }

    /**
     * Formatear fecha
     */
    formatDate(value, pattern = 'DD/MM/YYYY') {
        if (!value) return '';
        const date = new Date(value);
        return date.toLocaleDateString('es-ES');
    }

    /**
     * Formatear moneda
     */
    formatCurrency(value, currency = 'USD') {
        if (!value && value !== 0) return '';
        return new Intl.NumberFormat('es-ES', {
            style: 'currency',
            currency: currency
        }).format(value);
    }

    /**
     * Formatear número
     */
    formatNumber(value, decimals = 2) {
        if (!value && value !== 0) return '';
        return parseFloat(value).toFixed(decimals);
    }

    /**
     * Formatear booleano
     */
    formatBoolean(value, labels = { true: 'Sí', false: 'No' }) {
        return labels[value] || labels.false;
    }

    /**
     * Formatear badge
     */
    formatBadge(value, variants = {}) {
        const variant = variants[value] || 'secondary';
        return `<span class="badge bg-${variant}">${value}</span>`;
    }

    /**
     * Formatear enlace
     */
    formatLink(value, urlPattern, row) {
        if (!value) return '';
        const url = urlPattern.replace(/\{(\w+)\}/g, (match, key) => row[key] || '');
        return `<a href="${url}" target="_blank">${value}</a>`;
    }

    /**
     * Formatear imagen
     */
    formatImage(value, alt = 'Imagen') {
        if (!value) return '';
        return `<img src="${value}" alt="${alt}" class="img-thumbnail" style="max-width: 50px; max-height: 50px;">`;
    }

    /**
     * Generar celda de acciones
     */
    generateActionsCell(row) {
        const rowId = row[this.idField] || row.id;
        const isDeleted = row.deleted_at !== null;
        
        const buttons = this.actions.map(action => {
            switch (action) {
                case 'edit':
                    return `<button class="edit-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg mr-2" data-id="${rowId}" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>`;
                case 'delete':
                    if (isDeleted) return '';
                    return `<button class="delete-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg" data-id="${rowId}" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>`;
                case 'restore':
                    if (!isDeleted) return '';
                    return `<button class="restore-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg" data-id="${rowId}" title="Restore">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>`;
                case 'view':
                    return `<button class="view-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg mr-2" data-id="${rowId}" title="View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>`;
                default:
                    if (typeof action === 'object') {
                        return `<button class="inline-flex items-center justify-center w-9 h-9 ${action.class || 'bg-gradient-to-r from-gray-500 to-gray-600 text-white hover:from-gray-600 hover:to-gray-700'} rounded-lg transition-all duration-200 shadow-md hover:shadow-lg mr-2 custom-action-btn" 
                            data-id="${rowId}" data-action="${action.name}" title="${action.title || action.name}">
                            <i class="${action.icon || 'fas fa-cog'}"></i>
                        </button>`;
                    }
                    return '';
            }
        }).filter(button => button !== '').join('');

        return `<td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">${buttons}</td>`;
    }

    /**
     * Generar fila vacía
     */
    generateEmptyRow() {
        const colspan = this.headers.length + (this.actions.length > 0 ? 1 : 0);
        return `
            <tr>
                <td colspan="${colspan}" class="px-6 py-4 text-center">
                    <div class="text-gray-500 dark:text-gray-400">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>No hay datos disponibles</p>
                    </div>
                </td>
            </tr>
        `;
    }

    /**
     * Generar tabla vacía (solo para casos especiales)
     */
    generateEmptyTableHtml() {
        const colspan = this.headers.length + (this.actions.length > 0 ? 1 : 0);
        return `
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        ${this.generateTableHeader()}
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        <tr>
                            <td colspan="${colspan}" class="px-6 py-4 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No hay datos disponibles</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
    }

    /**
     * Obtener icono de ordenamiento
     */
    getSortIcon(field) {
        if (!this.sortable) return '';
        
        if (this.currentSort.field === field) {
            return this.currentSort.direction === 'asc' 
                ? '<i class="fas fa-sort-up ms-1"></i>'
                : '<i class="fas fa-sort-down ms-1"></i>';
        }
        
        return '<i class="fas fa-sort ms-1 text-muted"></i>';
    }

    /**
     * Vincular eventos de la tabla
     */
    bindTableEvents() {
        const table = document.querySelector(this.tableSelector);
        if (!table) return;

        // Eventos de ordenamiento
        if (this.sortable) {
            table.querySelectorAll('th.sort-header').forEach(th => {
                th.addEventListener('click', (e) => {
                    const field = e.currentTarget.dataset.field;
                    this.handleSort(field);
                });
            });
        }

        // Eventos de acciones en el tbody
        const tbodySelector = this.tableSelector.endsWith('-body') 
            ? this.tableSelector 
            : `${this.tableSelector}-body`;
        const tableBody = document.querySelector(tbodySelector);
        if (tableBody) {
            tableBody.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = e.currentTarget.dataset.id;
                    this.onEdit(id);
                });
            });

            tableBody.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = e.currentTarget.dataset.id;
                    this.onDelete(id);
                });
            });

            tableBody.querySelectorAll('.restore-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = e.currentTarget.dataset.id;
                    this.onRestore(id);
                });
            });

            tableBody.querySelectorAll('.custom-action-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = e.currentTarget.dataset.id;
                    const action = e.currentTarget.dataset.action;
                    if (this.onCustomAction) {
                        this.onCustomAction(action, id);
                    }
                });
            });
        }
    }

    /**
     * Manejar ordenamiento
     */
    handleSort(field) {
        if (this.currentSort.field === field) {
            this.currentSort.direction = this.currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            this.currentSort.field = field;
            this.currentSort.direction = 'asc';
        }
        
        this.onSort(this.currentSort.field, this.currentSort.direction);
    }

    /**
     * Renderizar paginación
     */
    renderPagination(data) {
        const paginationContainer = document.querySelector(this.paginationSelector);
        if (!paginationContainer) return;

        const pagination = data.pagination || {
            current_page: data.current_page,
            last_page: data.last_page,
            per_page: data.per_page,
            total: data.total
        };

        if (pagination.last_page <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }

        const paginationHtml = this.generatePaginationHtml(pagination);
        paginationContainer.innerHTML = paginationHtml;
        this.bindPaginationEvents();
    }

    /**
     * Generar HTML de paginación
     */
    generatePaginationHtml(pagination) {
        const { current_page, last_page } = pagination;
        let pages = [];

        // Lógica para mostrar páginas
        const delta = 2;
        const range = [];
        const rangeWithDots = [];

        for (let i = Math.max(2, current_page - delta); 
             i <= Math.min(last_page - 1, current_page + delta); 
             i++) {
            range.push(i);
        }

        if (current_page - delta > 2) {
            rangeWithDots.push(1, '...');
        } else {
            rangeWithDots.push(1);
        }

        rangeWithDots.push(...range);

        if (current_page + delta < last_page - 1) {
            rangeWithDots.push('...', last_page);
        } else {
            rangeWithDots.push(last_page);
        }

        const pageItems = rangeWithDots.map(page => {
            if (page === '...') {
                return '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            
            const isActive = page === current_page;
            const activeClass = isActive ? 'active' : '';
            
            return `
                <li class="page-item ${activeClass}">
                    <a class="page-link" href="#" data-page="${page}">${page}</a>
                </li>
            `;
        }).join('');

        const prevDisabled = current_page === 1 ? 'disabled' : '';
        const nextDisabled = current_page === last_page ? 'disabled' : '';

        return `
            <nav aria-label="Paginación">
                <ul class="pagination justify-content-center">
                    <li class="page-item ${prevDisabled}">
                        <a class="page-link" href="#" data-page="${current_page - 1}" aria-label="Anterior">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    ${pageItems}
                    <li class="page-item ${nextDisabled}">
                        <a class="page-link" href="#" data-page="${current_page + 1}" aria-label="Siguiente">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        `;
    }

    /**
     * Vincular eventos de paginación
     */
    bindPaginationEvents() {
        const pagination = document.querySelector(this.paginationSelector);
        if (!pagination) return;

        pagination.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = parseInt(e.currentTarget.dataset.page);
                if (page && !isNaN(page)) {
                    this.onPageChange(page);
                }
            });
        });
    }

    /**
     * Actualizar configuración
     */
    updateConfig(newConfig) {
        Object.assign(this, newConfig);
    }
}