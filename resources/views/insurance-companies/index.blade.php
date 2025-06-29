<x-crud.index-layout title="Insurance Companies Management" subtitle="Manage insurance companies and their information"
    entity-name="Insurance Company" entity-name-plural="Insurance Companies"
    search-placeholder="Search insurance companies..." show-deleted-label="Show inactive records"
    add-new-label="Add Insurance Company" manager-name="insuranceCompanyManager" table-id="insuranceCompanyTable"
    create-button-id="createInsuranceCompanyBtn" search-id="searchInput" show-deleted-id="showDeleted"
    per-page-id="perPage" pagination-id="pagination" alert-id="alertContainer" :table-columns="[
        ['field' => 'insurance_company_name', 'label' => 'Company Name', 'sortable' => true],
        ['field' => 'address', 'label' => 'Address', 'sortable' => false],
        ['field' => 'email', 'label' => 'Email', 'sortable' => true],
        ['field' => 'phone', 'label' => 'Phone', 'sortable' => false],
        ['field' => 'website', 'label' => 'Website', 'sortable' => false],
        ['field' => 'user_name', 'label' => 'Assigned User', 'sortable' => true],
        ['field' => 'created_at', 'label' => 'Created', 'sortable' => true],
    ]">

    @push('scripts')
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        @vite(['resources/js/crud/index.js'])

        <script>
            // Asegurar que SweetAlert2 esté disponible globalmente
            window.Swal = Swal;
            
            // Esperar a que el módulo esté cargado
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM Content Loaded');
                
                // Función para verificar elementos DOM requeridos
                function checkRequiredElements() {
                    const requiredSelectors = [
                        '#insuranceCompanyTable-body',
                        '#searchInput',
                        '#perPage',
                        '#pagination',
                        '#alertContainer'
                    ];
                    
                    const missing = [];
                    requiredSelectors.forEach(selector => {
                        if (!document.querySelector(selector)) {
                            missing.push(selector);
                        }
                    });
                    
                    if (missing.length > 0) {
                        console.warn('Missing DOM elements:', missing);
                        return false;
                    }
                    return true;
                }
                
                // Verificar que CrudSystem esté disponible
                if (typeof window.CrudSystem === 'undefined') {
                    console.error('CrudSystem not loaded');
                    return;
                }
                
                console.log('CrudSystem available:', window.CrudSystem);
                
                // Verificar elementos DOM
                if (!checkRequiredElements()) {
                    console.error('Required DOM elements not found');
                    return;
                }
                
                // Recuperar estado del toggle de localStorage antes de inicializar el manager
                const showDeletedState = localStorage.getItem('showDeleted') === 'true';
                console.log('Estado inicial de showDeleted:', showDeletedState);

                // Configuración específica para Insurance Companies
                const insuranceCompanyConfig = {
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
                    selectors: {
                        table: '#insuranceCompanyTable-body',
                        search: '#searchInput',
                        perPage: '#perPage',
                        showDeleted: '#showDeleted',
                        pagination: '#pagination',
                        alert: '#alertContainer',
                        createButton: '#createInsuranceCompanyBtn'
                    },
                    idField: 'uuid',
                    actions: ['edit', 'delete', 'restore'],
                    searchFields: ['insurance_company_name', 'address', 'email', 'phone', 'website'],
                    showDeleted: showDeletedState,
                    entityConfig: {
                        identifierField: 'insurance_company_name',
                        displayName: 'insurance company',
                        fallbackFields: ['email', 'address'],
                        detailFormat: (entity) => entity.insurance_company_name
                    },
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
                    tableHeaders: [{
                            field: 'insurance_company_name',
                            name: 'Company Name',
                            sortable: true
                        },
                        {
                            field: 'address',
                            name: 'Address',
                            sortable: false,
                            getter: (entity) => {
                                if (!entity.address) return 'N/A';
                                return entity.address.length > 50 ?
                                    entity.address.substring(0, 50) + '...' :
                                    entity.address;
                            }
                        },
                        {
                            field: 'email',
                            name: 'Email',
                            sortable: true,
                            getter: (entity) => entity.email || 'N/A'
                        },
                        {
                            field: 'phone',
                            name: 'Phone',
                            sortable: false,
                            getter: (entity) => {
                                if (!entity.phone) return 'N/A';
                                const cleaned = entity.phone.replace(/\D/g, '');
                                if (cleaned.length === 11 && cleaned.startsWith('1')) {
                                    const phoneDigits = cleaned.substring(1);
                                    return `(${phoneDigits.substring(0, 3)}) ${phoneDigits.substring(3, 6)}-${phoneDigits.substring(6, 10)}`;
                                } else if (cleaned.length === 10) {
                                    return `(${cleaned.substring(0, 3)}) ${cleaned.substring(3, 6)}-${cleaned.substring(6, 10)}`;
                                }
                                return entity.phone;
                            }
                        },
                        {
                            field: 'website',
                            name: 'Website',
                            sortable: false,
                            getter: (entity) => {
                                if (!entity.website) return 'N/A';
                                const displayUrl = entity.website.replace(/^https?:\/\//, '');
                                return `<a href="${entity.website}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">${displayUrl}</a>`;
                            }
                        },
                        {
                            field: 'user_name',
                            name: 'Created By',
                            sortable: true,
                            getter: (entity) => entity.user_name || 'No user assigned'
                        },
                        {
                            field: 'created_at',
                            name: 'Created',
                            sortable: true,
                            getter: (entity) => {
                                return entity.created_at ? new Date(entity.created_at).toLocaleDateString() : 'N/A';
                            }
                        },

                    ],
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
                };

                // Inicializar con CrudManagerModal (sistema original que funciona)
                window.insuranceCompanyManager = new CrudManagerModal(insuranceCompanyConfig);
                
                // Cargar datos iniciales
                window.insuranceCompanyManager.loadEntities();
            });
        </script>
    @endpush

</x-crud.index-layout>