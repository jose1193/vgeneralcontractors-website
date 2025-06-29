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
        this.currentData = null; // Para almacenar los datos actuales
        this.isEditing = false;
        this.alertTimeout = null; // Para manejar timeouts de alertas

        // Modo de registro único (solo editar, no crear/eliminar)
        this.singleRecordMode = options.singleRecordMode || false;

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

        // Configuración de colores para diferentes modos
        this.colorConfig = {
            create: {
                confirmButtonColor: "#10B981", // Verde
                headerColor: "#10B981",
                headerTextColor: "#FFFFFF",
            },
            edit: {
                confirmButtonColor: "#3B82F6", // Azul
                headerColor: "#3B82F6",
                headerTextColor: "#FFFFFF",
            },
        };

        // Configuración de traducciones
        this.translations = options.translations || {
            confirmDelete: "¿Estás seguro?",
            deleteMessage: "¿Deseas eliminar este elemento?",
            confirmRestore: "¿Restaurar registro?",
            restoreMessage: "¿Deseas restaurar este elemento?",
            yesDelete: "Sí, eliminar",
            yesRestore: "Sí, restaurar",
            cancel: "Cancelar",
            deletedSuccessfully: "eliminado exitosamente",
            restoredSuccessfully: "restaurado exitosamente",
            errorDeleting: "Error al eliminar el registro",
            errorRestoring: "Error al restaurar el registro",
        };

        // Configuración de campos para identificar entidades
        // Ejemplos de configuración para diferentes tipos de entidades:
        //
        // Para usuarios:
        // entityConfig: {
        //     identifierField: 'email',
        //     displayName: 'usuario',
        //     fallbackFields: ['name', 'username'],
        //     detailFormat: (entity) => `${entity.name} (${entity.email})`
        // }
        //
        // Para productos:
        // entityConfig: {
        //     identifierField: 'name',
        //     displayName: 'producto',
        //     fallbackFields: ['title', 'sku', 'code'],
        //     detailFormat: (entity) => entity.sku ? `${entity.name} - SKU: ${entity.sku}` : entity.name
        // }
        //
        // Para empresas:
        // entityConfig: {
        //     identifierField: 'company_name',
        //     displayName: 'empresa',
        //     fallbackFields: ['name', 'business_name', 'title'],
        //     detailFormat: (entity) => entity.company_name || entity.name || 'empresa'
        // }
        //
        this.entityConfig = options.entityConfig || {
            identifierField: "name", // Campo principal para identificar
            displayName: "elemento", // Nombre para mostrar en español
            fallbackFields: ["title", "description", "email"], // Campos alternativos
        };

        this.init();
    }

    /**
     * Inicializar el manager
     */
    init() {
        // Establecer el estado inicial del toggle
        $(this.showDeletedSelector).prop("checked", this.showDeleted);

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
            localStorage.setItem("showDeleted", this.showDeleted);
            this.currentPage = 1;
            this.loadEntities();
        });

        // Botones de acción en la tabla
        $(document).on("click", ".create-btn", () => this.showCreateModal());
        $(document).on("click", ".edit-btn", (e) => {
            const id = $(e.currentTarget).data("id");
            // Solo ejecutar si hay un ID válido
            if (id && id !== "undefined" && id !== "null") {
                this.showEditModal(id);
            } else {
                console.warn(
                    "Edit button clicked but no valid data-id found:",
                    id
                );
            }
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

            // Update sort classes
            $(".sort-header").removeClass("sort-asc sort-desc");
            $(e.currentTarget).addClass(
                this.sortDirection === "asc" ? "sort-asc" : "sort-desc"
            );

            this.loadEntities();
        });
    }

    /**
     * Cargar entidades
     */
    loadEntities(page = 1) {
        console.log("loadEntities called with page:", page);
        console.log("Routes:", this.routes);
        console.log("Index route:", this.routes.index);

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

        console.log("Making AJAX request with data:", requestData);

        return $.ajax({
            url: this.routes.index,
            type: "GET",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                Accept: "application/json",
            },
            data: requestData,
            success: (response) => {
                console.log("AJAX success response:", response);
                this.currentData = response; // Almacenar datos para el modo de registro único
                this.renderTable(response);
                this.renderPagination(response);
            },
            error: (xhr) => {
                console.error("AJAX error:", xhr);
                console.error("Error status:", xhr.status);
                console.error("Error statusText:", xhr.statusText);
                console.error("Error responseText:", xhr.responseText);
                try {
                    const responseJSON = JSON.parse(xhr.responseText);
                    console.error("Error responseJSON:", responseJSON);
                } catch (e) {
                    console.error("Could not parse error response as JSON");
                }
                console.error(
                    `Error loading ${this.entityNamePlural}:`,
                    xhr.responseText
                );
                this.showAlert(
                    "error",
                    `Error loading ${this.entityNamePlural}: ${xhr.status} ${xhr.statusText}`
                );
                this.hideTableLoading();
                throw new Error(
                    `Error loading ${this.entityNamePlural}: ${xhr.status} ${xhr.statusText}`
                );
            },
        });
    }

    /**
     * Limpiar alertas
     */
    clearAlerts() {
        if (this.alertTimeout) {
            clearTimeout(this.alertTimeout);
            this.alertTimeout = null;
        }
        $(this.alertSelector).empty().show();
    }

    /**
     * Mostrar modal de creación
     */
    async showCreateModal() {
        // Limpiar alertas previas
        this.clearAlerts();

        // En modo de registro único, redirigir a editar el registro existente
        if (this.singleRecordMode) {
            // Cargar entidades para obtener el registro único
            await this.loadEntities();

            // Si hay datos, abrir el modal de edición
            if (
                this.currentData &&
                this.currentData.data &&
                this.currentData.data.length > 0
            ) {
                const entity = this.currentData.data[0];
                await this.showEditModal(entity[this.idField]);
                return;
            }

            // Si no hay datos, mostrar mensaje
            Swal.fire({
                icon: "info",
                title: "Sin datos",
                text: "No se encontró información para editar. Contacte al administrador.",
            });
            return;
        }

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
            confirmButtonColor: this.colorConfig.create.confirmButtonColor,
            customClass: this.modalConfig.customClass,
            preConfirm: () => {
                return this.validateAndGetFormData();
            },
            didOpen: () => {
                this.initializeFormElements();
                this.applyHeaderColor("create");
                // Verificar estado inicial del botón
                setTimeout(() => this.updateSubmitButtonState(), 200);
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
        // Debug: Log del ID recibido
        console.log("showEditModal called with id:", id);

        // Limpiar alertas previas
        this.clearAlerts();

        this.isEditing = true;

        // Validar que tenemos un ID válido
        if (!id || id === "undefined" || id === "null") {
            console.error("Invalid ID provided to showEditModal:", id);
            this.showAlert("error", "Invalid ID provided for editing");
            return;
        }

        // Cargar datos de la entidad
        try {
            const editUrl = this.routes.edit.replace(":id", id);
            console.log("Edit URL:", editUrl);

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

            console.log("Edit AJAX response received successfully");
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
                confirmButtonColor: this.colorConfig.edit.confirmButtonColor,
                customClass: this.modalConfig.customClass,
                preConfirm: () => {
                    return this.validateAndGetFormData();
                },
                didOpen: () => {
                    this.initializeFormElements();
                    this.populateForm(this.currentEntity);
                    this.applyHeaderColor("edit");

                    // Initialize form elements and populate data

                    // Verificar y corregir valores de select después de un breve delay
                    setTimeout(() => {
                        this.verifyAndFixSelectValues(this.currentEntity);
                        // Verificar estado inicial del botón después de cargar datos
                        this.updateSubmitButtonState();
                    }, 200);
                },
            });

            if (result.isConfirmed && result.value) {
                await this.updateEntity(id, result.value);
            }
        } catch (error) {
            console.error("Error loading entity for edit:", error);
            console.error("Error details:", {
                status: error.status,
                statusText: error.statusText,
                responseText: error.responseText,
                url: this.routes.edit.replace(":id", id),
            });
            this.showAlert(
                "error",
                this.translations.errorLoadingDataForEdit ||
                    "Error al cargar los datos para editar"
            );
        }
    }

    /**
     * Generar HTML del formulario
     */
    generateFormHtml(entity = null) {
        let html = '<div class="crud-modal-form">';
        const isEditMode = entity !== null;

        this.formFields.forEach((field) => {
            // Verificar si el campo debe mostrarse en el modo actual
            if (field.showInCreate === false && !isEditMode) {
                return; // No mostrar el campo si es creación y showInCreate es false
            }
            if (field.showInEdit === false && isEditMode) {
                return; // No mostrar el campo si es edición y showInEdit es false
            }

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

        // Para campos hidden, no necesitamos wrapper ni label
        if (field.type === "hidden") {
            const hiddenValue = field.value || value || "";
            return `<input type="hidden" id="${field.name}" name="${field.name}" value="${hiddenValue}">`;
        }

        let html = `<div class="form-group mb-4">`;
        const labelClass = field.required
            ? "block text-sm font-medium text-gray-700 mb-2 required"
            : "block text-sm font-medium text-gray-700 mb-2";
        html += `<label for="${field.name}" class="${labelClass}">${field.label}</label>`;

        switch (field.type) {
            case "text":
            case "email":
            case "number":
            case "tel":
            case "url":
                const capitalizationClass =
                    field.type === "text" && field.capitalize
                        ? " auto-capitalize"
                        : "";
                html += `<input type="${field.type}" id="${field.name}" name="${
                    field.name
                }" value="${value}" ${required} ${disabled} class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent${capitalizationClass}" placeholder="${
                    field.placeholder || ""
                }">`;
                break;

            case "textarea":
                const textareaCapitalizationClass = field.capitalize
                    ? " auto-capitalize"
                    : "";
                html += `<textarea id="${field.name}" name="${
                    field.name
                }" ${required} ${disabled} rows="${
                    field.rows || 3
                }" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent${textareaCapitalizationClass}" placeholder="${
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
                    html += `<option value="${option.value}" ${selected}>${option.text}</option>`;
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
        // Configurar formato de teléfono en tiempo real
        this.setupPhoneFormatting();

        // Configurar validación en tiempo real
        this.setupRealTimeValidation();

        // Configurar capitalización automática
        this.setupAutoCapitalization();
    }

    /**
     * Configurar formato de teléfono en tiempo real
     */
    setupPhoneFormatting() {
        const phoneField = document.getElementById("phone");
        if (phoneField) {
            phoneField.addEventListener("input", (e) => {
                this.formatPhoneInput(e);
            });
        }
    }

    /**
     * Formatear entrada de teléfono
     */
    formatPhoneInput(event) {
        const input = event.target;
        const isBackspace = event.inputType === "deleteContentBackward";
        let value = input.value.replace(/\D/g, "");

        if (isBackspace) {
            // Para backspace, mantener el valor actual sin agregar más caracteres
        } else {
            // Limitar a 10 dígitos
            value = value.substring(0, 10);
        }

        let formattedValue = "";
        if (value.length === 0) {
            formattedValue = "";
        } else if (value.length <= 3) {
            formattedValue = `(${value}`;
        } else if (value.length <= 6) {
            formattedValue = `(${value.substring(0, 3)}) ${value.substring(3)}`;
        } else {
            formattedValue = `(${value.substring(0, 3)}) ${value.substring(
                3,
                6
            )}-${value.substring(6)}`;
        }

        input.value = formattedValue;

        // Trigger validation after formatting
        this.validatePhoneField(formattedValue);
    }

    /**
     * Configurar validación en tiempo real
     */
    setupRealTimeValidation() {
        // Validación de email
        const emailField = document.getElementById("email");
        if (emailField) {
            let emailTimeout;
            emailField.addEventListener("input", (e) => {
                clearTimeout(emailTimeout);
                emailTimeout = setTimeout(() => {
                    this.validateEmailField(e.target.value);
                }, 500); // Debounce de 500ms
            });
        }

        // Validación de teléfono
        const phoneField = document.getElementById("phone");
        if (phoneField) {
            let phoneTimeout;
            phoneField.addEventListener("input", (e) => {
                clearTimeout(phoneTimeout);
                phoneTimeout = setTimeout(() => {
                    this.validatePhoneField(e.target.value);
                }, 500); // Debounce de 500ms
            });
        }

        // Validación de username
        const usernameField = document.getElementById("username");
        if (usernameField) {
            let usernameTimeout;
            usernameField.addEventListener("input", (e) => {
                clearTimeout(usernameTimeout);
                usernameTimeout = setTimeout(() => {
                    this.validateUsernameField(e.target.value);
                }, 500); // Debounce de 500ms
            });
        }

        // Validación de name (para duplicados)
        const nameField = document.getElementById("name");
        if (nameField) {
            let nameTimeout;
            nameField.addEventListener("input", (e) => {
                clearTimeout(nameTimeout);
                nameTimeout = setTimeout(() => {
                    this.validateNameField(e.target.value);
                }, 500); // Debounce de 500ms
            });
        }

        // Validación de insurance_company_name (para duplicados)
        const insuranceCompanyNameField = document.getElementById("insurance_company_name");
        if (insuranceCompanyNameField) {
            let insuranceNameTimeout;
            insuranceCompanyNameField.addEventListener("input", (e) => {
                clearTimeout(insuranceNameTimeout);
                insuranceNameTimeout = setTimeout(() => {
                    this.validateInsuranceCompanyNameField(e.target.value);
                }, 500); // Debounce de 500ms
            });
        }
    }

    /**
     * Configurar capitalización automática para campos específicos
     */
    setupAutoCapitalization() {
        // Aplicar capitalización automática a todos los campos de texto relevantes
        const textInputs = document.querySelectorAll(
            '#swal2-content input[type="text"], #swal2-content textarea'
        );

        textInputs.forEach((input) => {
            // Excluir campos que no deben ser capitalizados
            const excludeFields = [
                "email",
                "phone",
                "url",
                "password",
                "username",
                "slug",
            ];
            const fieldName = input.name || input.id;

            // No aplicar capitalización a campos excluidos
            if (
                excludeFields.some((exclude) =>
                    fieldName.toLowerCase().includes(exclude)
                )
            ) {
                return;
            }

            // No aplicar capitalización a campos que ya tienen la clase auto-capitalize
            if (input.classList.contains("auto-capitalize")) {
                return;
            }

            // Agregar evento de capitalización
            input.addEventListener("input", (e) => {
                this.capitalizeInput(e);
            });
        });

        // También aplicar a campos específicos por nombre (compatibilidad con configuración anterior)
        const specificFields = [
            "name",
            "category_name",
            "category",
            "title",
            "description",
        ];
        specificFields.forEach((fieldName) => {
            const field = document.getElementById(fieldName);
            if (field && !field.hasAttribute("data-capitalize-applied")) {
                field.addEventListener("input", (e) => {
                    this.capitalizeInput(e);
                });
                // Marcar como procesado para evitar duplicados
                field.setAttribute("data-capitalize-applied", "true");
            }
        });
    }

    /**
     * Capitalizar la primera letra de cada palabra
     */
    capitalizeInput(event) {
        const input = event.target;
        const cursorPosition = input.selectionStart;
        const value = input.value;

        // Capitalizar la primera letra de cada palabra
        const capitalizedValue = value.replace(/\b\w/g, (match) =>
            match.toUpperCase()
        );

        // Solo actualizar si hay cambios para evitar loops
        if (capitalizedValue !== value) {
            input.value = capitalizedValue;
            // Restaurar la posición del cursor
            input.setSelectionRange(cursorPosition, cursorPosition);
        }
    }

    /**
     * Validar campo de email en tiempo real
     */
    async validateEmailField(email) {
        const errorElement = $(`#error-email`);

        if (!email) {
            this.clearFieldError("email");
            return;
        }

        // Validación básica de formato de email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            this.showFieldError(
                "email",
                this.translations.invalidEmail || "Invalid email format"
            );
            this.updateSubmitButtonState();
            return;
        }

        // Si existe el endpoint checkEmail, hacer validación de duplicados
        if (this.routes.checkEmail) {
            try {
                const response = await $.ajax({
                    url: this.routes.checkEmail,
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                        Accept: "application/json",
                    },
                    data: {
                        email: email,
                        uuid:
                            this.isEditing && this.currentEntity
                                ? this.currentEntity.uuid
                                : null,
                    },
                });

                if (response.exists) {
                    this.showFieldError(
                        "email",
                        this.translations.emailAlreadyInUse ||
                            "This email is already in use"
                    );
                    this.updateSubmitButtonState();
                } else {
                    this.clearFieldError("email");
                    this.showFieldSuccess(
                        "email",
                        this.translations.emailAvailable || "Email available"
                    );
                    this.updateSubmitButtonState();
                }
            } catch (error) {
                console.error("Error validating email:", error);
                // Si hay error en la validación, solo limpiar el error sin mostrar mensaje de éxito
                this.clearFieldError("email");
                this.updateSubmitButtonState();
            }
        } else {
            // Si no hay endpoint, solo limpiar errores (formato válido)
            this.clearFieldError("email");
            this.updateSubmitButtonState();
        }
    }

    /**
     * Validar campo de teléfono en tiempo real
     */
    async validatePhoneField(phone) {
        if (!phone) {
            this.clearFieldError("phone");
            return;
        }

        // Validación de formato completo (xxx) xxx-xxxx
        const phoneRegex = /^\(\d{3}\) \d{3}-\d{4}$/;
        if (!phoneRegex.test(phone)) {
            if (phone.length > 0) {
                this.showFieldError("phone", "Formato: (xxx) xxx-xxxx");
            }
            return;
        }

        try {
            // Convertir el teléfono al formato de almacenamiento para comparar
            const phoneForStorage = this.formatPhoneForStorage(phone);

            // Prepare phone for validation

            const response = await $.ajax({
                url: this.routes.checkPhone,
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                    Accept: "application/json",
                },
                data: {
                    phone: phoneForStorage,
                    uuid:
                        this.isEditing && this.currentEntity
                            ? this.currentEntity.uuid
                            : null,
                },
            });

            if (response.exists) {
                this.showFieldError(
                    "phone",
                    this.translations.phoneAlreadyInUse ||
                        "This phone is already in use"
                );
            } else {
                this.clearFieldError("phone");
                this.showFieldSuccess(
                    "phone",
                    this.translations.phoneAvailable || "Phone available"
                );
            }
        } catch (error) {
            console.error("Error validating phone:", error);
        }
    }

    /**
     * Validar campo de name en tiempo real (para duplicados)
     */
    async validateNameField(name) {
        if (!name) {
            this.clearFieldError("name");
            this.updateSubmitButtonState();
            return;
        }

        // Verificar si hay endpoint de checkName configurado
        if (!this.routes.checkName) {
            // Si no hay endpoint, solo limpiar errores
            this.clearFieldError("name");
            this.updateSubmitButtonState();
            return;
        }

        try {
            const response = await $.ajax({
                url: this.routes.checkName,
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                    Accept: "application/json",
                },
                data: {
                    name: name,
                    uuid:
                        this.isEditing && this.currentEntity
                            ? this.currentEntity.uuid
                            : null,
                },
            });

            if (response.exists) {
                this.showFieldError(
                    "name",
                    this.translations.nameAlreadyInUse ||
                        "This name is already in use"
                );
                this.updateSubmitButtonState();
            } else {
                this.clearFieldError("name");
                this.showFieldSuccess(
                    "name",
                    this.translations.nameAvailable || "Name available"
                );
                this.updateSubmitButtonState();
            }
        } catch (error) {
            console.error("Error validating name:", error);
            // Si hay error en la validación, solo limpiar el error sin mostrar mensaje de éxito
            this.clearFieldError("name");
            this.updateSubmitButtonState();
        }
    }

    /**
     * Validar campo de insurance_company_name en tiempo real (para duplicados)
     */
    async validateInsuranceCompanyNameField(companyName) {
        if (!companyName) {
            this.clearFieldError("insurance_company_name");
            this.updateSubmitButtonState();
            return;
        }

        // Verificar si hay endpoint de checkName configurado
        if (!this.routes.checkName) {
            // Si no hay endpoint, solo limpiar errores
            this.clearFieldError("insurance_company_name");
            this.updateSubmitButtonState();
            return;
        }

        try {
            const response = await $.ajax({
                url: this.routes.checkName,
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                    Accept: "application/json",
                },
                data: {
                    insurance_company_name: companyName,
                    exclude_uuid:
                        this.isEditing && this.currentEntity
                            ? this.currentEntity.uuid
                            : null,
                },
            });

            if (response.exists) {
                this.showFieldError(
                    "insurance_company_name",
                    this.translations.nameAlreadyInUse ||
                        "This company name is already in use"
                );
                this.updateSubmitButtonState();
            } else {
                this.clearFieldError("insurance_company_name");
                this.showFieldSuccess(
                    "insurance_company_name",
                    this.translations.nameAvailable || "Company name available"
                );
                this.updateSubmitButtonState();
            }
        } catch (error) {
            console.error("Error validating insurance company name:", error);
            // Si hay error en la validación, solo limpiar el error sin mostrar mensaje de éxito
            this.clearFieldError("insurance_company_name");
            this.updateSubmitButtonState();
        }
    }

    /**
     * Validar campo de username en tiempo real
     */
    async validateUsernameField(username) {
        if (!username) {
            this.clearFieldError("username");
            return;
        }

        // Validación básica de longitud mínima
        if (username.length < 7) {
            this.showFieldError(
                "username",
                this.translations.minimumCharacters || "Minimum 7 characters"
            );
            return;
        }

        // Validación de que tenga al menos 2 números
        const numberMatches = username.match(/\d/g);
        if (!numberMatches || numberMatches.length < 2) {
            this.showFieldError(
                "username",
                this.translations.mustContainNumbers ||
                    "Must contain at least 2 numbers"
            );
            return;
        }

        try {
            const response = await $.ajax({
                url: this.routes.checkUsername,
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                    Accept: "application/json",
                },
                data: {
                    username: username,
                    uuid:
                        this.isEditing && this.currentEntity
                            ? this.currentEntity.uuid
                            : null,
                },
            });

            if (response.exists) {
                this.showFieldError(
                    "username",
                    this.translations.usernameAlreadyInUse ||
                        "This username is already in use"
                );
            } else {
                this.clearFieldError("username");
                this.showFieldSuccess(
                    "username",
                    this.translations.usernameAvailable || "Username available"
                );
            }
        } catch (error) {
            console.error("Error validating username:", error);
        }
    }

    /**
     * Mostrar mensaje de éxito en campo
     */
    showFieldSuccess(fieldName, message) {
        const errorElement = $(`#error-${fieldName}`);
        const inputElement = $(`#${fieldName}`);

        if (errorElement.length) {
            errorElement
                .removeClass("hidden text-red-500")
                .addClass("text-green-500")
                .text(message);
        }

        if (inputElement.length) {
            inputElement.removeClass("error").addClass("valid");
        }
    }

    /**
     * Limpiar error de campo
     */
    clearFieldError(fieldName) {
        const errorElement = $(`#error-${fieldName}`);
        const inputElement = $(`#${fieldName}`);

        if (errorElement.length) {
            errorElement
                .addClass("hidden")
                .removeClass("text-red-500 text-green-500")
                .text("");
        }

        if (inputElement.length) {
            inputElement.removeClass("error valid");
        }

        // Actualizar estado del botón después de limpiar error
        setTimeout(() => this.updateSubmitButtonState(), 100);
    }

    /**
     * Poblar formulario con datos
     */
    populateForm(entity) {
        this.formFields.forEach((field) => {
            const element = $(`#${field.name}`);
            let value = entity[field.name];

            if (field.type === "checkbox") {
                element.prop("checked", !!value);
            } else {
                // Formatear teléfono para mostrar en el formulario
                if (field.name === "phone" && value) {
                    value = this.formatPhoneForDisplay(value);
                }

                // Para selects, asegurar que el valor se establezca correctamente
                if (field.type === "select") {
                    element.val(value || "");

                    // Verificar si el valor se estableció correctamente
                    setTimeout(() => {
                        const actualValue = element.val();
                        if (actualValue !== value && value) {
                            element.val(value);
                        }
                    }, 100);
                } else {
                    element.val(value || "");
                }
            }
        });
    }

    /**
     * Verificar y corregir valores de selects
     */
    verifyAndFixSelectValues(entity) {
        this.formFields.forEach((field) => {
            if (field.type === "select") {
                const element = $(`#${field.name}`);
                const expectedValue = entity[field.name];
                const actualValue = element.val();

                if (expectedValue && actualValue !== expectedValue) {
                    element.val(expectedValue);

                    // Verificar una vez más
                    setTimeout(() => {
                        const finalValue = element.val();
                        if (finalValue !== expectedValue) {
                            element.val(expectedValue);
                        }
                    }, 50);
                }
            }
        });
    }

    /**
     * Formatear teléfono para mostrar en formulario
     */
    formatPhoneForDisplay(phone) {
        if (!phone) return "";

        // Extraer solo los dígitos
        const cleaned = phone.replace(/\D/g, "");

        // Si tiene 11 dígitos y empieza con 1 (formato +1XXXXXXXXXX)
        if (cleaned.length === 11 && cleaned.startsWith("1")) {
            const phoneDigits = cleaned.substring(1); // Remover el 1
            return `(${phoneDigits.substring(0, 3)}) ${phoneDigits.substring(
                3,
                6
            )}-${phoneDigits.substring(6, 10)}`;
        }
        // Si tiene 10 dígitos (formato XXXXXXXXXX)
        else if (cleaned.length === 10) {
            return `(${cleaned.substring(0, 3)}) ${cleaned.substring(
                3,
                6
            )}-${cleaned.substring(6, 10)}`;
        }

        // Para otros formatos, devolver tal como está
        return phone;
    }

    /**
     * Formatear teléfono para almacenamiento/comparación
     */
    formatPhoneForStorage(phone) {
        if (!phone) return "";

        // Extraer solo los dígitos para enviar al backend
        const cleaned = phone.replace(/\D/g, "");

        // El backend espera solo dígitos y él se encarga del formato +1XXXXXXXXXX
        if (cleaned.length === 10) {
            return cleaned; // Enviar solo los 10 dígitos
        }

        // Si ya tiene 11 dígitos y empieza con 1, enviar tal como está
        if (cleaned.length === 11 && cleaned.startsWith("1")) {
            return cleaned;
        }

        return cleaned;
    }

    /**
     * Verificar si hay errores de validación visibles o campos requeridos vacíos
     */
    hasValidationErrors() {
        // Verificar errores de validación visibles
        const visibleErrors = $(".error-message:not(.hidden)").filter(
            function () {
                return (
                    $(this).hasClass("text-red-500") &&
                    $(this).text().trim() !== ""
                );
            }
        );
        
        // Verificar si hay campos requeridos vacíos
        const isEditMode = $(".swal2-popup").hasClass("swal-edit");
        let hasEmptyRequiredFields = false;
        
        this.formFields.forEach((field) => {
            // Verificar si el campo debe estar presente en el modo actual
            if (field.showInCreate === false && !isEditMode) {
                return; // Saltar validación para campos no visibles en creación
            }
            if (field.showInEdit === false && isEditMode) {
                return; // Saltar validación para campos no visibles en edición
            }
            
            if (field.required) {
                const element = $(`#${field.name}`);
                let value = "";
                
                if (field.type === "checkbox") {
                    // Los checkboxes no se consideran "vacíos" para propósitos de required
                    return;
                } else {
                    value = element.val();
                }
                
                if (!value || value.toString().trim() === "") {
                    hasEmptyRequiredFields = true;
                }
            }
        });
        
        return visibleErrors.length > 0 || hasEmptyRequiredFields;
    }

    /**
     * Actualizar estado del botón submit
     */
    updateSubmitButtonState() {
        const submitButton = $(".swal2-confirm");
        if (submitButton.length) {
            if (this.hasValidationErrors()) {
                submitButton.prop("disabled", true);
                submitButton.addClass("opacity-50 cursor-not-allowed");
                submitButton.attr(
                    "title",
                    "Complete todos los campos requeridos y corrija los errores antes de continuar"
                );
            } else {
                submitButton.prop("disabled", false);
                submitButton.removeClass("opacity-50 cursor-not-allowed");
                submitButton.removeAttr("title");
            }
        }
    }

    /**
     * Validar y obtener datos del formulario
     */
    validateAndGetFormData() {
        const formData = {};
        let isValid = true;
        const isEditMode = $(".swal2-popup").hasClass("swal-edit");

        // NO limpiar errores previos aquí - mantener los errores de validación en tiempo real
        // $(".error-message").addClass("hidden").text("");

        this.formFields.forEach((field) => {
            // Verificar si el campo debe estar presente en el modo actual
            if (field.showInCreate === false && !isEditMode) {
                return; // Saltar validación para campos no visibles en creación
            }
            if (field.showInEdit === false && isEditMode) {
                return; // Saltar validación para campos no visibles en edición
            }

            const element = $(`#${field.name}`);

            // Si el elemento no existe en el DOM, saltarlo
            if (!element.length) {
                return;
            }

            let value;

            if (field.type === "checkbox") {
                // Para checkboxes, asegurar que siempre enviemos un boolean
                value = element.is(":checked");
                // Log para debugging
                console.log(
                    `Checkbox ${
                        field.name
                    }: checked=${value}, type=${typeof value}`
                );
            } else {
                value = element.val();
            }

            // Validación requerida
            if (field.required && (!value || value.toString().trim() === "")) {
                const requiredMessage =
                    field.name === "last_name"
                        ? this.translations.lastNameRequired ||
                          "Last name is required"
                        : `${field.label} ${
                              this.translations.isRequired || "is required"
                          }`;
                this.showFieldError(field.name, requiredMessage);
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

        // Asegurar que todos los campos checkbox tengan un valor boolean explícito
        this.formFields.forEach((field) => {
            if (field.type === "checkbox") {
                // Si el campo no está en formData, significa que no está presente en este modo
                if (!(field.name in formData)) {
                    // Solo agregar si el campo debería estar presente según showInCreate/showInEdit
                    const shouldShow =
                        (isEditMode && field.showInEdit !== false) ||
                        (!isEditMode && field.showInCreate !== false);
                    if (shouldShow) {
                        formData[field.name] = false;
                    }
                } else {
                    // Asegurar que el valor sea boolean
                    formData[field.name] = Boolean(formData[field.name]);
                }
            }
        });

        // Log para debugging
        console.log("Final form data:", formData);

        // Verificar si hay errores de validación en tiempo real
        if (this.hasValidationErrors()) {
            this.updateSubmitButtonState();
            // Mostrar alerta de error para mayor claridad
            Swal.showValidationMessage(
                this.translations.pleaseCorrectErrors ||
                    "Please correct the errors before continuing"
            );
            return false;
        }

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
                message: (
                    this.translations.minimumCharacters ||
                    "Minimum {count} characters"
                ).replace("{count}", validation.minLength),
            };
        }

        if (validation.maxLength && value.length > validation.maxLength) {
            return {
                valid: false,
                message: (
                    this.translations.maximumCharacters ||
                    "Maximum {count} characters"
                ).replace("{count}", validation.maxLength),
            };
        }

        if (validation.pattern && !validation.pattern.test(value)) {
            return {
                valid: false,
                message:
                    validation.message ||
                    this.translations.invalidFormat ||
                    "Invalid format",
            };
        }

        if (validation.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            return {
                valid: false,
                message: this.translations.invalidEmail || "Invalid email",
            };
        }

        return { valid: true };
    }

    /**
     * Mostrar error de campo
     */
    showFieldError(fieldName, message) {
        const errorElement = $(`#error-${fieldName}`);
        const inputElement = $(`#${fieldName}`);

        if (errorElement.length) {
            errorElement
                .removeClass("hidden text-green-500")
                .addClass("text-red-500")
                .text(message);
        }

        if (inputElement.length) {
            inputElement.removeClass("valid").addClass("error");
        }

        // Actualizar estado del botón después de mostrar error
        setTimeout(() => this.updateSubmitButtonState(), 100);
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
            console.error("Error details:", {
                status: error.status,
                statusText: error.statusText,
                responseJSON: error.responseJSON,
                responseText: error.responseText,
            });

            if (error.status === 422 && error.responseJSON?.errors) {
                this.showValidationErrors(error.responseJSON.errors);
            } else {
                this.showAlert(
                    "error",
                    error.responseJSON?.message || "Error al crear el registro"
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
            console.error("Update error details:", {
                status: error.status,
                statusText: error.statusText,
                responseJSON: error.responseJSON,
                responseText: error.responseText,
            });

            if (error.status === 422 && error.responseJSON?.errors) {
                this.showValidationErrors(error.responseJSON.errors);
            } else {
                this.showAlert(
                    "error",
                    error.responseJSON?.message ||
                        "Error al actualizar el registro"
                );
            }
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
                const entityData = JSON.parse(
                    entityDataString.replace(/&quot;/g, '"')
                );
                return entityData;
            }

            return null;
        } catch (error) {
            console.error("Error parsing entity data from row:", error);
            return null;
        }
    }

    /**
     * Obtener identificador de entidad usando configuración
     */
    getEntityIdentifier(entity) {
        // Si hay una función personalizada de formato, usarla
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
                // Continuar con el método estándar si hay error
            }
        }

        // Intentar con el campo principal configurado
        if (entity && entity[this.entityConfig.identifierField]) {
            const identifier = entity[this.entityConfig.identifierField];
            return {
                identifier: identifier,
                displayName: this.entityConfig.displayName,
                field: this.entityConfig.identifierField,
            };
        }

        // Intentar con campos alternativos
        for (const field of this.entityConfig.fallbackFields) {
            if (entity && entity[field]) {
                const identifier = entity[field];
                return {
                    identifier: identifier,
                    displayName: this.entityConfig.displayName,
                    field: field,
                };
            }
        }

        // Fallback final
        return {
            identifier: "este elemento",
            displayName: "elemento",
            field: null,
        };
    }

    /**
     * Eliminar entidad
     */
    async deleteEntity(id) {
        let entityDisplayName = "";
        let entityIdentifier = "";
        let entity = null;

        // Primero intentar obtener datos desde la fila de la tabla
        const buttonElement = $(`.delete-btn[data-id="${id}"]`)[0];
        if (buttonElement) {
            entity = this.getEntityDataFromRow(buttonElement);
        }

        // Si no se pudo obtener desde la tabla, hacer llamada AJAX como fallback
        if (!entity) {
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

                entity = response.data || response;
            } catch (error) {
                console.error(
                    "Error getting entity data for delete confirmation:",
                    error
                );
                // Fallback: usar el nombre genérico de la entidad
                entityIdentifier = "este elemento";
                entityDisplayName = "elemento";
            }
        }

        // Obtener identificador usando la configuración
        if (entity) {
            const entityInfo = this.getEntityIdentifier(entity);
            entityIdentifier = entityInfo.identifier;
            entityDisplayName = entityInfo.displayName;
        }

        // Crear mensaje personalizado con la información específica del registro
        let customMessage = this.translations.deleteMessage;
        if (entityIdentifier && entityIdentifier !== "este elemento") {
            customMessage = `¿Deseas eliminar ${entityDisplayName}: <strong>${entityIdentifier}</strong>?`;
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
                    `${this.entityName} ${this.translations.deletedSuccessfully}`
                );
                this.loadEntities();
            } catch (error) {
                console.error("Error deleting entity:", error);
                this.showAlert("error", this.translations.errorDeleting);
            }
        }
    }

    /**
     * Restaurar entidad
     */
    async restoreEntity(id) {
        let entityDisplayName = "";
        let entityIdentifier = "";
        let entity = null;

        // Primero intentar obtener datos desde la fila de la tabla
        const buttonElement = $(`.restore-btn[data-id="${id}"]`)[0];
        if (buttonElement) {
            entity = this.getEntityDataFromRow(buttonElement);
        }

        // Si no se pudo obtener desde la tabla, hacer llamada AJAX como fallback
        if (!entity) {
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

                entity = response.data || response;
            } catch (error) {
                console.error(
                    "Error getting entity data for restore confirmation:",
                    error
                );
                // Fallback: usar el nombre genérico de la entidad
                entityIdentifier = "este elemento";
                entityDisplayName = "elemento";
            }
        }

        // Obtener identificador usando la configuración
        if (entity) {
            const entityInfo = this.getEntityIdentifier(entity);
            entityIdentifier = entityInfo.identifier;
            entityDisplayName = entityInfo.displayName;
        }

        // Crear mensaje personalizado con la información específica del registro
        let customMessage = this.translations.restoreMessage;
        if (entityIdentifier && entityIdentifier !== "este elemento") {
            customMessage = `¿Deseas restaurar ${entityDisplayName}: <strong>${entityIdentifier}</strong>?`;
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
                    `${this.entityName} ${this.translations.restoredSuccessfully}`
                );
                this.loadEntities();
            } catch (error) {
                console.error("Error restoring entity:", error);
                this.showAlert("error", this.translations.errorRestoring);
            }
        }
    }

    /**
     * Renderizar tabla
     */
    renderTable(data) {
        // En modo de registro único, no renderizamos tabla
        if (this.singleRecordMode) {
            console.log("Single record mode - skipping table rendering");
            return;
        }

        const entities = data.data;
        let html = "";

        if (entities.length === 0) {
            const noRecordsText =
                this.translations.noRecordsFound ||
                "No se encontraron registros";
            html = `<tr><td colspan="${this.tableHeaders.length}" class="px-6 py-4 text-center text-sm text-gray-500">${noRecordsText}</td></tr>`;
        } else {
            entities.forEach((entity) => {
                const isDeleted = entity.deleted_at !== null;
                const rowClass = isDeleted
                    ? "bg-red-50 dark:bg-red-900 opacity-60"
                    : "";

                // Almacenar datos de la entidad como JSON en atributo data
                const entityData = JSON.stringify(entity).replace(
                    /"/g,
                    "&quot;"
                );

                html += `<tr class="${rowClass}" data-entity="${entityData}">`;

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
        // En modo de registro único, no renderizamos paginación
        if (this.singleRecordMode) {
            console.log("Single record mode - skipping pagination rendering");
            return;
        }

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
        // Limpiar cualquier timeout previo
        if (this.alertTimeout) {
            clearTimeout(this.alertTimeout);
        }

        // Limpiar alertas previas inmediatamente
        $(this.alertSelector).empty().show();

        const alertClass =
            type === "success"
                ? "bg-green-100 border-green-400 text-green-700"
                : "bg-red-100 border-red-400 text-red-700";

        const iconSvg =
            type === "success"
                ? `<svg class="w-5 h-5 mr-2 inline-block" fill="currentColor" viewBox="0 0 20 20">
                 <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
               </svg>`
                : `<svg class="w-5 h-5 mr-2 inline-block" fill="currentColor" viewBox="0 0 20 20">
                 <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
               </svg>`;

        const alertHtml = `
            <div class="alert ${alertClass} border px-4 py-3 rounded mb-4 transition-all duration-300 ease-in-out" role="alert">
                <div class="flex items-center">
                    ${iconSvg}
                    <span class="block sm:inline">${message}</span>
                </div>
            </div>
        `;

        $(this.alertSelector).html(alertHtml);

        // Auto-hide después de 5 segundos con animación suave
        this.alertTimeout = setTimeout(() => {
            $(this.alertSelector).fadeOut(300, () => {
                $(this.alertSelector).empty();
            });
        }, 5000);
    }

    /**
     * Aplicar color al header del modal
     */
    applyHeaderColor(mode) {
        // Aplicar clase CSS al popup
        setTimeout(() => {
            const popup = document.querySelector(".swal2-popup");
            if (popup) {
                // Remover clases previas
                popup.classList.remove("swal-create", "swal-edit");
                // Agregar clase según el modo
                popup.classList.add(`swal-${mode}`);
            }
        }, 10);
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
