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
            $(document).ready(function() {
                // Recuperar estado del toggle de localStorage antes de inicializar el manager
                const showDeletedState = localStorage.getItem('showDeleted') === 'true';
                console.log('Estado inicial de showDeleted:', showDeletedState);

                // Make the manager globally accessible
                window.insuranceCompanyManager = new CrudManagerModal({
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
                    // Establecer el valor inicial basado en localStorage
                    showDeleted: showDeletedState,
                    entityConfig: {
                        identifierField: 'insurance_company_name',
                        displayName: '{{ __('insurance_company_singular') }}',
                        fallbackFields: ['email', 'address'],
                        detailFormat: (entity) => entity.insurance_company_name
                    },
                    formFields: [{
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
                    tableHeaders: [{
                            field: 'insurance_company_name',
                            name: '{{ __('company_name') }}',
                            sortable: true
                        },
                        {
                            field: 'address',
                            name: '{{ __('address') }}',
                            sortable: false,
                            getter: (entity) => {
                                if (!entity.address) return '{{ __('not_applicable') }}';
                                // Truncate long addresses
                                return entity.address.length > 50 ?
                                    entity.address.substring(0, 50) + '...' :
                                    entity.address;
                            }
                        },
                        {
                            field: 'email',
                            name: '{{ __('email') }}',
                            sortable: true,
                            getter: (entity) => {
                                return entity.email || '{{ __('not_applicable') }}';
                            }
                        },
                        {
                            field: 'phone',
                            name: '{{ __('phone') }}',
                            sortable: false,
                            getter: (entity) => {
                                if (!entity.phone) return '{{ __('not_applicable') }}';

                                // Extraer solo los dígitos
                                const cleaned = entity.phone.replace(/\D/g, '');

                                // Si tiene 11 dígitos y empieza con 1 (formato +1XXXXXXXXXX)
                                if (cleaned.length === 11 && cleaned.startsWith('1')) {
                                    const phoneDigits = cleaned.substring(1); // Remover el 1
                                    return `(${phoneDigits.substring(0, 3)}) ${phoneDigits.substring(3, 6)}-${phoneDigits.substring(6, 10)}`;
                                }
                                // Si tiene 10 dígitos (formato XXXXXXXXXX)
                                else if (cleaned.length === 10) {
                                    return `(${cleaned.substring(0, 3)}) ${cleaned.substring(3, 6)}-${cleaned.substring(6, 10)}`;
                                }

                                // Para otros formatos, devolver tal como está
                                return entity.phone;
                            }
                        },
                        {
                            field: 'website',
                            name: '{{ __('website') }}',
                            sortable: false,
                            getter: (entity) => {
                                if (!entity.website) return '{{ __('not_applicable') }}';

                                // Create clickable link
                                const displayUrl = entity.website.replace(/^https?:\/\//, '');
                                return `<a href="${entity.website}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">${displayUrl}</a>`;
                            }
                        },
                        {
                            field: 'user_name',
                            name: '{{ __('created_by') }}',
                            sortable: true,
                            getter: (entity) => {
                                return entity.user_name || '{{ __('no_user_assigned') }}';
                            }
                        },
                        {
                            field: 'created_at',
                            name: '{{ __('created') }}',
                            sortable: true,
                            getter: (entity) => {
                                return entity.created_at ? new Date(entity.created_at)
                                    .toLocaleDateString() : '{{ __('not_applicable') }}';
                            }
                        },
                        {
                            field: 'actions',
                            name: '{{ __('actions') }}',
                            sortable: false,
                            getter: (entity) => {
                                const isDeleted = entity.deleted_at !== null;
                                let buttons = '';

                                // Edit button (always available)
                                buttons += `<button data-id="${entity.uuid}" class="edit-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg mr-2" title="{{ __('edit_insurance_company') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>`;

                                if (isDeleted) {
                                    // Restore button
                                    buttons += `<button data-id="${entity.uuid}" class="restore-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg" title="{{ __('restore_insurance_company') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                </button>`;
                                } else {
                                    // Delete button
                                    buttons += `<button data-id="${entity.uuid}" class="delete-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg" title="{{ __('delete_insurance_company') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>`;
                                }

                                return buttons;
                            }
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
                    }
                });

                // Load initial data
                window.insuranceCompanyManager.loadEntities();
            });
        </script>
    @endpush

</x-crud.index-layout>
