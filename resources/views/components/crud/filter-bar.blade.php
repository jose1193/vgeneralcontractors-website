@props([
    'entityName' => 'Item',
    'showSearchBar' => true,
    'showInactiveToggle' => true,
    'showPerPage' => true,
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
    'managerName' => 'crudManager',
])

<div class="mb-5 flex flex-col sm:flex-row justify-between items-center">
    @if ($showSearchBar)
        <!-- Search Input -->
        <div class="relative w-full sm:w-1/3 mb-3 sm:mb-0">
            <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    viewBox="0 0 24 24" class="w-6 h-6 text-gray-400">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" id="{{ $searchId }}" placeholder="{{ $searchPlaceholder }}"
                class="pl-10 pr-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full text-sm text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600">
        </div>
    @endif

    <!-- Controls: Show Deleted, Per Page, Add Button -->
    <div class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
        @if ($showInactiveToggle)
            <!-- Show Deleted Toggle -->
            <x-crud.toggle-show-deleted :id="$showDeletedId" :label="$showDeletedLabel" :manager-name="$managerName" />
        @endif

        @if ($showPerPage)
            <!-- Per Page Selector -->
            <div class="flex items-center justify-end sm:justify-start w-full sm:w-auto">
                <label for="{{ $perPageId }}" class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300">Per
                    page:</label>
                <select id="{{ $perPageId }}"
                    class="border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm py-2 px-2 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600">
                    @foreach ($perPageOptions as $option)
                        <option value="{{ $option }}" {{ $option == $defaultPerPage ? 'selected' : '' }}>
                            {{ $option }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Add Entity Button -->
        <x-crud.button-create :id="$createButtonId" :label="$addNewLabel" :entity-name="$entityName" />
    </div>
</div>
