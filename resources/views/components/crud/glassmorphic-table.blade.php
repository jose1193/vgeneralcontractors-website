@props([
    'id' => 'glassmorphic-table',
    'columns' => [],
    'managerName' => 'crudManager',
    'loadingText' => 'Cargando datos...',
    'noDataText' => 'No se encontraron registros',
    'responsive' => true,
    'sortable' => true,
])

{{-- Main Container --}}
<div class="glassmorphic-container" id="{{ $id }}-container">
    {{-- Animated Border --}}
    <div class="glassmorphic-border"></div>
    
    {{-- Table Wrapper --}}
    <div class="glassmorphic-table-wrapper {{ $responsive ? 'overflow-x-auto' : '' }}">
        <table class="glassmorphic-table" id="{{ $id }}">
            {{-- Table Header --}}
            <thead class="glassmorphic-thead">
                <tr>
                    @foreach ($columns as $column)
                        <th class="glassmorphic-th {{ $sortable && ($column['sortable'] ?? true) ? 'sortable' : '' }}"
                            @if ($sortable && ($column['sortable'] ?? true)) 
                                data-field="{{ $column['field'] }}" 
                                data-sortable="true"
                            @endif>
                            <span>{{ $column['label'] }}</span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            
            {{-- Table Body --}}
            <tbody class="glassmorphic-tbody" id="{{ $id }}-body">
                {{-- Loading Row --}}
                <tr class="loading-row" id="loading-row-{{ $id }}">
                    <td colspan="{{ count($columns) }}" class="glassmorphic-td">
                        <div class="loading-spinner"></div>
                        <span>{{ $loadingText }}</span>
                    </td>
                </tr>
                
                {{-- No Data Row (Hidden by default) --}}
                <tr class="no-data-row" id="no-data-row-{{ $id }}" style="display: none;">
                    <td colspan="{{ count($columns) }}" class="glassmorphic-td no-data">
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p>{{ $noDataText }}</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/glassmorphic-table.css') }}">
@endpush

{{-- Scripts --}}
@push('scripts')
    <script src="{{ asset('js/glassmorphic-table.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the glassmorphic table
            const tableManager = new GlassmorphicTableManager('{{ $id }}', {
                managerName: '{{ $managerName }}',
                sortable: {{ $sortable ? 'true' : 'false' }},
                responsive: {{ $responsive ? 'true' : 'false' }},
                loadingText: '{{ $loadingText }}',
                noDataText: '{{ $noDataText }}'
            });
            
            // Make it globally accessible
            window['{{ $id }}Manager'] = tableManager;
        });
    </script>
@endpush