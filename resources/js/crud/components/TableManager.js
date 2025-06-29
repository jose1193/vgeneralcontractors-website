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
        this.sortable = config.sortable !== false;
        this.currentSort = {
            field: config.defaultSortField || 'created_at',
            direction: config.defaultSortDirection || 'desc'
        };
        this.onSort = config.onSort || (() => {});
        this.onEdit = config.onEdit || (() => {});
        this.onDelete = config.onDelete || (() => {});
        this.onPageChange = config.onPageChange || (() => {});
    }

    /**
     * Renderizar tabla con datos
     */
    renderTable(data) {
        const tableContainer = document.querySelector(this.tableSelector);
        if (!tableContainer) {
            console.error('Table container not found:', this.tableSelector);
            return;
        }

        const tableHtml = this.generateTableHtml(data.data || data);
        tableContainer.innerHTML = tableHtml;

        // Vincular eventos
        this.bindTableEvents();

        // Renderizar paginación si hay datos de paginación
        if (data.pagination || data.links) {
            this.renderPagination(data);
        }
    }

    /**
     * Generar HTML de la tabla
     */
    generateTableHtml(data) {
        if (!data || data.length === 0) {
            return this.generateEmptyTableHtml();
        }

        return `
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        ${this.generateTableHeader()}
                    </thead>
                    <tbody>
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
            const sortClass = this.sortable && header.sortable !== false ? 'sortable' : '';
            
            return `
                <th class="${sortClass}" data-sort="${fieldKey}">
                    ${label}
                    ${sortIcon}
                </th>
            `;
        }).join('');

        const actionsHeader = this.actions.length > 0 ? '<th class="text-center">Acciones</th>' : '';
        
        return `<tr>${headerCells}${actionsHeader}</tr>`;
    }

    /**
     * Generar fila de tabla
     */
    generateTableRow(row) {
        const cells = this.headers.map(header => {
            const value = this.getCellValue(row, header);
            return `<td>${value}</td>`;
        }).join('');

        const actionsCell = this.actions.length > 0 ? this.generateActionsCell(row) : '';
        
        return `<tr data-id="${row.id || row[this.idField] || ''}">${cells}${actionsCell}</tr>`;
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
        const buttons = this.actions.map(action => {
            switch (action) {
                case 'edit':
                    return `<button class="btn btn-sm btn-outline-primary me-1 edit-btn" data-id="${row.id}" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>`;
                case 'delete':
                    return `<button class="btn btn-sm btn-outline-danger delete-btn" data-id="${row.id}" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>`;
                case 'view':
                    return `<button class="btn btn-sm btn-outline-info me-1 view-btn" data-id="${row.id}" title="Ver">
                        <i class="fas fa-eye"></i>
                    </button>`;
                default:
                    if (typeof action === 'object') {
                        return `<button class="btn btn-sm ${action.class || 'btn-outline-secondary'} me-1 custom-action-btn" 
                            data-id="${row.id}" data-action="${action.name}" title="${action.title || action.name}">
                            <i class="${action.icon || 'fas fa-cog'}"></i>
                        </button>`;
                    }
                    return '';
            }
        }).join('');

        return `<td class="text-center">${buttons}</td>`;
    }

    /**
     * Generar tabla vacía
     */
    generateEmptyTableHtml() {
        const colspan = this.headers.length + (this.actions.length > 0 ? 1 : 0);
        return `
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        ${this.generateTableHeader()}
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="${colspan}" class="text-center py-4">
                                <div class="text-muted">
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
            table.querySelectorAll('th.sortable').forEach(th => {
                th.addEventListener('click', (e) => {
                    const field = e.currentTarget.dataset.sort;
                    this.handleSort(field);
                });
            });
        }

        // Eventos de acciones
        table.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                this.onEdit(id);
            });
        });

        table.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                this.onDelete(id);
            });
        });

        table.querySelectorAll('.custom-action-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                const action = e.currentTarget.dataset.action;
                if (this.onCustomAction) {
                    this.onCustomAction(action, id);
                }
            });
        });
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