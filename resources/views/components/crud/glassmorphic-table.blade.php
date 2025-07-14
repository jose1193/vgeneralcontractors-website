@props([
    'id' => 'crud-glassmorphic-table',
    'columns' => [],
    'loadingText' => 'Cargando...',
    'responsive' => true,
    'sortable' => true,
])

{{-- Contenedor principal con el borde de gradiente --}}
<div class="glassmorphic-container">
    {{-- Wrapper con el efecto de cristal (fondo y backdrop-filter) --}}
    <div class="glassmorphic-table-wrapper">
        <div class="{{ $responsive ? 'overflow-x-auto' : '' }}">
            <table id="{{ $id }}" class="w-full">
                <thead>
                    <tr>
                        @foreach ($columns as $column)
                            <th class="px-6 py-4 {{ $sortable && ($column['sortable'] ?? true) ? 'sort-header' : '' }}"
                                @if ($sortable && ($column['sortable'] ?? true)) data-field="{{ $column['field'] }}" @endif>
                                <span>{{ $column['label'] }}</span>
                                @if ($sortable && ($column['sortable'] ?? true))
                                    <span class="sort-icon"></span>
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="{{ $id }}-body">
                    {{-- La fila de carga será inyectada por JS, pero podemos tener un placeholder --}}
                    <tr id="loadingRow">
                        <td colspan="{{ count($columns) }}" class="px-6 py-4 text-center">
                            <svg class="animate-spin h-5 w-5 mr-3 text-blue-500 inline-block" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ $loadingText }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
    {{-- Asegúrate de que la ruta al CSS es correcta --}}
    <link rel="stylesheet" href="{{ asset('css/glassmorphic-table.css') }}">
@endpush

@push('scripts')
    {{-- Asegúrate de que la ruta al JS es correcta --}}
    <script src="{{ asset('js/glassmorphic-table.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pasamos el objeto de configuración directamente
            initGlassmorphicTable({
                tableId: '{{ $id }}',
                columns: @json($columns)
            });
        });
    </script>
@endpush