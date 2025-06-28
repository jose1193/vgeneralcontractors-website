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
     * Fetch API wrapper with error handling
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

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.message || `HTTP error! status: ${response.status}`
                );
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
        sortOrder = "desc"
    ) {
        const params = new URLSearchParams({
            page,
            search,
            status,
            sort_by: sortBy,
            sort_order: sortOrder,
        });

        return await this.apiRequest(`${this.baseUrl}?${params}`);
    }

    /**
     * Create new invoice
     */
    async createInvoice(formData) {
        return await this.apiRequest(this.baseUrl, {
            method: "POST",
            body: JSON.stringify(formData),
        });
    }

    /**
     * Update existing invoice
     */
    async updateInvoice(id, formData) {
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

        // Pagination
        currentPage: 1,
        totalPages: 1,
        perPage: 10,
        total: 0,

        // Filters
        search: "",
        statusFilter: "",
        dateFilter: "",
        sortBy: "created_at",
        sortOrder: "desc",
        showDeleted: false,

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
            await this.loadFormData();
            await this.loadInvoices();
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
                    this.sortOrder
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
                subtotal += (item.quantity || 0) * (item.rate || 0);
            });

            this.form.subtotal = subtotal;
            this.form.balance_due = subtotal + (this.form.tax_amount || 0);
        },

        // Submit form
        async submitForm() {
            this.submitting = true;
            this.errors = {};

            try {
                let response;
                if (this.isEditing) {
                    response = await window.invoiceDemoManager.updateInvoice(
                        this.currentInvoice.id,
                        this.form
                    );
                } else {
                    response = await window.invoiceDemoManager.createInvoice(
                        this.form
                    );
                }

                window.invoiceDemoManager.showSuccess(response.message);
                this.closeModal();
                await this.loadInvoices();
            } catch (error) {
                if (
                    error.message.includes("validation") ||
                    error.message.includes("422")
                ) {
                    // Handle validation errors
                    try {
                        const errorData = JSON.parse(error.message);
                        this.errors = errorData.errors || {};
                    } catch {
                        this.errors = { general: "Validation failed" };
                    }
                } else {
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
            if (
                !confirm(
                    `Are you sure you want to delete invoice ${invoice.invoice_number}?`
                )
            ) {
                return;
            }

            try {
                const response = await window.invoiceDemoManager.deleteInvoice(
                    invoice.id
                );
                window.invoiceDemoManager.showSuccess(response.message);
                await this.loadInvoices();
            } catch (error) {
                window.invoiceDemoManager.showError(
                    error.message || "Failed to delete invoice"
                );
            }
        },

        // Restore invoice
        async restoreInvoice(invoice) {
            try {
                const response = await window.invoiceDemoManager.restoreInvoice(
                    invoice.id
                );
                window.invoiceDemoManager.showSuccess(response.message);
                await this.loadInvoices();
            } catch (error) {
                window.invoiceDemoManager.showError(
                    error.message || "Failed to restore invoice"
                );
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

        // Format service description (all uppercase)
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
    };
}

// Make available globally
window.invoiceDemoData = invoiceDemoData;
