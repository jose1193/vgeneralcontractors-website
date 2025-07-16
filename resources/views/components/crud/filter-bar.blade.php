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

<div
    class="mb-5 flex flex-col lg:flex-row justify-between items-center backdrop-blur-sm bg-black/30 p-4 rounded-lg border border-white/10 shadow-lg shadow-purple-500/10">
    @if ($showSearchBar)
        <!-- Search Input -->
        <div class="relative w-full lg:w-1/3 mb-3 lg:mb-0">
            <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    viewBox="0 0 24 24" class="w-6 h-6 text-gray-400">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" id="{{ $searchId }}" placeholder="{{ $searchPlaceholder }}"
                class="pl-10 pr-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent w-full text-sm text-white bg-black/50 border-white/10 backdrop-blur-sm">
        </div>
    @endif

    <!-- Controls: Show Deleted, Per Page, Export, Add Button -->
    <div class="flex flex-col lg:flex-row items-center w-full lg:w-auto gap-3 lg:gap-4">
        <!-- Add Entity Button -->
        <div class="w-full lg:w-auto">
            <x-crud.button-create :id="$createButtonId" :label="$addNewLabel" :entity-name="$entityName" class="w-full sm:w-auto" />
        </div>

        <!-- Show Deleted Toggle: below button in mobile, inline in lg -->
        @if ($showInactiveToggle)
            <div class="w-full lg:w-auto order-2 lg:order-none">
                <x-crud.toggle-show-deleted :id="$showDeletedId" :label="$showDeletedLabel" :manager-name="$managerName"
                    class="w-full lg:w-auto" />
            </div>
        @endif

        <!-- Per Page Selector -->
        @if ($showPerPage)
            <div class="w-full lg:w-auto">
                <select id="{{ $perPageId }}"
                    class="border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm py-2 px-3 pr-8 min-w-[70px] w-full lg:w-auto text-white bg-black/50 border-white/10 backdrop-blur-sm">
                    @foreach ($perPageOptions as $option)
                        <option value="{{ $option }}" {{ $option == $defaultPerPage ? 'selected' : '' }}>
                            {{ $option }} {{ __('per_page') }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Export Dropdown -->
        @if ($showExport)
            <div class="relative w-full lg:w-auto">
                <select id="{{ $exportId }}"
                    class="border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm py-2 pl-10 pr-8 min-w-[140px] w-full lg:w-auto text-white bg-black/50 border-white/10 backdrop-blur-sm appearance-none cursor-pointer hover:bg-black/60 transition-colors duration-200">
                    <option value="" disabled selected>{{ __('export_label') }}</option>
                    <option value="pdf">📄 {{ __('pdf_report') }}</option>
                    <option value="excel">📊 {{ __('excel') }}</option>
                </select>
                <!-- Export Icon -->
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" class="w-4 h-4 text-gray-400">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4m4-5l5-5 5 5m-5-5v12"></path>
                    </svg>
                </span>
                <!-- Dropdown Arrow -->
                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" class="w-4 h-4 text-gray-400">
                        <path d="M19 9l-7 7-7-7"></path>
                    </svg>
                </span>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

                    // Simulate export process (replace with actual export logic)
                    setTimeout(() => {
                        // Reset dropdown
                        this.innerHTML = originalHTML;
                        this.disabled = false;
                        this.selectedIndex = 0;

                        // Show success message (you can replace this with your preferred notification system)
                        showExportNotification(selectedValue);

                        // Here you would typically call your export function
                        handleExport(selectedValue);
                    }, 1000);
                }
            });
        }

        function showExportNotification(format) {
            // Traducción dinámica del mensaje según formato
            let message = '';
            if (format === 'pdf') {
                message = @json(__('pdf_export_completed'));
            } else if (format === 'excel') {
                message = @json(__('excel_export_completed'));
            } else {
                message = format.toUpperCase() + ' ' + @json(__('export_completed_successfully'));
            }
            const notification = document.createElement('div');
            notification.className =
                'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg z-50 transform transition-all duration-300';
            notification.textContent = message;

            document.body.appendChild(notification);

            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        function handleExport(format) {
            // Replace this with your actual export logic
            console.log(`Exporting data as ${format}`);

            // Example: You might call different endpoints based on format
            if (format === 'pdf') {
                // window.open('/export/pdf', '_blank');
            } else if (format === 'excel') {
                // window.open('/export/excel', '_blank');
            }
        }
    });
</script>
