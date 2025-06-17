/**
 * PortfolioCrudManager - CRUD especial para portfolios con manejo de múltiples imágenes y previews
 * Inspirado en CrudManager, pero adaptado a las necesidades de portfolios
 */
export default class PortfolioCrudManager {
    /**
     * Constructor
     * @param {Object} options - Configuración inicial (rutas, selectores, etc)
     */
    constructor(options) {
        // Configuración básica
        this.entityName = options.entityName || "Portfolio";
        this.routes = options.routes || {};
        this.tableSelector = options.tableSelector || "#dataTable";
        this.modalSelector = options.modalSelector || "#entityModal";
        this.formSelector = options.formSelector || "#entityForm";
        this.alertSelector = options.alertSelector || "#alertMessage";
        this.addButtonSelector = options.addButtonSelector || "#addEntityBtn";
        this.paginationSelector = options.paginationSelector || "#pagination";
        this.searchSelector = options.searchSelector || "#searchInput";
        this.perPageSelector = options.perPageSelector || "#perPage";
        this.showDeletedSelector =
            options.showDeletedSelector || "#showDeleted";
        // ...otros selectores y configuraciones...

        // Estado
        this.currentPage = 1;
        this.perPage = 10;
        this.searchTerm = "";
        this.showDeleted = false;
        this.imagesToDelete = [];
        this.editing = false;
        this.editingUuid = null;
        // ...otros estados...

        // Inicializar
        this.init();
    }

    /**
     * Inicialización principal: carga portfolios, setea handlers, etc
     */
    init() {
        this.loadPortfolios();
        this.setupEventHandlers();
        this.setupImageUpload();
    }

    /**
     * Cargar portfolios desde el backend (AJAX)
     */
    loadPortfolios() {
        const self = this;
        const params = {
            page: this.currentPage,
            per_page: this.perPage,
            search: this.searchTerm,
            show_deleted: this.showDeleted ? "true" : "false",
        };
        $(this.tableSelector + " #loadingRow").show();
        $.ajax({
            url: this.routes.index,
            type: "GET",
            data: params,
            dataType: "json",
            success(response) {
                self.renderTable(response);
                self.renderPagination(response);
            },
            error(xhr) {
                $(self.tableSelector + " tbody").html(
                    `<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Error loading portfolios</td></tr>`
                );
            },
            complete() {
                $(self.tableSelector + " #loadingRow").hide();
            },
        });
    }

    /**
     * Renderizar la tabla de portfolios
     */
    renderTable(data) {
        const portfolios = data.data || [];
        let html = "";
        if (portfolios.length === 0) {
            html = `<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No portfolios found.</td></tr>`;
        } else {
            portfolios.forEach((portfolio) => {
                const isDeleted = portfolio.deleted_at !== null;
                const rowClass = isDeleted
                    ? "bg-red-50 dark:bg-red-900 opacity-60"
                    : "";
                // Imagen preview (solo la primera)
                let imgHtml = "";
                if (portfolio.images && portfolio.images.length > 0) {
                    imgHtml = `<img src="/${portfolio.images[0].path}" alt="Preview" class="h-12 w-20 object-cover rounded shadow" />`;
                }
                html += `<tr class="${rowClass}">
                    <td class="px-6 py-4">${
                        portfolio.project_type?.title || ""
                    }</td>
                    <td class="px-6 py-4">${
                        portfolio.project_type?.service_category?.category || ""
                    }</td>
                    <td class="px-6 py-4">${imgHtml}</td>
                    <td class="px-6 py-4 text-center">${
                        portfolio.created_at
                            ? new Date(portfolio.created_at).toLocaleString()
                            : ""
                    }</td>
                    <td class="px-6 py-4 text-center">${
                        isDeleted
                            ? '<span class="text-red-500">Inactive</span>'
                            : '<span class="text-green-600">Active</span>'
                    }</td>
                    <td class="px-6 py-4 text-center">
                        ${
                            isDeleted
                                ? `<button class="restore-btn text-green-600" data-id="${portfolio.uuid}" title="Restore"><svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m-15.357-2a8.001 8.001 0 0115.357 2M15 15h-5' /></svg></button>`
                                : `<button class="edit-btn text-blue-600 mr-2" data-id="${portfolio.uuid}" title="Edit"><svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' /></svg></button>
                               <button class="delete-btn text-red-600" data-id="${portfolio.uuid}" title="Delete"><svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16' /></svg></button>`
                        }
                    </td>
                </tr>`;
            });
        }
        $(this.tableSelector + " tbody").html(html);
    }

    /**
     * Renderizar la paginación
     */
    renderPagination(data) {
        const { current_page, last_page, from, to, total } = data;
        let html = `<div class="text-sm text-gray-700 dark:text-gray-300">Mostrando <span class="font-medium">${
            from || 0
        }</span> a <span class="font-medium">${
            to || 0
        }</span> de <span class="font-medium">${total}</span> resultados</div><div class="flex space-x-1">`;
        html += `<button class="px-3 py-1 rounded-md ${
            current_page === 1
                ? "opacity-50 cursor-not-allowed bg-gray-200 dark:bg-gray-700"
                : "bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600"
        }" ${
            current_page === 1
                ? "disabled"
                : 'data-page="' + (current_page - 1) + '"'
        }>Anterior</button>`;
        html += `<button class="px-3 py-1 rounded-md ${
            current_page === last_page
                ? "opacity-50 cursor-not-allowed bg-gray-200 dark:bg-gray-700"
                : "bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600"
        }" ${
            current_page === last_page
                ? "disabled"
                : 'data-page="' + (current_page + 1) + '"'
        }>Siguiente</button>`;
        html += `</div>`;
        $(this.paginationSelector).html(html);
        // Handlers
        const self = this;
        $(this.paginationSelector + " button:not([disabled])").on(
            "click",
            function () {
                self.currentPage = $(this).data("page");
                self.loadPortfolios();
            }
        );
    }

    /**
     * Setear handlers de eventos (botones, filtros, etc)
     */
    setupEventHandlers() {
        const self = this;
        $(this.searchSelector).on("keyup change", function () {
            self.searchTerm = $(this).val();
            self.currentPage = 1;
            self.loadPortfolios();
        });
        $(this.perPageSelector).on("change", function () {
            self.perPage = $(this).val();
            self.currentPage = 1;
            self.loadPortfolios();
        });
        $(this.showDeletedSelector).on("change", function () {
            self.showDeleted = $(this).is(":checked");
            self.currentPage = 1;
            self.loadPortfolios();
        });
        $(this.addButtonSelector).on("click", function () {
            self.showModal("create");
        });
        // Submit form
        $(document).on("submit", this.formSelector, function (e) {
            e.preventDefault();
            self.submitForm();
        });
        // Cancel/close modal
        $(document).on("click", "#closeModal, #cancelBtn", function () {
            self.closeModal();
        });
        // Editar
        $(document).on("click", ".edit-btn", function () {
            const uuid = $(this).data("id");
            self.showModal("edit", uuid);
        });
        $(document).on("click", ".delete-btn", function () {
            const uuid = $(this).data("id");
            if (confirm("Are you sure you want to delete this portfolio?")) {
                self.deletePortfolio(uuid);
            }
        });
        $(document).on("click", ".restore-btn", function () {
            const uuid = $(this).data("id");
            if (confirm("Restore this portfolio?")) {
                self.restorePortfolio(uuid);
            }
        });
    }

    /**
     * Mostrar modal de crear/editar portfolio y cargar categorías
     */
    showModal(mode, uuid = null) {
        // Limpiar
        $("#entityForm")[0].reset();
        $("#imagePreviews").empty();
        $("#entityUuid").val("");
        this.imagesToDelete = [];
        this.editing = mode === "edit";
        this.editingUuid = uuid;
        // Cargar categorías
        $.ajax({
            url: "/service-categories",
            type: "GET",
            dataType: "json",
            success(response) {
                const select = $("#service_category_id");
                select.empty();
                select.append(`<option value="">-- Selecciona --</option>`);
                (response.data || []).forEach((cat) => {
                    select.append(
                        `<option value="${cat.id}">${cat.category}</option>`
                    );
                });
            },
        });
        // Si es edición, cargar datos
        if (mode === "edit" && uuid) {
            const self = this;
            $.ajax({
                url: this.routes.edit.replace(":id", uuid),
                type: "GET",
                dataType: "json",
                success(resp) {
                    const p =
                        resp.portfolio || resp.entity || resp.data || resp;
                    $("#entityUuid").val(p.uuid);
                    $("#title").val(p.project_type?.title || "");
                    $("#description").val(p.project_type?.description || "");
                    $("#service_category_id").val(
                        p.project_type?.service_category_id || ""
                    );
                    // Previews de imágenes existentes
                    $("#imagePreviews").empty();
                    (p.images || []).forEach((img) => {
                        const preview = $(
                            `<div class="relative group" data-img-id="${img.id}"><img src="/${img.path}" class="h-20 w-32 object-cover rounded shadow" /><button type="button" class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 text-xs remove-existing-image-btn" data-img-id="${img.id}">&times;</button></div>`
                        );
                        $("#imagePreviews").append(preview);
                    });
                },
            });
        }
        // Mostrar modal
        $("#entityModal").removeClass("hidden").addClass("flex");
    }

    /**
     * Cerrar el modal
     */
    closeModal() {
        $("#entityModal").addClass("hidden").removeClass("flex");
        this.imagesToDelete = [];
        this.editing = false;
        this.editingUuid = null;
    }

    /**
     * Manejar subida de imágenes, previews y borrado antes de guardar
     */
    setupImageUpload() {
        const self = this;
        $(document).on("change", "#images", function (e) {
            const files = Array.from(e.target.files);
            const previews = $("#imagePreviews");
            // No borrar previews de imágenes existentes
            previews.find(".remove-existing-image-btn").parent().show();
            // Borrar previews de imágenes nuevas
            previews.find(".preview-new").remove();
            files.forEach((file, idx) => {
                if (!file.type.startsWith("image/")) return;
                const reader = new FileReader();
                reader.onload = function (ev) {
                    const preview = $(
                        `<div class="relative group preview-new"><img src="${ev.target.result}" class="h-20 w-32 object-cover rounded shadow" /><button type="button" class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 text-xs remove-image-btn" data-idx="${idx}">&times;</button></div>`
                    );
                    previews.append(preview);
                };
                reader.readAsDataURL(file);
            });
        });
        // Eliminar preview de imagen nueva (visual)
        $(document).on("click", ".remove-image-btn", function () {
            $(this).parent().remove();
            // Para borrado real, se debe reconstruir el FileList antes de submit
        });
        // Marcar imagen existente para eliminar
        $(document).on("click", ".remove-existing-image-btn", (e) => {
            const imgId = $(e.currentTarget).data("img-id");
            this.imagesToDelete.push(imgId);
            $(e.currentTarget).parent().hide();
        });
    }

    /**
     * Validar y enviar el formulario (crear/editar)
     */
    submitForm() {
        const self = this;
        const form = $(this.formSelector)[0];
        const formData = new FormData();
        // Campos básicos
        formData.append("title", $("#title").val());
        formData.append("description", $("#description").val());
        formData.append("service_category_id", $("#service_category_id").val());
        // UUID si edición
        if (this.editing && this.editingUuid) {
            formData.append("uuid", this.editingUuid);
        }
        // Imágenes nuevas (solo las que siguen en previews)
        const files = $("#images")[0].files;
        for (let i = 0; i < files.length; i++) {
            formData.append("images[]", files[i]);
        }
        // IDs de imágenes existentes a eliminar
        this.imagesToDelete.forEach((id) => {
            formData.append("images_to_delete[]", id);
        });
        // Limpiar errores
        $(".error-message").addClass("hidden").text("");
        // AJAX
        const url = this.editing
            ? this.routes.update.replace(":id", this.editingUuid)
            : this.routes.store;
        const method = this.editing ? "POST" : "POST"; // Laravel: usar POST + _method=PUT para update
        if (this.editing) formData.append("_method", "PUT");
        $("#saveBtn")
            .prop("disabled", true)
            .find(".button-text")
            .text("Saving...");
        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            success(resp) {
                self.closeModal();
                self.showAlert(resp.message || "Saved!", "success");
                self.loadPortfolios();
            },
            error(xhr) {
                if (
                    xhr.status === 422 &&
                    xhr.responseJSON &&
                    xhr.responseJSON.errors
                ) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach((field) => {
                        $(`#${field}Error`)
                            .removeClass("hidden")
                            .text(errors[field][0]);
                    });
                } else {
                    self.showAlert("Error saving portfolio", "error");
                }
            },
            complete() {
                $("#saveBtn")
                    .prop("disabled", false)
                    .find(".button-text")
                    .text("Save");
            },
        });
    }

    /**
     * Eliminar (soft delete) un portfolio
     */
    deletePortfolio(uuid) {
        const self = this;
        $.ajax({
            url: this.routes.destroy.replace(":id", uuid),
            type: "DELETE",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success(resp) {
                self.showAlert(resp.message || "Portfolio deleted", "success");
                self.loadPortfolios();
            },
            error() {
                self.showAlert("Error deleting portfolio", "error");
            },
        });
    }

    /**
     * Restaurar un portfolio eliminado
     */
    restorePortfolio(uuid) {
        const self = this;
        $.ajax({
            url: this.routes.restore.replace(":id", uuid),
            type: "PATCH",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success(resp) {
                self.showAlert(resp.message || "Portfolio restored", "success");
                self.loadPortfolios();
            },
            error() {
                self.showAlert("Error restoring portfolio", "error");
            },
        });
    }

    /**
     * Mostrar alertas
     */
    showAlert(message, type = "success") {
        // ...
    }

    // ...otros métodos utilitarios...
}
