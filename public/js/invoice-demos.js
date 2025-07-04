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
        // ✅ AGGRESSIVE cache busting with unique timestamp
        const timestamp = Date.now() + Math.random();
        const defaultOptions = {
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": this.csrfToken,
                "X-Requested-With": "XMLHttpRequest",
                // ✅ AGGRESSIVE cache bypass headers
                "Cache-Control":
                    "no-cache, no-store, must-revalidate, max-age=0",
                Pragma: "no-cache",
                Expires: "0",
                "If-Modified-Since": "0",
                "If-None-Match": "no-match",
                "X-Cache-Bust": timestamp.toString(),
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
        // 🐛 DEBUG: Log all parameters received by loadInvoices
        console.group('🔍 DEBUG: InvoiceDemoManager.loadInvoices() parameters');
        console.log('📄 page:', page);
        console.log('🔍 search:', search);
        console.log('📊 status:', status);
        console.log('📅 startDate:', startDate, '(type:', typeof startDate, ')');
        console.log('📅 endDate:', endDate, '(type:', typeof endDate, ')');
        
        // Inspección detallada de las fechas
        if (startDate) {
            try {
                const parsedStartDate = new Date(startDate);
                console.log('🔍 Parsed startDate:', parsedStartDate);
                console.log('🔍 startDate valid?', !isNaN(parsedStartDate.getTime()));
                console.log('🔍 startDate ISO string:', parsedStartDate.toISOString());
                console.log('🔍 startDate formatted:', parsedStartDate.toLocaleDateString());
            } catch (e) {
                console.error('❌ Error parsing startDate:', e);
            }
        }
        
        if (endDate) {
            try {
                const parsedEndDate = new Date(endDate);
                console.log('🔍 Parsed endDate:', parsedEndDate);
                console.log('🔍 endDate valid?', !isNaN(parsedEndDate.getTime()));
                console.log('🔍 endDate ISO string:', parsedEndDate.toISOString());
                console.log('🔍 endDate formatted:', parsedEndDate.toLocaleDateString());
            } catch (e) {
                console.error('❌ Error parsing endDate:', e);
            }
        }
        
        console.log('📄 perPage:', perPage);
        console.log('🗑️ includeDeleted:', includeDeleted);
        
        // ✅ AGGRESSIVE cache busting parameters
        const timestamp = Date.now();
        const random = Math.random().toString(36).substring(7);
        const params = new URLSearchParams({
            page,
            search,
            status,
            sort_by: sortBy,
            sort_order: sortOrder,
            per_page: perPage,
            // ✅ Always include date parameters, even if empty
            start_date: startDate || '',
            end_date: endDate || '',
            // ✅ Multiple cache busting parameters
            _t: timestamp,
            _r: random,
            _cb: `${timestamp}-${random}`,
        });

        // ✅ Log date parameters for debugging
        console.log('✅ Date parameters added to URL:');
        console.log('  - start_date:', params.get("start_date"), 'Type:', typeof params.get("start_date"));
        console.log('  - end_date:', params.get("end_date"), 'Type:', typeof params.get("end_date"));
        console.log('  - startDate original value:', startDate, 'Type:', typeof startDate);
        console.log('  - endDate original value:', endDate, 'Type:', typeof endDate);
        console.log('  - this.startDate:', this.startDate, 'Type:', typeof this.startDate);
        console.log('  - this.endDate:', this.endDate, 'Type:', typeof this.endDate);
        
        // Verificar si las fechas son válidas antes de enviar
        const isStartDateValid = startDate && startDate !== '' && startDate !== 'undefined' && startDate !== 'null';
        const isEndDateValid = endDate && endDate !== '' && endDate !== 'undefined' && endDate !== 'null';
        console.log('  - isStartDateValid:', isStartDateValid);
        console.log('  - isEndDateValid:', isEndDateValid);
        
        if (includeDeleted) {
            params.append("include_deleted", "1");
        }

        const finalUrl = `${this.baseUrl}?${params}`;
        console.log('🌐 Final URL:', finalUrl);
        console.log('📋 All URL params:', Object.fromEntries(params));
        
        // Verificación detallada de los parámetros de fecha en la URL
        console.group('🔍 Verificación final de parámetros de fecha en URL');
        console.log('URL completa:', finalUrl);
        console.log('Parámetro start_date en URL:', params.get('start_date'));
        console.log('Parámetro end_date en URL:', params.get('end_date'));
        
        // Verificar si los parámetros están correctamente codificados
        const urlObj = new URL(finalUrl, window.location.origin);
        console.log('Parámetros decodificados de la URL:');
        console.log('- start_date:', urlObj.searchParams.get('start_date'));
        console.log('- end_date:', urlObj.searchParams.get('end_date'));
        console.groupEnd();
        
        console.groupEnd(); // Cierre del grupo principal

        return await this.apiRequest(finalUrl);
    }

    /**
     * Create new invoice
     */
    async createInvoice(formData) {
        console.log(
            "Creating invoice with data:",
            JSON.parse(JSON.stringify(formData))
        );
        const response = await this.apiRequest(this.baseUrl, {
            method: "POST",
            body: JSON.stringify(formData),
        });

        // ✅ Force immediate cache invalidation after create
        if (response.success) {
            // Clear any browser cache for this endpoint
            if ("caches" in window) {
                caches.delete("invoice-demos-cache");
            }
        }

        return response;
    }

    /**
     * Update existing invoice
     */
    async updateInvoice(uuid, formData) {
        console.log("Updating invoice with UUID:", uuid);
        console.log("Update data:", JSON.parse(JSON.stringify(formData)));
        const response = await this.apiRequest(`${this.baseUrl}/${uuid}`, {
            method: "PUT",
            body: JSON.stringify(formData),
        });

        // ✅ Force immediate cache invalidation after update
        if (response.success) {
            // Clear any browser cache for this endpoint
            if ("caches" in window) {
                caches.delete("invoice-demos-cache");
            }
        }

        return response;
    }

    /**
     * Delete invoice (soft delete)
     */
    async deleteInvoice(uuid) {
        const response = await this.apiRequest(`${this.baseUrl}/${uuid}`, {
            method: "DELETE",
        });

        // ✅ Force immediate cache invalidation after delete
        if (response.success) {
            // Clear any browser cache for this endpoint
            if ("caches" in window) {
                caches.delete("invoice-demos-cache");
            }
        }

        return response;
    }

    /**
     * Restore deleted invoice
     */
    async restoreInvoice(uuid) {
        const response = await this.apiRequest(
            `${this.baseUrl}/${uuid}/restore`,
            {
                method: "PATCH",
            }
        );

        // ✅ Force immediate cache invalidation after restore
        if (response.success) {
            // Clear any browser cache for this endpoint
            if ("caches" in window) {
                caches.delete("invoice-demos-cache");
            }
        }

        return response;
    }

    /**
     * Generate PDF for invoice
     */
    async generatePdf(uuid) {
        return await this.apiRequest(`${this.baseUrl}/${uuid}/generate-pdf`, {
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
        modernDatePicker: null,
        sortBy: "created_at",
        sortOrder: "desc",
        showDeleted: false,
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
            this.initializeModernDatePicker();
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
            
            // 🐛 DEBUG: Log all filter values before sending request
            console.group('🔍 DEBUG: loadInvoices() called');
            console.log('📅 startDate:', this.startDate);
            console.log('📅 endDate:', this.endDate);
            console.log('🔍 search:', this.search);
            console.log('📊 statusFilter:', this.statusFilter);
            console.log('📄 currentPage:', this.currentPage);
            console.log('🗑️ showDeleted:', this.showDeleted);
            console.groupEnd();
            
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

        // Initialize Modern Date Picker (Litepicker alternative)
        initializeModernDatePicker() {
            // Wait for DOM to be ready
            this.$nextTick(() => {
                // 🐛 DEBUG: Check if element exists
                console.group('🔍 DEBUG: initializeModernDatePicker() called');
                const datePickerElement = document.querySelector('#dateRangePicker');
                console.log('📅 Date picker element found:', datePickerElement);
                
                if (!datePickerElement) {
                    console.error('❌ #dateRangePicker element not found in DOM!');
                    console.groupEnd();
                    return;
                }
                
                console.log('✅ Initializing ModernDateRangePicker...');
                
                // Initialize ModernDateRangePicker with enhanced features
                this.modernDatePicker = new ModernDateRangePicker({
                    element: "#dateRangePicker",
                    format: "YYYY-MM-DD",
                    displayFormat: "MMM DD, YYYY",
                    placeholder: "Select date range...",
                    theme: "dark",
                    numberOfMonths: window.innerWidth > 768 ? 2 : 1,
                    numberOfColumns: window.innerWidth > 768 ? 2 : 1,
                    singleMode: false,
                    allowRepick: true,
                    autoRefresh: true,
                    showTooltip: true,
                    showWeekNumbers: false,
                    dropdowns: {
                        minYear: 2020,
                        maxYear: new Date().getFullYear() + 5,
                        months: true,
                        years: true
                    },
                    buttonText: {
                        apply: "Apply",
                        cancel: "Cancel",
                        previousMonth: "‹",
                        nextMonth: "›",
                        reset: "Reset"
                    }
                })
                .on('select', (startDate, endDate, dateObjects) => {
                    console.group('🐛 DEBUG: Date picker select event');
                    console.log('📅 Raw startDate:', startDate, 'Type:', typeof startDate);
                    console.log('📅 Raw endDate:', endDate, 'Type:', typeof endDate);
                    console.log('📅 dateObjects:', dateObjects);
                    
                    // Función para formatear fecha a YYYY-MM-DD
                    const formatDateToISO = (date) => {
                        if (!date) return '';
                        
                        // Si ya es una cadena en formato correcto, devolverla
                        if (typeof date === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(date)) {
                            return date;
                        }
                        
                        // Si es un objeto Date, convertirlo
                        if (date instanceof Date) {
                            return date.toISOString().split('T')[0];
                        }
                        
                        // Intentar parsear como fecha
                        try {
                            const parsedDate = new Date(date);
                            if (!isNaN(parsedDate.getTime())) {
                                return parsedDate.toISOString().split('T')[0];
                            }
                        } catch (e) {
                            console.warn('Error parsing date:', date, e);
                        }
                        
                        return '';
                    };
                    
                    // Formatear las fechas
                    const formattedStartDate = formatDateToISO(startDate);
                    const formattedEndDate = formatDateToISO(endDate);
                    
                    console.log('📅 Formatted dates:');
                    console.log('  - formattedStartDate:', formattedStartDate);
                    console.log('  - formattedEndDate:', formattedEndDate);
                    
                    // Asignar las fechas formateadas (siempre asignar, incluso si están vacías)
                    this.startDate = formattedStartDate;
                    this.endDate = formattedEndDate;
                    
                    console.log('✅ Setting Alpine.js values:');
                    console.log('  - this.startDate:', this.startDate);
                    console.log('  - this.endDate:', this.endDate);
                    
                    // Solo actualizar display y cargar si tenemos fechas válidas
                    if (formattedStartDate && formattedEndDate) {
                        this.updateDateRangeDisplay(formattedStartDate, formattedEndDate);
                        this.activeQuickFilter = null; // Clear active quick filter
                        this.currentPage = 1;
                        
                        console.log('🔄 Calling loadInvoices() with valid dates...');
                        this.loadInvoices();
                        
                        console.log('📅 Date range selected:', { 
                            startDate: formattedStartDate, 
                            endDate: formattedEndDate 
                        });
                    } else {
                        console.warn('⚠️ One or both dates are invalid after formatting!');
                        console.log('  - Original startDate:', startDate);
                        console.log('  - Original endDate:', endDate);
                        console.log('  - Formatted startDate:', formattedStartDate);
                        console.log('  - Formatted endDate:', formattedEndDate);
                        
                        // Aún así llamar a loadInvoices para limpiar filtros
                        this.loadInvoices();
                    }
                    console.groupEnd();
                })
                .on('clear', () => {
                    console.group('🐛 DEBUG: Date picker clear event');
                    
                    // Limpiar todas las variables de fecha
                    this.startDate = "";
                    this.endDate = "";
                    this.dateRangeDisplay = "";
                    this.activeQuickFilter = null;
                    this.currentPage = 1;
                    
                    console.log('✅ Cleared values:');
                    console.log('  - this.startDate:', this.startDate, 'Type:', typeof this.startDate);
                    console.log('  - this.endDate:', this.endDate, 'Type:', typeof this.endDate);
                    console.log('  - this.dateRangeDisplay:', this.dateRangeDisplay);
                    console.log('  - this.activeQuickFilter:', this.activeQuickFilter);
                    
                    console.log('🔄 Calling loadInvoices() to clear filters...');
                    this.loadInvoices();
                    
                    console.log('📅 Date range cleared successfully');
                    console.groupEnd();
                })
                .on('show', () => {
                    console.log('📅 Date picker opened');
                })
                .on('hide', () => {
                    console.log('📅 Date picker closed');
                })
                .on('error', (error) => {
                    console.error('📅 Date picker error:', error);
                    this.showError('Error with date picker: ' + error.message);
                });

                // Store reference for compatibility
                this.dateRangePicker = this.modernDatePicker;

                // Debug log
                console.log("📅 ModernDateRangePicker initialized successfully");
                console.groupEnd();
            });
        },

        // Update date range display
        updateDateRangeDisplay(startDate, endDate) {
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const options = { year: 'numeric', month: 'short', day: 'numeric' };
                this.dateRangeDisplay = `${start.toLocaleDateString('en-US', options)} - ${end.toLocaleDateString('en-US', options)}`;
            } else {
                this.dateRangeDisplay = "";
            }
        },

        // Set predefined date ranges
        setDateRange(period) {
            console.group('🐛 DEBUG: setDateRange() called');
            console.log('📅 Period:', period);
            
            const today = new Date();
            console.log('📅 Today raw date object:', today);
            let startDate, endDate;

            switch (period) {
                case "today":
                    startDate = endDate = today;
                    console.log('📅 Today period - using same date for start and end:', today);
                    break;
                case "last7days":
                    startDate = new Date(
                        today.getTime() - 7 * 24 * 60 * 60 * 1000
                    );
                    endDate = today;
                    console.log('📅 Last 7 days period - startDate:', startDate, 'endDate:', endDate);
                    break;
                case "last30days":
                    startDate = new Date(
                        today.getTime() - 30 * 24 * 60 * 60 * 1000
                    );
                    endDate = today;
                    console.log('📅 Last 30 days period - startDate:', startDate, 'endDate:', endDate);
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
                    console.log('📅 This month period - startDate:', startDate, 'endDate:', endDate);
                    break;
                case "thisYear":
                    startDate = new Date(today.getFullYear(), 0, 1);
                    endDate = new Date(today.getFullYear(), 11, 31);
                    console.log('📅 This year period - startDate:', startDate, 'endDate:', endDate);
                    break;

                default:
                    console.warn('⚠️ Unknown period:', period);
                    console.groupEnd();
                    return;
            }

            // Verificar que las fechas son válidas antes de formatearlas
            console.log('🔍 Verificando validez de fechas:');
            console.log('  - startDate válida:', !isNaN(startDate.getTime()));
            console.log('  - endDate válida:', !isNaN(endDate.getTime()));
            
            // Formatear fechas para ISO
            const startISO = startDate.toISOString();
            const endISO = endDate.toISOString();
            console.log('📅 Fechas en formato ISO:');
            console.log('  - startISO:', startISO);
            console.log('  - endISO:', endISO);
            
            // Extraer solo la parte de la fecha (YYYY-MM-DD)
            this.startDate = startISO.split("T")[0];
            this.endDate = endISO.split("T")[0];
            this.activeQuickFilter = period;
            this.currentPage = 1;

            console.log('✅ Fechas calculadas y formateadas:');
            console.log('  - this.startDate:', this.startDate, '(type:', typeof this.startDate, ')');
            console.log('  - this.endDate:', this.endDate, '(type:', typeof this.endDate, ')');

            // Update modern date picker display
            if (this.modernDatePicker) {
                console.log('🔄 Actualizando ModernDateRangePicker...');
                console.log('  - Enviando startDate:', this.startDate);
                console.log('  - Enviando endDate:', this.endDate);
                this.modernDatePicker.setDateRange(this.startDate, this.endDate);
            } else {
                console.warn('⚠️ modernDatePicker not available');
            }
            
            // Update display
            this.updateDateRangeDisplay(this.startDate, this.endDate);

            console.log('🔄 Llamando a loadInvoices()...');
            console.log('  - this.startDate antes de loadInvoices:', this.startDate);
            console.log('  - this.endDate antes de loadInvoices:', this.endDate);
            this.loadInvoices();
            console.groupEnd();
        },

        // Clear date range
        clearDateRange() {
            this.startDate = "";
            this.endDate = "";
            this.dateRangeDisplay = "";

            // Clear Modern Date Picker
            if (this.modernDatePicker) {
                this.modernDatePicker.clear();
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

        // ✅ IMPROVED: Close modal with complete state cleanup
        closeModal() {
            this.showModal = false;
            this.isEditing = false;
            this.currentInvoice = null;
            this.resetForm();
            this.errors = {};
            this.invoiceNumberExists = false;
            this.submitting = false;
            this.generalError = "";

            // ✅ LOG for debugging
            console.log("Modal closed and state reset");
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
            // Función auxiliar para formatear fechas para inputs HTML5
            const formatDateForInput = (dateString, includeTime = false) => {
                if (!dateString) return "";
                const date = new Date(dateString);
                if (isNaN(date.getTime())) return ""; // Fecha inválida

                // Para inputs de tipo date: YYYY-MM-DD
                if (!includeTime) {
                    return date.toISOString().split("T")[0];
                }

                // Para inputs de tipo datetime-local: YYYY-MM-DDThh:mm
                return date.toISOString().slice(0, 16);
            };

            // Procesar los items para eliminar los UUIDs y evitar duplicidad
            const processedItems = invoice.items
                ? invoice.items.map((item) => {
                      // Crear una copia del item sin el UUID
                      const { uuid, ...itemWithoutUuid } = item;
                      console.log("Removed UUID from item:", uuid);
                      return itemWithoutUuid;
                  })
                : [];

            // ✅ Format phone for display - use the stored phone and format it
            const phoneToDisplay =
                invoice.bill_to_phone_raw || invoice.bill_to_phone || "";
            const formattedPhone = phoneToDisplay
                ? this.formatPhoneForDisplay(phoneToDisplay)
                : "";

            console.log("Phone formatting in populateForm:", {
                original: phoneToDisplay,
                formatted: formattedPhone,
            });

            this.form = {
                invoice_number: invoice.invoice_number || "",
                invoice_date: formatDateForInput(invoice.invoice_date),
                bill_to_name: invoice.bill_to_name || "",
                bill_to_address: invoice.bill_to_address || "",
                bill_to_phone: formattedPhone, // ✅ Use formatted phone
                subtotal: invoice.subtotal || 0,
                tax_amount: invoice.tax_amount || 0,
                balance_due: invoice.balance_due || 0,
                claim_number: invoice.claim_number || "",
                policy_number: invoice.policy_number || "",
                insurance_company: invoice.insurance_company || "",
                date_of_loss: formatDateForInput(invoice.date_of_loss),
                date_received: formatDateForInput(invoice.date_received, true),
                date_inspected: formatDateForInput(
                    invoice.date_inspected,
                    true
                ),
                date_entered: formatDateForInput(invoice.date_entered, true),
                price_list_code: invoice.price_list_code || "",
                type_of_loss: invoice.type_of_loss || "",
                notes: invoice.notes || "",
                status: invoice.status || "draft",
                items: processedItems,
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
            console.log("Calculating totals...");
            let subtotal = 0;
            this.form.items.forEach((item, index) => {
                // Asegurar que quantity y rate sean números
                const quantity = parseFloat(item.quantity || 0);
                const rate = parseFloat(item.rate || 0);

                console.log(
                    `Item ${index + 1}: quantity=${quantity}, rate=${rate}`
                );

                // Calcular el monto del ítem
                const itemAmount = quantity * rate;
                item.amount = itemAmount.toFixed(2); // Solo formateamos el amount para mostrar

                console.log(`Item ${index + 1} amount: ${itemAmount}`);

                subtotal += itemAmount;
            });

            console.log(`Subtotal: ${subtotal}`);

            // Actualizar el subtotal en el formulario
            this.form.subtotal = subtotal.toFixed(2);

            // Calcular balance_due
            const taxAmount = parseFloat(this.form.tax_amount || 0);
            this.form.balance_due = (subtotal + taxAmount).toFixed(2); // Solo formateamos el balance_due para mostrar

            console.log(
                `Tax: ${taxAmount}, Balance Due: ${this.form.balance_due}`
            );
        },

        // Submit form
        async submitForm() {
            if (this.submitting) return;

            // ✅ PREVENT submission if invoice number exists
            if (this.invoiceNumberExists) {
                window.invoiceDemoManager.showError(
                    "Cannot save: Invoice number already exists. Please use a different number."
                );
                return;
            }

            // ✅ VALIDATE invoice number before submission
            if (!this.isEditing) {
                await this.checkInvoiceNumberExists();
                if (this.invoiceNumberExists) {
                    window.invoiceDemoManager.showError(
                        "Cannot create: Invoice number already exists. Please generate a new number."
                    );
                    return;
                }
            }

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
                // ✅ Prepare form data with properly formatted phone
                const formDataToSubmit = { ...this.form };

                // Format phone for storage before sending
                if (formDataToSubmit.bill_to_phone) {
                    const originalPhone = formDataToSubmit.bill_to_phone;
                    formDataToSubmit.bill_to_phone =
                        this.formatPhoneForStorage(originalPhone);

                    console.log("Phone formatting for submission:", {
                        original: originalPhone,
                        formatted: formDataToSubmit.bill_to_phone,
                    });
                }

                let response;
                if (this.isEditing) {
                    console.log(
                        "Calling updateInvoice with UUID:",
                        this.currentInvoice.uuid
                    );
                    response = await window.invoiceDemoManager.updateInvoice(
                        this.currentInvoice.uuid,
                        formDataToSubmit
                    );
                    console.log("Update response:", response);
                } else {
                    console.log("Calling createInvoice");
                    response = await window.invoiceDemoManager.createInvoice(
                        formDataToSubmit
                    );
                    console.log("Create response:", response);
                }

                window.invoiceDemoManager.showSuccess(response.message);
                this.closeModal();

                // ✅ FORCE reload with cache bypass
                await this.loadInvoices();

                // ✅ Revalidate invoice number for future use
                this.invoiceNumberExists = false;
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
                // Usar uuid en lugar de id para la eliminación
                console.log("Eliminando factura con UUID:", invoice.uuid);
                const response = await window.invoiceDemoManager.deleteInvoice(
                    invoice.uuid
                );

                Swal.fire({
                    title: "Eliminado",
                    text: "La factura ha sido eliminada exitosamente",
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false,
                });

                // ✅ FORCE complete state refresh after delete
                this.invoices = []; // Clear current array to force refresh
                this.loading = true; // Show loading state

                // ✅ Wait a moment to ensure backend is updated
                await new Promise((resolve) => setTimeout(resolve, 100));

                // ✅ Force reload with cache bypass
                await this.loadInvoices();

                // Opcionalmente, mostrar un mensaje sugiriendo ver facturas eliminadas
                if (!this.showDeleted) {
                    setTimeout(() => {
                        window.invoiceDemoManager.showNotification(
                            "Puedes ver las facturas eliminadas activando el filtro 'Mostrar eliminadas'",
                            "info"
                        );
                    }, 2500); // Mostrar después de que se cierre el mensaje de éxito
                }
            } catch (error) {
                console.error("Error al eliminar factura:", error);
                Swal.fire({
                    title: "Error",
                    text: error.message || "Error al eliminar la factura",
                    icon: "error",
                });
            }
        },

        // Generate PDF for invoice
        async generatePdf(invoice) {
            this.pdfGenerating = true;
            console.log("Generating PDF for invoice UUID:", invoice.uuid);
            try {
                await window.invoiceDemoManager.generatePdf(invoice.uuid);
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
                    invoice.uuid
                );

                Swal.fire({
                    title: "Restaurado",
                    text: "La factura ha sido restaurada exitosamente",
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false,
                });

                // ✅ FORCE complete state refresh after restore
                this.invoices = []; // Clear current array to force refresh
                this.loading = true; // Show loading state

                // ✅ Wait a moment to ensure backend is updated
                await new Promise((resolve) => setTimeout(resolve, 100));

                // ✅ Automatically switch to active view after restore
                if (this.showDeleted) {
                    this.showDeleted = false;
                    this.currentPage = 1; // Reiniciar a la primera página
                    window.invoiceDemoManager.showNotification(
                        "Cambiando a vista de facturas activas",
                        "info"
                    );
                }

                // ✅ Force reload with cache bypass
                await this.loadInvoices();

                // ✅ Show additional confirmation
                setTimeout(() => {
                    window.invoiceDemoManager.showNotification(
                        "La factura ha sido restaurada y está ahora visible en la lista activa",
                        "success"
                    );
                }, 1000);
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
            // ✅ FORCE complete state refresh when toggling
            this.invoices = []; // Clear current array to force refresh
            this.loading = true; // Show loading state

            // Reiniciar a la primera página cuando se cambia el filtro
            this.currentPage = 1;

            // Mostrar mensaje informativo según el estado del toggle
            if (this.showDeleted) {
                window.invoiceDemoManager.showNotification(
                    "Mostrando facturas eliminadas",
                    "info"
                );
            } else {
                window.invoiceDemoManager.showNotification(
                    "Mostrando facturas activas",
                    "info"
                );
            }

            // ✅ Add small delay to ensure UI updates before reload
            setTimeout(() => {
                // Recargar la lista de facturas con el nuevo filtro
                this.loadInvoices();
            }, 50);
        },

        clearAllFilters() {
            // Clear all search and filter variables
            this.search = "";
            this.statusFilter = "";
            this.startDate = "";
            this.endDate = "";
            this.dateRangeDisplay = "";
            this.activeQuickFilter = null;
            this.showDeleted = false; // ✅ Reset showDeleted toggle
            this.currentPage = 1;

            // Clear flatpickr instance if exists
            if (this.dateRangePicker) {
                this.dateRangePicker.clear();
            }

            // Also try to clear using the global flatpickr approach
            const dateInput = document.getElementById("dateRangePicker");
            if (dateInput && dateInput._flatpickr) {
                dateInput._flatpickr.clear();
            }

            // Force reload invoices with cleared filters
            this.loadInvoices();

            // Show confirmation message
            window.invoiceDemoManager.showSuccess(
                "Filters cleared successfully"
            );
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

        // ✅ Check if invoice number exists (REAL-TIME VALIDATION)
        async checkInvoiceNumberExists() {
            if (!this.form.invoice_number) {
                this.invoiceNumberExists = false;
                return;
            }

            try {
                const excludeId =
                    this.isEditing && this.currentInvoice
                        ? this.currentInvoice.uuid
                        : null;

                // ✅ ENHANCED logging for debugging validation issues
                console.log("🔍 Starting invoice number validation:", {
                    invoice_number: this.form.invoice_number,
                    exclude_id: excludeId,
                    is_editing: this.isEditing,
                    current_invoice_exists: !!this.currentInvoice,
                    current_invoice_uuid: this.currentInvoice?.uuid,
                    current_invoice_number: this.currentInvoice?.invoice_number,
                });

                const response =
                    await window.invoiceDemoManager.checkInvoiceNumberExists(
                        this.form.invoice_number,
                        excludeId
                    );

                this.invoiceNumberExists = response.exists;

                // ✅ DETAILED result logging
                console.log("✅ Invoice number validation result:", {
                    invoice_number: this.form.invoice_number,
                    exclude_id: excludeId,
                    exists: response.exists,
                    status: response.exists ? "❌ DUPLICATE" : "✅ AVAILABLE",
                });

                // ✅ Show visual feedback
                if (this.invoiceNumberExists) {
                    console.warn("❌ Invoice number already exists!");
                    window.invoiceDemoManager.showError(
                        `Invoice number "${this.form.invoice_number}" already exists. Please use a different number.`
                    );
                }
            } catch (error) {
                console.error("❌ Failed to check invoice number:", error);
                this.invoiceNumberExists = false;
            }
        },

        // ✅ Generate invoice number
        async generateInvoiceNumber() {
            try {
                const response =
                    await window.invoiceDemoManager.generateInvoiceNumber();
                this.form.invoice_number = response.invoice_number;
                this.invoiceNumberExists = false;

                // ✅ LOG for debugging
                console.log(
                    "Generated invoice number:",
                    response.invoice_number
                );
            } catch (error) {
                window.invoiceDemoManager.showError(
                    error.message || "Failed to generate invoice number"
                );
            }
        },

        // ✅ Format phone for display (xxx) xxx-xxxx
        formatPhoneForDisplay(phone) {
            if (!phone) return "";

            // Extract only digits
            const cleaned = phone.replace(/\D/g, "");

            // If it has 11 digits and starts with 1 (format +1XXXXXXXXXX)
            if (cleaned.length === 11 && cleaned.startsWith("1")) {
                const phoneDigits = cleaned.substring(1); // Remove the 1
                return `(${phoneDigits.substring(
                    0,
                    3
                )}) ${phoneDigits.substring(3, 6)}-${phoneDigits.substring(
                    6,
                    10
                )}`;
            }
            // If it has 10 digits (format XXXXXXXXXX)
            else if (cleaned.length === 10) {
                return `(${cleaned.substring(0, 3)}) ${cleaned.substring(
                    3,
                    6
                )}-${cleaned.substring(6, 10)}`;
            }

            // For other formats, return as is
            return phone;
        },

        // ✅ Format phone for storage/comparison
        formatPhoneForStorage(phone) {
            if (!phone) return "";

            // Extract only digits to send to backend
            const cleaned = phone.replace(/\D/g, "");

            // Backend expects only digits and handles +1XXXXXXXXXX format
            if (cleaned.length === 10) {
                return cleaned; // Send only 10 digits
            }

            // If already has 11 digits and starts with 1, send as is
            if (cleaned.length === 11 && cleaned.startsWith("1")) {
                return cleaned;
            }

            return cleaned;
        },

        // ✅ Format phone input in real-time (called from modal)
        formatPhoneInput(event) {
            const input = event.target;
            const isBackspace = event.inputType === "deleteContentBackward";
            let value = input.value.replace(/\D/g, "");

            if (isBackspace) {
                // For backspace, keep current value without adding more characters
            } else {
                // Limit to 10 digits
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

            // Update the form field directly
            this.form.bill_to_phone = formattedValue;

            console.log("Phone formatted in real-time:", {
                original: input.value,
                formatted: formattedValue,
            });
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

// Extend the invoiceDemoData function to include formatting functions
const originalInvoiceDemoData = invoiceDemoData;
invoiceDemoData = function () {
    const data = originalInvoiceDemoData();

    // Format invoice number input (only numbers and starts with VG-)
    data.formatInvoiceNumberInput = function (event) {
        const input = event.target;
        const cursorPosition = input.selectionStart;
        let value = input.value;

        // Asegurar que comience con VG-
        if (!value.startsWith("VG-")) {
            value = "VG-" + value.replace("VG-", "");
        }

        // Después del prefijo VG-, solo permitir números
        const prefix = "VG-";
        const numberPart = value
            .substring(prefix.length)
            .replace(/[^0-9]/g, "");
        const newValue = prefix + numberPart;

        // Solo actualizar si hay cambios para evitar loops
        if (newValue !== value) {
            input.value = newValue;
            this.form.invoice_number = newValue;
            // Restaurar la posición del cursor, ajustando si es necesario
            const newCursorPos = Math.min(cursorPosition, newValue.length);
            input.setSelectionRange(newCursorPos, newCursorPos);
        }
    };

    // Add formatting functions
    data.formatPhoneInput = function (event) {
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
        this.form.bill_to_phone = formattedValue;
    };

    // Format name input (capitalize with spaces)
    data.formatNameInput = function (event) {
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
    };

    // Format uppercase input (for claim/policy numbers)
    data.formatUppercaseInput = function (event, fieldName) {
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
    };

    // Format service description input (all uppercase)
    data.formatServiceDescriptionInput = function (event, itemIndex) {
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
    };

    // Format notes input (capitalize only first letter)
    data.formatNotesInput = function (event) {
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
    };

    // Format address input (capitalize each word)
    data.formatAddressInput = function (event) {
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
    };

    // Format item description input (capitalize each word)
    data.formatItemDescriptionInput = function (event, itemIndex) {
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
    };

    // Format currency input for rate field
    data.formatCurrencyInput = function (event, itemIndex) {
        const input = event.target;
        const cursorPosition = input.selectionStart;
        let value = input.value;

        // Eliminar todo excepto números y punto decimal
        value = value.replace(/[^0-9.]/g, "");

        // Asegurar que solo haya un punto decimal
        const parts = value.split(".");
        if (parts.length > 2) {
            value = parts[0] + "." + parts.slice(1).join("");
        }

        // Limitar a dos decimales
        if (parts.length > 1 && parts[1].length > 2) {
            value = parts[0] + "." + parts[1].substring(0, 2);
        }

        // Actualizar el valor en el input y en el modelo
        if (value !== input.value) {
            input.value = value;
            this.form.items[itemIndex].rate = value;
            // Restaurar la posición del cursor
            const newCursorPos = Math.min(cursorPosition, value.length);
            input.setSelectionRange(newCursorPos, newCursorPos);
        }

        // Calcular totales después de actualizar el valor
        this.calculateTotals();
    };

    // Format general currency input (for subtotal, tax_amount)
    data.formatGeneralCurrencyInput = function (event, fieldName) {
        const input = event.target;
        const cursorPosition = input.selectionStart;
        let value = input.value;

        // Eliminar todo excepto números y punto decimal
        value = value.replace(/[^0-9.]/g, "");

        // Asegurar que solo haya un punto decimal
        const parts = value.split(".");
        if (parts.length > 2) {
            value = parts[0] + "." + parts.slice(1).join("");
        }

        // Limitar a dos decimales
        if (parts.length > 1 && parts[1].length > 2) {
            value = parts[0] + "." + parts[1].substring(0, 2);
        }

        // Actualizar el valor en el input y en el modelo
        if (value !== input.value) {
            input.value = value;
            this.form[fieldName] = value;
            // Restaurar la posición del cursor
            const newCursorPos = Math.min(cursorPosition, value.length);
            input.setSelectionRange(newCursorPos, newCursorPos);
        }

        // Calcular totales después de actualizar el valor
        this.calculateTotals();
    };

    return data;
};

// Update global reference
window.invoiceDemoData = invoiceDemoData;
