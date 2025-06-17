/**
 * PortfolioCrudManager - CRUD Manager para portfolios con manejo de imágenes
 */
class PortfolioCrudManager {
    constructor(options) {
        // Configuración básica
        this.entityName = "Portfolio";
        this.entityNamePlural = "Portfolios";
        this.routes = options.routes || {};

        // Selectores del DOM
        this.tableSelector = "#dataTable tbody";
        this.modalSelector = "#entityModal";
        this.formSelector = "#entityForm";
        this.searchSelector = "#searchInput";
        this.perPageSelector = "#perPage";
        this.showDeletedSelector = "#showDeleted";
        this.paginationSelector = "#pagination";
        this.alertSelector = "#alertMessage";
        this.addButtonSelector = "#addEntityBtn";
        this.modalTitleSelector = "#modalTitle";
        this.saveBtnSelector = "#saveBtn";
        this.idInputSelector = "#entityUuid";

        // Estado
        this.currentPage = 1;
        this.perPage = 10;
        this.searchTerm = "";
        this.showDeleted = false;
        this.sortField = "created_at";
        this.sortDirection = "desc";

        // Específico de portfolios
        this.pendingImages = [];
        this.maxImages = 10;
        this.maxFileSize = 2 * 1024 * 1024; // 2MB

        // Inicializar
        this.init();
    }

    init() {
        this.setupEventHandlers();
        this.setupImageUpload();
        this.loadEntities();
    }

    setupEventHandlers() {
        const self = this;

        // Search
        $(this.searchSelector).on("keyup change", function () {
            self.searchTerm = $(this).val();
            self.currentPage = 1;
            self.loadEntities();
        });

        // Per page
        $(this.perPageSelector).on("change", function () {
            self.perPage = $(this).val();
            self.currentPage = 1;
            self.loadEntities();
        });

        // Show deleted
        $(this.showDeletedSelector).on("change", function () {
            self.showDeleted = $(this).is(":checked");
            self.currentPage = 1;
            self.loadEntities();
        });

        // Add button
        $(this.addButtonSelector).on("click", function () {
            self.showAddModal();
        });

        // Form submit
        $(this.formSelector).on("submit", function (e) {
            e.preventDefault();
            self.submitForm();
        });

        // Close modal
        $(document).on("click", "#closeModal, #cancelBtn", function () {
            self.closeModal();
        });

        // Dynamic buttons
        $(document).on("click", ".edit-btn", function () {
            self.editEntity($(this).data("id"));
        });

        $(document).on("click", ".delete-btn", function () {
            if (confirm("¿Está seguro de eliminar este portfolio?")) {
                self.deleteEntity($(this).data("id"));
            }
        });

        $(document).on("click", ".restore-btn", function () {
            if (confirm("¿Está seguro de restaurar este portfolio?")) {
                self.restoreEntity($(this).data("id"));
            }
        });
    }

    loadEntities() {
        const self = this;

        const params = new URLSearchParams({
            page: this.currentPage,
            per_page: this.perPage,
            search: this.searchTerm,
            show_deleted: this.showDeleted ? "true" : "false",
            sort_field: this.sortField,
            sort_direction: this.sortDirection,
        });

        $(this.tableSelector).html(
            '<tr><td colspan="6" class="text-center py-4">Loading...</td></tr>'
        );

        $.ajax({
            url: `${this.routes.index}?${params.toString()}`,
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
            success: function (response) {
                self.renderTable(response.data);
                self.renderPagination(response);
            },
            error: function (xhr) {
                console.error("Error loading portfolios:", xhr);
                $(self.tableSelector).html(
                    '<tr><td colspan="6" class="text-center py-4 text-red-600">Error loading portfolios</td></tr>'
                );
            },
        });
    }

    renderTable(data) {
        const portfolios = data.data || [];
        let html = "";

        if (portfolios.length === 0) {
            html =
                '<tr><td colspan="6" class="text-center py-4 text-gray-500">No portfolios found</td></tr>';
        } else {
            portfolios.forEach((portfolio) => {
                const isDeleted = portfolio.deleted_at !== null;
                const rowClass = isDeleted ? "bg-red-50 opacity-60" : "";

                // Imagen
                let imgHtml = '<span class="text-gray-400">No images</span>';
                if (portfolio.images && portfolio.images.length > 0) {
                    imgHtml = `<img src="/${portfolio.images[0].path}" alt="Preview" class="h-12 w-20 object-cover rounded shadow" />`;
                }

                html += `
                    <tr class="${rowClass}">
                        <td class="px-6 py-4">${
                            portfolio.project_type?.title || "N/A"
                        }</td>
                        <td class="px-6 py-4">${
                            portfolio.project_type?.service_category
                                ?.service_category_name || "N/A"
                        }</td>
                        <td class="px-6 py-4">${imgHtml}</td>
                        <td class="px-6 py-4 text-center">${
                            portfolio.created_at
                                ? new Date(
                                      portfolio.created_at
                                  ).toLocaleDateString()
                                : "N/A"
                        }</td>
                        <td class="px-6 py-4 text-center">
                            ${
                                isDeleted
                                    ? '<span class="text-red-500">Inactive</span>'
                                    : '<span class="text-green-600">Active</span>'
                            }
                        </td>
                        <td class="px-6 py-4 text-center">
                            ${
                                isDeleted
                                    ? `<button class="restore-btn text-green-600 hover:text-green-900 px-2 py-1 rounded" data-id="${portfolio.uuid}" title="Restore">↻ Restore</button>`
                                    : `<button class="edit-btn text-blue-600 hover:text-blue-900 px-2 py-1 rounded mr-2" data-id="${portfolio.uuid}" title="Edit">✏️ Edit</button>
                                 <button class="delete-btn text-red-600 hover:text-red-900 px-2 py-1 rounded" data-id="${portfolio.uuid}" title="Delete">🗑️ Delete</button>`
                            }
                        </td>
                    </tr>
                `;
            });
        }

        $(this.tableSelector).html(html);
    }

    renderPagination(data) {
        const { current_page, last_page, from, to, total } = data;
        const self = this;

        let html = `
            <div class="text-sm text-gray-300">
                Showing ${from || 0} to ${to || 0} of ${total} results
            </div>
            <div class="flex space-x-1">
                <button class="px-3 py-1 rounded ${
                    current_page === 1
                        ? "opacity-50 cursor-not-allowed"
                        : "hover:bg-gray-600"
                }" ${current_page === 1 ? "disabled" : ""} data-page="${
            current_page - 1
        }">Previous</button>
                <button class="px-3 py-1 rounded ${
                    current_page === last_page
                        ? "opacity-50 cursor-not-allowed"
                        : "hover:bg-gray-600"
                }" ${current_page === last_page ? "disabled" : ""} data-page="${
            current_page + 1
        }">Next</button>
            </div>
        `;

        $(this.paginationSelector).html(html);

        $(this.paginationSelector + " button:not([disabled])").on(
            "click",
            function () {
                self.currentPage = $(this).data("page");
                self.loadEntities();
            }
        );
    }

    showAddModal() {
        this.resetForm();
        this.loadServiceCategories();
        $(this.modalTitleSelector).text("Create Portfolio");
        $(this.modalSelector).removeClass("hidden").addClass("flex");
    }

    editEntity(id) {
        const self = this;
        this.loadServiceCategories();

        $.ajax({
            url: this.routes.edit.replace(":id", id),
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
            success: function (response) {
                if (response.success) {
                    const portfolio = response.portfolio;

                    self.resetForm();
                    $(self.idInputSelector).val(portfolio.uuid);
                    $("#title").val(portfolio.project_type?.title || "");
                    $("#description").val(
                        portfolio.project_type?.description || ""
                    );
                    $("#service_category_id").val(
                        portfolio.project_type?.service_category_id || ""
                    );

                    // Mostrar imágenes existentes
                    $("#imagePreviews").empty();
                    if (portfolio.images) {
                        portfolio.images.forEach((img) => {
                            const preview = $(`
                                <div class="relative group" data-img-id="${img.id}">
                                    <img src="/${img.path}" class="h-20 w-32 object-cover rounded shadow" />
                                    <button type="button" class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 text-xs remove-existing-image-btn" data-img-id="${img.id}">&times;</button>
                                </div>
                            `);
                            $("#imagePreviews").append(preview);
                        });
                    }

                    $(self.modalTitleSelector).text("Edit Portfolio");
                    $(self.modalSelector)
                        .removeClass("hidden")
                        .addClass("flex");
                }
            },
            error: function (xhr) {
                console.error("Error loading portfolio:", xhr);
                self.showAlert("Error loading portfolio data", "error");
            },
        });
    }

    submitForm() {
        const self = this;
        const entityId = $(this.idInputSelector).val();
        const isEdit = !!entityId;

        $(".error-message").addClass("hidden").text("");

        const formData = new FormData();
        formData.append("title", $("#title").val());
        formData.append("description", $("#description").val());
        formData.append("service_category_id", $("#service_category_id").val());

        if (isEdit) {
            formData.append("_method", "PUT");
        }

        // Imágenes
        const files = $("#images")[0]?.files || [];
        for (let i = 0; i < files.length; i++) {
            formData.append("images[]", files[i]);
        }

        // Imágenes a eliminar
        this.pendingImages.forEach((id) => {
            formData.append("images_to_delete[]", id);
        });

        const saveBtn = $(this.saveBtnSelector);
        const originalText = saveBtn.find(".button-text").text();

        saveBtn.prop("disabled", true).find(".button-text").text("Saving...");

        $.ajax({
            url: isEdit
                ? this.routes.update.replace(":id", entityId)
                : this.routes.store,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                self.closeModal();
                self.showAlert(
                    response.message || "Portfolio saved successfully!",
                    "success"
                );
                self.loadEntities();
            },
            error: function (xhr) {
                if (xhr.status === 422) {
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
            complete: function () {
                saveBtn
                    .prop("disabled", false)
                    .find(".button-text")
                    .text(originalText);
            },
        });
    }

    deleteEntity(id) {
        const self = this;
        $.ajax({
            url: this.routes.destroy.replace(":id", id),
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                self.showAlert(
                    response.message || "Portfolio deleted",
                    "success"
                );
                self.loadEntities();
            },
            error: function () {
                self.showAlert("Error deleting portfolio", "error");
            },
        });
    }

    restoreEntity(id) {
        const self = this;
        $.ajax({
            url: this.routes.restore.replace(":id", id),
            method: "PATCH",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                self.showAlert(
                    response.message || "Portfolio restored",
                    "success"
                );
                self.loadEntities();
            },
            error: function () {
                self.showAlert("Error restoring portfolio", "error");
            },
        });
    }

    loadServiceCategories() {
        $.ajax({
            url: "/api/service-categories",
            method: "GET",
            success: function (response) {
                const categories = response.data || response;
                let options = '<option value="">-- Select Category --</option>';
                categories.forEach((cat) => {
                    options += `<option value="${cat.id}">${cat.service_category_name}</option>`;
                });
                $("#service_category_id").html(options);
            },
            error: function (xhr) {
                console.error("Error loading categories:", xhr);
            },
        });
    }

    setupImageUpload() {
        const self = this;

        $(document).on("change", "#images", function (e) {
            const files = Array.from(e.target.files);
            const previews = $("#imagePreviews");

            previews.find(".preview-new").remove();

            files.forEach((file, idx) => {
                if (!file.type.startsWith("image/")) return;

                const reader = new FileReader();
                reader.onload = function (ev) {
                    const preview = $(`
                        <div class="relative group preview-new">
                            <img src="${ev.target.result}" class="h-20 w-32 object-cover rounded shadow" />
                            <button type="button" class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 text-xs remove-image-btn" data-idx="${idx}">&times;</button>
                        </div>
                    `);
                    previews.append(preview);
                };
                reader.readAsDataURL(file);
            });
        });

        $(document).on("click", ".remove-image-btn", function () {
            $(this).parent().remove();
        });

        $(document).on("click", ".remove-existing-image-btn", function () {
            const imgId = $(this).data("img-id");
            self.pendingImages.push(imgId);
            $(this).parent().hide();
        });
    }

    resetForm() {
        $(this.formSelector)[0].reset();
        $(this.idInputSelector).val("");
        $(".error-message").addClass("hidden").text("");
        $("#imagePreviews").empty();
        this.pendingImages = [];
    }

    closeModal() {
        $(this.modalSelector).addClass("hidden").removeClass("flex");
        this.resetForm();
    }

    showAlert(message, type = "success") {
        const alertDiv = $(this.alertSelector);

        alertDiv.removeClass(
            "bg-green-100 bg-red-100 text-green-800 text-red-800 border-green-400 border-red-400"
        );

        if (type === "success") {
            alertDiv.addClass("bg-green-100 text-green-800 border-green-400");
        } else {
            alertDiv.addClass("bg-red-100 text-red-800 border-red-400");
        }

        alertDiv.find("span").text(message);
        alertDiv.removeClass("hidden");

        setTimeout(() => {
            alertDiv.addClass("hidden");
        }, 5000);
    }
}

// Exportar la clase
export default PortfolioCrudManager;
