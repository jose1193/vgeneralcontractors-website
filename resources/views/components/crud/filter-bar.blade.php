@props([
    'entityName' => 'Item',
    'showSearchBar' => true,
    'showInactiveToggle' => true,
    'showPerPage' => true,
    'showExport' => true,
    'showDateRange' => true,
    'perPageOptions' => [5, 10, 15, 25, 50],
    'defaultPerPage' => 10,
    'addButtonId' => 'addEntityBtn',
    'searchId' => 'searchInput',
    'searchPlaceholder' => 'Search...',
    'showDeletedId' => 'showDeleted',
    'showDeletedLabel' => 'Show Inactive Items',
    'perPageId' => 'perPage',
    'createButtonId' => 'createBtn',
    'addNewLabel' => 'Add New',
    'exportId' => 'exportSelect',
    'exportLabel' => 'Export Data',
    'dateRangeStartId' => 'dateRangeStart',
    'dateRangeEndId' => 'dateRangeEnd',
    'managerName' => 'crudManager',
])

<div class="mb-6 backdrop-blur-sm bg-black/30 rounded-lg border border-white/10 shadow-lg shadow-purple-500/10">
    <!-- Main Filter Bar - Always Visible -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 p-4">
        <!-- Left Side: Search Input -->
        @if ($showSearchBar)
            <div class="relative w-full sm:flex-1 sm:max-w-md">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        viewBox="0 0 24 24" class="w-5 h-5 text-gray-400">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" id="{{ $searchId }}" placeholder="{{ $searchPlaceholder }}"
                    class="pl-10 pr-4 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent w-full text-sm text-white bg-black/50 border-white/10 backdrop-blur-sm placeholder-gray-400 transition-all duration-200">
            </div>
        @endif

        <!-- Right Side: Create Button & Filter Toggle -->
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <!-- Create Button -->
            <x-crud.button-create :id="$createButtonId" :label="$addNewLabel" :entity-name="$entityName" class="w-full sm:w-auto" />

            <!-- Advanced Filters Toggle Button -->
            @if ($showInactiveToggle || $showPerPage || $showExport || $showDateRange)
                <button id="toggleFilters" type="button"
                    class="inline-flex items-center justify-center px-3 py-2.5 border border-white/10 rounded-lg shadow-sm bg-black/50 text-white hover:bg-black/60 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200 backdrop-blur-sm w-full sm:w-auto text-center">
                    <span class="flex items-center justify-center w-full">
                        <svg id="filterIcon" class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                        </svg>
                        <span id="filterText" class="text-sm font-medium">{{ __('filters') }}</span>
                        <svg id="chevronIcon" class="w-4 h-4 ml-1.5 transform transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </button>
            @endif
        </div>
    </div>

    <!-- Collapsible Advanced Filters Section -->
    @if ($showInactiveToggle || $showPerPage || $showExport || $showDateRange)
        <div id="advancedFilters" class="hidden border-t border-white/10 bg-black/20 backdrop-blur-sm">
            <div class="p-4">
                <!-- Responsive Grid Layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-4">

                    <!-- Date Range Section - Full width on mobile, spans 2 columns on larger screens -->
                    @if ($showDateRange)
                        <div
                            class="md:col-span-2 lg:col-span-3 xl:col-span-2 flex flex-col items-center justify-center h-full">
                            <div class="space-y-3 w-full">
                                <label class="flex items-center gap-1 text-sm font-medium text-gray-300">
                                    📅 {{ __('date_range') }}
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <!-- Start Date -->
                                    <div class="relative">
                                        <label for="{{ $dateRangeStartId }}"
                                            class="sr-only">{{ __('start_date') }}</label>
                                        <div class="relative">
                                            <input type="text" id="{{ $dateRangeStartId }}" name="date_range_start"
                                                placeholder="{{ __('start_date') }}" readonly
                                                class="w-full pl-10 pr-4 py-2.5 text-sm text-white bg-black/50 border border-white/10 rounded-lg shadow-sm backdrop-blur-sm placeholder-gray-400 cursor-pointer hover:bg-black/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                                            <!-- Calendar Icon -->
                                            <span
                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                            <!-- Clear Button -->
                                            <button type="button" id="{{ $dateRangeStartId }}_clear"
                                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-white transition-colors duration-200 opacity-0 pointer-events-none">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- End Date -->
                                    <div class="relative">
                                        <label for="{{ $dateRangeEndId }}" class="sr-only">{{ __('end_date') }}</label>
                                        <div class="relative">
                                            <input type="text" id="{{ $dateRangeEndId }}" name="date_range_end"
                                                placeholder="{{ __('end_date') }}" readonly
                                                class="w-full pl-10 pr-4 py-2.5 text-sm text-white bg-black/50 border border-white/10 rounded-lg shadow-sm backdrop-blur-sm placeholder-gray-400 cursor-pointer hover:bg-black/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                                            <!-- Calendar Icon -->
                                            <span
                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                            <!-- Clear Button -->
                                            <button type="button" id="{{ $dateRangeEndId }}_clear"
                                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-white transition-colors duration-200 opacity-0 pointer-events-none">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif


                    <!-- Export Options (moved up) -->
                    @if ($showExport)
                        <div class="flex flex-col items-start justify-center h-full">
                            <label for="{{ $exportId }}"
                                class="flex items-center gap-1 text-sm font-medium text-gray-300 mb-2">
                                📋 {{ __('export_data') }}
                            </label>
                            <div class="relative w-full">
                                <select id="{{ $exportId }}"
                                    class="border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm py-2.5 px-3 w-full text-white bg-black/50 border-white/10 backdrop-blur-sm appearance-none cursor-pointer hover:bg-black/60 transition-all duration-200">
                                    <option value="" disabled selected>{{ __('choose_format') }}</option>
                                    <option value="pdf">📄 {{ __('pdf_report') }}</option>
                                    <option value="excel">📊 {{ __('excel') }}</option>
                                </select>
                                <!-- Dropdown Arrow -->
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                        class="w-4 h-4 text-gray-400">
                                        <path d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Per Page Selector -->
                    @if ($showPerPage)
                        <div class="flex flex-col">
                            <label for="{{ $perPageId }}" class="text-sm font-medium text-gray-300 mb-2">📄
                                {{ __('items_per_page') }}</label>
                            <select id="{{ $perPageId }}"
                                class="border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm py-2.5 px-3 text-white bg-black/50 border-white/10 backdrop-blur-sm transition-all duration-200 hover:bg-black/60">
                                @foreach ($perPageOptions as $option)
                                    <option value="{{ $option }}"
                                        {{ $option == $defaultPerPage ? 'selected' : '' }}>
                                        {{ $option }} {{ __('per_page') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Status Toggle Only, no label (moved down) -->
                    @if ($showInactiveToggle)
                        <div class="flex flex-col items-start justify-center h-full w-full lg:w-auto">
                            <x-crud.toggle-show-deleted :id="$showDeletedId" :label="$showDeletedLabel" :manager-name="$managerName" />
                        </div>
                    @endif
                </div>

                <!-- Clear Filters Button -->
                <div class="pt-6 border-t border-white/10 flex justify-end">
                    <button id="clearFilters" type="button"
                        class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-red-500/30 hover:bg-red-500/40 border border-red-400/30 rounded-lg shadow-lg backdrop-blur-md transition-all duration-200 hover:shadow-red-500/20 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:ring-offset-2 focus:ring-offset-transparent">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="font-medium">{{ __('clear_filters') }}</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Enhanced Flatpickr Styles -->
<style>
    /* Glassmorphic Flatpickr Theme */
    .flatpickr-calendar {
        background: rgba(0, 0, 0, 0.8) !important;
        backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 12px !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 30px rgba(147, 51, 234, 0.1) !important;
    }

    .flatpickr-calendar:before,
    .flatpickr-calendar:after {
        display: none !important;
    }

    .flatpickr-months {
        background: transparent !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    .flatpickr-month {
        background: transparent !important;
        color: white !important;
    }

    .flatpickr-current-month .flatpickr-monthDropdown-months {
        background: rgba(0, 0, 0, 0.8) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
    }

    .flatpickr-current-month .numInputWrapper {
        color: white !important;
    }

    .flatpickr-current-month input.cur-year {
        background: transparent !important;
        color: white !important;
    }

    .flatpickr-weekdays {
        background: transparent !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    .flatpickr-weekday {
        background: transparent !important;
        color: rgba(255, 255, 255, 0.7) !important;
        font-weight: 500 !important;
    }

    .flatpickr-days {
        background: transparent !important;
    }

    .flatpickr-day {
        background: transparent !important;
        color: white !important;
        border: 1px solid transparent !important;
        border-radius: 6px !important;
        margin: 1px !important;
    }

    .flatpickr-day:hover {
        background: rgba(147, 51, 234, 0.2) !important;
        border-color: rgba(147, 51, 234, 0.4) !important;
    }

    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange {
        background: rgba(147, 51, 234, 0.6) !important;
        border-color: rgba(147, 51, 234, 0.8) !important;
        color: white !important;
    }

    .flatpickr-day.inRange {
        background: rgba(147, 51, 234, 0.2) !important;
        border-color: rgba(147, 51, 234, 0.3) !important;
        color: white !important;
    }

    .flatpickr-day.today {
        border-color: rgba(147, 51, 234, 0.6) !important;
        color: rgba(147, 51, 234, 1) !important;
    }

    .flatpickr-day.today:hover {
        background: rgba(147, 51, 234, 0.2) !important;
        color: white !important;
    }

    .flatpickr-day.disabled {
        color: rgba(255, 255, 255, 0.3) !important;
    }

    .flatpickr-prev-month,
    .flatpickr-next-month {
        color: white !important;
        fill: white !important;
    }

    .flatpickr-prev-month:hover,
    .flatpickr-next-month:hover {
        color: rgba(147, 51, 234, 1) !important;
        fill: rgba(147, 51, 234, 1) !important;
    }

    /* Clear button visibility */
    .date-input-active .opacity-0 {
        opacity: 1 !important;
        pointer-events: auto !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Flatpickr for date range inputs
        let startDatePicker = null;
        let endDatePicker = null;

        // Initialize Start Date Picker
        const startDateInput = document.getElementById('{{ $dateRangeStartId }}');
        if (startDateInput) {
            startDatePicker = flatpickr(startDateInput, {
                dateFormat: "Y-m-d",
                placeholder: "{{ __('start_date') }}",
                allowInput: false,
                clickOpens: true,
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        // Update end date picker's minDate
                        if (endDatePicker) {
                            endDatePicker.set('minDate', selectedDates[0]);
                        }
                        // Show clear button
                        startDateInput.classList.add('date-input-active');
                    } else {
                        startDateInput.classList.remove('date-input-active');
                    }

                    // Trigger filter update
                    handleDateRangeChange();
                }
            });
        }

        // Initialize End Date Picker
        const endDateInput = document.getElementById('{{ $dateRangeEndId }}');
        if (endDateInput) {
            endDatePicker = flatpickr(endDateInput, {
                dateFormat: "Y-m-d",
                placeholder: "{{ __('end_date') }}",
                allowInput: false,
                clickOpens: true,
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        // Update start date picker's maxDate
                        if (startDatePicker) {
                            startDatePicker.set('maxDate', selectedDates[0]);
                        }
                        // Show clear button
                        endDateInput.classList.add('date-input-active');
                    } else {
                        endDateInput.classList.remove('date-input-active');
                    }

                    // Trigger filter update
                    handleDateRangeChange();
                }
            });
        }

        // Clear button functionality
        const startClearBtn = document.getElementById('{{ $dateRangeStartId }}_clear');
        if (startClearBtn) {
            startClearBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (startDatePicker) {
                    startDatePicker.clear();
                    if (endDatePicker) {
                        endDatePicker.set('minDate', null);
                    }
                }
                startDateInput.classList.remove('date-input-active');
                handleDateRangeChange();
            });
        }

        const endClearBtn = document.getElementById('{{ $dateRangeEndId }}_clear');
        if (endClearBtn) {
            endClearBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (endDatePicker) {
                    endDatePicker.clear();
                    if (startDatePicker) {
                        startDatePicker.set('maxDate', null);
                    }
                }
                endDateInput.classList.remove('date-input-active');
                handleDateRangeChange();
            });
        }

        // Handle date range changes
        function handleDateRangeChange() {
            const startDate = startDatePicker ? startDatePicker.selectedDates[0] : null;
            const endDate = endDatePicker ? endDatePicker.selectedDates[0] : null;

            console.log('Date range changed:', {
                start: startDate ? startDate.toISOString().split('T')[0] : null,
                end: endDate ? endDate.toISOString().split('T')[0] : null
            });

            // Add your date range filtering logic here
            // This function will be called whenever the date range changes
        }

        // Toggle Advanced Filters (existing code)
        const toggleButton = document.getElementById('toggleFilters');
        const advancedFilters = document.getElementById('advancedFilters');
        const chevronIcon = document.getElementById('chevronIcon');
        const filterText = document.getElementById('filterText');

        if (toggleButton && advancedFilters) {
            toggleButton.addEventListener('click', function() {
                const isHidden = advancedFilters.classList.contains('hidden');

                if (isHidden) {
                    // Show filters
                    advancedFilters.classList.remove('hidden');
                    advancedFilters.style.maxHeight = '0px';
                    advancedFilters.style.overflow = 'hidden';
                    advancedFilters.style.transition = 'max-height 0.3s ease-out';
                    filterText.textContent = @json(__('hide_filters'));

                    // Animate in
                    requestAnimationFrame(() => {
                        advancedFilters.style.maxHeight = advancedFilters.scrollHeight + 'px';
                    });

                    // Update button appearance
                    chevronIcon.style.transform = 'rotate(180deg)';
                    filterText.textContent = 'Hide Filters';
                    toggleButton.classList.add('bg-purple-600/20', 'border-purple-500/30');
                } else {
                    // Hide filters
                    advancedFilters.style.maxHeight = '0px';
                    filterText.textContent = @json(__('filters'));
                    setTimeout(() => {
                        advancedFilters.classList.add('hidden');
                        advancedFilters.style.removeProperty('max-height');
                        advancedFilters.style.removeProperty('overflow');
                        advancedFilters.style.removeProperty('transition');
                    }, 300);

                    // Update button appearance
                    chevronIcon.style.transform = 'rotate(0deg)';
                    filterText.textContent = 'Filters';
                    toggleButton.classList.remove('bg-purple-600/20', 'border-purple-500/30');
                }
            });
        }

        // Enhanced Clear Filters Functionality
        const clearFiltersBtn = document.getElementById('clearFilters');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function() {
                // Reset search input
                const searchInput = document.getElementById('{{ $searchId }}');
                if (searchInput) searchInput.value = '';

                // Reset per page selector
                const perPageSelect = document.getElementById('{{ $perPageId }}');
                if (perPageSelect) perPageSelect.selectedIndex = 0;

                // Reset export dropdown
                const exportSelect = document.getElementById('{{ $exportId }}');
                if (exportSelect) exportSelect.selectedIndex = 0;

                // Reset show deleted toggle
                const showDeletedToggle = document.getElementById('{{ $showDeletedId }}');
                if (showDeletedToggle && showDeletedToggle.type === 'checkbox') {
                    showDeletedToggle.checked = false;
                }

                // Reset date range pickers
                if (startDatePicker) {
                    startDatePicker.clear();
                    startDatePicker.set('maxDate', null);
                }
                if (endDatePicker) {
                    endDatePicker.clear();
                    endDatePicker.set('minDate', null);
                }

                // Remove active states
                startDateInput?.classList.remove('date-input-active');
                endDateInput?.classList.remove('date-input-active');

                // Show success message
                showClearNotification();
            });
        }

        // Export Functionality (existing code)
        const exportSelect = document.getElementById('{{ $exportId }}');
        if (exportSelect) {
            exportSelect.addEventListener('change', function() {
                const selectedValue = this.value;

                if (selectedValue) {
                    // Add loading state
                    const originalHTML = this.innerHTML;
                    this.innerHTML = '<option value="" disabled selected>' +
                        @json(__('exporting')) + '</option>';
                    this.disabled = true;

                    // Simulate export process
                    setTimeout(() => {
                        // Reset dropdown
                        this.innerHTML = originalHTML;
                        this.disabled = false;
                        this.selectedIndex = 0;

                        // Show success message
                        showExportNotification(selectedValue);
                        handleExport(selectedValue);
                    }, 1000);
                }
            });
        }

        // Notification Functions (existing code)
        function showExportNotification(format) {
            let message = '';
            if (format === 'pdf') {
                message = @json(__('pdf_export_completed'));
            } else if (format === 'excel') {
                message = @json(__('excel_export_completed'));
            } else {
                message = format.toUpperCase() + ' ' + @json(__('export_completed_successfully'));
            }
            showNotification(message, 'success');
        }

        function showClearNotification() {
            showNotification(@json(__('filters_cleared')), 'info');
        }

        function showNotification(message, type = 'success') {
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-blue-500';
            const notification = document.createElement('div');
            notification.className =
                `fixed top-4 right-4 ${bgColor} text-white px-4 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 max-w-sm`;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Animate in
            requestAnimationFrame(() => {
                notification.style.transform = 'translateY(0)';
                notification.style.opacity = '1';
            });

            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-100%)';
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        function handleExport(format) {
            console.log(`Exporting data as ${format}`);
            // Add your actual export logic here
        }
    });
</script>
