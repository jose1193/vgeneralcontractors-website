@props([
    'id' => 'crud-glassmorphic-table',
    'columns' => [],
    'managerName' => 'crudManager',
    'loadingText' => 'Loading...',
    'noDataText' => 'No records found',
    'responsive' => true,
    'sortable' => true,
    'darkMode' => true,
])

<div id="{{ $id }}-container" class="glassmorphic-table-container">
    <div class="{{ $responsive ? 'overflow-x-auto' : '' }}">
        <table id="{{ $id }}" class="glassmorphic-table">
            <thead>
                <tr>
                    @foreach ($columns as $index => $column)
                        <th class="{{ $sortable && ($column['sortable'] ?? true) ? 'cursor-pointer sort-header' : '' }}"
                            @if ($sortable && ($column['sortable'] ?? true)) data-field="{{ $column['field'] }}" @endif>
                            {{ $column['label'] }}
                            @if ($sortable && ($column['sortable'] ?? true))
                                <span class="sort-icon"></span>
                            @endif
                            <div class="shimmer-effect" style="animation-delay: {{ $index * 0.2 }}s"></div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="{{ $id }}-body">
                {{-- This will be populated by JavaScript --}}
                {{-- Example of a row structure for reference --}}
                {{--
                <tr class="glassmorphic-table-row">
                    <td data-label="Column 1">Data 1</td>
                    <td data-label="Column 2">Data 2</td>
                </tr>
                --}}
            </tbody>
        </table>
    </div>
    
    {{-- Loading/No Data Overlay --}}
    <div id="{{ $id }}-overlay" class="absolute inset-0 z-10 flex items-center justify-center bg-black/20 backdrop-blur-sm">
        <div class="text-center text-white">
            <svg id="{{ $id }}-loader" class="animate-spin h-6 w-6 mx-auto mb-3 text-purple-400" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p id="{{ $id }}-overlay-text" class="font-semibold">{{ $loadingText }}</p>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/glassmorphic-table.css') }}">
    <style>
        /* Sort icons */
        .sort-header .sort-icon::after {
            content: '↕️';
            font-size: 0.75rem;
            margin-left: 0.25rem;
            opacity: 0.5;
        }

        .sort-header.sort-asc .sort-icon::after {
            content: '↑';
            opacity: 1;
            color: #3B82F6;
        }

        .sort-header.sort-desc .sort-icon::after {
            content: '↓';
            opacity: 1;
            color: #3B82F6;
        }

        .sort-header:hover .sort-icon::after {
            opacity: 0.8;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/glassmorphic-table.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initGlassmorphicTable('{{ $id }}');
        });
    </script>
@endpush