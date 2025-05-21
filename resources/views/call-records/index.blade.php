<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Call Records') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700 dark:bg-red-800 dark:text-red-200"
                    role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header Controls Row -->
                    <div class="flex flex-col sm:flex-row items-start justify-between mb-8 space-y-6 sm:space-y-0">
                        <!-- Left side - Search + Date Range -->
                        <div class="flex flex-col w-full sm:w-auto space-y-4">
                            <!-- Search Input -->
                            <div class="relative w-full sm:w-80">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" id="search-input" placeholder="Search..."
                                    class="w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <button id="clear-search"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Date Range Button -->
                            <div class="flex items-center space-x-2">
                                <button id="date-range-btn"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span id="date-range-text">Date Range</span>
                                </button>
                                <button id="clear-date-range"
                                    class="hidden inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                    <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="ml-1">Clear</span>
                                </button>
                                <input type="hidden" id="start-date-input">
                                <input type="hidden" id="end-date-input">
                            </div>
                        </div>

                        <!-- Right side - Per Page + Refresh -->
                        <div class="flex items-center space-x-4 w-full sm:w-auto justify-end">
                            <div class="relative w-48">
                                <select id="per-page"
                                    class="appearance-none block w-full py-2 px-3 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pr-8">
                                    <option value="10">10 per page</option>
                                    <option value="25">25 per page</option>
                                    <option value="50">50 per page</option>
                                    <option value="100">100 per page</option>
                                </select>

                            </div>

                            <button id="refresh-btn"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                REFRESH LIST
                            </button>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="overflow-x-auto relative">
                        <!-- Loading Indicator positioned over the table -->
                        <div id="loading-indicator"
                            class="absolute inset-0 flex justify-center items-center bg-white bg-opacity-75 z-10 hidden">
                            <svg class="animate-spin h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr class="text-center bg-gray-50 dark:bg-gray-800">
                                    <th data-sort="start_timestamp"
                                        class="sort-header px-6 py-3 text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer bg-gray-100 dark:bg-gray-700">
                                        Date/Time <span class="sort-icon"></span>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-700">
                                        From
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-700">
                                        To
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-700">
                                        Duration
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-700">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-700">
                                        Sentiment
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-700">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="calls-table-body"
                                class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                <!-- Table rows will be inserted here by JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls -->
                    <div class="mt-4 flex justify-between items-center">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Showing <span id="pagination-from">0</span> to <span id="pagination-to">0</span> of <span
                                id="pagination-total">0</span> results
                        </div>
                        <div class="flex space-x-2">
                            <button id="prev-page"
                                class="px-3 py-1 rounded-md border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                Previous
                            </button>
                            <button id="next-page"
                                class="px-3 py-1 rounded-md border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call Details Modal -->
    <div id="call-details-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50 hidden">
        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div class="pointer-events-auto w-screen max-w-2xl">
                        <div class="flex h-full flex-col bg-white dark:bg-gray-900 shadow-xl">
                            <div class="px-4 py-6 sm:px-6">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Call Details</h2>
                                    <button id="close-modal-btn"
                                        class="rounded-md bg-white dark:bg-gray-900 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <span class="sr-only">Close panel</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="relative mt-6 flex-1 px-4 sm:px-6 overflow-y-auto max-h-[calc(100vh-5rem)]"
                                id="modal-content">
                                <!-- Modal content will be inserted here by JavaScript -->
                                <div id="modal-loading" class="flex justify-center my-8">
                                    <svg class="animate-spin h-8 w-8 text-indigo-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                                <div id="modal-details" class="space-y-6 hidden">
                                    <!-- Details will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // State variables
                let currentPage = 1;
                let perPage = 10;
                let sortField = 'start_timestamp';
                let sortDirection = 'desc';
                let searchTerm = '';
                let startDate = '';
                let endDate = '';

                // DOM elements
                const tableBody = document.getElementById('calls-table-body');
                const prevPageBtn = document.getElementById('prev-page');
                const nextPageBtn = document.getElementById('next-page');
                const searchInput = document.getElementById('search-input');
                const clearSearchBtn = document.getElementById('clear-search');
                const perPageSelect = document.getElementById('per-page');
                const refreshBtn = document.getElementById('refresh-btn');
                const loadingIndicator = document.getElementById('loading-indicator');
                const paginationFrom = document.getElementById('pagination-from');
                const paginationTo = document.getElementById('pagination-to');
                const paginationTotal = document.getElementById('pagination-total');
                const sortHeaders = document.querySelectorAll('.sort-header');
                const callDetailsModal = document.getElementById('call-details-modal');
                const closeModalBtn = document.getElementById('close-modal-btn');
                const modalLoading = document.getElementById('modal-loading');
                const modalDetails = document.getElementById('modal-details');
                const startDateInput = document.getElementById('start-date-input');
                const endDateInput = document.getElementById('end-date-input');
                const dateRangeBtn = document.getElementById('date-range-btn');
                const clearDateRangeBtn = document.getElementById('clear-date-range');

                // Initialize flatpickr calendar dropdown
                const picker = flatpickr(dateRangeBtn, {
                    mode: "range",
                    dateFormat: "Y-m-d",
                    showMonths: 1,
                    static: true,
                    altInput: false,
                    maxDate: "today",
                    onChange: function(selectedDates, dateStr) {
                        if (selectedDates.length === 2) {
                            startDate = selectedDates[0].toISOString().split('T')[0];
                            endDate = selectedDates[1].toISOString().split('T')[0];

                            // Update hidden inputs
                            startDateInput.value = startDate;
                            endDateInput.value = endDate;

                            // Update button text with selected range
                            const startFormatted = selectedDates[0].toLocaleDateString();
                            const endFormatted = selectedDates[1].toLocaleDateString();
                            const dateRangeText = document.getElementById('date-range-text');
                            dateRangeText.textContent = `${startFormatted} - ${endFormatted}`;

                            // Show the clear button when date range is selected
                            clearDateRangeBtn.classList.remove('hidden');

                            // Reset to page 1 and fetch calls
                            currentPage = 1;
                            fetchCalls();
                        }
                    }
                });

                // Clear date filter
                clearDateRangeBtn.addEventListener('click', function() {
                    picker.clear();
                    startDate = '';
                    endDate = '';
                    startDateInput.value = '';
                    endDateInput.value = '';
                    const dateRangeText = document.getElementById('date-range-text');
                    dateRangeText.textContent = 'Date Range';

                    // Hide the clear button when date range is cleared
                    clearDateRangeBtn.classList.add('hidden');

                    currentPage = 1;
                    fetchCalls();
                });

                // Clear search input
                clearSearchBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    searchTerm = '';
                    currentPage = 1;
                    fetchCalls();
                });

                // Format phone numbers
                function formatPhoneNumber(phoneNumber) {
                    if (!phoneNumber) return 'N/A';

                    // Strip non-numeric characters
                    const cleaned = ('' + phoneNumber).replace(/\D/g, '');

                    // Check if the number has 10 digits (standard US phone number)
                    if (cleaned.length === 10) {
                        return `(${cleaned.substring(0, 3)}) ${cleaned.substring(3, 6)}-${cleaned.substring(6, 10)}`;
                    } else if (cleaned.length === 11 && cleaned.charAt(0) === '1') {
                        // Handle US numbers with country code
                        return `(${cleaned.substring(1, 4)}) ${cleaned.substring(4, 7)}-${cleaned.substring(7, 11)}`;
                    }

                    // Return the original number if format doesn't match expected patterns
                    return phoneNumber;
                }

                // Format timestamp
                function formatTimestamp(timestamp) {
                    if (!timestamp) return 'N/A';
                    const date = new Date(timestamp * 1000);
                    return date.toISOString().replace('T', ' ').substring(0, 19);
                }

                // Format duration
                function formatDuration(durationMs) {
                    if (!durationMs) return 'N/A';
                    return Math.round(durationMs / 1000) + 's';
                }

                // Fetch calls from API
                function fetchCalls() {
                    showLoading(true);

                    const params = new URLSearchParams({
                        page: currentPage,
                        per_page: perPage,
                        sort_field: sortField,
                        sort_direction: sortDirection,
                    });

                    if (searchTerm) {
                        params.append('search', searchTerm);
                    }

                    if (startDate && endDate) {
                        params.append('start_date', startDate);
                        params.append('end_date', endDate);
                    }

                    fetch(`/api/call-records?${params.toString()}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            renderTable(data);
                            updatePagination(data);
                            showLoading(false);
                        })
                        .catch(error => {
                            console.error('Error fetching calls:', error);
                            tableBody.innerHTML = `
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-red-500">
                                    Error loading calls: ${error.message}
                                </td>
                            </tr>
                        `;
                            showLoading(false);
                        });
                }

                // Render table with data
                function renderTable(data) {
                    if (!data.calls || data.calls.length === 0) {
                        tableBody.innerHTML = `
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No call records found
                            </td>
                        </tr>
                    `;
                        return;
                    }

                    let html = '';

                    data.calls.forEach(call => {
                        const callSuccessful = call.call_analysis && call.call_analysis.call_successful;
                        const sentiment = call.call_analysis && call.call_analysis.user_sentiment ? call
                            .call_analysis.user_sentiment : null;

                        html += `
                        <tr class="text-center">
                            <td class="px-6 py-4 whitespace-nowrap">
                                ${call.start_timestamp ? formatTimestamp(call.start_timestamp) : 'N/A'}
                            </td>
                            <td class="px-6 py-4">
                                ${formatPhoneNumber(call.from_number)}
                            </td>
                            <td class="px-6 py-4">
                                ${formatPhoneNumber(call.to_number)}
                            </td>
                            <td class="px-6 py-4">
                                ${call.duration_ms ? formatDuration(call.duration_ms) : 'N/A'}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${callSuccessful ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${call.call_status || 'Unknown'}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    ${sentiment === 'Positive' ? 'bg-green-100 text-green-800' : 
                                    sentiment === 'Negative' ? 'bg-red-100 text-red-800' : 
                                    'bg-gray-100 text-gray-800'}">
                                    ${sentiment || 'Unknown'}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex justify-center space-x-4">
                                    <button data-call-id="${call.call_id}" class="view-details-btn text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        View Details
                                    </button>
                                    ${call.recording_url ? `
                                                                                                                                                                                                                                                                <a href="${call.recording_url}" target="_blank" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                                                                                                                                                                                                                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                                                                                                                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                                                                                                                                                                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                                                                                                                                                                                                                    </svg>
                                                                                                                                                                                                                                                                </a>
                                                                                                                                                                                                                                                            ` : ''}
                                </div>
                            </td>
                        </tr>
                    `;
                    });

                    tableBody.innerHTML = html;

                    // Add event listeners to view details buttons
                    document.querySelectorAll('.view-details-btn').forEach(button => {
                        button.addEventListener('click', function() {
                            const callId = this.getAttribute('data-call-id');
                            showCallDetails(callId);
                        });
                    });
                }

                // Update pagination information
                function updatePagination(data) {
                    const from = data.total === 0 ? 0 : (data.current_page - 1) * data.per_page + 1;
                    const to = Math.min(data.current_page * data.per_page, data.total);

                    paginationFrom.textContent = from;
                    paginationTo.textContent = to;
                    paginationTotal.textContent = data.total;

                    prevPageBtn.disabled = data.current_page <= 1;
                    nextPageBtn.disabled = data.current_page >= data.last_page;
                }

                // Show/hide loading indicator
                function showLoading(show) {
                    if (show) {
                        loadingIndicator.classList.remove('hidden');
                    } else {
                        loadingIndicator.classList.add('hidden');
                    }
                }

                // Show call details
                function showCallDetails(callId) {
                    callDetailsModal.classList.remove('hidden');
                    modalLoading.classList.remove('hidden');
                    modalDetails.classList.add('hidden');

                    fetch(`/api/call-records/${callId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            renderCallDetails(data.call);
                            modalLoading.classList.add('hidden');
                            modalDetails.classList.remove('hidden');
                        })
                        .catch(error => {
                            console.error('Error fetching call details:', error);
                            modalDetails.innerHTML = `
                            <div class="text-red-500 text-center">
                                Error loading call details: ${error.message}
                            </div>
                        `;
                            modalLoading.classList.add('hidden');
                            modalDetails.classList.remove('hidden');
                        });
                }

                // Render call details in modal
                function renderCallDetails(call) {
                    let html = `
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">From</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                ${formatPhoneNumber(call.from_number)}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">To</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                ${formatPhoneNumber(call.to_number)}
                            </p>
                        </div>
                    </div>
                `;

                    if (call.call_analysis && call.call_analysis.call_summary) {
                        html += `
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Summary</h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                ${call.call_analysis.call_summary}
                            </p>
                        </div>
                    `;
                    }

                    if (call.transcript) {
                        html += `
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Transcript</h3>
                            <div class="mt-2 space-y-4">
                                ${call.transcript.split('\n').map(line => `
                                                                                                                                                                                                                                                            <p class="text-sm text-gray-500 dark:text-gray-400">${line}</p>
                                                                                                                                                                                                                                                        `).join('')}
                            </div>
                        </div>
                    `;
                    }

                    if (call.metadata && Object.keys(call.metadata).length > 0) {
                        html += `
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Additional Information</h3>
                            <dl class="mt-2 space-y-2">
                                ${Object.entries(call.metadata).map(([key, value]) => `
                                                                                                                                                                                                                                                            <div>
                                                                                                                                                                                                                                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                                                                                                                                                                                                                                    ${key.charAt(0).toUpperCase() + key.slice(1).replace(/_/g, ' ')}
                                                                                                                                                                                                                                                                </dt>
                                                                                                                                                                                                                                                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                                                                                                                                                                                                                                    ${typeof value === 'object' ? JSON.stringify(value) : value}
                                                                                                                                                                                                                                                                </dd>
                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                        `).join('')}
                            </dl>
                        </div>
                    `;
                    }

                    modalDetails.innerHTML = html;
                }

                // Event Listeners
                prevPageBtn.addEventListener('click', function() {
                    if (currentPage > 1) {
                        currentPage--;
                        fetchCalls();
                    }
                });

                nextPageBtn.addEventListener('click', function() {
                    currentPage++;
                    fetchCalls();
                });

                searchInput.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') {
                        searchTerm = this.value.trim();
                        currentPage = 1;
                        fetchCalls();
                    }
                });

                perPageSelect.addEventListener('change', function() {
                    perPage = parseInt(this.value);
                    currentPage = 1;
                    fetchCalls();
                });

                refreshBtn.addEventListener('click', function() {
                    fetchCalls();
                });

                sortHeaders.forEach(header => {
                    header.addEventListener('click', function() {
                        const field = this.getAttribute('data-sort');

                        if (sortField === field) {
                            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
                        } else {
                            sortField = field;
                            sortDirection = 'asc';
                        }

                        // Update sort icons
                        document.querySelectorAll('.sort-icon').forEach(icon => {
                            icon.textContent = '';
                        });

                        const sortIcon = this.querySelector('.sort-icon');
                        sortIcon.textContent = sortDirection === 'asc' ? '↑' : '↓';

                        currentPage = 1;
                        fetchCalls();
                    });
                });

                closeModalBtn.addEventListener('click', function() {
                    callDetailsModal.classList.add('hidden');
                });

                // Close modal when clicking outside
                callDetailsModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        callDetailsModal.classList.add('hidden');
                    }
                });

                // Initial load
                fetchCalls();
            });
        </script>
    @endpush
</x-app-layout>
