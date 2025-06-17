/**
 * CRUD Manager Modal - Maneja operaciones CRUD usando SweetAlert2 modales
 * Basado en crud-manager.js pero optimizado para modales
 */
class CrudManagerModal {
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
        this.isEditing = false;

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
                content: "swal-modal-content",
            },
        };

        this.init();
    }

    /**
     * Inicializar el manager
     */
    init() {
        this.attachEventListeners();
        this.loadEntities();
    }

    /**
     * Adjuntar event listeners
     */
    attachEventListeners() {
        // Búsqueda
        $(this.searchSelector).on(
            "input",
            debounce(() => {
                this.searchTerm = $(this.searchSelector).val();
                this.currentPage = 1;
                this.loadEntities();
            }, 300)
        );

        // Paginación
        $(this.perPageSelector).on("change", () => {
            this.perPage = $(this.perPageSelector).val();
            this.currentPage = 1;
            this.loadEntities();
        });

        // Toggle eliminados
        $(this.showDeletedSelector).on("change", () => {
            this.showDeleted = $(this.showDeletedSelector).is(":checked");
            this.currentPage = 1;
            this.loadEntities();
        });

        // Botones de acción en la tabla
        $(document).on("click", ".create-btn", () => this.showCreateModal());
        $(document).on("click", ".edit-btn", (e) => {
            const id = $(e.currentTarget).data("id");
            this.showEditModal(id);
        });
        $(document).on("click", ".delete-btn", (e) => {
            const id = $(e.currentTarget).data("id");
            this.deleteEntity(id);
        });
        $(document).on("click", ".restore-btn", (e) => {
            const id = $(e.currentTarget).data("id");
            this.restoreEntity(id);
        });

        // Ordenamiento
        $(document).on("click", ".sort-header", (e) => {
            const field = $(e.currentTarget).data("field");
            if (this.sortField === field) {
                this.sortDirection =
                    this.sortDirection === "asc" ? "desc" : "asc";
            } else {
                this.sortField = field;
                this.sortDirection = "asc";
            }
            this.loadEntities();
        });
    }

    /**
     * Cargar entidades
     */
    loadEntities(page = 1) {
        this.currentPage = page;

        // Mostrar loading
        this.showTableLoading();

        const requestData = {
            page: this.currentPage,
            per_page: this.perPage,
            sort_field: this.sortField,
            sort_direction: this.sortDirection,
            search: this.searchTerm,
            show_deleted: this.showDeleted ? "true" : "false",
        };

        $.ajax({
            url: this.routes.index,
            type: "GET",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                Accept: "application/json",
            },
            data: requestData,
            success: (response) => {
                this.renderTable(response);
                this.renderPagination(response);
            },
            error: (xhr) => {
                console.error(
                    `Error loading ${this.entityNamePlural}:`,
                    xhr.responseText
                );
                this.showAlert(
                    "error",
                    `Error loading ${this.entityNamePlural}`
                );
                this.hideTableLoading();
            },
        });
    }

    /**
     * Mostrar modal de creación
     */
    async showCreateModal() {
        this.isEditing = false;
        this.currentEntity = null;

        const formHtml = this.generateFormHtml();

        const result = await Swal.fire({
            title: `Crear ${this.entityName}`,
            html: formHtml,
            width: this.modalConfig.width,
            showCloseButton: this.modalConfig.showCloseButton,
            showCancelButton: this.modalConfig.showCancelButton,
            confirmButtonText: this.modalConfig.confirmButtonText,
            cancelButtonText: this.modalConfig.cancelButtonText,
            customClass: this.modalConfig.customClass,
            preConfirm: () => {
                return this.validateAndGetFormData();
            },
            didOpen: () => {
                this.initializeFormElements();
            },
        });

        if (result.isConfirmed && result.value) {
            await this.createEntity(result.value);
        }
    }

    /**
     * Mostrar modal de edición
     */
    async showEditModal(id) {
        this.isEditing = true;

        // Cargar datos de la entidad
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

            this.currentEntity = response.data || response;

            const formHtml = this.generateFormHtml(this.currentEntity);

            const result = await Swal.fire({
                title: `Editar ${this.entityName}`,
                html: formHtml,
                width: this.modalConfig.width,
                showCloseButton: this.modalConfig.showCloseButton,
                showCancelButton: this.modalConfig.showCancelButton,
                confirmButtonText: "Actualizar",
                cancelButtonText: this.modalConfig.cancelButtonText,
                customClass: this.modalConfig.customClass,
                preConfirm: () => {
                    return this.validateAndGetFormData();
                },
                didOpen: () => {
                    this.initializeFormElements();
                    this.populateForm(this.currentEntity);
                },
            });

            if (result.isConfirmed && result.value) {
                await this.updateEntity(id, result.value);
            }
        } catch (error) {
            console.error("Error loading entity for edit:", error);
            this.showAlert("error", "Error al cargar los datos para editar");
        }
    }

    /**
     * Generar HTML del formulario
     */
    generateFormHtml(entity = null) {
        let html = '<div class="crud-modal-form">';

        this.formFields.forEach((field) => {
            html += this.generateFieldHtml(field, entity);
        });

        html += "</div>";
        return html;
    }

    /**
     * Generar HTML de un campo
     */
    generateFieldHtml(field, entity = null) {
        const value = entity ? entity[field.name] || "" : "";
        const required = field.required ? "required" : "";
        const disabled = field.disabled ? "disabled" : "";

        let html = `<div class="form-group mb-4">`;
        html += `<label for="${field.name}" class="block text-sm font-medium text-gray-700 mb-2">${field.label}</label>`;

        switch (field.type) {
            case "text":
            case "email":
            case "number":
            case "tel":
                html += `<input type="${field.type}" id="${field.name}" name="${
                    field.name
                }" value="${value}" ${required} ${disabled} class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="${
                    field.placeholder || ""
                }">`;
                break;

            case "textarea":
                html += `<textarea id="${field.name}" name="${
                    field.name
                }" ${required} ${disabled} rows="${
                    field.rows || 3
                }" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="${
                    field.placeholder || ""
                }">${value}</textarea>`;
                break;

            case "select":
                html += `<select id="${field.name}" name="${field.name}" ${required} ${disabled} class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">`;
                if (field.placeholder) {
                    html += `<option value="">${field.placeholder}</option>`;
                }
                field.options.forEach((option) => {
                    const selected = value == option.value ? "selected" : "";
                    html += `<option value="${option.value}" ${selected}>${option.label}</option>`;
                });
                html += `</select>`;
                break;

            case "checkbox":
                const checked = value ? "checked" : "";
                html += `<div class="flex items-center">`;
                html += `<input type="checkbox" id="${field.name}" name="${field.name}" ${checked} ${disabled} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">`;
                html += `<label for="${
                    field.name
                }" class="ml-2 block text-sm text-gray-700">${
                    field.checkboxLabel || field.label
                }</label>`;
                html += `</div>`;
                break;

            case "date":
                html += `<input type="date" id="${field.name}" name="${field.name}" value="${value}" ${required} ${disabled} class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">`;
                break;

            case "time":
                html += `<input type="time" id="${field.name}" name="${field.name}" value="${value}" ${required} ${disabled} class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">`;
                break;
        }

        if (field.help) {
            html += `<p class="mt-1 text-sm text-gray-500">${field.help}</p>`;
        }

        html += `<div class="error-message text-red-500 text-sm mt-1 hidden" id="error-${field.name}"></div>`;
        html += `</div>`;

        return html;
    }

    /**
     * Inicializar elementos del formulario
     */
    initializeFormElements() {
        // Aquí puedes agregar inicializaciones específicas como:
        // - Selectores de fecha
        // - Validaciones en tiempo real
        // - Formateo de campos

        // Ejemplo: validación en tiempo real
        this.formFields.forEach((field) => {
            if (field.validation) {
                $(`#${field.name}`).on("blur", () => {
                    this.validateField(field.name);
                });
            }
        });
    }

    /**
     * Poblar formulario con datos
     */
    populateForm(entity) {
        this.formFields.forEach((field) => {
            const element = $(`#${field.name}`);
            const value = entity[field.name];

            if (field.type === "checkbox") {
                element.prop("checked", !!value);
            } else {
                element.val(value || "");
            }
        });
    }

    /**
     * Validar y obtener datos del formulario
     */
    validateAndGetFormData() {
        const formData = {};
        let isValid = true;

        // Limpiar errores previos
        $(".error-message").addClass("hidden").text("");

        this.formFields.forEach((field) => {
            const element = $(`#${field.name}`);
            let value;

            if (field.type === "checkbox") {
                value = element.is(":checked");
            } else {
                value = element.val();
            }

            // Validación requerida
            if (field.required && (!value || value.toString().trim() === "")) {
                this.showFieldError(field.name, `${field.label} es requerido`);
                isValid = false;
            }

            // Validaciones específicas
            if (value && field.validation) {
                const validationResult = this.validateFieldValue(field, value);
                if (!validationResult.valid) {
                    this.showFieldError(field.name, validationResult.message);
                    isValid = false;
                }
            }

            formData[field.name] = value;
        });

        return isValid ? formData : false;
    }

    /**
     * Validar valor de campo
     */
    validateFieldValue(field, value) {
        const validation = field.validation;

        if (validation.minLength && value.length < validation.minLength) {
            return {
                valid: false,
                message: `Mínimo ${validation.minLength} caracteres`,
            };
        }

        if (validation.maxLength && value.length > validation.maxLength) {
            return {
                valid: false,
                message: `Máximo ${validation.maxLength} caracteres`,
            };
        }

        if (validation.pattern && !validation.pattern.test(value)) {
            return {
                valid: false,
                message: validation.message || "Formato inválido",
            };
        }

        if (validation.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            return { valid: false, message: "Email inválido" };
        }

        return { valid: true };
    }

    /**
     * Mostrar error de campo
     */
    showFieldError(fieldName, message) {
        $(`#error-${fieldName}`).removeClass("hidden").text(message);
    }

    /**
     * Crear entidad
     */
    async createEntity(data) {
        try {
            Swal.showLoading();

            const response = await $.ajax({
                url: this.routes.store,
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                    Accept: "application/json",
                },
                data: data,
            });

            Swal.close();
            this.showAlert("success", `${this.entityName} creado exitosamente`);
            this.loadEntities();
        } catch (error) {
            Swal.close();
            console.error("Error creating entity:", error);

            if (error.status === 422 && error.responseJSON?.errors) {
                this.showValidationErrors(error.responseJSON.errors);
            } else {
                this.showAlert("error", "Error al crear el registro");
            }
        }
    }

    /**
     * Actualizar entidad
     */
    async updateEntity(id, data) {
        try {
            Swal.showLoading();

            const response = await $.ajax({
                url: this.routes.update.replace(":id", id),
                type: "PUT",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                    Accept: "application/json",
                },
                data: data,
            });

            Swal.close();
            this.showAlert(
                "success",
                `${this.entityName} actualizado exitosamente`
            );
            this.loadEntities();
        } catch (error) {
            Swal.close();
            console.error("Error updating entity:", error);

            if (error.status === 422 && error.responseJSON?.errors) {
                this.showValidationErrors(error.responseJSON.errors);
            } else {
                this.showAlert("error", "Error al actualizar el registro");
            }
        }
    }

    /**
     * Eliminar entidad
     */
    async deleteEntity(id) {
        const result = await Swal.fire({
            title: "¿Estás seguro?",
            text: `¿Deseas eliminar este ${this.entityName}?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        });

        if (result.isConfirmed) {
            try {
                await $.ajax({
                    url: this.routes.destroy.replace(":id", id),
                    type: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                        Accept: "application/json",
                    },
                });

                this.showAlert(
                    "success",
                    `${this.entityName} eliminado exitosamente`
                );
                this.loadEntities();
            } catch (error) {
                console.error("Error deleting entity:", error);
                this.showAlert("error", "Error al eliminar el registro");
            }
        }
    }

    /**
     * Restaurar entidad
     */
    async restoreEntity(id) {
        const result = await Swal.fire({
            title: "¿Restaurar registro?",
            text: `¿Deseas restaurar este ${this.entityName}?`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Sí, restaurar",
            cancelButtonText: "Cancelar",
        });

        if (result.isConfirmed) {
            try {
                await $.ajax({
                    url: this.routes.restore.replace(":id", id),
                    type: "PATCH",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                        Accept: "application/json",
                    },
                });

                this.showAlert(
                    "success",
                    `${this.entityName} restaurado exitosamente`
                );
                this.loadEntities();
            } catch (error) {
                console.error("Error restoring entity:", error);
                this.showAlert("error", "Error al restaurar el registro");
            }
        }
    }

    /**
     * Renderizar tabla
     */
    renderTable(data) {
        const entities = data.data;
        let html = "";

        if (entities.length === 0) {
            html = `<tr><td colspan="${this.tableHeaders.length}" class="px-6 py-4 text-center text-sm text-gray-500">No se encontraron registros</td></tr>`;
        } else {
            entities.forEach((entity) => {
                const isDeleted = entity.deleted_at !== null;
                const rowClass = isDeleted
                    ? "bg-red-50 dark:bg-red-900 opacity-60"
                    : "";

                html += `<tr class="${rowClass}">`;

                this.tableHeaders.forEach((header) => {
                    let value = header.getter
                        ? header.getter(entity)
                        : entity[header.field];
                    html += `<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 text-center">${value}</td>`;
                });

                html += `</tr>`;
            });
        }

        $(this.tableSelector).html(html);
    }

    /**
     * Renderizar paginación
     */
    renderPagination(data) {
        let paginationHtml = "";

        if (data.last_page > 1) {
            paginationHtml += '<div class="flex items-center justify-between">';
            paginationHtml += `<div class="text-sm text-gray-700">Mostrando ${data.from} a ${data.to} de ${data.total} resultados</div>`;
            paginationHtml += '<div class="flex space-x-1">';

            // Botón anterior
            if (data.current_page > 1) {
                paginationHtml += `<button class="pagination-btn px-3 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50" data-page="${
                    data.current_page - 1
                }">Anterior</button>`;
            }

            // Números de página
            for (
                let i = Math.max(1, data.current_page - 2);
                i <= Math.min(data.last_page, data.current_page + 2);
                i++
            ) {
                const activeClass =
                    i === data.current_page
                        ? "bg-blue-500 text-white"
                        : "bg-white text-gray-700 hover:bg-gray-50";
                paginationHtml += `<button class="pagination-btn px-3 py-2 text-sm border border-gray-300 rounded-md ${activeClass}" data-page="${i}">${i}</button>`;
            }

            // Botón siguiente
            if (data.current_page < data.last_page) {
                paginationHtml += `<button class="pagination-btn px-3 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50" data-page="${
                    data.current_page + 1
                }">Siguiente</button>`;
            }

            paginationHtml += "</div></div>";
        }

        $(this.paginationSelector).html(paginationHtml);

        // Event listener para paginación
        $(".pagination-btn").on("click", (e) => {
            const page = $(e.target).data("page");
            this.loadEntities(page);
        });
    }

    /**
     * Mostrar loading en tabla
     */
    showTableLoading() {
        const loadingHtml = `
            <tr id="loadingRow">
                <td colspan="${this.tableHeaders.length}" class="px-6 py-4 text-center">
                    <svg class="animate-spin h-5 w-5 mr-3 text-blue-500 inline-block" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Cargando...
                </td>
            </tr>
        `;
        $(this.tableSelector).html(loadingHtml);
    }

    /**
     * Ocultar loading en tabla
     */
    hideTableLoading() {
        $("#loadingRow").remove();
    }

    /**
     * Mostrar alerta
     */
    showAlert(type, message) {
        const alertClass =
            type === "success"
                ? "bg-green-100 border-green-400 text-green-700"
                : "bg-red-100 border-red-400 text-red-700";
        const alertHtml = `
            <div class="alert ${alertClass} border px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">${message}</span>
            </div>
        `;

        $(this.alertSelector).html(alertHtml);

        // Auto-hide después de 5 segundos
        setTimeout(() => {
            $(this.alertSelector).fadeOut();
        }, 5000);
    }

    /**
     * Mostrar errores de validación
     */
    showValidationErrors(errors) {
        let errorMessage = "Errores de validación:\n";
        Object.keys(errors).forEach((field) => {
            errorMessage += `• ${errors[field][0]}\n`;
        });

        Swal.fire({
            icon: "error",
            title: "Errores de validación",
            text: errorMessage,
        });
    }
}

/**
 * Función debounce para optimizar búsquedas
 */
function debounce(func, wait) {
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

// Hacer disponible globalmente
window.CrudManagerModal = CrudManagerModal;
