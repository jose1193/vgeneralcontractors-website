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
    {{-- Glassmorphic background container --}}
    <div class="min-h-screen bg-black p-8 flex flex-col items-center justify-start">
        {{-- Header section with title and subtitle --}}
        <div class="w-full max-w-7xl mb-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-white mb-2">
                    {{ $title }}
                </h2>
                <p class="text-lg text-white/70">
                    {{ $subtitle }}
                </p>
            </div>
        </div>

        <div class="w-full max-w-7xl">
            <!-- Success and error messages -->
            <div id="{{ $alertId }}"></div>
            @if (session()->has('message'))
                <x-crud.alert type="success" :message="session('message')" />
            @endif
            @if (session()->has('error'))
                <x-crud.alert type="error" :message="session('error')" />
            @endif

            <!-- Filter and action bar - separated as normal div -->
            <div class="bg-black/60 backdrop-blur-md border border-white/10 rounded-lg p-6 mb-6 shadow-lg">
                <x-crud.filter-bar :search-id="$searchId" :search-placeholder="$searchPlaceholder" :show-deleted-id="$showDeletedId" :show-deleted-label="$showDeletedLabel"
                    :per-page-id="$perPageId" :create-button-id="$createButtonId" :add-new-label="$addNewLabel" :manager-name="$managerName" />
            </div>

            <!-- Glassmorphic Table Container -->
            <div class="relative overflow-hidden rounded-[5px]">
                <!-- Animated border wrapper -->
                <div class="absolute inset-0 rounded-[5px] p-[3px] animate-border-glow">
                    <!-- Gradient border -->
                    <div class="absolute inset-0 rounded-[5px] bg-gradient-to-r from-yellow-400 via-purple-500 via-orange-500 to-yellow-400 bg-[length:300%_300%] animate-gradient-border opacity-80"></div>
                    <!-- Inner container -->
                    <div class="relative w-full h-full bg-black/90 backdrop-blur-xl rounded-[2px] border border-white/5">
                        <!-- Table container with glassmorphic effect -->
                        <div class="relative backdrop-blur-xl bg-black/40 border-0 rounded-[2px] overflow-hidden m-[3px] animate-table-shadow glassmorphic-table">
                            <div class="p-6">
                                <!-- Table -->
                                <x-crud.advanced-table :id="$tableId" :columns="$tableColumns" :manager-name="$managerName" />

                                <!-- Pagination -->
                                <div id="{{ $paginationId }}" class="mt-4 flex justify-between items-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{ $slot }}
    </div>
</x-app-layout>
