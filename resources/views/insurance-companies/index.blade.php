@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">{{ __('insurance_companies_management') }}</h1>
                <p class="text-gray-400">{{ __('manage_insurance_companies_subtitle') }}</p>
            </div>

            <!-- Filter Bar Component with Date Range -->
            <x-crud.filter-bar :entityName="__('insurance_company')" :showSearchBar="true" :showInactiveToggle="true" :showPerPage="true" :showExport="true"
                :showDateRange="true" :searchId="'searchInput'" :showDeletedId="'showDeleted'" :perPageId="'perPage'" :exportId="'exportSelect'" :dateRangeStartId="'dateRangeStart'"
                :dateRangeEndId="'dateRangeEnd'" :clearDatesId="'clearDates'" :managerName="'insuranceCompanyManager'" :createButtonId="'createInsuranceCompanyBtn'" :addNewLabel="__('add_insurance_company')"
                :searchPlaceholder="__('search_insurance_companies')" :showDeletedLabel="__('show_inactive_records')" />

            <!-- Table Container -->
            <div class="glassmorphism-table-container">
                <table id="dataTable" class="w-full">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="text-left py-3 px-4 text-white font-semibold">#</th>
                            <th class="text-left py-3 px-4 text-white font-semibold cursor-pointer sort-header"
                                data-field="insurance_company_name">
                                {{ __('company_name') }}
                                <span class="ml-1 sort-indicator">↕</span>
                            </th>
                            <th class="text-left py-3 px-4 text-white font-semibold cursor-pointer sort-header"
                                data-field="email">
                                {{ __('email') }}
                                <span class="ml-1 sort-indicator">↕</span>
                            </th>
                            <th class="text-left py-3 px-4 text-white font-semibold">{{ __('phone') }}</th>
                            <th class="text-left py-3 px-4 text-white font-semibold">{{ __('website') }}</th>
                            <th class="text-left py-3 px-4 text-white font-semibold cursor-pointer sort-header"
                                data-field="created_at">
                                {{ __('created') }}
                                <span class="ml-1 sort-indicator">↕</span>
                            </th>
                            <th class="text-left py-3 px-4 text-white font-semibold">{{ __('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded here by CrudManager -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="pagination" class="mt-6"></div>
        </div>
    </div>

    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <style>
        .glassmorphism-table-container {
            background: rgba(0, 0, 0, 0.78);
            backdrop-filter: blur(20px) saturate(1.3);
            -webkit-backdrop-filter: blur(20px) saturate(1.3);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-top: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(138, 43, 226, 0.25),
                0 16px 64px 0 rgba(128, 0, 255, 0.18),
                0 4px 16px 0 rgba(75, 0, 130, 0.3),
                0 2px 8px 0 rgba(147, 51, 234, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.15),
                inset 0 -1px 0 rgba(255, 255, 255, 0.08);
            padding: 1.5rem;
            margin: 2rem 0;
        }

        .sort-header:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sort-indicator {
            opacity: 0.5;
        }

        .sort-header[data-sort="asc"] .sort-indicator::before {
            content: "↑";
        }

        .sort-header[data-sort="desc"] .sort-indicator::before {
            content: "↓";
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing Insurance Company Manager with CrudManager...');

            // Initialize the CrudManager with date filtering support
            window.insuranceCompanyManager = new CrudManager({
                entityName: "Insurance Company",
                entityNamePlural: "Insurance Companies",
                routes: {
                    index: "{{ route('insurance-companies.index') }}",
                    store: "{{ route('insurance-companies.store') }}",
                    edit: "{{ route('insurance-companies.edit', ':id') }}",
                    update: "{{ route('insurance-companies.update', ':id') }}",
                    destroy: "{{ route('insurance-companies.destroy', ':id') }}",
                    restore: "{{ route('insurance-companies.restore', ':id') }}"
                },
                tableSelector: "#dataTable tbody",
                searchSelector: "#searchInput",
                perPageSelector: "#perPage",
                showDeletedSelector: "#showDeleted",
                paginationSelector: "#pagination",
                alertSelector: "#alertContainer",
                idField: "uuid",
                tableHeaders: [{
                        key: "insurance_company_name",
                        label: "{{ __('company_name') }}",
                        sortable: true
                    },
                    {
                        key: "email",
                        label: "{{ __('email') }}",
                        sortable: true
                    },
                    {
                        key: "phone",
                        label: "{{ __('phone') }}",
                        sortable: false
                    },
                    {
                        key: "website",
                        label: "{{ __('website') }}",
                        sortable: false
                    },
                    {
                        key: "created_at",
                        label: "{{ __('created') }}",
                        sortable: true,
                        type: "date"
                    }
                ],
                formFields: [{
                        name: 'insurance_company_name',
                        type: 'text',
                        label: "{{ __('company_name') }}",
                        placeholder: "{{ __('enter_company_name') }}",
                        required: true,
                        validation: {
                            required: true,
                            minLength: 2,
                            maxLength: 255
                        }
                    },
                    {
                        name: 'address',
                        type: 'textarea',
                        label: "{{ __('address') }}",
                        placeholder: "{{ __('enter_address') }}",
                        required: false,
                        validation: {
                            minLength: 10,
                            maxLength: 500
                        }
                    },
                    {
                        name: 'email',
                        type: 'email',
                        label: "{{ __('email') }}",
                        placeholder: "{{ __('email') }}",
                        required: false,
                        validation: {
                            email: true
                        }
                    },
                    {
                        name: 'phone',
                        type: 'tel',
                        label: "{{ __('phone') }}",
                        placeholder: "{{ __('enter_phone_number') }}",
                        required: false
                    },
                    {
                        name: 'website',
                        type: 'url',
                        label: "{{ __('website') }}",
                        placeholder: "{{ __('website_placeholder') }}",
                        required: false,
                        validation: {
                            url: true
                        }
                    }
                ],
                showSequentialNumbers: true,
                sequentialNumberLabel: "#",
                dateField: "created_at", // Default date field for filtering
                defaultSortField: "insurance_company_name",
                defaultSortDirection: "asc",
                entityConfig: {
                    identifierField: 'insurance_company_name',
                    displayName: 'insurance company',
                    fallbackFields: ['email', 'address']
                },
                translations: {
                    confirmDelete: "{{ __('confirm_delete_entity') }}",
                    deleteMessage: "{{ __('delete_element_question') }}",
                    confirmRestore: "{{ __('confirm_restore_entity') }}",
                    restoreMessage: "{{ __('restore_element_question') }}",
                    yesDelete: "{{ __('yes_delete') }}",
                    yesRestore: "{{ __('yes_restore') }}",
                    cancel: "{{ __('cancel') }}",
                    deletedSuccessfully: "{{ __('deleted_successfully') }}",
                    restoredSuccessfully: "{{ __('restored_successfully') }}",
                    errorDeleting: "{{ __('errorDeleting') }}",
                    errorRestoring: "{{ __('errorRestoring') }}",
                    showing: "{{ __('showing') }}",
                    to: "{{ __('to') }}",
                    of: "{{ __('of') }}",
                    results: "{{ __('results') }}",
                    totalRecords: "{{ __('total_records') }}",
                    records: "{{ __('records') }}"
                }
            });

            console.log('Insurance Company Manager initialized with date filtering:', window
                .insuranceCompanyManager);
        });
    </script>
@endsection
