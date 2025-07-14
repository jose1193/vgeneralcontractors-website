@props([
    'id' => 'crud-glassmorphic-table',
    'columns' => [],
    'loadingText' => 'Loading...',
    'noDataText' => 'No records found',
])

{{-- 
  The main container with the glassmorphic effect.
  All table elements are placed inside this container.
--}}
<div class="glass-container">
    <div class="table-wrapper">
        <table id="{{ $id }}" class="glass-table">
            <thead>
                <tr>
                    {{-- Loop through columns to create table headers --}}
                    @foreach ($columns as $column)
                        <th class="{{ ($column['sortable'] ?? true) ? 'sort-header' : '' }}"
                            @if ($column['sortable'] ?? true) data-field="{{ $column['field'] }}" @endif>
                            
                            {{-- Column Label --}}
                            <span>{{ $column['label'] }}</span>

                            {{-- Sort Icon (styled via CSS) --}}
                            @if ($column['sortable'] ?? true)
                                <span class="sort-icon"></span>
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="{{ $id }}-body">
                {{-- 
                  Initial loading row. 
                  This will be replaced by data or the "no data" message via JavaScript.
                --}}
                <tr class="table-state-row" id="loadingRow-{{$id}}">
                    <td colspan="{{ count($columns) }}">
                        <div class="spinner"></div>
                        <span>{{ $loadingText }}</span>
                    </td>
                </tr>
                {{-- 
                  "No data" row. It's hidden by default and shown by JavaScript if the API returns no records.
                --}}
                <tr class="table-state-row" id="noDataRow-{{$id}}" style="display: none;">
                    <td colspan="{{ count($columns) }}">
                        {{ $noDataText }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@push('styles')
    {{-- Link to the new stylesheet --}}
    <link rel="stylesheet" href="{{ asset('css/glassmorphic-table.css') }}">
@endpush

@push('scripts')
    {{-- Link to the table's JavaScript logic --}}
    <script src="{{ asset('js/glassmorphic-table.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pass the table ID and columns configuration to the JavaScript.
            // The columns array is needed for the responsive view (data-label attributes).
            const columns = @json($columns);
            initGlassmorphicTable('{{ $id }}', columns);
        });
    </script>
@endpush