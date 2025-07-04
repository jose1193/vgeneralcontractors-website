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

            <!-- Main container -->
            <div class="backdrop-blur-lg bg-white/10 dark:bg-gray-800/20 shadow-2xl rounded-xl border border-white/20 dark:border-gray-600/30 animate-container-glow">
                <div class="p-6 bg-gradient-to-br from-white/5 to-transparent rounded-xl">
                    <!-- Filter and action bar -->
                    <x-crud.filter-bar :search-id="$searchId" :search-placeholder="$searchPlaceholder" :show-deleted-id="$showDeletedId" :show-deleted-label="$showDeletedLabel"
                        :per-page-id="$perPageId" :create-button-id="$createButtonId" :add-new-label="$addNewLabel" :manager-name="$managerName" />

                    <!-- Table -->
                    <x-crud.advanced-table :id="$tableId" :columns="$tableColumns" :manager-name="$managerName" />

                    <!-- Pagination -->
                    <div id="{{ $paginationId }}" class="mt-4 flex justify-between items-center"></div>
                </div>
            </div>
        </div>

        {{ $slot }}
    </div>
</x-app-layout>

@push('styles')
    <style>
        /* Glassmorphic Container Animations */
        @keyframes container-glow {
            0%, 100% {
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1),
                           0 0 0 1px rgba(255, 255, 255, 0.1),
                           inset 0 1px 0 rgba(255, 255, 255, 0.1);
            }
            50% {
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15),
                           0 0 0 1px rgba(255, 255, 255, 0.2),
                           inset 0 1px 0 rgba(255, 255, 255, 0.2);
            }
        }

        /* Animation Classes */
        .animate-container-glow {
            animation: container-glow 4s ease-in-out infinite;
        }

        /* Enhanced backdrop blur for better glassmorphic effect */
        .backdrop-blur-lg {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
        }

        /* Dark mode adjustments */
        @media (prefers-color-scheme: dark) {
            .animate-container-glow {
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3),
                           0 0 0 1px rgba(255, 255, 255, 0.1),
                           inset 0 1px 0 rgba(255, 255, 255, 0.1);
            }
        }
    </style>
@endpush
