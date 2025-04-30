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
                <div class="flex flex-wrap items-end gap-3 py-5 mb-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start
                            Date</label>
                        <input type="date" id="start_date" name="start_date"
                            class="w-44 sm:w-48 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End
                            Date</label>
                        <input type="date" id="end_date" name="end_date"
                            class="w-44 sm:w-48 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    </div>
                    <button id="clearDateFilters" type="button"
                        class="px-3 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring focus:ring-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Clear
                    </button>
                    <div class="ml-auto flex space-x-2">
                        <button id="sendRejectionBtn" disabled
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring focus:ring-red-200 disabled:opacity-25">
                            <span class="mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            Send Rejection
                        </button>
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
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <input type="checkbox" id="selectAll"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </th>
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
                                    data-field="insurance_property">
                                    Insurance
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
                                <td colspan="8" class="px-6 py-4 text-center">
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

    <!-- Rejection Notification Modal -->
    <div id="rejectionModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Send Rejection Notification
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                Please select the reason(s) for rejecting the selected appointment(s):
                            </p>

                            <div class="mt-4">
                                <div class="flex items-start mb-2">
                                    <div class="flex items-center h-5">
                                        <input id="reason_no_contact" name="rejection_reason" type="checkbox"
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="reason_no_contact"
                                            class="font-medium text-gray-700 dark:text-gray-300">Unable to contact</label>
                                    </div>
                                </div>

                                <div class="flex items-start mb-2">
                                    <div class="flex items-center h-5">
                                        <input id="reason_no_insurance" name="rejection_reason" type="checkbox"
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="reason_no_insurance"
                                            class="font-medium text-gray-700 dark:text-gray-300">No property
                                            insurance</label>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label for="reason_other"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Other
                                        reason(s):</label>
                                    <textarea id="reason_other" name="reason_other" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="sendRejectionNotification"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Send Notification
                    </button>
                    <button type="button" id="cancelRejection"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                </div>
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
                        checkName: "{{ route('appointments.check-email') }}",
                        sendRejection: "{{ route('appointments.send-rejection') }}"
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
                            field: 'checkbox',
                            name: '',
                            sortable: false,
                            getter: (entity) =>
                                `<input type="checkbox" class="appointment-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" data-id="${entity.uuid}">`
                        },
                        {
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
                            field: 'insurance_property',
                            name: 'Insurance',
                            sortable: true,
                            getter: (entity) => {
                                if (entity.insurance_property === true) {
                                    return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Yes</span>';
                                } else {
                                    return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">No</span>';
                                }
                            }
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

                // Selected appointments tracking
                let selectedAppointments = [];

                // Handle select all checkbox
                $(document).on('change', '#selectAll', function() {
                    const isChecked = $(this).prop('checked');
                    $('.appointment-checkbox').prop('checked', isChecked);

                    // Update selected appointments array
                    selectedAppointments = isChecked ?
                        $('.appointment-checkbox').map(function() {
                            return $(this).data('id');
                        }).get() : [];

                    // Enable/disable rejection button
                    updateRejectionButtonState();
                });

                // Handle individual checkbox changes
                $(document).on('change', '.appointment-checkbox', function() {
                    const id = $(this).data('id');

                    if ($(this).prop('checked')) {
                        // Add to selected if not already there
                        if (!selectedAppointments.includes(id)) {
                            selectedAppointments.push(id);
                        }
                    } else {
                        // Remove from selected
                        selectedAppointments = selectedAppointments.filter(item => item !== id);
                        // Uncheck "select all" if any individual checkbox is unchecked
                        $('#selectAll').prop('checked', false);
                    }

                    // Enable/disable rejection button
                    updateRejectionButtonState();
                });

                // Update rejection button state
                function updateRejectionButtonState() {
                    $('#sendRejectionBtn').prop('disabled', selectedAppointments.length === 0);
                }

                // Open rejection modal
                $('#sendRejectionBtn').on('click', function() {
                    $('#rejectionModal').removeClass('hidden');
                });

                // Close rejection modal
                $('#cancelRejection').on('click', function() {
                    $('#rejectionModal').addClass('hidden');
                    resetRejectionForm();
                });

                // Submit rejection notification
                $('#sendRejectionNotification').on('click', function() {
                    // Get selected reason(s)
                    const noContact = $('#reason_no_contact').prop('checked');
                    const noInsurance = $('#reason_no_insurance').prop('checked');
                    const otherReason = $('#reason_other').val().trim();

                    // Validate at least one reason is selected
                    if (!noContact && !noInsurance && otherReason === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Please select at least one reason for rejection',
                            confirmButtonColor: '#3B82F6'
                        });
                        return;
                    }

                    // Show loading state
                    const originalBtnText = $('#sendRejectionNotification').text();
                    $('#sendRejectionNotification').html(`
                        <svg class="animate-spin h-4 w-4 mr-2 text-white inline-block" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sending...
                    `).prop('disabled', true);

                    // Send the rejection notification
                    $.ajax({
                        url: window.appointmentManager.routes.sendRejection,
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            appointment_ids: selectedAppointments,
                            no_contact: noContact,
                            no_insurance: noInsurance,
                            other_reason: otherReason
                        },
                        success: function(response) {
                            $('#rejectionModal').addClass('hidden');
                            resetRejectionForm();

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Rejection notifications sent successfully',
                                confirmButtonColor: '#3B82F6'
                            });

                            // Clear selected appointments
                            selectedAppointments = [];
                            $('.appointment-checkbox, #selectAll').prop('checked', false);
                            updateRejectionButtonState();

                            // Refresh the table
                            window.appointmentManager.loadEntities();
                        },
                        error: function(xhr) {
                            let errorMessage =
                                'An error occurred while sending rejection notifications';

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage,
                                confirmButtonColor: '#3B82F6'
                            });
                        },
                        complete: function() {
                            $('#sendRejectionNotification').text(originalBtnText).prop('disabled',
                                false);
                        }
                    });
                });

                // Reset rejection form
                function resetRejectionForm() {
                    $('#reason_no_contact, #reason_no_insurance').prop('checked', false);
                    $('#reason_other').val('');
                }

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
