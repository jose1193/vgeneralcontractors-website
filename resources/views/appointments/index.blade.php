@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <!-- Success and error messages -->
        <div id="alertContainer"></div>
        @if (session()->has('message'))
            <x-alerts.success :message="session('message')" />
        @endif
        @if (session()->has('error'))
            <x-alerts.error :message="session('error')" />
        @endif

        <!-- Main container -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <!-- Filter and action bar -->
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 space-y-4 md:space-y-0">
                    <!-- Search input -->
                    <div class="w-full md:w-1/2 lg:w-2/5">
                        <x-crud.input-search id="searchInput" placeholder="Search appointments..." />
                    </div>

                    <div class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                        <!-- Toggle to show inactive appointments -->
                        <x-crud.toggle-deleted id="showDeleted" label="Show Inactive Appointments" />

                        <!-- Per page dropdown -->
                        <x-select-input-per-pages name="perPage" id="perPage" class="sm:w-32">
                            <option value="5">5 per page</option>
                            <option value="10" selected>10 per page</option>
                            <option value="15">15 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                        </x-select-input-per-pages>

                        <!-- Add appointment button -->
                        <div class="w-full sm:w-auto">
                            <a href="{{ route('appointments.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring focus:ring-green-200 disabled:opacity-25">
                                <span class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </span>
                                Add Appointment
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Date range filters -->
                <div class="flex flex-col md:flex-row items-center py-5 mb-4 space-y-3 md:space-y-0 md:space-x-4">
                    <div class="w-full md:w-1/3 lg:w-1/4">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start
                            Date</label>
                        <input type="date" id="start_date" name="start_date"
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    </div>
                    <div class="w-full md:w-1/3 lg:w-1/4">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End
                            Date</label>
                        <input type="date" id="end_date" name="end_date"
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    </div>
                    <div class="self-end">
                        <button id="clearDateFilters" type="button"
                            class="px-3 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring focus:ring-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                            Clear
                        </button>
                    </div>
                    <div class="ml-auto self-end">
                        <button id="exportToExcel"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-200 disabled:opacity-25">
                            <span class="mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </span>
                            Excel Export
                        </button>
                    </div>
                </div>

                <!-- Appointments table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer sort-header"
                                    data-field="first_name">
                                    Name
                                    <span class="sort-icon"></span>
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer sort-header"
                                    data-field="email">
                                    Email
                                    <span class="sort-icon"></span>
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Phone
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer sort-header"
                                    data-field="inspection_date">
                                    Inspection Date
                                    <span class="sort-icon"></span>
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer sort-header"
                                    data-field="status_lead">
                                    Status Lead
                                    <span class="sort-icon"></span>
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody id="appointmentsTable"
                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr id="loadingRow">
                                <td colspan="6" class="px-6 py-4 text-center">
                                    <svg class="animate-spin h-5 w-5 mr-3 text-blue-500 inline-block" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Loading appointments...
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
        <script>
            $(document).ready(function() {
                // Make the manager globally accessible
                window.appointmentManager = new CrudManager({
                    entityName: 'Appointment',
                    entityNamePlural: 'Appointments',
                    routes: {
                        index: "{{ route('appointments.index') }}",
                        store: "{{ route('appointments.store') }}",
                        edit: "{{ route('appointments.edit', ':id') }}",
                        update: "{{ route('appointments.update', ':id') }}",
                        destroy: "{{ route('appointments.destroy', ':id') }}",
                        restore: "{{ route('appointments.restore', ':id') }}",
                        checkName: "{{ route('appointments.check-email') }}"
                    },
                    tableSelector: '#appointmentsTable',
                    searchSelector: '#searchInput',
                    perPageSelector: '#perPage',
                    showDeletedSelector: '#showDeleted',
                    paginationSelector: '#pagination',
                    alertSelector: '#alertContainer',
                    // Date filter selectors
                    startDateSelector: '#start_date',
                    endDateSelector: '#end_date',
                    clearDateFilterSelector: '#clearDateFilters',
                    idField: 'uuid',
                    searchFields: ['first_name', 'last_name', 'email', 'status_lead', 'phone'],
                    tableHeaders: [{
                            field: 'first_name',
                            name: 'Name',
                            sortable: true,
                            getter: (entity) => `${entity.first_name} ${entity.last_name}`
                        },
                        {
                            field: 'email',
                            name: 'Email',
                            sortable: true
                        },
                        {
                            field: 'phone',
                            name: 'Phone',
                            sortable: false
                        },
                        {
                            field: 'inspection_date',
                            name: 'Inspection Date',
                            sortable: true,
                            getter: (entity) => entity.inspection_date ? new Date(entity.inspection_date)
                                .toLocaleDateString() : 'N/A'
                        },
                        {
                            field: 'status_lead',
                            name: 'Status',
                            sortable: true,
                            getter: (entity) => {
                                const statusMap = {
                                    'New': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">New</span>',
                                    'Called': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Called</span>',
                                    'Pending': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">Pending</span>',
                                    'Declined': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Declined</span>'
                                };
                                return entity.status_lead ? statusMap[entity.status_lead] || entity
                                    .status_lead : 'N/A';
                            }
                        },
                        {
                            field: 'actions',
                            name: 'Actions',
                            sortable: false,
                            getter: (appointment) => {
                                const editUrl = `{{ url('/appointments') }}/${appointment.uuid}/edit`;

                                let actionsHtml = `
                                    <div class="flex justify-center space-x-1">
                                        <a href="${editUrl}" class="btn btn-icon btn-sm btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>`;

                                if (appointment.deleted_at) {
                                    actionsHtml += `
                                        <button data-id="${appointment.uuid}" class="restore-btn btn btn-icon btn-sm btn-success">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>`;
                                } else {
                                    actionsHtml += `
                                        <button data-id="${appointment.uuid}" class="delete-btn btn btn-icon btn-sm btn-danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>`;
                                }

                                actionsHtml += `</div>`;
                                return actionsHtml;
                            }
                        }
                    ],
                    validationFields: [{
                            name: 'first_name',
                            errorMessage: 'Please enter a valid first name.'
                        },
                        {
                            name: 'last_name',
                            errorMessage: 'Please enter a valid last name.'
                        },
                        {
                            name: 'email',
                            validation: {
                                url: "{{ route('appointments.check-email') }}",
                                delay: 500,
                                minLength: 5,
                                errorMessage: 'This email is already taken.',
                                successMessage: 'Email is available.'
                            },
                            errorMessage: 'Please choose a different email.'
                        },
                        {
                            name: 'phone',
                            errorMessage: 'Please enter a valid phone number.'
                        },
                        {
                            name: 'inspection_date',
                            errorMessage: 'Please enter a valid inspection date.'
                        },
                        {
                            name: 'status_lead',
                            errorMessage: 'Please select a valid lead status.'
                        }
                    ],
                    defaultSortField: 'inspection_date',
                    defaultSortDirection: 'desc'
                });

                // Add event listeners for delete and restore buttons
                $(document).on('click', '.delete-btn', function() {
                    const id = $(this).data('id');
                    window.appointmentManager.deleteEntity(id);
                });

                $(document).on('click', '.restore-btn', function() {
                    const id = $(this).data('id');
                    window.appointmentManager.restoreEntity(id);
                });

                // Initialize loading of entities
                window.appointmentManager.loadEntities();

                // Handle export to Google Sheets
                $('#exportToExcel').on('click', function() {
                    exportAppointmentsToExcel();
                });

                function exportAppointmentsToExcel() {
                    // Show loading indicator
                    const originalButtonContent = $('#exportToExcel').html();
                    $('#exportToExcel').html(`
                        <svg class="animate-spin h-4 w-4 mr-2 text-white inline-block" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Exporting...
                    `).prop('disabled', true);

                    // Gather filter parameters
                    const searchValue = $(window.appointmentManager.searchSelector).val();
                    const showDeleted = $(window.appointmentManager.showDeletedSelector).is(':checked') ? 'true' :
                        'false';
                    const startDate = $('#start_date').val();
                    const endDate = $('#end_date').val();
                    const sortField = window.appointmentManager.sortField;
                    const sortDirection = window.appointmentManager.sortDirection;

                    // Create the URL with query parameters
                    const exportUrl = new URL(window.appointmentManager.routes.index, window.location.origin);
                    exportUrl.searchParams.append('export', 'excel');
                    exportUrl.searchParams.append('search', searchValue);
                    exportUrl.searchParams.append('show_deleted', showDeleted);
                    if (startDate) exportUrl.searchParams.append('start_date', startDate);
                    if (endDate) exportUrl.searchParams.append('end_date', endDate);
                    exportUrl.searchParams.append('sort_field', sortField);
                    exportUrl.searchParams.append('sort_direction', sortDirection);

                    // Ensure the button resets after a max time (fallback)
                    const resetTimeout = setTimeout(function() {
                        $('#exportToExcel').html(originalButtonContent).prop('disabled', false);
                    }, 10000); // 10 seconds timeout as fallback

                    try {
                        // Use fetch API instead of iframe for better control
                        fetch(exportUrl.toString())
                            .then(response => {
                                clearTimeout(resetTimeout);

                                if (!response.ok) {
                                    throw new Error('Export failed');
                                }

                                // Check content disposition to confirm it's a file download
                                const contentDisposition = response.headers.get('content-disposition');
                                if (!contentDisposition || !contentDisposition.includes('attachment')) {
                                    throw new Error('Invalid response format');
                                }

                                return response.blob();
                            })
                            .then(blob => {
                                // Create download link
                                const url = window.URL.createObjectURL(blob);
                                const a = document.createElement('a');
                                const filename = 'appointments_export_' + new Date().toISOString().slice(0, 10) +
                                    '.xlsx';

                                a.href = url;
                                a.download = filename;
                                document.body.appendChild(a);
                                a.click();

                                // Cleanup
                                window.URL.revokeObjectURL(url);
                                a.remove();

                                // Reset button and show success message
                                $('#exportToExcel').html(originalButtonContent).prop('disabled', false);

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Export Successful',
                                    text: 'Your appointments have been exported to Excel',
                                    confirmButtonColor: '#3B82F6'
                                });
                            })
                            .catch(error => {
                                console.error('Export error:', error);

                                // Reset button and show error message
                                $('#exportToExcel').html(originalButtonContent).prop('disabled', false);

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Export Failed',
                                    text: 'There was an error exporting to Excel. Please try again.',
                                    confirmButtonColor: '#3B82F6'
                                });
                            });
                    } catch (error) {
                        // Handle any unexpected errors
                        clearTimeout(resetTimeout);
                        console.error('Unexpected export error:', error);

                        // Reset button and show error message
                        $('#exportToExcel').html(originalButtonContent).prop('disabled', false);

                        Swal.fire({
                            icon: 'error',
                            title: 'Export Failed',
                            text: 'There was an unexpected error. Please try again.',
                            confirmButtonColor: '#3B82F6'
                        });
                    }
                }
            });
        </script>
    @endpush

    <style>
        .swal-wide {
            width: 100%;
            max-width: 800px;
        }

        .swal-wide-popup {
            width: 100%;
            max-width: 800px !important;
        }
    </style>
@endsection
