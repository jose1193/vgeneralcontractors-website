/**
 * Invoice Demo CRUD Management
 * Modern ES6+ JavaScript with Alpine.js integration
 * Laravel 2025 Best Practices
 */

class InvoiceDemoManager {
    constructor() {
        this.baseUrl = "/invoices";
        this.csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");
        this.debounceTimer = null;
    }

    /**
     * Fetch API wrapper with enhanced error handling and logging
     */
    async apiRequest(url, options = {}) {
        const defaultOptions = {
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": this.csrfToken,
                "X-Requested-With": "XMLHttpRequest",
            },
        };

        const config = { ...defaultOptions, ...options };

        console.group("API Request");
        console.log("URL:", url);
        console.log("Method:", options.method || "GET");

        // Log request body if present (but sanitize sensitive data)
        if (options.body) {
            try {
                const bodyData = JSON.parse(options.body);
                // Create a sanitized copy for logging (remove sensitive fields)
                const sanitizedBody = { ...bodyData };
                console.log("Request body:", sanitizedBody);
            } catch (e) {
                console.log("Request body: [Unable to parse]");
            }
        }
        console.groupEnd();

        try {
            const response = await fetch(url, config);
            let data;

            // Log response status
            console.group("API Response");
            console.log("Status:", response.status);
            console.log("Status Text:", response.statusText);

            // Try to parse JSON response
            try {
                data = await response.json();
                console.log("Response data:", data);
            } catch (parseError) {
                console.error("Error parsing response:", parseError);
                data = { message: "Invalid response format" };
            }
            console.groupEnd();

            if (!response.ok) {
                // Enhanced error object with response details
                const error = new Error(
                    data.message || `HTTP error! status: ${response.status}`
                );
                error.response = {
                    status: response.status,
                    statusText: response.statusText,
                    data: data,
                };
                throw error;
            }

            return data;
        } catch (error) {
            console.error("API Request failed:", error);
            throw error;
        }
    }

    /**
     * Load invoice data with filters and pagination
     */
    async loadInvoices(
        page = 1,
        search = "",
        status = "",
        sortBy = "created_at",
        sortOrder = "desc",
        startDate = "",
        endDate = "",
        perPage = 10
    ) {
        const params = new URLSearchParams({
            page,
            search,
            status,
            sort_by: sortBy,
            sort_order: sortOrder,
            per_page: perPage,
        });

        if (startDate) {
            params.append("start_date", startDate);
        }
        if (endDate) {
            params.append("end_date", endDate);
        }

        return await this.apiRequest(`${this.baseUrl}?${params}`);
    }

    /**
     * Create new invoice
     */
    async createInvoice(formData) {
        console.log(
            "Creating invoice with data:",
            JSON.parse(JSON.stringify(formData))
        );
        return await this.apiRequest(this.baseUrl, {
            method: "POST",
            body: JSON.stringify(formData),
        });
    }

    /**
     * Update existing invoice
     */
    async updateInvoice(id, formData) {
        console.log("Updating invoice with ID:", id);
        console.log("Update data:", JSON.parse(JSON.stringify(formData)));
        return await this.apiRequest(`${this.baseUrl}/${id}`, {
            method: "PUT",
            body: JSON.stringify(formData),
        });
    }

    /**
     * Delete invoice (soft delete)
     */
    async deleteInvoice(id) {
        return await this.apiRequest(`${this.baseUrl}/${id}`, {
            method: "DELETE",
        });
    }

    /**
     * Restore deleted invoice
     */
    async restoreInvoice(id) {
        return await this.apiRequest(`${this.baseUrl}/${id}/restore`, {
            method: "POST",
        });
    }

    /**
     * Generate PDF for invoice
     */
    async generatePdf(id) {
        return await this.apiRequest(`${this.baseUrl}/${id}/generate-pdf`, {
            method: "POST",
        });
    }

    /**
     * Get URL for viewing PDF
     */
    getPdfViewUrl(id) {
        return `${this.baseUrl}/${id}/pdf`;
    }

    /**
     * Get URL for downloading PDF
     */
    getPdfDownloadUrl(id) {
        return `${this.baseUrl}/${id}/download-pdf`;
    }

    /**
     * Get form data (dropdowns, etc.)
     */
    async getFormData() {
        return await this.apiRequest(`${this.baseUrl}/form-data`);
    }

    /**
     * Check if invoice number exists
     */
    async checkInvoiceNumberExists(invoiceNumber, excludeId = null) {
        const params = new URLSearchParams({ invoice_number: invoiceNumber });
        if (excludeId) {
            params.append("exclude_id", excludeId);
        }

        return await this.apiRequest(
            `${this.baseUrl}/check-invoice-number?${params}`
        );
    }

    /**
     * Generate new invoice number
     */
    async generateInvoiceNumber() {
        return await this.apiRequest(`${this.baseUrl}/generate-invoice-number`);
    }

    /**
     * Debounced search function
     */
    debounceSearch(callback, delay = 300) {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(callback, delay);
    }

    /**
     * Format currency
     */
    formatCurrency(amount) {
        return new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: "USD",
        }).format(amount);
    }

    /**
     * Show success message
     */
    showSuccess(message) {
        if (typeof Swal !== "undefined") {
            Swal.fire({
                title: "Success",
                text: message,
                icon: "success",
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
            });
        } else {
            alert(message);
        }
    }

    /**
     * Show error message
     */
    showError(message) {
        if (typeof Swal !== "undefined") {
            Swal.fire({
                title: "Error",
                text: message,
                icon: "error",
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
            });
        } else {
            alert("Error: " + message);
        }
    }

    /**
     * Format date
     */
    formatDate(dateString) {
        if (!dateString) return "";
        return new Date(dateString).toLocaleDateString("en-US", {
            year: "numeric",
            month: "short",
            day: "numeric",
        });
    }

    /**
     * Show success notification
     */
    showSuccess(message) {
        this.showNotification(message, "success");
    }

    /**
     * Show error notification
     */
    showError(message) {
        this.showNotification(message, "error");
    }

    /**
     * Handle API errors with detailed logging
     */
    handleApiError(error) {
        console.group("🔴 API ERROR DETAILS");
        console.error("Error object:", error);

        if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            const status = error.response.status;
            const data = error.response.data;

            console.log("Response status:", status);
            console.log("Response status text:", error.response.statusText);
            console.log("Response data:", data);

            if (status === 422) {
                // Validation error
                const errorMessage = data.message || "Validation failed";
                console.log("Validation errors:", data.errors);

                // Log detailed validation errors
                if (data.errors) {
                    console.group("📋 Validation Errors Detail");
                    console.table(
                        Object.entries(data.errors).map(([field, messages]) => {
                            return {
                                field,
                                message: Array.isArray(messages)
                                    ? messages.join(", ")
                                    : messages,
                            };
                        })
                    );

                    // Log specific problematic fields that commonly cause issues
                    if (data.errors.invoice_number)
                        console.log(
                            "📝 Invoice number error:",
                            data.errors.invoice_number
                        );
                    if (data.errors.bill_to_phone)
                        console.log(
                            "📞 Phone error:",
                            data.errors.bill_to_phone
                        );
                    if (data.errors.items)
                        console.log("📦 Items error:", data.errors.items);
                    console.groupEnd();
                }

                this.showError(errorMessage);
            } else if (status === 403) {
                // Permission error
                const errorMessage = data.message || "Permission denied";
                this.showError(errorMessage);
            } else {
                // Other server errors
                const errorMessage = data.message || "Server error";
                this.showError(errorMessage);
            }
        } else if (error.request) {
            // The request was made but no response was received
            console.log("No response received:", error.request);
            this.showError("No response from server. Please try again later.");
        } else {
            // Something happened in setting up the request that triggered an Error
            console.log("Error message:", error.message);
            this.showError(error.message || "An error occurred");
        }

        console.groupEnd();
    }

    /**
     * Show notification
     */
    showNotification(message, type = "info") {
        // Create notification element
        const notification = document.createElement("div");
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg transition-all duration-300 transform translate-x-full`;

        const bgColor =
            {
                success: "bg-green-500",
                error: "bg-red-500",
                warning: "bg-yellow-500",
                info: "bg-blue-500",
            }[type] || "bg-blue-500";

        notification.classList.add(bgColor);
        notification.innerHTML = `
            <div class="flex items-center text-white">
                <span class="mr-2">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove("translate-x-full");
        }, 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add("translate-x-full");
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
}

// Initialize global instance
window.invoiceDemoManager = new InvoiceDemoManager();

/**
 * Alpine.js Invoice Demo Component
 */
function invoiceDemoData() {
    return {
        // State
        invoices: [],
        loading: false,
        showModal: false,
        isEditing: false,
        submitting: false,
        currentInvoice: null,
        pdfGenerating: false,
        message: "",
        messageType: "",

        // Pagination
        currentPage: 1,
        totalPages: 1,
        perPage: 10,
        total: 0,

        // Filters
        search: "",
        statusFilter: "",
        dateFilter: "",
        startDate: "",
        endDate: "",
        dateRangeDisplay: "",
        flatpickrInstance: null,
        sortBy: "created_at",
        sortOrder: "desc",
        showDeleted: false,

        // Filtros y paginación
        search: "",
        statusFilter: "",
        currentPage: 1,
        perPage: 10,
        totalPages: 1,
        total: 0,
        sortBy: "created_at",
        sortOrder: "desc",

        // Filtros de fecha
        startDate: "",
        endDate: "",
        dateRangeDisplay: "",
        dateRangePicker: null,

        // Nuevas variables para filtros optimizados
        showAdvancedFilters: false,
        activeQuickFilter: null,

        // Form data
        form: {
            invoice_number: "",
            invoice_date: new Date().toISOString().split("T")[0],
            bill_to_name: "",
            bill_to_address: "",
            bill_to_phone: "",
            subtotal: 0,
            tax_amount: 0,
            balance_due: 0,
            claim_number: "",
            policy_number: "",
            insurance_company: "",
            date_of_loss: "",
            date_received: "",
            date_inspected: "",
            date_entered: "",
            price_list_code: "",
            type_of_loss: "",
            notes: "",
            status: "draft",
            items: [],
        },

        // Form data options
        formData: {
            statuses: [
                { value: "draft", label: "Draft" },
                { value: "sent", label: "Sent" },
                { value: "paid", label: "Paid" },
                { value: "cancelled", label: "Cancelled" },
            ],
            common_insurance_companies: [
                "State Farm",
                "Allstate",
                "GEICO",
                "Progressive",
                "USAA",
                "Liberty Mutual",
                "Farmers",
                "Nationwide",
                "American Family",
            ],
            type_of_loss_options: [
                "Wind",
                "Hail",
                "Fire",
                "Water",
                "Theft",
                "Vandalism",
                "Other",
            ],
        },

        // Form validation
        errors: {},
        invoiceNumberExists: false,

        // Mini-modals for adding new options
        showAddInsuranceModal: false,
        showAddTypeOfLossModal: false,
        newInsuranceCompany: { name: "" },
        newTypeOfLoss: { name: "" },

        // Form data (dropdowns) - removed duplicate

        // Initialize component
        async init() {
            console.log("🚀 Initializing Invoice Demo Manager...");
            await this.loadFormData();
            await this.loadInvoices();
            this.initializeDatePicker();
        },

        // Load form data
        async loadFormData() {
            try {
                const response = await window.invoiceDemoManager.getFormData();
                this.formData.type_of_loss_options =
                    response.data.type_of_loss_options || [];
                this.formData.common_insurance_companies =
                    response.data.common_insurance_companies || [];
            } catch (error) {
                console.error("Failed to load form data:", error);
                window.invoiceDemoManager.showError("Failed to load form data");
            }
        },

        // Load invoices
        async loadInvoices() {
            this.loading = true;
            try {
                const response = await window.invoiceDemoManager.loadInvoices(
                    this.currentPage,
                    this.search,
                    this.statusFilter,
                    this.sortBy,
                    this.sortOrder,
                    this.startDate,
                    this.endDate,
                    this.perPage
                );

                this.invoices = response.data.data || [];
                this.currentPage = response.data.current_page || 1;
                this.totalPages = response.data.last_page || 1;
                this.perPage = response.data.per_page || 10;
                this.total = response.data.total || 0;
            } catch (error) {
                console.error("Failed to load invoices:", error);
                window.invoiceDemoManager.showError("Failed to load invoices");
            } finally {
                this.loading = false;
            }
        },

        // Search invoices (debounced)
        searchInvoices() {
            window.invoiceDemoManager.debounceSearch(() => {
                this.currentPage = 1;
                this.loadInvoices();
            });
        },

        // Filter by status
        filterByStatus() {
            this.currentPage = 1;
            this.loadInvoices();
        },

        // Change items per page
        changePerPage() {
            this.currentPage = 1;
            this.loadInvoices();
        },

        // Initialize Flatpickr
        initializeDatePicker() {
            // Initialize flatpickr for date range
            const picker = flatpickr("#dateRangePicker", {
                mode: "range",
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "M j, Y",
                showMonths: 2,
                theme: "dark",
                onClose: (selectedDates) => {
                    if (selectedDates.length === 2) {
                        this.startDate = selectedDates[0]
                            .toISOString()
                            .split("T")[0];
                        this.endDate = selectedDates[1]
                            .toISOString()
                            .split("T")[0];
                        this.currentPage = 1;
                        this.loadInvoices();
                    }
                },
                onClear: () => {
                    this.startDate = "";
                    this.endDate = "";
                    this.dateRangeDisplay = "";
                    this.currentPage = 1;
                    this.loadInvoices();
                },
            });

            this.dateRangePicker = picker;
        },

        // Set predefined date ranges
        setDateRange(period) {
            const today = new Date();
            let startDate, endDate;

            switch (period) {
                case "today":
                    startDate = endDate = today;
                    break;
                case "last7days":
                    startDate = new Date(
                        today.getTime() - 7 * 24 * 60 * 60 * 1000
                    );
                    endDate = today;
                    break;
                case "last30days":
                    startDate = new Date(
                        today.getTime() - 30 * 24 * 60 * 60 * 1000
                    );
                    endDate = today;
                    break;
                case "thisMonth":
                    startDate = new Date(
                        today.getFullYear(),
                        today.getMonth(),
                        1
                    );
                    endDate = new Date(
                        today.getFullYear(),
                        today.getMonth() + 1,
                        0
                    );
                    break;
                case "thisYear":
                    startDate = new Date(today.getFullYear(), 0, 1);
                    endDate = new Date(today.getFullYear(), 11, 31);
                    break;
                default:
                    return;
            }

            this.startDate = startDate.toISOString().split("T")[0];
            this.endDate = endDate.toISOString().split("T")[0];
            this.activeQuickFilter = period;
            this.currentPage = 1;

            // Update flatpickr display
            if (this.dateRangePicker) {
                this.dateRangePicker.setDate([startDate, endDate]);
            }

            this.loadInvoices();
        },

        // Clear date range
        clearDateRange() {
            this.startDate = "";
            this.endDate = "";
            this.dateRangeDisplay = "";

            // Clear Flatpickr
            if (this.dateRangePicker) {
                this.dateRangePicker.clear();
            }

            // Apply filter (show all)
            this.filterByDateRange();
        },

        // Filter by date range
        filterByDateRange() {
            this.currentPage = 1;
            this.loadInvoices();
        },

        // Sort invoices
        sortInvoices(field) {
            if (this.sortBy === field) {
                this.sortOrder = this.sortOrder === "asc" ? "desc" : "asc";
            } else {
                this.sortBy = field;
                this.sortOrder = "asc";
            }
            this.loadInvoices();
        },

        // Pagination
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.loadInvoices();
            }
        },

        // Open modal for creating new invoice
        openCreateModal() {
            this.isEditing = false;
            this.currentInvoice = null;
            this.resetForm();
            this.showModal = true;
        },

        // Open modal for editing invoice
        async openEditModal(invoice) {
            this.isEditing = true;
            this.currentInvoice = invoice;
            this.populateForm(invoice);
            this.showModal = true;
        },

        // Close modal
        closeModal() {
            this.showModal = false;
            this.resetForm();
            this.errors = {};
            this.invoiceNumberExists = false;
        },

        // Reset form
        resetForm() {
            this.form = {
                invoice_number: "",
                invoice_date: "",
                bill_to_name: "",
                bill_to_address: "",
                bill_to_phone: "",
                subtotal: 0,
                tax_amount: 0,
                balance_due: 0,
                claim_number: "",
                policy_number: "",
                insurance_company: "",
                date_of_loss: "",
                date_received: "",
                date_inspected: "",
                date_entered: "",
                price_list_code: "",
                type_of_loss: "",
                notes: "",
                status: "draft",
                items: [],
            };
        },

        // Populate form with invoice data
        populateForm(invoice) {
            this.form = {
                invoice_number: invoice.invoice_number || "",
                invoice_date: invoice.invoice_date || "",
                bill_to_name: invoice.bill_to_name || "",
                bill_to_address: invoice.bill_to_address || "",
                bill_to_phone: invoice.bill_to_phone_raw || "",
                subtotal: invoice.subtotal || 0,
                tax_amount: invoice.tax_amount || 0,
                balance_due: invoice.balance_due || 0,
                claim_number: invoice.claim_number || "",
                policy_number: invoice.policy_number || "",
                insurance_company: invoice.insurance_company || "",
                date_of_loss: invoice.date_of_loss || "",
                date_received: invoice.date_received || "",
                date_inspected: invoice.date_inspected || "",
                date_entered: invoice.date_entered || "",
                price_list_code: invoice.price_list_code || "",
                type_of_loss: invoice.type_of_loss || "",
                notes: invoice.notes || "",
                status: invoice.status || "draft",
                items: invoice.items || [],
            };
        },

        // Add new item to invoice
        addItem() {
            this.form.items.push({
                service_name: "",
                description: "",
                quantity: 1,
                rate: 0,
                sort_order: this.form.items.length,
            });
        },

        // Remove item from invoice
        removeItem(index) {
            this.form.items.splice(index, 1);
            this.calculateTotals();
        },

        // Calculate invoice totals
        calculateTotals() {
            let subtotal = 0;
            this.form.items.forEach((item) => {
                // Asegurar que quantity y rate sean números
                const quantity = parseFloat(item.quantity || 0);
                const rate = parseFloat(item.rate || 0);

                // Calcular el monto del ítem
                const itemAmount = quantity * rate;
                item.amount = itemAmount.toFixed(2); // Solo formateamos el amount para mostrar

                subtotal += itemAmount;
            });

            // Actualizar el subtotal en el formulario
            this.form.subtotal = subtotal.toFixed(2);

            // Calcular balance_due
            const taxAmount = parseFloat(this.form.tax_amount || 0);
            this.form.balance_due = (subtotal + taxAmount).toFixed(2); // Solo formateamos el balance_due para mostrar
        },

        // Submit form
        async submitForm() {
            if (this.submitting) return;

            this.submitting = true;
            this.errors = {};
            this.generalError = "";

            // Enhanced logging for debugging
            console.group("Form Submission");
            console.log("Operation:", this.isEditing ? "UPDATE" : "CREATE");
            console.log(
                "Invoice ID:",
                this.isEditing ? this.currentInvoice?.id : "New Invoice"
            );

            // Log critical fields that often cause validation issues
            console.log("Critical fields:", {
                invoice_number: this.form.invoice_number,
                bill_to_phone: this.form.bill_to_phone,
                invoice_date: this.form.invoice_date,
                items_count: this.form.items.length,
            });

            // Log complete form data
            console.log(
                "Complete form data:",
                JSON.parse(JSON.stringify(this.form))
            );
            console.groupEnd();

            try {
                let response;
                if (this.isEditing) {
                    console.log(
                        "Calling updateInvoice with ID:",
                        this.currentInvoice.id
                    );
                    response = await window.invoiceDemoManager.updateInvoice(
                        this.currentInvoice.id,
                        this.form
                    );
                    console.log("Update response:", response);
                } else {
                    console.log("Calling createInvoice");
                    response = await window.invoiceDemoManager.createInvoice(
                        this.form
                    );
                    console.log("Create response:", response);
                }

                window.invoiceDemoManager.showSuccess(response.message);
                this.closeModal();
                await this.loadInvoices();
            } catch (error) {
                console.error("Form submission error:", error);

                if (error.response && error.response.status === 422) {
                    // Enhanced 422 validation error handling
                    console.group("Validation Error (422)");
                    console.log("Error response:", error.response);

                    try {
                        const errorData =
                            error.response.data || JSON.parse(error.message);
                        this.errors = errorData.errors || {};

                        // Log detailed validation errors
                        console.log("All validation errors:", this.errors);
                        console.table(
                            Object.entries(this.errors).map(
                                ([field, messages]) => {
                                    return {
                                        field,
                                        message: Array.isArray(messages)
                                            ? messages.join(", ")
                                            : messages,
                                    };
                                }
                            )
                        );

                        // Log specific problematic fields
                        if (this.errors.invoice_number)
                            console.log(
                                "Invoice number error:",
                                this.errors.invoice_number
                            );
                        if (this.errors.bill_to_phone)
                            console.log(
                                "Phone error:",
                                this.errors.bill_to_phone
                            );
                        if (this.errors.items)
                            console.log("Items error:", this.errors.items);

                        console.groupEnd();
                    } catch (parseError) {
                        console.error(
                            "Error parsing validation response:",
                            parseError
                        );
                        console.groupEnd();
                        this.errors = { general: "Validation failed" };
                    }
                } else if (error.response && error.response.status === 500) {
                    console.group("Server Error (500)");
                    console.log("Error response:", error.response);
                    console.groupEnd();
                    window.invoiceDemoManager.showError(
                        "Server error: " +
                            (error.response.data?.message ||
                                "Failed to save invoice")
                    );
                } else {
                    console.group("Other Error");
                    console.log("Error object:", error);
                    console.groupEnd();
                    window.invoiceDemoManager.showError(
                        error.message || "Failed to save invoice"
                    );
                }
            } finally {
                this.submitting = false;
            }
        },

        // Delete invoice
        async deleteInvoice(invoice) {
            const result = await Swal.fire({
                title: "¿Estás seguro?",
                html: `¿Deseas eliminar la factura: <strong>${invoice.invoice_number}</strong>?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
            });

            if (!result.isConfirmed) {
                return;
            }

            try {
                const response = await window.invoiceDemoManager.deleteInvoice(
                    invoice.id
                );

                Swal.fire({
                    title: "Eliminado",
                    text: "La factura ha sido eliminada exitosamente",
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false,
                });

                await this.loadInvoices();
            } catch (error) {
                Swal.fire({
                    title: "Error",
                    text: error.message || "Error al eliminar la factura",
                    icon: "error",
                });
            }
        },

        // Generate PDF for invoice
        async generatePdf(invoiceId) {
            this.pdfGenerating = true;
            try {
                await window.invoiceDemoManager.generatePdf(invoiceId);
                window.invoiceDemoManager.showSuccess(
                    "PDF generated successfully"
                );
                // Refresh the invoice list to get updated pdf_url
                await this.loadInvoices();
            } catch (error) {
                console.error("Failed to generate PDF:", error);
                window.invoiceDemoManager.showError("Failed to generate PDF");
            } finally {
                this.pdfGenerating = false;
            }
        },

        // Restore invoice
        async restoreInvoice(invoice) {
            const result = await Swal.fire({
                title: "¿Restaurar factura?",
                html: `¿Deseas restaurar la factura: <strong>${invoice.invoice_number}</strong>?`,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Sí, restaurar",
                cancelButtonText: "Cancelar",
            });

            if (!result.isConfirmed) {
                return;
            }

            try {
                const response = await window.invoiceDemoManager.restoreInvoice(
                    invoice.id
                );

                Swal.fire({
                    title: "Restaurado",
                    text: "La factura ha sido restaurada exitosamente",
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false,
                });

                await this.loadInvoices();
            } catch (error) {
                Swal.fire({
                    title: "Error",
                    text: error.message || "Error al restaurar la factura",
                    icon: "error",
                });
            }
        },

        // Check if invoice number exists
        async checkInvoiceNumberExists() {
            if (!this.form.invoice_number) {
                this.invoiceNumberExists = false;
                return;
            }

            try {
                const response =
                    await window.invoiceDemoManager.checkInvoiceNumberExists(
                        this.form.invoice_number,
                        this.isEditing ? this.currentInvoice.uuid : null
                    );
                this.invoiceNumberExists = response.exists;
            } catch (error) {
                console.error("Failed to check invoice number:", error);
            }
        },

        // Generate invoice number
        async generateInvoiceNumber() {
            try {
                const response =
                    await window.invoiceDemoManager.generateInvoiceNumber();
                this.form.invoice_number = response.invoice_number;
                this.invoiceNumberExists = false;
            } catch (error) {
                window.invoiceDemoManager.showError(
                    error.message || "Failed to generate invoice number"
                );
            }
        },

        // Get status badge class
        getStatusBadgeClass(status) {
            const classes = {
                draft: "bg-gray-100 text-gray-800",
                sent: "bg-blue-100 text-blue-800",
                paid: "bg-green-100 text-green-800",
                cancelled: "bg-red-100 text-red-800",
            };
            return classes[status] || "bg-gray-100 text-gray-800";
        },

        // Format currency
        formatCurrency(amount) {
            return window.invoiceDemoManager.formatCurrency(amount);
        },

        // Format date
        formatDate(dateString) {
            return window.invoiceDemoManager.formatDate(dateString);
        },

        // Additional helper methods for pagination and UI
        getPageNumbers() {
            const pages = [];
            const start = Math.max(1, this.currentPage - 2);
            const end = Math.min(this.totalPages, start + 4);

            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            return pages;
        },

        // Change page helper
        changePage(page) {
            this.goToPage(page);
        },

        // Edit invoice helper
        editInvoice(uuid) {
            const invoice = this.invoices.find((inv) => inv.uuid === uuid);
            if (invoice) {
                this.openEditModal(invoice);
            }
        },

        // Helper methods moved to avoid duplication - see methods above

        // ==================== MINI-MODAL METHODS ====================

        // Add new insurance company
        async addNewInsuranceCompany() {
            if (!this.newInsuranceCompany.name.trim()) return;

            try {
                // Add to local list immediately for better UX
                const newCompany = this.newInsuranceCompany.name.trim();
                if (
                    !this.formData.common_insurance_companies.includes(
                        newCompany
                    )
                ) {
                    this.formData.common_insurance_companies.push(newCompany);
                    this.formData.common_insurance_companies.sort();
                }

                // Set the new company as selected
                this.form.insurance_company = newCompany;

                // Close modal and reset
                this.showAddInsuranceModal = false;
                this.newInsuranceCompany.name = "";

                window.invoiceDemoManager.showSuccess(
                    `Insurance company "${newCompany}" added successfully`
                );
            } catch (error) {
                console.error("Failed to add insurance company:", error);
                window.invoiceDemoManager.showError(
                    "Failed to add insurance company"
                );
            }
        },

        // Add new type of loss
        async addNewTypeOfLoss() {
            if (!this.newTypeOfLoss.name.trim()) return;

            try {
                // Add to local list immediately for better UX
                const newType = this.newTypeOfLoss.name.trim();
                if (!this.formData.type_of_loss_options.includes(newType)) {
                    this.formData.type_of_loss_options.push(newType);
                    this.formData.type_of_loss_options.sort();
                }

                // Set the new type as selected
                this.form.type_of_loss = newType;

                // Close modal and reset
                this.showAddTypeOfLossModal = false;
                this.newTypeOfLoss.name = "";

                window.invoiceDemoManager.showSuccess(
                    `Type of loss "${newType}" added successfully`
                );
            } catch (error) {
                console.error("Failed to add type of loss:", error);
                window.invoiceDemoManager.showError(
                    "Failed to add type of loss"
                );
            }
        },

        // ==================== FORMATTING METHODS ====================

        // Format phone input in real-time
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
                formattedValue = `(${value.substring(0, 3)}) ${value.substring(
                    3
                )}`;
            } else {
                formattedValue = `(${value.substring(0, 3)}) ${value.substring(
                    3,
                    6
                )}-${value.substring(6)}`;
            }

            input.value = formattedValue;
            this.form.bill_to_phone = formattedValue;
        },

        // Format name input (capitalize with spaces)
        formatNameInput(event) {
            const input = event.target;
            const cursorPosition = input.selectionStart;
            let value = input.value;

            // Capitalizar la primera letra de cada palabra
            const capitalizedValue = value.replace(/\b\w/g, (match) =>
                match.toUpperCase()
            );

            // Solo actualizar si hay cambios para evitar loops
            if (capitalizedValue !== value) {
                input.value = capitalizedValue;
                this.form.bill_to_name = capitalizedValue;
                // Restaurar la posición del cursor
                input.setSelectionRange(cursorPosition, cursorPosition);
            }
        },

        // Format uppercase input (for claim/policy numbers)
        formatUppercaseInput(event, fieldName) {
            const input = event.target;
            const cursorPosition = input.selectionStart;
            let value = input.value;

            // Convertir letras a mayúsculas, mantener números y guiones
            const uppercaseValue = value.toUpperCase();

            // Solo actualizar si hay cambios para evitar loops
            if (uppercaseValue !== value) {
                input.value = uppercaseValue;
                this.form[fieldName] = uppercaseValue;
                // Restaurar la posición del cursor
                input.setSelectionRange(cursorPosition, cursorPosition);
            }
        },

        // Format invoice number input (ensure it starts with VG- and is uppercase)
        formatInvoiceNumberInput(event) {
            const input = event.target;
            const cursorPosition = input.selectionStart;
            let value = input.value;

            // Convertir a mayúsculas
            let uppercaseValue = value.toUpperCase();

            // Asegurar que comience con VG-
            if (
                !uppercaseValue.startsWith("VG-") &&
                uppercaseValue.length > 0
            ) {
                if (uppercaseValue.startsWith("VG")) {
                    uppercaseValue = "VG-" + uppercaseValue.substring(2);
                } else {
                    uppercaseValue = "VG-" + uppercaseValue;
                }
            }

            // Permitir solo números después del prefijo VG-
            if (uppercaseValue.startsWith("VG-")) {
                const prefix = "VG-";
                const numericPart = uppercaseValue.substring(prefix.length);
                // Reemplazar cualquier carácter no numérico en la parte después del prefijo
                const numericOnly = numericPart.replace(/[^0-9]/g, "");
                uppercaseValue = prefix + numericOnly;
            }

            // Solo actualizar si hay cambios para evitar loops
            if (uppercaseValue !== value) {
                input.value = uppercaseValue;
                this.form.invoice_number = uppercaseValue;
                // Ajustar la posición del cursor si se agregó el prefijo
                const cursorAdjustment = uppercaseValue.length - value.length;
                input.setSelectionRange(
                    cursorPosition + cursorAdjustment,
                    cursorPosition + cursorAdjustment
                );
            }
        },

        // Format service description input (all uppercase)
        formatServiceDescriptionInput(event, itemIndex) {
            const input = event.target;
            const cursorPosition = input.selectionStart;
            let value = input.value;

            // Convertir todo a mayúsculas
            const uppercaseValue = value.toUpperCase();

            // Solo actualizar si hay cambios para evitar loops
            if (uppercaseValue !== value) {
                input.value = uppercaseValue;
                this.form.items[itemIndex].service_name = uppercaseValue;
                // Restaurar la posición del cursor
                input.setSelectionRange(cursorPosition, cursorPosition);
            }
        },

        // Format notes input (capitalize only first letter)
        formatNotesInput(event) {
            const input = event.target;
            const cursorPosition = input.selectionStart;
            let value = input.value;

            // Capitalizar solo la primera letra del texto completo
            const capitalizedValue =
                value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();

            // Solo actualizar si hay cambios para evitar loops
            if (capitalizedValue !== value && value.length > 0) {
                input.value = capitalizedValue;
                this.form.notes = capitalizedValue;
                // Restaurar la posición del cursor
                input.setSelectionRange(cursorPosition, cursorPosition);
            }
        },

        // Format address input (capitalize each word)
        formatAddressInput(event) {
            const input = event.target;
            const cursorPosition = input.selectionStart;
            let value = input.value;

            // Capitalizar la primera letra de cada palabra
            const capitalizedValue = value.replace(/\b\w/g, (match) =>
                match.toUpperCase()
            );

            // Solo actualizar si hay cambios para evitar loops
            if (capitalizedValue !== value) {
                input.value = capitalizedValue;
                this.form.bill_to_address = capitalizedValue;
                // Restaurar la posición del cursor
                input.setSelectionRange(cursorPosition, cursorPosition);
            }
        },

        // Format item description input (capitalize each word)
        formatItemDescriptionInput(event, itemIndex) {
            const input = event.target;
            const cursorPosition = input.selectionStart;
            let value = input.value;

            // Capitalizar la primera letra de cada palabra
            const capitalizedValue = value.replace(/\b\w/g, (match) =>
                match.toUpperCase()
            );

            // Solo actualizar si hay cambios para evitar loops
            if (capitalizedValue !== value) {
                input.value = capitalizedValue;
                this.form.items[itemIndex].description = capitalizedValue;
                // Restaurar la posición del cursor
                input.setSelectionRange(cursorPosition, cursorPosition);
            }
        },

        // ==================== CURRENCY INPUT FORMATTING ====================

        // Format currency input with thousands separator and decimals
        formatCurrencyInput(event, itemIndex) {
            const input = event.target;
            const cursorPosition = input.selectionStart;
            let value = input.value;

            // Remove all non-numeric characters except decimal point
            let numericValue = value.replace(/[^0-9.]/g, "");

            // Handle multiple decimal points - keep only the first one
            const decimalParts = numericValue.split(".");
            if (decimalParts.length > 2) {
                numericValue =
                    decimalParts[0] + "." + decimalParts.slice(1).join("");
            }

            // Limit to 2 decimal places
            if (decimalParts.length === 2 && decimalParts[1].length > 2) {
                numericValue =
                    decimalParts[0] + "." + decimalParts[1].substring(0, 2);
            }

            // Convert to number for formatting
            const numberValue = parseFloat(numericValue) || 0;

            // Format for display with thousands separator
            let formattedValue = "";
            if (numericValue === "" || numericValue === "0") {
                formattedValue = "";
            } else if (numericValue.endsWith(".")) {
                // If user is typing decimal point, keep it
                const integerPart =
                    Math.floor(numberValue).toLocaleString("en-US");
                formattedValue = integerPart + ".";
            } else if (
                numericValue.includes(".") &&
                numericValue.split(".")[1].length === 1
            ) {
                // If user has typed one decimal place
                const integerPart =
                    Math.floor(numberValue).toLocaleString("en-US");
                const decimalPart = numericValue.split(".")[1];
                formattedValue = integerPart + "." + decimalPart;
            } else {
                // Format with full decimals or whole number
                formattedValue = numberValue.toLocaleString("en-US", {
                    minimumFractionDigits: numericValue.includes(".") ? 2 : 0,
                    maximumFractionDigits: 2,
                });
            }

            // Update input value and model
            if (formattedValue !== value) {
                input.value = formattedValue;

                // Store raw numeric value for calculations
                this.form.items[itemIndex].rate = numericValue;

                // Calculate totals after updating rate
                this.calculateTotals();

                // Adjust cursor position after formatting
                const lengthDifference = formattedValue.length - value.length;
                const newCursorPosition = Math.max(
                    0,
                    cursorPosition + lengthDifference
                );

                // Set cursor position in next tick to avoid conflicts
                setTimeout(() => {
                    input.setSelectionRange(
                        newCursorPosition,
                        newCursorPosition
                    );
                }, 0);
            }
        },

        // Format general currency inputs (for subtotal, tax_amount, etc.)
        formatGeneralCurrencyInput(event, fieldName) {
            const input = event.target;
            const cursorPosition = input.selectionStart;
            let value = input.value;

            // Remove all non-numeric characters except decimal point
            let numericValue = value.replace(/[^0-9.]/g, "");

            // Handle multiple decimal points - keep only the first one
            const decimalParts = numericValue.split(".");
            if (decimalParts.length > 2) {
                numericValue =
                    decimalParts[0] + "." + decimalParts.slice(1).join("");
            }

            // Limit to 2 decimal places
            if (decimalParts.length === 2 && decimalParts[1].length > 2) {
                numericValue =
                    decimalParts[0] + "." + decimalParts[1].substring(0, 2);
            }

            // Convert to number for formatting
            const numberValue = parseFloat(numericValue) || 0;

            // Format for display with thousands separator
            let formattedValue = "";
            if (numericValue === "" || numericValue === "0") {
                formattedValue = "";
            } else if (numericValue.endsWith(".")) {
                // If user is typing decimal point, keep it
                const integerPart =
                    Math.floor(numberValue).toLocaleString("en-US");
                formattedValue = integerPart + ".";
            } else if (
                numericValue.includes(".") &&
                numericValue.split(".")[1].length === 1
            ) {
                // If user has typed one decimal place
                const integerPart =
                    Math.floor(numberValue).toLocaleString("en-US");
                const decimalPart = numericValue.split(".")[1];
                formattedValue = integerPart + "." + decimalPart;
            } else {
                // Format with full decimals or whole number
                formattedValue = numberValue.toLocaleString("en-US", {
                    minimumFractionDigits: numericValue.includes(".") ? 2 : 0,
                    maximumFractionDigits: 2,
                });
            }

            // Update input value and model
            if (formattedValue !== value) {
                input.value = formattedValue;

                // Store raw numeric value for calculations
                this.form[fieldName] = numericValue;

                // Calculate totals after updating financial fields
                if (fieldName === "subtotal" || fieldName === "tax_amount") {
                    this.calculateTotals();
                }

                // Adjust cursor position after formatting
                const lengthDifference = formattedValue.length - value.length;
                const newCursorPosition = Math.max(
                    0,
                    cursorPosition + lengthDifference
                );

                // Set cursor position in next tick to avoid conflicts
                setTimeout(() => {
                    input.setSelectionRange(
                        newCursorPosition,
                        newCursorPosition
                    );
                }, 0);
            }
        },

        toggleDeleted() {
            this.currentPage = 1;
            this.loadInvoices();
        },

        // ========== NUEVAS FUNCIONES PARA FILTROS OPTIMIZADOS ==========

        // Toggle advanced filters
        showAdvancedFilters: false,
        activeQuickFilter: null,

        // Clear all filters
        clearAllFilters() {
            this.search = "";
            this.statusFilter = "";
            this.dateRangeDisplay = "";
            this.activeQuickFilter = null;
            this.currentPage = 1;

            // Clear flatpickr if exists
            if (this.dateRangePicker) {
                this.dateRangePicker.clear();
            }

            this.loadInvoices();
        },

        // Check if there are active filters
        hasActiveFilters() {
            return !!(
                this.search ||
                this.statusFilter ||
                this.startDate ||
                this.endDate ||
                this.showDeleted
            );
        },

        // Get count of active filters
        getActiveFiltersCount() {
            let count = 0;
            if (this.search) count++;
            if (this.statusFilter) count++;
            if (this.startDate || this.endDate) count++;
            if (this.showDeleted) count++;
            return count;
        },

        // Enhanced setDateRange with active filter tracking
        setDateRange(range) {
            this.activeQuickFilter = range;
            const today = new Date();
            let startDate, endDate;

            switch (range) {
                case "today":
                    startDate = endDate = today.toISOString().split("T")[0];
                    break;
                case "last7days":
                    startDate = new Date(today.setDate(today.getDate() - 7))
                        .toISOString()
                        .split("T")[0];
                    endDate = new Date().toISOString().split("T")[0];
                    break;
                case "last30days":
                    startDate = new Date(today.setDate(today.getDate() - 30))
                        .toISOString()
                        .split("T")[0];
                    endDate = new Date().toISOString().split("T")[0];
                    break;
                case "thisMonth":
                    startDate = new Date(
                        today.getFullYear(),
                        today.getMonth(),
                        1
                    )
                        .toISOString()
                        .split("T")[0];
                    endDate = new Date(
                        today.getFullYear(),
                        today.getMonth() + 1,
                        0
                    )
                        .toISOString()
                        .split("T")[0];
                    break;
                case "thisYear":
                    startDate = new Date(today.getFullYear(), 0, 1)
                        .toISOString()
                        .split("T")[0];
                    endDate = new Date(today.getFullYear(), 11, 31)
                        .toISOString()
                        .split("T")[0];
                    break;
            }

            this.startDate = startDate;
            this.endDate = endDate;
            this.dateRangeDisplay = `${startDate} to ${endDate}`;

            if (this.dateRangePicker) {
                this.dateRangePicker.setDate([startDate, endDate]);
            }

            this.filterByDateRange();
        },
    };
}

// Make available globally
window.invoiceDemoData = invoiceDemoData;
