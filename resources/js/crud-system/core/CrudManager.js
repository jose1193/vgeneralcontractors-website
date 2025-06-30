import { CrudApiClient } from "./CrudApiClient.js";
import { CrudFormBuilder } from "./CrudFormBuilder.js";
import { CrudValidator } from "./CrudValidator.js";
import { CrudTableRenderer } from "./CrudTableRenderer.js";
import { CrudModalManager } from "./CrudModalManager.js";
import { CrudEventHandler } from "./CrudEventHandler.js";
import { debounce } from "../utils/CrudUtils.js";

export class CrudManager {
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
        this.showDeletedSelector =
            options.showDeletedSelector || "#showDeleted";
        this.paginationSelector = options.paginationSelector || "#pagination";
        this.alertSelector = options.alertSelector || "#alertContainer";

        // Configuración de tabla
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

        // Configuración de modales
        this.modalConfig = {
            width: options.modalWidth || "800px",
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonText: "Save",
            cancelButtonText: "Cancel",
        };

        // Configuración de colores para diferentes modos
        this.colorConfig = {
            create: { confirmButtonColor: "#10B981" },
            edit: { confirmButtonColor: "#3B82F6" },
        };

        // Configuración de traducciones
        this.translations = options.translations || {};

        // Configuración de entidad
        this.entityConfig = options.entityConfig || {
            identifierField: "name",
            displayName: "element",
            fallbackFields: ["title", "description", "email"],
        };

        // Inicializar módulos
        this.apiClient = new CrudApiClient(this.routes);
        this.formBuilder = new CrudFormBuilder(this.formFields);
        this.validator = new CrudValidator(this.formFields, this.routes);
        this.tableRenderer = new CrudTableRenderer(
            this.tableHeaders,
            this.tableSelector
        );
        this.modalManager = new CrudModalManager(
            this.modalConfig,
            this.colorConfig
        );
        this.eventHandler = new CrudEventHandler(this);
    }

    /**
     * Inicializar el manager
     */
    init() {
        // Establecer el estado inicial del toggle
        $(this.showDeletedSelector).prop("checked", this.showDeleted);

        this.eventHandler.attachEventListeners();
        this.loadEntities();
    }

    /**
     * Cargar entidades
     */
    async loadEntities(page = 1) {
        console.log("loadEntities called with page:", page);
        this.currentPage = page;

        // Mostrar loading
        this.tableRenderer.showTableLoading();

        const requestData = {
            page: this.currentPage,
            per_page: this.perPage,
            sort_field: this.sortField,
            sort_direction: this.sortDirection,
            search: this.searchTerm,
            show_deleted: this.showDeleted ? "true" : "false",
        };

        try {
            const response = await $.ajax({
                url: this.routes.index,
                type: "GET",
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                    Accept: "application/json",
                },
                data: requestData,
            });

            console.log("AJAX success response:", response);
            this.currentData = response;
            this.tableRenderer.renderTable(response, this.singleRecordMode);
            this.tableRenderer.renderPagination(
                response,
                this.singleRecordMode,
                this.paginationSelector
            );
        } catch (error) {
            console.error("Error loading entities:", error);
            this.modalManager.showAlert(
                "error",
                `Error loading ${this.entityNamePlural}: ${error.status} ${error.statusText}`
            );
            this.tableRenderer.hideTableLoading();
            throw error;
        }
    }

    /**
     * Mostrar modal de creación
     */
    async showCreateModal() {
        // En modo de registro único, redirigir a editar
        if (this.singleRecordMode) {
            await this.loadEntities();
            if (
                this.currentData &&
                this.currentData.data &&
                this.currentData.data.length > 0
            ) {
                const entity = this.currentData.data[0];
                await this.showEditModal(entity[this.idField]);
                return;
            }
            Swal.fire({
                icon: "info",
                title: "No data",
                text: "No se encontró información para editar. Contacte al administrador.",
            });
            return;
        }

        this.isEditing = false;
        this.currentEntity = null;

        const formHtml = this.formBuilder.generateFormHtml();
        await this.modalManager.showCreateModal(
            `Crear ${this.entityName}`,
            formHtml,
            () => this.validateAndGetFormData(),
            () => this.initializeFormElements(),
            (result) => this.createEntity(result.value)
        );
    }

    /**
     * Mostrar modal de edición
     */
    async showEditModal(id) {
        console.log("showEditModal called with id:", id);

        if (!id || id === "undefined" || id === "null") {
            console.error("Invalid ID provided to showEditModal:", id);
            this.modalManager.showAlert(
                "error",
                "Invalid ID provided for editing"
            );
            return;
        }

        this.isEditing = true;

        try {
            const editUrl = this.routes.edit.replace(":id", id);
            const response = await $.ajax({
                url: editUrl,
                type: "GET",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                    Accept: "application/json",
                },
            });

            this.currentEntity = response.data || response;
            const formHtml = this.formBuilder.generateFormHtml(
                this.currentEntity
            );

            await this.modalManager.showEditModal(
                `Editar ${this.entityName}`,
                formHtml,
                () => this.validateAndGetFormData(),
                () => {
                    this.initializeFormElements();
                    this.formBuilder.populateForm(this.currentEntity);
                    this.validator.verifyAndFixSelectValues(this.currentEntity);
                },
                (result) => this.updateEntity(id, result.value)
            );
        } catch (error) {
            console.error("Error loading entity for edit:", error);
            this.modalManager.showAlert(
                "error",
                "Error loading data for editing"
            );
        }
    }

    /**
     * Crear entidad
     */
    async createEntity(data) {
        try {
            Swal.showLoading();
            await this.apiClient.createEntity(data);
            Swal.close();
            this.modalManager.showAlert(
                "success",
                `${this.entityName} creado exitosamente`
            );
            this.loadEntities();
        } catch (error) {
            Swal.close();
            console.error("Error creating entity:", error);
            if (error.status === 422 && error.responseJSON?.errors) {
                this.modalManager.showValidationErrors(
                    error.responseJSON.errors
                );
            } else {
                this.modalManager.showAlert(
                    "error",
                    error.responseJSON?.message || "Error creating record"
                );
            }
        }
    }

    /**
     * Actualizar entidad
     */
    async updateEntity(id, data) {
        try {
            Swal.showLoading();
            await this.apiClient.updateEntity(id, data);
            Swal.close();
            this.modalManager.showAlert(
                "success",
                `${this.entityName} actualizado exitosamente`
            );
            this.loadEntities();
        } catch (error) {
            Swal.close();
            console.error("Error updating entity:", error);
            if (error.status === 422 && error.responseJSON?.errors) {
                this.modalManager.showValidationErrors(
                    error.responseJSON.errors
                );
            } else {
                this.modalManager.showAlert(
                    "error",
                    error.responseJSON?.message || "Error updating record"
                );
            }
        }
    }

    /**
     * Eliminar entidad
     */
    async deleteEntity(id) {
        const entity = await this.getEntityForConfirmation(id);
        const entityInfo = this.getEntityIdentifier(entity);

        let customMessage = this.translations.deleteMessage;
        if (entityInfo.identifier && entityInfo.identifier !== "this element") {
            customMessage = `¿Deseas eliminar ${entityInfo.displayName}: <strong>${entityInfo.identifier}</strong>?`;
        }

        const result = await Swal.fire({
            title: this.translations.confirmDelete,
            html: customMessage,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: this.translations.yesDelete,
            cancelButtonText: this.translations.cancel,
        });

        if (result.isConfirmed) {
            try {
                await this.apiClient.deleteEntity(id);
                this.modalManager.showAlert(
                    "success",
                    `${this.entityName} ${this.translations.deletedSuccessfully}`
                );
                this.loadEntities();
            } catch (error) {
                console.error("Error deleting entity:", error);
                this.modalManager.showAlert(
                    "error",
                    this.translations.errorDeleting
                );
            }
        }
    }

    /**
     * Restaurar entidad
     */
    async restoreEntity(id) {
        const entity = await this.getEntityForConfirmation(id);
        const entityInfo = this.getEntityIdentifier(entity);

        let customMessage = this.translations.restoreMessage;
        if (entityInfo.identifier && entityInfo.identifier !== "this element") {
            customMessage = `¿Deseas restaurar ${entityInfo.displayName}: <strong>${entityInfo.identifier}</strong>?`;
        }

        const result = await Swal.fire({
            title: this.translations.confirmRestore,
            html: customMessage,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: this.translations.yesRestore,
            cancelButtonText: this.translations.cancel,
        });

        if (result.isConfirmed) {
            try {
                await this.apiClient.restoreEntity(id);
                this.modalManager.showAlert(
                    "success",
                    `${this.entityName} ${this.translations.restoredSuccessfully}`
                );
                this.loadEntities();
            } catch (error) {
                console.error("Error restoring entity:", error);
                this.modalManager.showAlert(
                    "error",
                    this.translations.errorRestoring
                );
            }
        }
    }

    /**
     * Inicializar elementos del formulario
     */
    initializeFormElements() {
        this.validator.setupPhoneFormatting();
        this.validator.setupRealTimeValidation();
        this.validator.setupAutoCapitalization();
    }

    /**
     * Validar y obtener datos del formulario
     */
    validateAndGetFormData() {
        return this.validator.validateAndGetFormData(this.isEditing);
    }

    /**
     * Obtener entidad para confirmación
     */
    async getEntityForConfirmation(id) {
        const buttonElement = $(
            `.delete-btn[data-id="${id}"], .restore-btn[data-id="${id}"]`
        )[0];
        if (buttonElement) {
            return this.getEntityDataFromRow(buttonElement);
        }

        try {
            const response = await $.ajax({
                url: this.routes.edit.replace(":id", id),
                type: "GET",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                    Accept: "application/json",
                },
            });
            return response.data || response;
        } catch (error) {
            console.error("Error getting entity data:", error);
            return null;
        }
    }

    /**
     * Obtener datos de entidad desde la fila de la tabla
     */
    getEntityDataFromRow(button) {
        try {
            const row = $(button).closest("tr");
            const entityDataString = row.attr("data-entity");
            if (entityDataString) {
                return JSON.parse(entityDataString.replace(/&quot;/g, '"'));
            }
            return null;
        } catch (error) {
            console.error("Error parsing entity data from row:", error);
            return null;
        }
    }

    /**
     * Obtener identificador de entidad
     */
    getEntityIdentifier(entity) {
        if (
            this.entityConfig.detailFormat &&
            typeof this.entityConfig.detailFormat === "function"
        ) {
            try {
                const customIdentifier = this.entityConfig.detailFormat(entity);
                return {
                    identifier: customIdentifier,
                    displayName: this.entityConfig.displayName,
                    field: "custom",
                };
            } catch (error) {
                console.error("Error using custom detail format:", error);
            }
        }

        if (entity && entity[this.entityConfig.identifierField]) {
            return {
                identifier: entity[this.entityConfig.identifierField],
                displayName: this.entityConfig.displayName,
                field: this.entityConfig.identifierField,
            };
        }

        for (const field of this.entityConfig.fallbackFields) {
            if (entity && entity[field]) {
                return {
                    identifier: entity[field],
                    displayName: this.entityConfig.displayName,
                    field: field,
                };
            }
        }

        return {
            identifier: "this element",
            displayName: "element",
            field: null,
        };
    }
}
