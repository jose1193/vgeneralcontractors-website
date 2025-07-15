<x-crud.index-layout 
    title="Insurance Companies Management" 
    subtitle="Manage insurance companies and their information"
    entity-name="Insurance Company" 
    entity-name-plural="Insurance Companies"
    search-placeholder="Search insurance companies..." 
    show-deleted-label="{{ __('show_inactive_records') }}"
    add-new-label="Add Insurance Company" 
    manager-name="insuranceCompanyManager" 
    table-id="insuranceCompanyTable"
    create-button-id="createInsuranceCompanyBtn" 
    search-id="searchInput" 
    show-deleted-id="showDeleted"
    per-page-id="perPage" 
    pagination-id="pagination" 
    alert-id="alertContainer"
    
    // ==================================================================
    // 1. ÚNICA FUENTE DE VERDAD PARA LAS COLUMNAS
    // Este array ahora controla tanto las cabeceras (<thead>) como
    // la estructura que usará JavaScript para las filas (<tbody>).
    // ==================================================================
    :table-columns="[
        ['field' => 'insurance_company_name', 'label' => 'Company Name', 'sortable' => true],
        ['field' => 'address', 'label' => 'Address', 'sortable' => false],
        ['field' => 'email', 'label' => 'Email', 'sortable' => true],
        ['field' => 'phone', 'label' => 'Phone', 'sortable' => false],
        ['field' => 'website', 'label' => 'Website', 'sortable' => false],
        ['field' => 'user_name', 'label' => 'Created By', 'sortable' => true],
        ['field' => 'created_at', 'label' => 'Created', 'sortable' => true],
        ['field' => 'actions', 'label' => 'Actions', 'sortable' => false],
    ]">

    {{-- El slot sigue estando disponible para modales u otro contenido --}}
    {{-- @slot('slot')
        <x-my-modal />
    @endslot --}}

    @push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Recuperar estado del toggle de localStorage
            const showDeletedState = localStorage.getItem('showDeleted') === 'true';

            // 1. Convertimos las columnas de Blade a una variable de JavaScript
            const tableColumnsFromBlade = @json($tableColumns);

            // 2. Mapeamos el array para enriquecerlo con los 'getters' de JavaScript
            const tableHeadersForManager = tableColumnsFromBlade.map(column => {
                const getters = {
                    address: (entity) => {
                        if (!entity.address) return 'N/A';
                        return entity.address.length > 50 ? entity.address.substring(0, 50) + '...' : entity.address;
                    },
                    email: (entity) => entity.email || 'N/A',
                    phone: (entity) => {
                        if (!entity.phone) return 'N/A';
                        const cleaned = String(entity.phone).replace(/\D/g, '');
                        if (cleaned.length === 11 && cleaned.startsWith('1')) {
                            const phoneDigits = cleaned.substring(1);
                            return `(${phoneDigits.substring(0, 3)}) ${phoneDigits.substring(3, 6)}-${phoneDigits.substring(6, 10)}`;
                        } else if (cleaned.length === 10) {
                            return `(${cleaned.substring(0, 3)}) ${cleaned.substring(3, 6)}-${cleaned.substring(6, 10)}`;
                        }
                        return entity.phone;
                    },
                    website: (entity) => {
                        if (!entity.website) return 'N/A';
                        const displayUrl = entity.website.replace(/^https?:\/\//, '');
                        return `<a href="${entity.website}" target="_blank" class="text-blue-500 hover:text-blue-400 underline">${displayUrl}</a>`;
                    },
                    user_name: (entity) => entity.user_name || 'No user assigned',
                    created_at: (entity) => entity.created_at ? new Date(entity.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A',
                    actions: (entity) => {
                        const isDeleted = entity.deleted_at !== null;
                        let buttons = '';
                        buttons += `<button data-id="${entity.uuid}" class="edit-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg mr-2" title="Edit Insurance Company"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>`;
                        if (isDeleted) {
                            buttons += `<button data-id="${entity.uuid}" class="restore-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg" title="Restore Insurance Company"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg></button>`;
                        } else {
                            buttons += `<button data-id="${entity.uuid}" class="delete-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg" title="Delete Insurance Company"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>`;
                        }
                        return buttons;
                    }
                };
                return {
                    field: column.field,
                    name: column.label,
                    sortable: column.sortable,
                    getter: getters[column.field]
                };
            });


            window.insuranceCompanyManager = new CrudManagerModal({
                entityName: 'Insurance Company',
                entityNamePlural: 'Insurance Companies',
                routes: {
                    index: "{{ secure_url(route('insurance-companies.index', [], false)) }}",
                    store: "{{ secure_url(route('insurance-companies.store', [], false)) }}",
                    edit: "{{ secure_url(route('insurance-companies.edit', ':id', false)) }}",
                    update: "{{ secure_url(route('insurance-companies.update', ':id', false)) }}",
                    destroy: "{{ secure_url(route('insurance-companies.destroy', ':id', false)) }}",
                    restore: "{{ secure_url(route('insurance-companies.restore', ':id', false)) }}",
                    checkEmail: "{{ secure_url(route('insurance-companies.check-email', [], false)) }}",
                    checkPhone: "{{ secure_url(route('insurance-companies.check-phone', [], false)) }}",
                    checkName: "{{ secure_url(route('insurance-companies.check-name', [], false)) }}"
                },
                tableSelector: '#insuranceCompanyTable-body',
                searchSelector: '#searchInput',
                perPageSelector: '#perPage',
                showDeletedSelector: '#showDeleted',
                paginationSelector: '#pagination',
                alertSelector: '#alertContainer',
                createButtonSelector: '#createInsuranceCompanyBtn',
                idField: 'uuid',
                searchFields: ['insurance_company_name', 'address', 'email', 'phone', 'website'],
                showDeleted: showDeletedState,
                
                tableHeaders: tableHeadersForManager, // <-- La nueva variable
                
                entityConfig: {
                    identifierField: 'insurance_company_name',
                    displayName: 'insurance company',
                    fallbackFields: ['email', 'address'],
                    detailFormat: (entity) => entity.insurance_company_name
                },

                // ===============================================
                // BLOQUE 'formFields' COMPLETO
                // ===============================================
                formFields: [{
                        name: 'insurance_company_name',
                        type: 'text',
                        label: 'Company Name',
                        placeholder: 'Enter insurance company name',
                        required: true,
                        validation: {
                            required: true,
                            minLength: 2,
                            maxLength: 255,
                            unique: {
                                url: "{{ route('insurance-companies.check-name') }}",
                                errorMessage: 'This company name is already registered',
                                successMessage: 'Company name is available'
                            }
                        },
                        capitalize: true
                    },
                    {
                        name: 'address',
                        type: 'textarea',
                        label: 'Address',
                        placeholder: 'Enter company address (optional)',
                        required: false,
                        rows: 3,
                        validation: {
                            required: false,
                            minLength: 10,
                            maxLength: 500
                        },
                        capitalize: true
                    },
                    {
                        name: 'email',
                        type: 'email',
                        label: 'Email',
                        placeholder: 'Enter email address (optional)',
                        required: false,
                        validation: {
                            required: false,
                            email: true,
                            unique: {
                                url: "{{ route('insurance-companies.check-email') }}",
                                errorMessage: 'This email is already registered',
                                successMessage: 'Email is available'
                            }
                        }
                    },
                    {
                        name: 'phone',
                        type: 'tel',
                        label: 'Phone',
                        placeholder: 'Enter phone number (optional)',
                        required: false,
                        validation: {
                            required: false,
                            unique: {
                                url: "{{ route('insurance-companies.check-phone') }}",
                                message: 'This phone number is already taken'
                            }
                        }
                    },
                    {
                        name: 'website',
                        type: 'url',
                        label: 'Website',
                        placeholder: 'Enter website URL (optional)',
                        required: false,
                        validation: {
                            required: false,
                            url: true
                        }
                    },
                    {
                        name: 'user_id',
                        type: 'hidden',
                        value: '{{ auth()->id() }}'
                    }
                ],

                // ===============================================
                // BLOQUE 'translations' COMPLETO
                // ===============================================
                translations: {
                    confirmDelete: '¿Estás seguro?',
                    deleteMessage: '¿Deseas eliminar esta compañía de seguros?',
                    confirmRestore: '¿Restaurar registro?',
                    restoreMessage: '¿Deseas restaurar esta compañía de seguros?',
                    yesDelete: 'Sí, eliminar',
                    yesRestore: 'Sí, restaurar',
                    cancel: 'Cancelar',
                    deletedSuccessfully: 'eliminada exitosamente',
                    restoredSuccessfully: 'restaurada exitosamente',
                    errorDeleting: 'Error al eliminar el registro',
                    errorRestoring: 'Error al restaurar el registro',
                    emailAlreadyInUse: 'Este email ya está en uso',
                    phoneAlreadyInUse: 'Este teléfono ya está en uso',
                    nameAlreadyInUse: 'Este nombre de compañía ya está en uso',
                    emailAvailable: 'Email disponible',
                    phoneAvailable: 'Teléfono disponible',
                    nameAvailable: 'Nombre disponible',
                    invalidEmail: 'Formato de email inválido',
                    minimumCharacters: 'Mínimo 7 caracteres',
                    mustContainNumbers: 'Debe contener al menos 2 números',
                    usernameAlreadyInUse: 'Este nombre de usuario ya está en uso',
                    usernameAvailable: 'Nombre de usuario disponible',
                    pleaseCorrectErrors: 'Por favor corrige los errores antes de continuar',
                    noRecordsFound: 'No se encontraron registros'
                }
            });

            // Load initial data
            window.insuranceCompanyManager.loadEntities();
        });
    </script>
@endpush

</x-crud.index-layout>