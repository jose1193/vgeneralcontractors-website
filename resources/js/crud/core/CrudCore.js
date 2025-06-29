/**
 * CrudCore - Clase base para operaciones CRUD
 * Contiene la lógica fundamental compartida por todos los CRUDs
 */
import { ApiManager } from './ApiManager.js';
import { EventManager } from './EventManager.js';

export class CrudCore {
    constructor(options) {
        // Configuración básica
        this.entityName = options.entityName || "Entity";
        this.entityNamePlural = options.entityNamePlural || "Entities";
        this.routes = options.routes || {};
        this.idField = options.idField || "id";

        // Selectores de elementos DOM
        this.tableSelector = options.tableSelector || "#dataTable";
        this.searchSelector = options.searchSelector || "#searchInput";
        this.perPageSelector = options.perPageSelector || "#perPage";
        this.showDeletedSelector = options.showDeletedSelector || "#showDeleted";
        this.paginationSelector = options.paginationSelector || "#pagination";
        this.alertSelector = options.alertSelector || "#alertContainer";

        // Configuración de datos
        this.tableHeaders = options.tableHeaders || [];
        this.validationFields = options.validationFields || [];
        this.formFields = options.formFields || [];

        // Estado interno
        this.currentPage = 1;
        this.perPage = 10;
        this.sortField = options.defaultSortField || "created_at";
        this.sortDirection = options.defaultSortDirection || "desc";
        this.searchTerm = "";
        this.showDeleted = options.showDeleted || false;
        this.currentEntity = null;
        this.currentData = null;
        this.isEditing = false;
        this.alertTimeout = null;

        // Modo de registro único
        this.singleRecordMode = options.singleRecordMode || false;

        // Inicializar managers
        this.api = new ApiManager(this.routes);
        this.events = new EventManager();

        // Configuración de modales
        this.modalConfig = {
            width: options.modalWidth || "800px",
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonText: "Guardar",
            cancelButtonText: "Cancelar",
            customClass: {
                container: "swal-modal-container",
                popup: "swal-modal-popup",
                header: "swal-modal-header",
                title: "swal-modal-title",
                content: "swal-modal-content",
                actions: "swal-modal-actions",
                confirmButton: "swal-confirm-button",
                cancelButton: "swal-cancel-button"
            },
            ...options.modalConfig
        };
    }

    /**
     * Inicializar el sistema CRUD
     */
    init() {
        this.bindEvents();
        this.loadData();
    }

    /**
     * Vincular eventos del DOM
     */
    bindEvents() {
        // Búsqueda
        const searchInput = document.querySelector(this.searchSelector);
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce((e) => {
                this.searchTerm = e.target.value;
                this.currentPage = 1;
                this.loadData();
            }, 300));
        }

        // Elementos por página
        const perPageSelect = document.querySelector(this.perPageSelector);
        if (perPageSelect) {
            perPageSelect.addEventListener('change', (e) => {
                this.perPage = parseInt(e.target.value);
                this.currentPage = 1;
                this.loadData();
            });
        }

        // Mostrar eliminados
        const showDeletedCheckbox = document.querySelector(this.showDeletedSelector);
        if (showDeletedCheckbox) {
            showDeletedCheckbox.addEventListener('change', (e) => {
                this.showDeleted = e.target.checked;
                this.currentPage = 1;
                this.loadData();
            });
        }
    }

    /**
     * Cargar datos desde el servidor
     */
    async loadData() {
        try {
            const params = {
                page: this.currentPage,
                per_page: this.perPage,
                search: this.searchTerm,
                sort_field: this.sortField,
                sort_direction: this.sortDirection,
                show_deleted: this.showDeleted
            };

            const response = await this.api.getEntities(params);
            this.currentData = response;
            
            // Emitir evento de datos cargados
            this.events.emit('dataLoaded', response);
            
            return response;
        } catch (error) {
            console.error('Error loading data:', error);
            this.showAlert('Error al cargar los datos', 'error');
            throw error;
        }
    }

    /**
     * Cambiar página
     */
    changePage(page) {
        this.currentPage = page;
        this.loadData();
    }

    /**
     * Cambiar ordenamiento
     */
    changeSort(field) {
        if (this.sortField === field) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortField = field;
            this.sortDirection = 'asc';
        }
        this.currentPage = 1;
        this.loadData();
    }

    /**
     * Mostrar alerta
     */
    showAlert(message, type = 'info', duration = 5000) {
        const alertContainer = document.querySelector(this.alertSelector);
        if (!alertContainer) return;

        // Limpiar timeout anterior
        if (this.alertTimeout) {
            clearTimeout(this.alertTimeout);
        }

        // Crear elemento de alerta
        const alertElement = document.createElement('div');
        alertElement.className = `alert alert-${type} alert-dismissible fade show`;
        alertElement.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Agregar al contenedor
        alertContainer.innerHTML = '';
        alertContainer.appendChild(alertElement);

        // Auto-ocultar después del tiempo especificado
        if (duration > 0) {
            this.alertTimeout = setTimeout(() => {
                alertElement.remove();
            }, duration);
        }
    }

    /**
     * Función debounce para optimizar eventos
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

    /**
     * Obtener configuración de campo
     */
    getFieldConfig(fieldName) {
        return this.formFields.find(field => field.name === fieldName) || {};
    }

    /**
     * Obtener configuración de header de tabla
     */
    getHeaderConfig(fieldName) {
        return this.tableHeaders.find(header => header.key === fieldName) || {};
    }

    /**
     * Limpiar estado actual
     */
    clearCurrentState() {
        this.currentEntity = null;
        this.isEditing = false;
    }

    /**
     * Obtener valor del campo de búsqueda
     */
    getSearchValue() {
        const searchInput = document.querySelector(this.searchSelector);
        return searchInput ? searchInput.value.trim() : '';
    }

    /**
     * Obtener valor del selector de registros por página
     */
    getPerPageValue() {
        const perPageSelect = document.querySelector(this.perPageSelector);
        return perPageSelect ? parseInt(perPageSelect.value) : this.perPage;
    }

    /**
     * Actualizar paginación
     */
    updatePagination(response) {
        const paginationContainer = document.querySelector(this.paginationSelector);
        if (!paginationContainer || !response.meta) return;

        const { current_page, last_page, from, to, total } = response.meta;
        
        let paginationHtml = '<nav aria-label="Page navigation">';
        paginationHtml += '<ul class="pagination justify-content-center">';
        
        // Botón anterior
        if (current_page > 1) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${current_page - 1}">Previous</a></li>`;
        } else {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }
        
        // Páginas
        const startPage = Math.max(1, current_page - 2);
        const endPage = Math.min(last_page, current_page + 2);
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === current_page) {
                paginationHtml += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }
        
        // Botón siguiente
        if (current_page < last_page) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${current_page + 1}">Next</a></li>`;
        } else {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">Next</span></li>';
        }
        
        paginationHtml += '</ul></nav>';
        
        // Información de registros
        paginationHtml += `<div class="text-center mt-2"><small class="text-muted">Showing ${from} to ${to} of ${total} results</small></div>`;
        
        paginationContainer.innerHTML = paginationHtml;
        
        // Agregar event listeners
        paginationContainer.querySelectorAll('a[data-page]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                this.currentPage = parseInt(e.target.dataset.page);
                this.loadData();
            });
        });
    }

    /**
     * Obtener configuración de selectores desde options
     */
    getSelectors() {
        return {
            table: this.tableSelector,
            search: this.searchSelector,
            perPage: this.perPageSelector,
            showDeleted: this.showDeletedSelector,
            pagination: this.paginationSelector,
            alert: this.alertSelector
        };
    }
}