/**
 * CRUD System - Punto de entrada principal
 * Sistema modular para operaciones CRUD
 */

// Core modules
import { CrudCore } from './core/CrudCore.js';
import { TableManager } from './components/TableManager.js';
import { FormManager } from './components/FormManager.js';
import { ModalManager } from './components/ModalManager.js';
import { ValidationManager } from './validation/ValidationManager.js';
import { ApiManager } from './core/ApiManager.js';
import { EventManager } from './core/EventManager.js';

// Utility modules
import { DomUtils } from './utils/DomUtils.js';
import { StringUtils } from './utils/StringUtils.js';
import { DebounceUtils } from './utils/DebounceUtils.js';

/**
 * CrudManagerModal - Clase principal que integra todos los módulos
 * Mantiene compatibilidad con la API existente
 */
class CrudManagerModal extends CrudCore {
    constructor(config) {
        super(config);
        
        // Inicializar managers
        this.apiManager = new ApiManager(config.routes);
        this.tableManager = new TableManager({
            tableSelector: config.selectors?.table || config.tableSelector,
            headers: config.tableHeaders,
            actions: config.actions || ['edit', 'delete'],
            idField: config.idField || 'id',
            onSort: (field, direction) => this.changeSort(field),
            onEdit: (id) => this.showEditModal(id),
            onDelete: (id) => this.deleteEntity(id),
            onRestore: (id) => this.restoreEntity(id)
        });
        this.formManager = new FormManager();
        this.modalManager = new ModalManager();
        this.validationManager = new ValidationManager();
        this.eventManager = new EventManager();
        
        // Configurar eventos entre módulos
        this.setupModuleEvents();
        
        // Inicializar componentes
        this.init();
    }
    
    setupModuleEvents() {
        // Eventos de validación
        this.eventManager.on('validation:field', (data) => {
            this.validationManager.validateField(data.field, data.value, data.rules);
        });
        
        // Eventos de tabla
        this.eventManager.on('table:refresh', () => {
            this.loadEntities();
        });
        
        // Eventos de modal
        this.eventManager.on('modal:close', () => {
            this.formManager.clearForm();
        });
    }
    
    // Métodos de compatibilidad con la API existente
    async loadEntities() {
        try {
            const response = await this.api.getEntities({
                search: this.getSearchValue(),
                page: this.currentPage,
                per_page: this.getPerPageValue(),
                sort_field: this.sortField,
                sort_direction: this.sortDirection,
                show_deleted: this.showDeleted
            });
            
            this.tableManager.renderTable(response);
            this.updatePagination(response);
            
        } catch (error) {
            console.error('Error loading entities:', error);
            this.showAlert('Error loading data', 'error');
        }
    }
    
    async createEntity(data) {
        try {
            const response = await this.api.createEntity(data);
            this.modalManager.close();
            this.showAlert(`${this.entityName} created successfully`, 'success');
            this.loadEntities();
            return response;
        } catch (error) {
            console.error('Error creating entity:', error);
            this.showAlert('Error creating record', 'error');
            throw error;
        }
    }
    
    async updateEntity(id, data) {
        try {
            const response = await this.api.updateEntity(id, data);
            this.modalManager.close();
            this.showAlert(`${this.entityName} updated successfully`, 'success');
            this.loadEntities();
            return response;
        } catch (error) {
            console.error('Error updating entity:', error);
            this.showAlert('Error updating record', 'error');
            throw error;
        }
    }
    
    async deleteEntity(id) {
        try {
            const confirmed = await this.modalManager.showConfirmation(
                this.translations.confirmDelete || 'Are you sure?',
                this.translations.deleteMessage || 'This action cannot be undone.'
            );
            
            if (confirmed) {
                await this.api.deleteEntity(id);
                this.showAlert(`${this.entityName} deleted successfully`, 'success');
                this.loadEntities();
            }
        } catch (error) {
            console.error('Error deleting entity:', error);
            this.showAlert('Error deleting record', 'error');
        }
    }
    
    async restoreEntity(id) {
        try {
            const confirmed = await this.modalManager.showConfirmation(
                this.translations.confirmRestore || 'Restore record?',
                this.translations.restoreMessage || 'Do you want to restore this record?'
            );
            
            if (confirmed) {
                await this.api.restoreEntity(id);
                this.showAlert(`${this.entityName} restored successfully`, 'success');
                this.loadEntities();
            }
        } catch (error) {
            console.error('Error restoring entity:', error);
            this.showAlert('Error restoring record', 'error');
        }
    }
    
    showCreateModal() {
        const formHtml = this.formManager.generateFormHtml(this.formFields);
        this.modalManager.showForm(
            `Create ${this.entityName}`,
            formHtml,
            async (formData) => {
                await this.createEntity(formData);
            }
        );
    }
    
    async showEditModal(id) {
        try {
            const entity = await this.api.getEntity(id);
            const formHtml = this.formManager.generateFormHtml(this.formFields, entity);
            
            this.modalManager.showForm(
                `Edit ${this.entityName}`,
                formHtml,
                async (formData) => {
                    await this.updateEntity(id, formData);
                }
            );
        } catch (error) {
            console.error('Error loading entity for edit:', error);
            this.showAlert('Error loading record', 'error');
        }
    }
}

// Export all modules
export {
    CrudManagerModal,
    CrudCore,
    TableManager,
    FormManager,
    ModalManager,
    ValidationManager,
    ApiManager,
    EventManager,
    DomUtils,
    StringUtils,
    DebounceUtils
};

// Make modules globally available for compatibility
if (typeof window !== 'undefined') {
    window.CrudSystem = {
        CrudManagerModal,
        CrudManager: CrudManagerModal, // Alias for compatibility
        CrudCore,
        TableManager,
        FormManager,
        ModalManager,
        ValidationManager,
        ApiManager,
        EventManager,
        DomUtils,
        StringUtils,
        DebounceUtils
    };
}