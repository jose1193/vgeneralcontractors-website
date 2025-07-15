@props([
    'id' => 'generic-table',
    'columns' => [],
    'managerName' => null,
    'loadingText' => 'Loading...',
    'noDataText' => 'No records found',
    'responsive' => true,
    'sortable' => true,
    'theme' => 'black-crystal', // black-crystal or light-crystal
])

<div class="relative overflow-hidden rounded-xl backdrop-blur-sm">
    {{-- Black Crystal Container --}}
    <div
        class="relative bg-gradient-to-br {{ $theme === 'black-crystal' ? 'from-black/90 to-gray-900/95' : 'from-white/80 to-gray-100/85' }} border border-opacity-20 {{ $theme === 'black-crystal' ? 'border-purple-500/30' : 'border-blue-300/30' }} rounded-xl shadow-2xl">
        <div class="{{ $responsive ? 'overflow-x-auto' : '' }} backdrop-blur-[2px]">
            <table id="{{ $id }}" class="w-full table-auto">
                <thead>
                    <tr class="border-b {{ $theme === 'black-crystal' ? 'border-white/10' : 'border-black/10' }}">
                        @foreach ($columns as $column)
                            <th class="px-6 py-4 text-sm font-semibold {{ $theme === 'black-crystal' ? 'text-gray-300' : 'text-gray-700' }} {{ $sortable && ($column['sortable'] ?? true) ? 'cursor-pointer sort-header hover:bg-opacity-10' : '' }}"
                                @if ($sortable && ($column['sortable'] ?? true)) data-field="{{ $column['field'] }}" @endif>
                                <div class="flex items-center justify-center space-x-2">
                                    <span>{{ $column['label'] }}</span>
                                    @if ($sortable && ($column['sortable'] ?? true))
                                        <span class="sort-icon opacity-50 transition-opacity"></span>
                                    @endif
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="{{ $id }}-body"
                    class="divide-y {{ $theme === 'black-crystal' ? 'divide-white/5' : 'divide-black/5' }}">
                    <tr id="loadingRow" class="animate-pulse">
                        <td colspan="{{ count($columns) }}" class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-3">
                                <svg class="animate-spin h-5 w-5 {{ $theme === 'black-crystal' ? 'text-purple-500' : 'text-blue-500' }}"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span class="{{ $theme === 'black-crystal' ? 'text-gray-300' : 'text-gray-600' }}">
                                    {{ $loadingText }}
                                </span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .sort-header .sort-icon::after {
            content: '↕️';
            font-size: 0.75rem;
            opacity: 0.5;
            transition: all 0.3s ease;
        }

        .sort-header.sort-asc .sort-icon::after {
            content: '↑';
            opacity: 1;
            color: {{ $theme === 'black-crystal' ? '#9333ea' : '#3b82f6' }};
        }

        .sort-header.sort-desc .sort-icon::after {
            content: '↓';
            opacity: 1;
            color: {{ $theme === 'black-crystal' ? '#9333ea' : '#3b82f6' }};
        }

        .sort-header:hover .sort-icon::after {
            opacity: 0.8;
        }

        /* Modern 2025 Row Styles */
        #{{ $id }}-body tr {
            @apply transition-all duration-300 ease-in-out;
            backdrop-filter: blur(8px);
        }

        #{{ $id }}-body tr:hover {
            @apply transform scale-[1.01] z-10 relative;
            box-shadow: 0 0 25px {{ $theme === 'black-crystal' ? 'rgba(147, 51, 234, 0.15)' : 'rgba(59, 130, 246, 0.15)' }};
            background: {{ $theme === 'black-crystal' ? 'rgba(0, 0, 0, 0.7)' : 'rgba(255, 255, 255, 0.9)' }};
        }

        #{{ $id }}-body tr td {
            @apply px-6 py-4 text-sm;
            color: {{ $theme === 'black-crystal' ? 'rgba(229, 231, 235, 0.9)' : 'rgba(31, 41, 55, 0.9)' }};
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('{{ $id }}');
            if (!table) return;

            // Initialize sort functionality
            if ({{ $sortable ? 'true' : 'false' }}) {
                const headers = table.querySelectorAll('.sort-header');
                headers.forEach(header => {
                    header.addEventListener('click', function() {
                        const field = this.dataset.field;
                        const currentOrder = this.classList.contains('sort-asc') ? 'desc' :
                            this.classList.contains('sort-desc') ? 'clear' : 'asc';

                        // Remove sorting classes from all headers
                        headers.forEach(h => {
                            h.classList.remove('sort-asc', 'sort-desc');
                        });

                        // Apply new sorting class
                        if (currentOrder === 'asc') {
                            this.classList.add('sort-asc');
                        } else if (currentOrder === 'desc') {
                            this.classList.add('sort-desc');
                        }

                        // Trigger sort event for the manager to handle
                        if (window[{{ json_encode($managerName) }}]) {
                            window[{{ json_encode($managerName) }}].handleSort(field,
                            currentOrder);
                        }
                    });
                });
            }
        });
    </script>
@endpush
