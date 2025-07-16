@props([
    'entityName' => 'Item',
    'showSearchBar' => true,
    'showInactiveToggle' => true,
    'showPerPage' => true,
    'showExport' => true,
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
    'managerName' => 'crudManager',
])

<div class="mb-5 backdrop-blur-sm bg-black/30 rounded-lg border border-white/10 shadow-lg shadow-purple-500/10">
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
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <!-- Create Button -->
            <x-crud.button-create :id="$createButtonId" :label="$addNewLabel" :entity-name="$entityName" class="flex-1 sm:flex-none" />

            <!-- Advanced Filters Toggle Button -->
            @if ($showInactiveToggle || $showPerPage || $showExport)
                <button id="toggleFilters" type="button"
                    class="inline-flex items-center px-3 py-2.5 border border-white/10 rounded-lg shadow-sm bg-black/50 text-white hover:bg-black/60 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200 backdrop-blur-sm">
                    <svg id="filterIcon" class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                    </svg>
                    <span id="filterText" class="text-sm font-medium">Filters</span>
                    <svg id="chevronIcon" class="w-4 h-4 ml-1.5 transform transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            @endif
        </div>
    </div>

    <!-- Collapsible Advanced Filters Section -->
    @if ($showInactiveToggle || $showPerPage || $showExport)
        <div id="advancedFilters" class="hidden border-t border-white/10 bg-black/20 backdrop-blur-sm">
            <div class="p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Show Inactive Toggle -->
                    @if ($showInactiveToggle)
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-300 mb-2">Status Filter</label>
                            <x-crud.toggle-show-deleted :id="$showDeletedId" :label="$showDeletedLabel" :manager-name="$managerName" />
                        </div>
                    @endif

                    <!-- Per Page Selector -->
                    @if ($showPerPage)
                        <div class="flex flex-col">
                            <label for="{{ $perPageId }}" class="text-sm font-medium text-gray-300 mb-2">Items per
                                page</label>
                            <select id="{{ $perPageId }}"
                                class="border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm py-2.5 px-3 text-white bg-black/50 border-white/10 backdrop-blur-sm transition-all duration-200">
                                @foreach ($perPageOptions as $option)
                                    <option value="{{ $option }}"
                                        {{ $option == $defaultPerPage ? 'selected' : '' }}>
                                        {{ $option }} {{ __('per_page') }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Export Options -->
                    @if ($showExport)
                        <div class="flex flex-col">
                            <label for="{{ $exportId }}"
                                class="text-sm font-medium text-gray-300 mb-2">{{ $exportLabel }}</label>
                            <div class="relative">
                                <select id="{{ $exportId }}"
                                    class="border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm py-2.5 pl-10 pr-8 w-full text-white bg-black/50 border-white/10 backdrop-blur-sm appearance-none cursor-pointer hover:bg-black/60 transition-all duration-200">
                                    <option value="" disabled selected>Choose format...</option>
                                    <option value="pdf">📄 {{ __('pdf_report') }}</option>
                                    <option value="excel">📊 {{ __('excel') }}</option>
                                </select>
                                <!-- Export Icon -->
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                        class="w-4 h-4 text-gray-400">
                                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4m4-5l5-5 5 5m-5-5v12"></path>
                                    </svg>
                                </span>
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
                </div>

                <!-- Clear Filters Button -->
                <div class="mt-4 pt-3 border-t border-white/10">
                    <button id="clearFilters" type="button"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 hover:text-white transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear all filters
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Advanced Filters
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

        // Clear Filters Functionality
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

                // Reset show deleted toggle (assuming it's a checkbox)
                const showDeletedToggle = document.getElementById('{{ $showDeletedId }}');
                if (showDeletedToggle && showDeletedToggle.type === 'checkbox') {
                    showDeletedToggle.checked = false;
                }

                // Show success message
                showClearNotification();
            });
        }

        // Export Functionality (from original code)
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

        // Notification Functions
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
            showNotification('All filters have been cleared', 'info');
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
