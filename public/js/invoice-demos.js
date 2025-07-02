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
        perPage = 10,
        includeDeleted = false
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
        if (includeDeleted) {
            params.append("include_deleted", "1");
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
            
            // Ensure DOM is ready before initializing
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    this.initializeComponents();
                });
            } else {
                this.initializeComponents();
            }
        },

        async initializeComponents() {
            console.log("🔧 Initializing components...");
            await this.loadFormData();
            await this.loadInvoices();
            
            // Initialize date picker with a delay to ensure all scripts and DOM are ready
            setTimeout(() => {
                console.log("🔧 Starting date picker initialization...");
                this.initializeDatePicker();
            }, 300);
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
                    this.perPage,
                    this.showDeleted
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
            console.log("🔧 Initializing date picker...");

            // Check if flatpickr is available
            if (typeof flatpickr === 'undefined') {
                console.warn("⚠️ Flatpickr not available, retrying in 500ms...");
                setTimeout(() => this.initializeDatePicker(), 500);
                return;
            }

            const dateInput = document.getElementById('dateRangePicker');
            if (!dateInput) {
                console.warn("⚠️ Date input element not found, retrying in 500ms...");
                setTimeout(() => this.initializeDatePicker(), 500);
                return;
            }

            console.log("📅 Found date input element:", dateInput);
            console.log("📅 Input styles:", window.getComputedStyle(dateInput));

            try {
                // Destroy any existing instance
                if (this.dateRangePicker && typeof this.dateRangePicker.destroy === 'function') {
                    console.log("🗑️ Destroying existing Flatpickr instance...");
                    this.dateRangePicker.destroy();
                }

                // Remove readonly temporarily to test
                dateInput.removeAttribute('readonly');

                // Initialize Flatpickr with enhanced config
                this.dateRangePicker = flatpickr(dateInput, {
                    mode: "range",
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "M j, Y",
                    allowInput: false,
                    clickOpens: true,
                    static: false,
                    appendTo: document.body,
                    positionElement: dateInput,
                    ignoredFocusElements: [],
                    onReady: function(selectedDates, dateStr, instance) {
                        console.log("📅 Flatpickr ready!", instance);
                        // Re-add readonly after initialization
                        dateInput.setAttribute('readonly', 'readonly');
                    },
                    onOpen: function(selectedDates, dateStr, instance) {
                        console.log("📅 Flatpickr opened!");
                        // Force maximum z-index and positioning
                        const calendar = instance.calendarContainer;
                        if (calendar) {
                            calendar.style.zIndex = "999999";
                            calendar.style.position = "fixed";
                            calendar.style.pointerEvents = "auto";
                            console.log("📅 Calendar z-index set to 999999");
                        }
                    },
                    onClose: function(selectedDates, dateStr, instance) {
                        console.log("📅 Flatpickr closed with dates:", selectedDates);
                        if (selectedDates.length === 2) {
                            // Update the display and trigger filtering
                            const startDate = selectedDates[0].toISOString().split('T')[0];
                            const endDate = selectedDates[1].toISOString().split('T')[0];
                            console.log("📅 Date range selected:", startDate, "to", endDate);
                        }
                    },
                    onChange: function(selectedDates, dateStr, instance) {
                        console.log("📅 Date changed:", selectedDates, dateStr);
                    },
                    onClear: function() {
                        console.log("📅 Date cleared");
                    }
                });

                // Store reference for later use
                this.flatpickrInstance = this.dateRangePicker;

                console.log("✅ Flatpickr initialized successfully");
                console.log("📅 Picker instance:", this.dateRangePicker);

                // Add multiple event listeners for debugging and manual opening
                dateInput.addEventListener('click', (e) => {
                    console.log("🖱️ Input clicked!", e);
                    e.preventDefault();
                    e.stopPropagation();
                    if (this.dateRangePicker) {
                        console.log("📅 Manually opening calendar...");
                        this.dateRangePicker.open();
                    }
                });

                dateInput.addEventListener('focus', (e) => {
                    console.log("🎯 Input focused!", e);
                    if (this.dateRangePicker) {
                        this.dateRangePicker.open();
                    }
                });

                // Test if calendar opens programmatically
                setTimeout(() => {
                    console.log("🧪 Testing calendar open...");
                    if (this.dateRangePicker) {
                        this.dateRangePicker.open();
                        setTimeout(() => {
                            this.dateRangePicker.close();
                            console.log("🧪 Test complete - calendar should have opened and closed");
                        }, 1000);
                    }
                }, 2000);

            } catch (error) {
                console.error("❌ Error initializing Flatpickr:", error);
                // Retry after a delay
                setTimeout(() => {
                    console.log("🔄 Retrying Flatpickr initialization...");
                    this.initializeDatePicker();
                }, 1000);
            }
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
            this.activeQuickFilter = null;

            // Update flatpickr display
            if (this.dateRangePicker && typeof this.dateRangePicker.clear === 'function') {
                this.dateRangePicker.clear();
            } else if (this.flatpickrInstance && typeof this.flatpickrInstance.clear === 'function') {
                this.flatpickrInstance.clear();
            }

            this.currentPage = 1;
            this.loadInvoices();
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

        // ============ ADDITIONAL METHODS ============

        toggleDeleted() {
            this.currentPage = 1;
            this.loadInvoices();
        },

        clearAllFilters() {
            console.log("🧹 Clearing all filters...");

            // Clear all search and filter variables
            this.search = "";
            this.statusFilter = "";
            this.startDate = "";
            this.endDate = "";
            this.dateRangeDisplay = "";
            this.activeQuickFilter = null;
            this.showDeleted = false;
            this.currentPage = 1;

            // Clear flatpickr instance if exists
            if (this.flatpickrInstance && typeof this.flatpickrInstance.clear === 'function') {
                this.flatpickrInstance.clear();
            } else if (this.dateRangePicker && typeof this.dateRangePicker.clear === 'function') {
                this.dateRangePicker.clear();
            }
            
            // Also try to clear using the global flatpickr approach
            const dateInput = document.getElementById("dateRangePicker");
            if (dateInput && dateInput._flatpickr && typeof dateInput._flatpickr.clear === 'function') {
                dateInput._flatpickr.clear();
            }

            // Force reload invoices with cleared filters
            this.loadInvoices();

            // Show confirmation message
            window.invoiceDemoManager.showSuccess(
                "Filters cleared successfully"
            );

            console.log("✅ All filters cleared successfully");
        },

        hasActiveFilters() {
            return !!(
                this.search ||
                this.statusFilter ||
                this.startDate ||
                this.endDate ||
                this.showDeleted
            );
        },

        getActiveFiltersCount() {
            let count = 0;
            if (this.search) count++;
            if (this.statusFilter) count++;
            if (this.startDate || this.endDate) count++;
            if (this.showDeleted) count++;
            return count;
        },

        // Format currency
        formatCurrency(amount) {
            return window.invoiceDemoManager.formatCurrency(amount);
        },

        // Format date
        formatDate(dateString) {
            return window.invoiceDemoManager.formatDate(dateString);
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
    };
}

// Make available globally
window.invoiceDemoData = invoiceDemoData;
