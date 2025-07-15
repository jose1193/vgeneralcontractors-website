@props([
    'title',
    'subtitle',
    'entityName',
    'entityNamePlural',
    'searchPlaceholder',
    'showDeletedLabel',
    'addNewLabel',
    'managerName',
    'tableColumns' => [],
    'tableId' => 'crud-table',
    'createButtonId' => 'createBtn',
    'searchId' => 'searchInput',
    'showDeletedId' => 'showDeleted',
    'perPageId' => 'perPage',
    'paginationId' => 'pagination',
    'alertId' => 'alertContainer',
])

{{-- Estilos del modal CRUD ahora están incluidos en app.css --}}

<x-app-layout>
    {{-- Dark background container with consistent styling --}}
    <div class="min-h-screen bg-gray-900" style="background-color: #141414;">
        {{-- Header section with title and subtitle --}}
        <div class="p-4 sm:p-6">
            <div class="mb-4 sm:mb-8 text-center sm:text-center md:text-left lg:text-left">
                <h2 class="text-base sm:text-base md:text-2xl lg:text-2xl font-bold text-white mb-2">
                    {{ $title }}
                </h2>
                <p class="text-base sm:text-base md:text-base lg:text-base text-gray-400">
                    {{ $subtitle }}
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-2 sm:py-4 md:py-2 lg:py-2 px-4 sm:px-6 lg:px-8">
            <!-- Success and error messages -->
            <div id="{{ $alertId }}"></div>
            @if (session()->has('message'))
                <x-crud.alert type="success" :message="session('message')" />
            @endif
            @if (session()->has('error'))
                <x-crud.alert type="error" :message="session('error')" />
            @endif

            <!-- Filter and action bar (outside the main container) -->
            <div class="mb-6">
                <x-crud.filter-bar :search-id="$searchId" :search-placeholder="$searchPlaceholder" :show-deleted-id="$showDeletedId" :show-deleted-label="$showDeletedLabel"
                    :per-page-id="$perPageId" :create-button-id="$createButtonId" :add-new-label="$addNewLabel" :manager-name="$managerName" />
            </div>

            <!-- Table with enhanced border -->
            <div class="mb-6">
                <x-crud.glass-advanced-table :id="$tableId" :columns="$tableColumns" :manager-name="$managerName" />
            </div>

            <!-- Pagination -->
            <div id="{{ $paginationId }}" class="mt-4 flex justify-between items-center text-white/80"></div>
        </div>

        {{ $slot }}
    </div>
</x-app-layout>
