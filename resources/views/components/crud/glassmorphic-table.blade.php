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

<div class="glassmorphic-table-container">
    {{-- Outer glow container --}}
    <div class="glassmorphic-outer-glow">
        {{-- Main table wrapper with glassmorphic effect --}}
        <div class="glassmorphic-table-wrapper">
            {{-- Table container --}}
            <div class="{{ $responsive ? 'glassmorphic-table-responsive' : 'glassmorphic-table-fixed' }}">
                <table id="{{ $id }}" class="glassmorphic-table">
                    {{-- Table Header --}}
                    <thead class="glassmorphic-table-header">
                        <tr class="glassmorphic-header-row">
                            @foreach ($columns as $index => $column)
                                <th class="glassmorphic-header-cell {{ $sortable && ($column['sortable'] ?? true) ? 'glassmorphic-sortable' : '' }}"
                                    @if ($sortable && ($column['sortable'] ?? true)) data-field="{{ $column['field'] }}" 
                                        role="button" 
                                        tabindex="0"
                                        aria-sort="none" @endif>
                                    {{-- Header content with shimmer effect --}}
                                    <div class="glassmorphic-header-content">
                                        <div class="glassmorphic-shimmer glassmorphic-shimmer-{{ $index % 5 }}">
                                        </div>
                                        <span class="glassmorphic-header-text">{{ $column['label'] }}</span>
                                        @if ($sortable && ($column['sortable'] ?? true))
                                            <span class="glassmorphic-sort-icon" aria-hidden="true"></span>
                                        @endif
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    {{-- Table Body --}}
                    <tbody id="{{ $id }}-body" class="glassmorphic-table-body">
                        {{-- Loading row --}}
                        <tr id="loadingRow" class="glassmorphic-loading-row">
                            <td colspan="{{ count($columns) }}" class="glassmorphic-loading-cell">
                                <div class="glassmorphic-loading-content">
                                    <div class="glassmorphic-spinner">
                                        <div class="glassmorphic-spinner-inner"></div>
                                    </div>
                                    <span class="glassmorphic-loading-text">{{ $loadingText }}</span>
                                </div>
                            </td>
                        </tr>

                        {{-- No data row (hidden by default) --}}
                        <tr id="noDataRow" class="glassmorphic-no-data-row" style="display: none;">
                            <td colspan="{{ count($columns) }}" class="glassmorphic-no-data-cell">
                                <div class="glassmorphic-no-data-content">
                                    <svg class="glassmorphic-no-data-icon" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <span class="glassmorphic-no-data-text">{{ $noDataText }}</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/glassmorphic-table.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/glassmorphic-table.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the glassmorphic table
            const tableManager = new GlassmorphicTable('{{ $id }}', {
                managerName: '{{ $managerName }}',
                sortable: {{ $sortable ? 'true' : 'false' }},
                responsive: {{ $responsive ? 'true' : 'false' }},
                loadingText: '{{ $loadingText }}',
                noDataText: '{{ $noDataText }}'
            });

            // Make it globally accessible
            window.glassmorphicTable = tableManager;
        });
    </script>
@endpush
