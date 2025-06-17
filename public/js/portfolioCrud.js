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
        // Base URL para las rutas del CRUD de portfolios
        this.baseUrl = "/portfolios";

        // Configuración por defecto
        this.config = {
            perPage: 10,
            currentPage: 1,
            ...options,
        };

        // Elementos del DOM - ARREGLADOS para coincidir con HTML
        this.tableBody = $("#dataTable tbody");
        this.pagination = $("#pagination");
        this.searchInput = $("#searchInput");
        this.showDeletedCheckbox = $("#showDeleted");
        this.addButton = $("#addEntityBtn");
        this.modal = $("#entityModal");
        this.modalTitle = $("#modalTitle");
        this.modalBody = $("#entityForm");

        // Variables de control
        this.isLoading = false;
        this.currentEditUuid = null;
        this.pendingImages = [];
        this.currentImages = [];
        this.maxImages = 10;
        this.maxFileSize = 2 * 1024 * 1024; // 2MB
        this.maxTotalNewSize = 10 * 1024 * 1024; // 10MB

        // Inicializar
        this.init();
    }

    /**
     * Inicialización principal: carga portfolios, setea handlers, etc
     */
    init() {
        this.setupEventHandlers();
        this.loadPortfolios();
    }

    /**
     * Cargar portfolios desde el backend (AJAX)
     */
    loadPortfolios() {
        console.log("🔄 Loading portfolios...");
        if (this.isLoading) {
            console.log("❌ Already loading, skipping...");
            return;
        }

        this.isLoading = true;
        this.tableBody.html(
            '<tr><td colspan="8" class="text-center py-4">Cargando portfolios...</td></tr>'
        );

        const params = new URLSearchParams({
            page: this.config.currentPage,
            per_page: this.config.perPage,
            search: this.searchInput.val() || "",
            show_deleted: this.showDeletedCheckbox.is(":checked")
                ? "true"
                : "false",
        });

        const url = `${this.baseUrl}?${params.toString()}`;
        console.log("📡 Making AJAX request to:", url);

        $.ajax({
            url: url,
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
            success: (response) => {
                console.log("✅ AJAX Success:", response);
                this.renderTable(response.data);
                this.renderPagination(response);
            },
            error: (xhr) => {
                console.error("❌ AJAX Error:", xhr);
                console.error("Response Text:", xhr.responseText);
                this.tableBody.html(
                    '<tr><td colspan="8" class="text-center py-4 text-red-600">Error cargando portfolios</td></tr>'
                );
            },
            complete: () => {
                console.log("🏁 AJAX Complete");
                this.isLoading = false;
            },
        });
    }

    /**
     * Renderizar la tabla de portfolios
     */
    renderTable(data) {
        const portfolios = data.data || [];
        let html = "";
        const noPortfoliosMsg =
            window.translations?.no_portfolios_found || "No portfolios found.";
        if (portfolios.length === 0) {
            html = `<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">${noPortfoliosMsg}</td></tr>`;
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
        $(this.tableBody).html(html);
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
        $(this.pagination).html(html);
        // Handlers
        const self = this;
        $(this.pagination + " button:not([disabled])").on("click", function () {
            self.config.currentPage = $(this).data("page");
            self.loadPortfolios();
        });
    }

    /**
     * Setear handlers de eventos (botones, filtros, etc)
     */
    setupEventHandlers() {
        console.log("🔧 Setting up event handlers...");
        const self = this;

        // Search input
        $(this.searchInput).on("keyup change", function () {
            console.log("🔍 Search changed:", $(this).val());
            self.config.search = $(this).val();
            self.config.currentPage = 1;
            self.loadPortfolios();
        });

        // Add button
        console.log("🔘 Setting up add button:", this.addButton.length);
        $(this.addButton).on("click", function () {
            console.log("➕ Add button clicked!");
            self.showModal("create");
        });

        // Submit form - ARREGLADO
        $(document).on("submit", "#entityForm", function (e) {
            console.log("📝 Form submitted");
            e.preventDefault();
            self.submitForm();
        });

        // Cancel/close modal - ARREGLADO
        $(document).on("click", "#closeModal, #cancelBtn", function () {
            console.log("❌ Modal closed");
            self.closeModal();
        });

        // Edit and delete buttons (dynamic)
        $(document).on("click", ".edit-btn", function () {
            console.log("✏️ Edit clicked:", $(this).data("id"));
            self.showModal("edit", $(this).data("id"));
        });

        $(document).on("click", ".delete-btn", function () {
            console.log("🗑️ Delete clicked:", $(this).data("id"));
            if (confirm("¿Está seguro de eliminar este portfolio?")) {
                self.deletePortfolio($(this).data("id"));
            }
        });

        $(document).on("click", ".restore-btn", function () {
            console.log("♻️ Restore clicked:", $(this).data("id"));
            if (confirm("¿Está seguro de restaurar este portfolio?")) {
                self.restorePortfolio($(this).data("id"));
            }
        });

        console.log("✅ Event handlers setup complete");
    }

    /**
     * Mostrar modal para crear/editar
     */
    showModal(mode, uuid = null) {
        console.log("🔧 Opening modal:", mode, uuid);

        // Limpiar - ARREGLADO para coincidir con HTML
        $("#entityForm")[0].reset();
        $("#imagePreviews").empty();
        $("#entityUuid").val("");
        this.pendingImages = [];
        this.currentImages = [];
        this.currentEditUuid = uuid;

        // Cargar categorías
        $.ajax({
            url: "/api/service-categories",
            type: "GET",
            success(response) {
                console.log("✅ Categories loaded:", response);
                const categories = response.data || response;
                let options = '<option value="">-- Select Category --</option>';
                categories.forEach((cat) => {
                    options += `<option value="${cat.id}">${cat.service_category_name}</option>`;
                });
                $("#service_category_id").html(options);
            },
            error(xhr) {
                console.error("❌ Error loading categories:", xhr);
            },
        });

        // Si es edición, cargar datos
        if (mode === "edit" && uuid) {
            const self = this;
            $.ajax({
                url: `/portfolios/${uuid}`,
                type: "GET",
                dataType: "json",
                success(resp) {
                    console.log("✅ Portfolio data loaded:", resp);
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
                error(xhr) {
                    console.error("❌ Error loading portfolio:", xhr);
                },
            });
        }

        // Mostrar modal - ARREGLADO para coincidir con HTML
        $("#entityModal").removeClass("hidden").addClass("flex");
    }

    /**
     * Cerrar el modal
     */
    closeModal() {
        // ARREGLADO para coincidir con HTML
        $("#entityModal").addClass("hidden").removeClass("flex");
        this.pendingImages = [];
        this.currentImages = [];
        this.currentEditUuid = null;
    }

    /**
     * Manejar subida de imágenes, previews y borrado antes de guardar
     */
    setupImageUpload() {
        const self = this;
        $(document).on("change", "#images", function (e) {
            const files = Array.from(e.target.files);
            const previews = $("#image-previews");
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
            this.pendingImages.push(imgId);
            $(e.currentTarget).parent().hide();
        });
    }

    /**
     * Validar y enviar el formulario (crear/editar)
     */
    submitForm() {
        console.log("📝 Submitting form...");
        const self = this;
        const form = $("#entityForm")[0]; // ARREGLADO
        const formData = new FormData();

        // Campos básicos - ARREGLADOS para coincidir con HTML
        formData.append("title", $("#title").val());
        formData.append("description", $("#description").val());
        formData.append("service_category_id", $("#service_category_id").val());

        // UUID si edición
        if (this.currentEditUuid) {
            formData.append("uuid", this.currentEditUuid);
        }

        // Imágenes nuevas (solo las que siguen en previews)
        const files = $("#images")[0].files;
        for (let i = 0; i < files.length; i++) {
            formData.append("images[]", files[i]);
        }

        // IDs de imágenes existentes a eliminar
        this.pendingImages.forEach((id) => {
            formData.append("images_to_delete[]", id);
        });

        // Limpiar errores
        $(".error-message").addClass("hidden").text("");

        // AJAX
        const url = this.currentEditUuid
            ? `/portfolios/${this.currentEditUuid}`
            : `/portfolios`;
        const method = this.currentEditUuid ? "POST" : "POST";
        if (this.currentEditUuid) formData.append("_method", "PUT");

        $("#saveBtn") // ARREGLADO
            .prop("disabled", true)
            .find(".button-text")
            .text("Saving...");

        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success(resp) {
                console.log("✅ Form saved:", resp);
                self.closeModal();
                self.showAlert(resp.message || "Saved!", "success");
                self.loadPortfolios();
            },
            error(xhr) {
                console.error("❌ Form error:", xhr);
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
                $("#saveBtn") // ARREGLADO
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
            url: `/portfolios/${uuid}`,
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
            url: `/portfolios/${uuid}/restore`,
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
     * Mostrar alertas de éxito/error
     */
    showAlert(message, type = "success") {
        console.log(`📢 Alert: ${type} - ${message}`);
        const alertDiv = $("#alertMessage");

        // Limpiar clases anteriores
        alertDiv.removeClass(
            "bg-green-100 bg-red-100 text-green-800 text-red-800 border-green-400 border-red-400"
        );

        // Agregar clases según el tipo
        if (type === "success") {
            alertDiv.addClass("bg-green-100 text-green-800 border-green-400");
        } else {
            alertDiv.addClass("bg-red-100 text-red-800 border-red-400");
        }

        // Mostrar mensaje
        alertDiv.find("span").text(message);
        alertDiv.removeClass("hidden");

        // Auto-hide después de 5 segundos
        setTimeout(() => {
            alertDiv.addClass("hidden");
        }, 5000);
    }

    // ...otros métodos utilitarios...
}
