<x-crud.index-layout title="{{ __('insurance_companies_title') }}" subtitle="{{ __('insurance_companies_subtitle') }}"
    entity-name="{{ __('insurance_company_singular') }}" entity-name-plural="{{ __('insurance_company_plural') }}"
    search-placeholder="{{ __('search_insurance_companies_placeholder') }}" show-deleted-label="{{ __('show_inactive_records') }}"
    add-new-label="{{ __('add_insurance_company') }}" manager-name="insuranceCompanyManager" table-id="insuranceCompanyTable"
    create-button-id="createInsuranceCompanyBtn" search-id="searchInput" show-deleted-id="showDeleted"
    per-page-id="perPage" pagination-id="pagination" alert-id="alertContainer" :table-columns="[
              ['field' => 'insurance_company_name', 'label' => __('company_name'), 'sortable' => true],
        ['field' => 'address', 'label' => __('address'), 'sortable' => false],
        ['field' => 'email', 'label' => __('email'), 'sortable' => true],
        ['field' => 'phone', 'label' => __('phone'), 'sortable' => false],
        ['field' => 'website', 'label' => __('website'), 'sortable' => false],
        ['field' => 'user_name', 'label' => __('created_by'), 'sortable' => true],
        ['field' => 'created_at', 'label' => __('created'), 'sortable' => true],
        ['field' => 'actions', 'label' => __('actions'), 'sortable' => false],
    ]">

    @push('scripts')
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Define las columnas para el nuevo gestor de la tabla
            const tableColumns = [
                { field: 'insurance_company_name', label: '{{ __('company_name') }}', sortable: true },
                { 
                    field: 'address', 
                    label: '{{ __('address') }}', 
                    sortable: false,
                    render: (content) => {
                        const address = content || '{{ __('not_applicable') }}';
                        return `<span title="${address}">${address.substring(0, 25)}...</span>`;
                    }
                },
                { field: 'email', label: '{{ __('email') }}', sortable: true },
                { 
                    field: 'phone', 
                    label: '{{ __('phone') }}', 
                    sortable: false,
                    render: (content) => typeof content === 'object' && content.international ? content.international : content
                },
                {
                    field: 'website',
                    label: '{{ __('website') }}',
                    sortable: false,
                    render: (content) => {
                        if (!content) return '{{ __('not_applicable') }}';
                        return `<a href="${content}" target="_blank" class="text-blue-400 hover:text-blue-300">{{ __('visit_website') }}</a>`;
                    }
                },
                { field: 'user.name', label: '{{ __('created_by') }}', sortable: true },
                { 
                    field: 'created_at',
                    label: '{{ __('created') }}',
                    sortable: true,
                    render: (content) => new Date(content).toLocaleDateString()
                },
                {
                    field: 'actions',
                    label: '{{ __('actions') }}',
                    sortable: false,
                    render: (content, item) => {
                        let buttons = `
                            <div class="flex items-center justify-center space-x-2">
                                <button data-id="${item.uuid}" class="edit-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg" title="{{ __('edit_insurance_company') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z"></path></svg>
                                </button>`;

                        if (item.deleted_at) {
                            buttons += `<button data-id="${item.uuid}" class="restore-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg" title="{{ __('restore_insurance_company') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        </button>`;
                        } else {
                            buttons += `<button data-id="${item.uuid}" class="delete-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg" title="{{ __('delete_insurance_company') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>`;
                        }
                        buttons += '</div>';
                        return buttons;
                    }
                }
            ];

            // 2. Inicializa el nuevo gestor de la tabla
            const tableManager = initGlassmorphicTable('insuranceCompanyTable', tableColumns, 'insuranceCompanyTableManager');

            // 3. Inicializa tu CrudManagerModal CON LA CONFIGURACIÓN COMPLETA
            const crudManager = new CrudManagerModal({
                entityName: '{{ __('insurance_company_singular') }}',
                entityNamePlural: '{{ __('insurance_company_plural') }}',
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
                showDeleted: localStorage.getItem('showDeleted') === 'true',
                entityConfig: {
                    identifierField: 'insurance_company_name',
                    displayName: '{{ __('insurance_company_singular') }}',
                    fallbackFields: ['email', 'address'],
                    detailFormat: (entity) => entity.insurance_company_name
                },
                formFields: [
                    {
                        name: 'insurance_company_name',
                        type: 'text',
                        label: '{{ __('company_name') }}',
                        placeholder: '{{ __('enter_insurance_company_name_placeholder') }}',
                        required: true,
                        validation: {
                            required: true,
                            minLength: 2,
                            maxLength: 255,
                            unique: {
                                url: "{{ route('insurance-companies.check-name') }}",
                                errorMessage: '{{ __('company_name_already_registered') }}',
                                successMessage: '{{ __('company_name_available') }}'
                            }
                        },
                        capitalize: true
                    },
                    {
                        name: 'address',
                        type: 'textarea',
                        label: '{{ __('address') }}',
                        placeholder: '{{ __('enter_company_address_placeholder') }}',
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
                        label: '{{ __('email') }}',
                        placeholder: '{{ __('enter_email_address_placeholder') }}',
                        required: false,
                        validation: {
                            required: false,
                            email: true,
                            unique: {
                                url: "{{ route('insurance-companies.check-email') }}",
                                errorMessage: '{{ __('email_already_registered') }}',
                                successMessage: '{{ __('email_available') }}'
                            }
                        }
                    },
                    {
                        name: 'phone',
                        type: 'tel',
                        label: '{{ __('phone') }}',
                        placeholder: '{{ __('enter_phone_number_placeholder') }}',
                        required: false,
                        validation: {
                            required: false,
                            unique: {
                                url: "{{ route('insurance-companies.check-phone') }}",
                                message: '{{ __('phone_already_taken') }}'
                            }
                        }
                    },
                    {
                        name: 'website',
                        type: 'url',
                        label: '{{ __('website') }}',
                        placeholder: '{{ __('enter_website_url_placeholder') }}',
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
                translations: {
                    confirmDelete: '{{ __('confirm_delete') }}',
                    deleteMessage: '{{ __('delete_insurance_company_message') }}',
                    confirmRestore: '{{ __('confirm_restore_record') }}',
                    restoreMessage: '{{ __('restore_insurance_company_message') }}',
                    yesDelete: '{{ __('yes_delete') }}',
                    yesRestore: '{{ __('yes_restore') }}',
                    cancel: '{{ __('cancel') }}',
                    deletedSuccessfully: '{{ __('deleted_successfully') }}',
                    restoredSuccessfully: '{{ __('restored_successfully') }}',
                    errorDeleting: '{{ __('error_deleting_record') }}',
                    errorRestoring: '{{ __('error_restoring_record') }}',
                    emailAlreadyInUse: '{{ __('email_already_in_use') }}',
                    phoneAlreadyInUse: '{{ __('phone_already_in_use') }}',
                    nameAlreadyInUse: '{{ __('company_name_already_in_use') }}',
                    emailAvailable: '{{ __('email_available') }}',
                    phoneAvailable: '{{ __('phone_available') }}',
                    nameAvailable: '{{ __('name_available') }}',
                    invalidEmail: '{{ __('invalid_email_format') }}',
                    minimumCharacters: '{{ __('minimum_characters') }}',
                    mustContainNumbers: '{{ __('must_contain_numbers') }}',
                    usernameAlreadyInUse: '{{ __('username_already_in_use') }}',
                    usernameAvailable: '{{ __('username_available') }}',
                    pleaseCorrectErrors: '{{ __('please_correct_errors') }}',
                    noRecordsFound: '{{ __('no_records_found') }}'
                },

                // Callbacks para conectar con GlassmorphicTableManager
                onDataLoading: () => tableManager.showLoading(),
                onDataLoaded: (response) => {
                    // Asumiendo que tu API devuelve un objeto con una propiedad 'data'
                    tableManager.renderRows(response.data || []); 
                    // Aquí también puedes manejar la paginación, pasándola a tu CrudManager si es necesario
                },
                onDataError: (message) => tableManager.showError(message),
                onNoData: () => tableManager.showNoData('{{ __('no_records_found') }}'),
            });

            // 4. Conecta el evento de ordenación de la tabla con el gestor del CRUD
            document.getElementById('insuranceCompanyTable-container').addEventListener('table-sort', (e) => {
                const { field, direction } = e.detail;
                crudManager.sortBy(field, direction);
            });
            
            // Asigna el gestor del CRUD a window para acceso global si es necesario
            window.insuranceCompanyManager = crudManager;

            // Carga inicial de los datos
            crudManager.loadEntities();
        });
    </script>
    @endpush

</x-crud.index-layout>
