@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-white mb-8">Test Insurance Companies Date Filters</h1>

            <!-- Filter Bar Component -->
            <x-crud.filter-bar :entityName="'Insurance Company'" :showSearchBar="true" :showInactiveToggle="true" :showPerPage="true" :showExport="true"
                :showDateRange="true" :searchId="'searchInput'" :showDeletedId="'showDeleted'" :perPageId="'perPage'" :exportId="'exportSelect'" :dateRangeStartId="'dateRangeStart'"
                :dateRangeEndId="'dateRangeEnd'" :clearDatesId="'clearDates'" :managerName="'insuranceCompanyManager'" :createButtonId="'createBtn'" :addNewLabel="'Add New Insurance Company'" />

            <!-- Table Container -->
            <div class="glassmorphism-table-container">
                <table id="dataTable" class="w-full">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="text-left py-3 px-4 text-white font-semibold">#</th>
                            <th class="text-left py-3 px-4 text-white font-semibold">Company Name</th>
                            <th class="text-left py-3 px-4 text-white font-semibold">Email</th>
                            <th class="text-left py-3 px-4 text-white font-semibold">Phone</th>
                            <th class="text-left py-3 px-4 text-white font-semibold">Created At</th>
                            <th class="text-left py-3 px-4 text-white font-semibold">Actions</th>
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing Insurance Company Manager...');

            // Import CrudManager if using ES6 modules, or use global version
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
                tableSelector: "#dataTable",
                searchSelector: "#searchInput",
                perPageSelector: "#perPage",
                showDeletedSelector: "#showDeleted",
                paginationSelector: "#pagination",
                alertSelector: "#alertContainer",
                tableHeaders: [{
                        key: "insurance_company_name",
                        label: "Company Name",
                        sortable: true
                    },
                    {
                        key: "email",
                        label: "Email",
                        sortable: true
                    },
                    {
                        key: "phone",
                        label: "Phone",
                        sortable: false
                    },
                    {
                        key: "created_at",
                        label: "Created At",
                        sortable: true,
                        type: "date"
                    }
                ],
                showSequentialNumbers: true,
                sequentialNumberLabel: "#",
                dateField: "created_at", // Default date field for filtering
                defaultSortField: "insurance_company_name",
                defaultSortDirection: "asc"
            });

            console.log('Insurance Company Manager initialized:', window.insuranceCompanyManager);
        });
    </script>
@endsection
