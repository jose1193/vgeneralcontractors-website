<x-app-layout>
    {{-- Dark background container with consistent styling --}}
    <div class="min-h-screen bg-gray-900" style="background-color: #141414;">
        {{-- Header section with title and subtitle --}}
        <div class="p-4 sm:p-6">
            <div class="mb-4 sm:mb-8 text-center sm:text-center md:text-left lg:text-left">
                <h2 class="text-base sm:text-base md:text-2xl lg:text-2xl font-bold text-white mb-2">
                    {{ __('service_categories_title') }}</h2>
                <p class="text-base sm:text-base md:text-base lg:text-base text-gray-400">
                    {{ __('service_categories_subtitle') }}
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-2 sm:py-4 md:py-2 lg:py-2 px-4 sm:px-6 lg:px-8">
            <!-- Success and error messages -->
            <div id="alertContainer"></div>
            @if (session()->has('message'))
                <x-alerts.success :message="session('message')" />
            @endif
            @if (session()->has('error'))
                <x-alerts.error :message="session('error')" />
            @endif

            <!-- Main container -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg">
                <div class="p-6">
                    <!-- Filter and action bar -->
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 space-y-4 md:space-y-0">
                        <!-- Search input -->
                        <div class="w-full md:w-1/2 lg:w-2/5">
                            <x-crud.input-search id="searchInput" placeholder="{{ __('search_service_categories') }}"
                                manager-name="serviceCategoryManager" />
                        </div>

                        <div
                            class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                            <!-- Toggle to show inactive service categories -->
                            <x-crud.toggle-deleted id="showDeleted" label="{{ __('show_inactive_records') }}"
                                manager-name="serviceCategoryManager" />

                            <!-- Per page dropdown -->
                            <x-select-input-per-pages name="perPage" id="perPage" class="sm:w-32">
                                <option value="5">5 {{ __('per_page') }}</option>
                                <option value="10" selected>10 {{ __('per_page') }}</option>
                                <option value="15">15 {{ __('per_page') }}</option>
                                <option value="25">25 {{ __('per_page') }}</option>
                                <option value="50">50 {{ __('per_page') }}</option>
                            </x-select-input-per-pages>

                            <!-- Add service category button -->
                            <div class="w-full sm:w-auto">
                                <button id="createServiceCategoryBtn"
                                    class="create-btn w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring focus:ring-green-200 disabled:opacity-25">
                                    <span class="mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </span>
                                    {{ __('add_service_category') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Service Categories table -->
                    <div
                        class="overflow-x-auto bg-gray-50 dark:bg-gray-700 rounded-lg shadow-inner border border-gray-200 dark:border-gray-600">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer sort-header"
                                        data-field="category">
                                        {{ __('category') }}
                                        <span class="sort-icon"></span>
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer sort-header"
                                        data-field="created_at">
                                        {{ __('created') }}
                                        <span class="sort-icon"></span>
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer sort-header"
                                        data-field="updated_at">
                                        {{ __('updated') }}
                                        <span class="sort-icon"></span>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="serviceCategoryTable"
                                class=" dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr id="loadingRow">
                                    <td colspan="4" class="px-6 py-4 text-center">
                                        <svg class="animate-spin h-5 w-5 mr-3 text-blue-500 inline-block"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        {{ __('loading_service_categories') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div id="pagination" class="mt-4 flex justify-between items-center"></div>
                </div>
            </div>
        </div>

        @push('scripts')
            <!-- SweetAlert2 -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <!-- CrudManagerModal -->
            <script src="{{ asset('js/crud-manager-modal.js') }}"></script>

            <!-- Estilos personalizados para SweetAlert2 -->
            <style>
                /* Estilos para modal de creación (verde) */
                .swal2-popup.swal-create .swal2-header,
                .swal2-popup.swal-create .swal2-title {
                    background: linear-gradient(135deg, #10B981, #059669) !important;
                    color: white !important;
                }

                /* Estilos para modal de edición (azul) */
                .swal2-popup.swal-edit .swal2-header,
                .swal2-popup.swal-edit .swal2-title {
                    background: linear-gradient(135deg, #3B82F6, #2563EB) !important;
                    color: white !important;
                }

                /* Forzar estilos del header */
                .swal2-header {
                    padding: 0 !important;
                    border-radius: 12px 12px 0 0 !important;
                }

                .swal2-popup.swal-create .swal2-header,
                .swal2-popup.swal-edit .swal2-header {
                    border-radius: 12px 12px 0 0 !important;
                }

                .swal2-title {
                    padding: 1.5rem !important;
                    margin: 0 !important;
                    width: 100% !important;
                    text-align: center !important;
                }

                /* Estilos generales para el modal */
                .swal2-popup {
                    border-radius: 12px !important;
                    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
                }

                .swal2-title {
                    font-size: 1.5rem !important;
                    font-weight: 600 !important;
                    margin: 0 !important;
                    padding: 1rem !important;
                }

                .swal2-close {
                    font-size: 1.5rem !important;
                    font-weight: 300 !important;
                    right: 1rem !important;
                    top: 1rem !important;
                }

                .swal2-close:hover {
                    background: rgba(255, 255, 255, 0.1) !important;
                    border-radius: 50% !important;
                }

                /* Estilos para el formulario */
                .crud-modal-form {
                    padding: 1rem;
                }

                .form-group label {
                    font-weight: 500 !important;
                    color: #374151 !important;
                }

                .form-group input,
                .form-group select,
                .form-group textarea {
                    transition: all 0.2s ease !important;
                }

                .form-group input:focus,
                .form-group select:focus,
                .form-group textarea:focus {
                    ring: 2px !important;
                    ring-color: #3B82F6 !important;
                    border-color: #3B82F6 !important;
                    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
                }

                /* Estilos para mensajes de validación */
                .error-message {
                    font-size: 0.875rem !important;
                    margin-top: 0.25rem !important;
                    transition: all 0.2s ease !important;
                }

                .error-message.text-red-500 {
                    color: #EF4444 !important;
                }

                .error-message.text-green-500 {
                    color: #10B981 !important;
                }

                /* Estilos para campos con error */
                .form-group input.error,
                .form-group select.error,
                .form-group textarea.error {
                    border-color: #EF4444 !important;
                    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
                }

                /* Estilos para campos válidos */
                .form-group input.valid,
                .form-group select.valid,
                .form-group textarea.valid {
                    border-color: #10B981 !important;
                    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
                }
            </style>
            <script>
                $(document).ready(function() {
                    // Recuperar estado del toggle de localStorage antes de inicializar el manager
                    const showDeletedState = localStorage.getItem('showDeleted') === 'true';
                    console.log('Estado inicial de showDeleted:', showDeletedState);

                    // Make the manager globally accessible
                    window.serviceCategoryManager = new CrudManagerModal({
                        entityName: 'ServiceCategory',
                        entityNamePlural: 'ServiceCategories',
                        routes: {
                            index: "{{ secure_url(route('service-categories.index', [], false)) }}",
                            store: "{{ secure_url(route('service-categories.store', [], false)) }}",
                            edit: "{{ secure_url(route('service-categories.edit', ':id', false)) }}",
                            update: "{{ secure_url(route('service-categories.update', ':id', false)) }}",
                            destroy: "{{ secure_url(route('service-categories.destroy', ':id', false)) }}",
                            restore: "{{ secure_url(route('service-categories.restore', ':id', false)) }}",
                            checkCategory: "{{ secure_url(route('service-categories.check-category', [], false)) }}"
                        },
                        tableSelector: '#serviceCategoryTable',
                        searchSelector: '#searchInput',
                        perPageSelector: '#perPage',
                        showDeletedSelector: '#showDeleted',
                        paginationSelector: '#pagination',
                        alertSelector: '#alertContainer',
                        createButtonSelector: '#createServiceCategoryBtn',
                        idField: 'uuid',
                        searchFields: ['category'],
                        // Establecer el valor inicial basado en localStorage
                        showDeleted: showDeletedState,
                        formFields: [{
                            name: 'category',
                            type: 'text',
                            label: '{{ __('category_name') }}',
                            placeholder: '{{ __('enter_category_name') }}',
                            required: true,
                            validation: {
                                required: true,
                                minLength: 3,
                                maxLength: 100,
                                unique: {
                                    url: "{{ route('service-categories.check-category') }}",
                                    message: '{{ __('category_already_taken') }}'
                                }
                            }
                        }],
                        tableHeaders: [{
                                field: 'category',
                                name: '{{ __('category') }}',
                                sortable: true
                            },
                            {
                                field: 'created_at',
                                name: '{{ __('created') }}',
                                sortable: true,
                                getter: (entity) => entity.created_at ? new Date(entity.created_at)
                                    .toLocaleDateString() : 'N/A'
                            },
                            {
                                field: 'updated_at',
                                name: '{{ __('updated') }}',
                                sortable: true,
                                getter: (entity) => entity.updated_at ? new Date(entity.updated_at)
                                    .toLocaleDateString() : 'N/A'
                            },
                            {
                                field: 'actions',
                                name: '{{ __('actions') }}',
                                sortable: false,
                                getter: (serviceCategory) => {
                                    let actionsHtml = `
                                <div class="flex justify-center space-x-2">
                                    <button data-id="${serviceCategory.uuid}" class="edit-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105" title="{{ __('edit_service_category') }}">
                                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                             <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                         </svg>
                                    </button>`;

                                    if (serviceCategory.deleted_at) {
                                        actionsHtml += `
                                    <button data-id="${serviceCategory.uuid}" class="restore-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105" title="{{ __('restore_service_category') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>`;
                                    } else {
                                        actionsHtml += `
                                    <button data-id="${serviceCategory.uuid}" class="delete-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105" title="{{ __('delete_service_category') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>`;
                                    }

                                    actionsHtml += `</div>`;
                                    return actionsHtml;
                                }
                            }
                        ],
                        defaultSortField: 'created_at',
                        defaultSortDirection: 'desc',
                        translations: {
                            create: '{{ __('create_service_category') }}',
                            edit: '{{ __('edit_service_category') }}',
                            delete: '{{ __('delete_service_category') }}',
                            restore: '{{ __('restore_service_category') }}',
                            confirmDelete: '{{ __('confirm_delete_service_category') }}',
                            confirmRestore: '{{ __('confirm_restore_service_category') }}',
                            deleteMessage: '{{ __('delete_service_category_message') }}',
                            restoreMessage: '{{ __('restore_service_category_message') }}',
                            success: '{{ __('success') }}',
                            error: '{{ __('error') }}',
                            saving: '{{ __('saving') }}',
                            loading: '{{ __('loading') }}',
                            cancel: '{{ __('cancel') }}',
                            save: '{{ __('save') }}',
                            update: '{{ __('update') }}',
                            yes: '{{ __('yes') }}',
                            no: '{{ __('no') }}'
                        }
                    });

                    // Initialize loading of entities
                    window.serviceCategoryManager.loadEntities();
                });
            </script>
        @endpush
    </div>
</x-app-layout>
