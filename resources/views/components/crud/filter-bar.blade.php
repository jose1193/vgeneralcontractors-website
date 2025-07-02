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

<div class="mb-8">
    <!-- Glass Filter Container -->
    <div class="glass-filter-container backdrop-blur-sm bg-white/5 border border-white/10 rounded-2xl p-6">
        <div class="flex flex-col lg:flex-row gap-6 items-end">
            @if ($showSearchBar)
                <!-- Search Input Section -->
                <div class="flex-1 lg:max-w-md">
                    <label class="block text-sm font-medium text-white/90 mb-3">
                        🔍 Search {{ $entityName }}s
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="h-5 w-5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="{{ $searchId }}" placeholder="{{ $searchPlaceholder }}"
                            class="glass-input-filter w-full h-12 pl-12 pr-4 text-sm rounded-xl backdrop-blur-md bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-purple-400/50 focus:border-transparent transition-all duration-300">
                    </div>
                </div>
            @endif

            <!-- Controls Section -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-4 w-full lg:w-auto">
                @if ($showInactiveToggle)
                    <!-- Show Deleted Toggle -->
                    <div class="min-w-fit">
                        <label class="block text-sm font-medium text-white/90 mb-3">
                            🗑️ {{ $showDeletedLabel }}
                        </label>
                        <div class="flex items-center">
                            <x-crud.toggle-show-deleted :id="$showDeletedId" :label="$showDeletedLabel" :manager-name="$managerName" />
                        </div>
                    </div>
                @endif

                @if ($showPerPage)
                    <!-- Per Page Selector -->
                    <div class="min-w-fit">
                        <label class="block text-sm font-medium text-white/90 mb-3">
                            📄 Per Page
                        </label>
                        <select id="{{ $perPageId }}"
                            class="glass-input-filter h-12 px-4 pr-10 text-sm rounded-xl backdrop-blur-md bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-purple-400/50 focus:border-transparent transition-all duration-300 min-w-[120px] cursor-pointer">
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}" {{ $option == $defaultPerPage ? 'selected' : '' }}
                                    class="bg-gray-800 text-white">
                                    {{ $option }} per page
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Add Entity Button -->
                <div class="min-w-fit">
                    <label class="block text-sm font-medium text-white/90 mb-3">
                        ➕ Actions
                    </label>
                    <x-crud.button-create :id="$createButtonId" :label="$addNewLabel" :entity-name="$entityName" />
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        /* Glass Filter Container */
        .glass-filter-container {
            position: relative;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.04) 100%);
            backdrop-filter: blur(12px);
            box-shadow:
                0 8px 32px rgba(139, 92, 246, 0.1),
                0 4px 16px rgba(99, 102, 241, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .glass-filter-container:hover {
            box-shadow:
                0 12px 40px rgba(139, 92, 246, 0.15),
                0 6px 20px rgba(99, 102, 241, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
        }

        /* Glass Input Styling */
        .glass-input-filter {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
            color: white;
        }

        .glass-input-filter:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.25);
        }

        .glass-input-filter:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(139, 92, 246, 0.5);
            box-shadow:
                0 0 0 3px rgba(139, 92, 246, 0.15),
                0 4px 12px rgba(139, 92, 246, 0.1);
            color: white;
        }

        .glass-input-filter::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        /* Select Arrow Styling */
        .glass-input-filter option {
            background: rgb(31, 41, 55);
            color: rgb(243, 244, 246);
            padding: 0.75rem;
        }

        /* Labels Enhancement */
        .glass-filter-container label {
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            font-weight: 600;
        }

        /* Responsive adjustments */
        @media (max-width: 640px) {
            .glass-filter-container {
                padding: 1.5rem;
            }

            .glass-input-filter {
                height: 2.75rem;
            }
        }

        /* Enhanced focus states */
        .glass-input-filter:focus-within {
            transform: translateY(-1px);
        }

        /* Smooth transitions for all interactive elements */
        .glass-filter-container * {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
@endpush
