<x-app-layout>
    {{-- Commenting out Jetstream header to avoid duplicate headers --}}
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Appointments Management') }}
        </h2>
    </x-slot> --}}

    {{-- Dark background container with consistent styling --}}
    <div class="min-h-screen bg-gray-900" style="background-color: #141414;">
        {{-- Header section with title and subtitle --}}
        <div class="p-4 sm:p-6">
            <div class="mb-4 sm:mb-8 text-center sm:text-center md:text-left lg:text-left">
                <h2 class="text-base sm:text-base md:text-2xl lg:text-2xl font-bold text-white mb-2">
                    {{ __('appointments_management_title') }}</h2>
                <p class="text-base sm:text-base md:text-base lg:text-base text-gray-400">
                    {{ __('appointments_management_subtitle') }}
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
                            <x-crud.input-search id="searchInput" placeholder="{{ __('search_appointments') }}" />
                        </div>

                        <div
                            class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                            <!-- Toggle to show inactive appointments -->
                            <x-crud.toggle-deleted id="showDeleted" label="{{ __('show_inactive_appointments') }}" />

                            <!-- Per page dropdown -->
                            <x-select-input-per-pages name="perPage" id="perPage" class="sm:w-32">
                                <option value="5">5 {{ __('per_page') }}</option>
                                <option value="10" selected>10 {{ __('per_page') }}</option>
                                <option value="15">15 {{ __('per_page') }}</option>
                                <option value="25">25 {{ __('per_page') }}</option>
                                <option value="50">50 {{ __('per_page') }}</option>
                            </x-select-input-per-pages>

                            <!-- Add appointment button -->
                            <div class="w-full sm:w-auto">
                                <a href="{{ route('appointments.create') }}"
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring focus:ring-green-200 disabled:opacity-25">
                                    <span class="mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </span>
                                    {{ __('add_appointment') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Date range filters -->
                    <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-end gap-3 py-5 mb-4">
                        <div class="w-full sm:w-auto">
                            <label for="start_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('start_date') }}</label>
                            <input type="date" id="start_date" name="start_date"
                                class="w-full sm:w-44 md:w-48 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        </div>
                        <div class="w-full sm:w-auto">
                            <label for="end_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('end_date') }}</label>
                            <input type="date" id="end_date" name="end_date"
                                class="w-full sm:w-44 md:w-48 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        </div>
                        <div class="w-full sm:w-auto">
                            <label for="status_lead_filter"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('lead_status') }}</label>
                            <select id="status_lead_filter" name="status_lead_filter"
                                class="w-full sm:w-44 md:w-48 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">{{ __('all_statuses') }}</option>
                                <option value="New">{{ __('new_status') }}</option>
                                <option value="Called">{{ __('called_status') }}</option>
                                <option value="Pending">{{ __('pending_status') }}</option>
                                <option value="Declined">{{ __('declined_status') }}</option>
                            </select>
                        </div>
                        <button id="clearDateFilters" type="button"
                            class="w-full sm:w-auto px-3 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring focus:ring-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                            {{ __('clear') }}
                        </button>
                        <div class="w-full sm:ml-auto flex flex-col sm:flex-row gap-2 sm:space-x-2 sm:gap-0">
                            <button id="sendRejectionBtn" disabled
                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring focus:ring-red-200 disabled:opacity-25">
                                <span class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                                {{ __('send_rejection') }}
                            </button>
                            <button id="exportToExcel"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-200 disabled:opacity-25">
                                <span class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                </span>
                                {{ __('excel_export') }}
                            </button>
                        </div>
                    </div>

                    <!-- Appointments table -->
                    <div
                        class="overflow-x-auto bg-gray-50 dark:bg-gray-700 rounded-lg shadow-inner border border-gray-200 dark:border-gray-600">
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
                                        {{ __('name') }}
                                        <span class="sort-icon"></span>
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer sort-header"
                                        data-field="email">
                                        {{ __('email') }}
                                        <span class="sort-icon"></span>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('phone') }}
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer sort-header"
                                        data-field="inspection_date">
                                        {{ __('inspection_date') }}
                                        <span class="sort-icon"></span>
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer sort-header"
                                        data-field="inspection_time">
                                        {{ __('inspection_time') }}
                                        <span class="sort-icon"></span>
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer sort-header"
                                        data-field="insurance_property">
                                        {{ __('insurance') }}
                                        <span class="sort-icon"></span>
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer sort-header"
                                        data-field="status_lead">
                                        {{ __('status_lead') }}
                                        <span class="sort-icon"></span>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('inspection_status') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="appointmentsTable"
                                class=" dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr id="loadingRow">
                                    <td colspan="10" class="px-6 py-4 text-center">
                                        <svg class="animate-spin h-5 w-5 mr-3 text-blue-500 inline-block"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        {{ __('loading_appointments') }}
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
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100"
                                    id="modal-title">
                                    {{ __('send_rejection_notification') }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    {{ __('select_reason_rejecting') }}
                                </p>

                                <div class="mt-4">
                                    <div class="flex items-start mb-3">
                                        <div class="flex items-center h-5">
                                            <input id="reason_no_contact" name="rejection_reason" type="radio"
                                                value="no_contact"
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="reason_no_contact"
                                                class="font-medium text-gray-700 dark:text-gray-300">{{ __('unable_to_contact') }}</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start mb-3">
                                        <div class="flex items-center h-5">
                                            <input id="reason_no_insurance" name="rejection_reason" type="radio"
                                                value="no_insurance"
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="reason_no_insurance"
                                                class="font-medium text-gray-700 dark:text-gray-300">{{ __('no_property_insurance') }}</label>
                                        </div>
                                    </div>

                                    <div class="flex items-start mb-3">
                                        <div class="flex items-center h-5">
                                            <input id="reason_other_option" name="rejection_reason" type="radio"
                                                value="other"
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="reason_other_option"
                                                class="font-medium text-gray-700 dark:text-gray-300">{{ __('other_reason') }}</label>
                                        </div>
                                    </div>

                                    <div id="other_reason_container" class="mt-4 hidden">
                                        <label for="reason_other"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('specify_other_reason') }}</label>
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
                            {{ __('send_notification') }}
                        </button>
                        <button type="button" id="cancelRejection"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                            {{ __('cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                $(document).ready(function() {
                    // Recuperar estado del toggle de localStorage antes de inicializar el manager
                    const showDeletedState = localStorage.getItem('showDeleted') === 'true';
                    console.log('Estado inicial de showDeleted:', showDeletedState);

                    // Make the manager globally accessible
                    window.appointmentManager = new CrudManager({
                        entityName: 'Appointment',
                        entityNamePlural: 'Appointments',
                        routes: {
                            index: "{{ secure_url(route('appointments.index', [], false)) }}",
                            store: "{{ secure_url(route('appointments.store', [], false)) }}",
                            edit: "{{ secure_url(route('appointments.edit', ':id', false)) }}",
                            update: "{{ secure_url(route('appointments.update', ':id', false)) }}",
                            destroy: "{{ secure_url(route('appointments.destroy', ':id', false)) }}",
                            restore: "{{ secure_url(route('appointments.restore', ':id', false)) }}",
                            checkName: "{{ secure_url(route('appointments.check-email', [], false)) }}",
                            sendRejection: "{{ secure_url(route('appointments.send-rejection', [], false)) }}"
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
                        statusLeadFilterSelector: '#status_lead_filter',
                        idField: 'uuid',
                        searchFields: ['first_name', 'last_name', 'email', 'status_lead', 'phone'],
                        // Establecer el valor inicial basado en localStorage
                        showDeleted: showDeletedState,
                        tableHeaders: [{
                                field: 'checkbox',
                                name: '',
                                sortable: false,
                                getter: (entity) =>
                                    entity.deleted_at ? '' :
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
                                field: 'inspection_time',
                                name: 'Inspection Time',
                                sortable: true,
                                getter: (entity) => entity.inspection_time ? new Date(
                                        `2000-01-01T${entity.inspection_time}`)
                                    .toLocaleTimeString([], {
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    }) : 'N/A'
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
                                field: 'inspection_status',
                                name: 'Inspection Status',
                                sortable: true,
                                getter: (entity) => {
                                    const statusMap = {
                                        'Confirmed': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Confirmed</span>',
                                        'Completed': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Completed</span>',
                                        'Pending': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">Pending</span>',
                                        'Declined': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Declined</span>'
                                    };
                                    return entity.inspection_status ? statusMap[entity.inspection_status] ||
                                        entity
                                        .inspection_status : 'N/A';
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
                                    <a href="${editUrl}" class="inline-flex items-center justify-center p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors duration-200" title="Edit">
                                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                         </svg>
                                    </a>`;

                                    // Botón para compartir ubicación - Se agrega independientemente del estado de borrado
                                    if (appointment.latitude && appointment.longitude) {
                                        const address =
                                            `${appointment.address || ''}, ${appointment.city || ''}`;
                                        actionsHtml += `
                                    <button data-id="${appointment.uuid}" data-lat="${appointment.latitude}" data-lng="${appointment.longitude}" data-address="${address}" class="share-location btn btn-icon btn-sm btn-info" style="background-color: #4299e1; color: white; border-radius: 0.375rem; padding: 0.375rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </button>`;
                                    } else {
                                        // Si no hay coordenadas, aún mostrar el botón pero con un comportamiento diferente
                                        actionsHtml += `
                                    <button data-id="${appointment.uuid}" data-no-coords="true" class="share-location btn btn-icon btn-sm" style="background-color: #9CA3AF; color: white; border-radius: 0.375rem; padding: 0.375rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </button>`;
                                    }

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

                    // Add statusLeadFilter property to appointmentManager
                    window.appointmentManager.statusLeadFilter = '';

                    // Extend the original loadEntities method
                    const originalLoadEntities = window.appointmentManager.loadEntities;
                    window.appointmentManager.loadEntities = function(page = 1) {
                        // Set current page
                        this.currentPage = page;

                        // Show loading state
                        $(this.tableSelector + ' #loadingRow').show();
                        $(this.tableSelector + ' tr:not(#loadingRow)').remove();

                        // Prepare request data
                        const requestData = {
                            page: this.currentPage,
                            per_page: this.perPage,
                            sort_field: this.sortField,
                            sort_direction: this.sortDirection,
                            search: this.searchTerm,
                            show_deleted: this.showDeleted ? "true" : "false",
                        };

                        // Add date filters if they exist
                        if (this.startDate) {
                            requestData.start_date = this.startDate;
                        }

                        if (this.endDate) {
                            requestData.end_date = this.endDate;
                        }

                        // Add status_lead_filter if it exists
                        if (this.statusLeadFilter) {
                            requestData.status_lead_filter = this.statusLeadFilter;
                        }

                        // Make AJAX request
                        $.ajax({
                            url: this.routes.index,
                            type: 'GET',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Accept': 'application/json'
                            },
                            data: requestData,
                            success: (response) => {
                                this.renderTable(response);
                                this.renderPagination(response);
                            },
                            error: (xhr) => {
                                console.error(`Error loading ${this.entityNamePlural}:`, xhr.responseText);

                                // Show error message in table
                                $(this.tableSelector).html(`
                                <tr>
                                    <td colspan="${this.tableHeaders.length}" class="px-6 py-4 text-center text-sm text-red-500">
                                        Error loading ${this.entityNamePlural}. Please check the console for details.
                                    </td>
                                </tr>
                            `);
                            },
                            complete: () => {
                                $(this.tableSelector + ' #loadingRow').hide();
                            }
                        });
                    };

                    // Add event listeners for delete and restore buttons
                    $(document).on('click', '.delete-btn', function() {
                        const id = $(this).data('id');
                        window.appointmentManager.deleteEntity(id);
                    });

                    $(document).on('click', '.restore-btn', function() {
                        const id = $(this).data('id');
                        window.appointmentManager.restoreEntity(id);
                    });

                    // Add event listener for status lead filter
                    $('#status_lead_filter').on('change', function() {
                        // Update the statusLeadFilter property
                        window.appointmentManager.statusLeadFilter = $(this).val();
                        // Reset to first page when changing filter
                        window.appointmentManager.currentPage = 1;
                        // Load entities with new filter
                        window.appointmentManager.loadEntities();
                    });

                    // Add event listeners for date filters to update properties
                    $('#start_date').on('change', function() {
                        window.appointmentManager.startDate = $(this).val();
                        window.appointmentManager.currentPage = 1; // Reset to first page
                        window.appointmentManager.loadEntities();
                    });

                    $('#end_date').on('change', function() {
                        window.appointmentManager.endDate = $(this).val();
                        window.appointmentManager.currentPage = 1; // Reset to first page
                        window.appointmentManager.loadEntities();
                    });

                    // Initialize loading of entities
                    window.appointmentManager.loadEntities();

                    // Update the clear filters button to also clear status filter
                    $('#clearDateFilters').on('click', function() {
                        $('#start_date, #end_date').val('');
                        $('#status_lead_filter').val('');
                        window.appointmentManager.startDate = '';
                        window.appointmentManager.endDate = '';
                        window.appointmentManager.statusLeadFilter = '';
                        window.appointmentManager.loadEntities();
                    });

                    // Handle export to Excel with status filter
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
                        const startDate = window.appointmentManager.startDate;
                        const endDate = window.appointmentManager.endDate;
                        const statusLeadFilter = window.appointmentManager.statusLeadFilter;
                        const sortField = window.appointmentManager.sortField;
                        const sortDirection = window.appointmentManager.sortDirection;

                        // Create the URL with query parameters
                        const exportUrl = new URL(window.appointmentManager.routes.index, window.location.origin);
                        exportUrl.searchParams.append('export', 'excel');
                        exportUrl.searchParams.append('search', searchValue);
                        exportUrl.searchParams.append('show_deleted', showDeleted);
                        if (startDate) exportUrl.searchParams.append('start_date', startDate);
                        if (endDate) exportUrl.searchParams.append('end_date', endDate);
                        if (statusLeadFilter) exportUrl.searchParams.append('status_lead_filter', statusLeadFilter);
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

                    // Replace the handle export to Excel
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
                        // Get selected reason
                        const selectedReason = $('input[name="rejection_reason"]:checked').val();
                        const otherReason = $('#reason_other').val().trim();

                        // Validate a reason is selected
                        if (!selectedReason) {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __('error_occurred') }}',
                                text: '{{ __('please_select_reason') }}',
                                confirmButtonColor: '#3B82F6'
                            });
                            return;
                        }

                        // If "other" is selected, validate text is provided
                        if (selectedReason === 'other' && otherReason === '') {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __('error_occurred') }}',
                                text: '{{ __('please_provide_other_reason') }}',
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
                    {{ __('sending') }}...
                `).prop('disabled', true);

                        // Prepare data based on selected reason
                        const requestData = {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            appointment_ids: selectedAppointments,
                            no_contact: selectedReason === 'no_contact',
                            no_insurance: selectedReason === 'no_insurance',
                            other_reason: selectedReason === 'other' ? otherReason : null
                        };

                        // Send the rejection notification
                        $.ajax({
                            url: window.appointmentManager.routes.sendRejection,
                            type: 'POST',
                            data: requestData,
                            success: function(response) {
                                $('#rejectionModal').addClass('hidden');
                                resetRejectionForm();

                                Swal.fire({
                                    icon: 'success',
                                    title: '{{ __('success_title') }}',
                                    text: '{{ __('rejection_notifications_sent') }}',
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
                                    '{{ __('rejection_error') }}';

                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: '{{ __('error_occurred') }}',
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
                        $('input[name="rejection_reason"]').prop('checked', false);
                        $('#reason_other').val('');
                        $('#other_reason_container').addClass('hidden');
                    }

                    // Toggle other reason textarea visibility
                    $(document).on('change', 'input[name="rejection_reason"]', function() {
                        if ($(this).val() === 'other') {
                            $('#other_reason_container').removeClass('hidden');
                        } else {
                            $('#other_reason_container').addClass('hidden');
                        }
                    });

                    // Compartir ubicación desde el listado
                    $(document).on('click', '.share-location', function(e) {
                        e.preventDefault();

                        // Verificar si no hay coordenadas disponibles
                        if ($(this).data('no-coords')) {
                            Swal.fire({
                                icon: 'warning',
                                title: '{{ __('no_location_title') }}',
                                text: '{{ __('no_coordinates_edit_appointment') }}',
                                confirmButtonColor: '#3B82F6'
                            });
                            return;
                        }

                        const lat = $(this).data('lat');
                        const lng = $(this).data('lng');
                        const address = $(this).data('address');

                        if (!lat || !lng) {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __('error_title') }}',
                                text: '{{ __('no_coordinates_address') }}',
                                confirmButtonColor: '#3B82F6'
                            });
                            return;
                        }

                        const mapsUrl = `https://www.google.com/maps?q=${lat},${lng}`;

                        // Mostrar opciones de compartir
                        Swal.fire({
                            title: '{{ __('share_location_title') }}',
                            html: `
                        <p class="mb-4">${address}</p>
                        <div class="flex flex-wrap justify-center gap-2">
                            <a href="https://wa.me/?text=${encodeURIComponent('{{ __('location_for_inspection') }} ' + address + ' - ' + mapsUrl)}" target="_blank" class="px-3 py-2 bg-green-500 text-white text-sm font-medium rounded-md hover:bg-green-600">
                                {{ __('whatsapp') }}
                            </a>
                            <a href="mailto:?subject=${encodeURIComponent('{{ __('location_for_inspection') }}')}&body=${encodeURIComponent('{{ __('location_for_inspection') }} ' + address + '\n\n{{ __('view_google_maps') }} ' + mapsUrl)}" class="px-3 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                {{ __('email_share') }}
                            </a>
                            <a href="${mapsUrl}" target="_blank" class="px-3 py-2 bg-red-500 text-white text-sm font-medium rounded-md hover:bg-red-600">
                                {{ __('open_maps') }}
                            </a>
                            <button id="copy-map-link" class="px-3 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600">
                                {{ __('copy_link') }}
                            </button>
                        </div>
                    `,
                            showConfirmButton: false,
                            showCloseButton: true,
                            customClass: {
                                container: 'swal-wide'
                            }
                        });

                        // Copiar enlace
                        $(document).on('click', '#copy-map-link', function() {
                            navigator.clipboard.writeText(mapsUrl).then(() => {
                                $(this).text('{{ __('copied') }}');
                                setTimeout(() => {
                                    $(this).text('{{ __('copy_link') }}');
                                }, 2000);
                            });
                        });
                    });
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
    </div>
</x-app-layout>
