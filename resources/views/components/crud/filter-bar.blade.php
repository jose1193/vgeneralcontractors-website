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

<div class="mb-5 flex flex-col sm:flex-row justify-between items-center backdrop-blur-md bg-white/10 dark:bg-gray-900/20 rounded-xl border border-white/20 dark:border-gray-700/30 p-4 animate-filter-glow">
    @if ($showSearchBar)
        <!-- Search Input -->
        <div class="relative w-full sm:w-1/3 mb-3 sm:mb-0">
            <span class="absolute inset-y-0 left-0 flex items-center pl-2 z-10">
                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    viewBox="0 0 24 24" class="w-6 h-6 text-gray-400 animate-search-pulse">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" id="{{ $searchId }}" placeholder="{{ $searchPlaceholder }}"
                class="pl-10 pr-4 py-2 backdrop-blur-sm bg-white/20 dark:bg-gray-800/30 border border-white/30 dark:border-gray-600/50 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400/50 focus:backdrop-blur-md w-full text-sm text-gray-700 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-300 hover:bg-white/30 dark:hover:bg-gray-800/40 animate-input-glow">
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
                <select id="{{ $perPageId }}"
                    class="backdrop-blur-sm bg-white/20 dark:bg-gray-800/30 border border-white/30 dark:border-gray-600/50 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400/50 text-sm py-2 px-3 pr-8 min-w-[70px] w-auto text-gray-700 dark:text-gray-200 transition-all duration-300 hover:bg-white/30 dark:hover:bg-gray-800/40 animate-select-glow">
                    @foreach ($perPageOptions as $option)
                        <option value="{{ $option }}" {{ $option == $defaultPerPage ? 'selected' : '' }}>
                            {{ $option }} {{ __('per_page') }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Add Entity Button -->
        <x-crud.button-create :id="$createButtonId" :label="$addNewLabel" :entity-name="$entityName" />
    </div>
</div>

@push('styles')
    <style>
        /* Glassmorphic Filter Bar Animations */
        @keyframes filter-glow {
            0%, 100% {
                box-shadow: 0 4px 20px rgba(31, 38, 135, 0.2),
                           0 0 0 1px rgba(255, 255, 255, 0.1),
                           inset 0 1px 0 rgba(255, 255, 255, 0.1);
            }
            50% {
                box-shadow: 0 8px 30px rgba(31, 38, 135, 0.3),
                           0 0 0 1px rgba(255, 255, 255, 0.2),
                           inset 0 1px 0 rgba(255, 255, 255, 0.15);
            }
        }

        @keyframes search-pulse {
            0%, 100% {
                opacity: 0.6;
                transform: scale(1);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
        }

        @keyframes input-glow {
            0%, 100% {
                box-shadow: 0 2px 10px rgba(59, 130, 246, 0.1),
                           inset 0 1px 0 rgba(255, 255, 255, 0.1);
            }
            50% {
                box-shadow: 0 4px 20px rgba(59, 130, 246, 0.2),
                           inset 0 1px 0 rgba(255, 255, 255, 0.2);
            }
        }

        @keyframes select-glow {
            0%, 100% {
                box-shadow: 0 2px 8px rgba(31, 38, 135, 0.1);
            }
            50% {
                box-shadow: 0 4px 15px rgba(31, 38, 135, 0.2);
            }
        }

        /* Animation Classes */
        .animate-filter-glow {
            animation: filter-glow 4s ease-in-out infinite;
        }

        .animate-search-pulse {
            animation: search-pulse 2s ease-in-out infinite;
        }

        .animate-input-glow {
            animation: input-glow 3s ease-in-out infinite;
        }

        .animate-select-glow {
            animation: select-glow 3s ease-in-out infinite;
        }

        /* Enhanced Focus States */
        input:focus.animate-input-glow {
            animation-duration: 1s;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1),
                       0 4px 20px rgba(59, 130, 246, 0.3) !important;
        }

        select:focus.animate-select-glow {
            animation-duration: 1s;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1),
                       0 4px 15px rgba(31, 38, 135, 0.3) !important;
        }
    </style>
@endpush
