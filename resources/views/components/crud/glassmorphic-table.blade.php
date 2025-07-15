@props([
    'id' => 'glassmorphic-table',
    'columns' => [],
    'loadingText' => 'Cargando registros...',
    'noDataText' => 'No se encontraron registros.',
])

{{-- Contenedor principal relativo para posicionar los efectos de borde y sombra --}}
<div class="relative overflow-hidden rounded-lg">

    <!-- 1. Borde animado con gradiente y efecto de brillo -->
    <div class="absolute inset-0 rounded-lg p-[2px] animate-border-glow">
        <div class="absolute inset-0 rounded-lg bg-gradient-to-r from-yellow-400 via-purple-500 via-orange-500 to-yellow-400 bg-[length:300%_300%] animate-gradient-border opacity-80"></div>
        <div class="relative w-full h-full bg-black/80 filter blur-[2px] rounded-md"></div>
    </div>

    <!-- 2. Contenedor de la tabla con efecto glassmorphic y sombra animada -->
    <div class="relative bg-black/50 backdrop-blur-sm border-0 rounded-md overflow-hidden m-[2px] animate-table-shadow shadow-lg shadow-purple-500/20">
        <div class="overflow-x-auto">
            <table id="{{ $id }}" class="w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        {{-- Itera sobre las columnas para crear las cabeceras --}}
                        @foreach ($columns as $index => $column)
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-300/80 relative sort-header cursor-pointer"
                                data-field="{{ $column['field'] ?? '' }}">

                                <!-- 3. Animación de brillo (shimmer) en cada cabecera -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-purple-500/10 to-transparent animate-shimmer"
                                     style="animation-delay: {{ $index * 0.2 }}s;">
                                </div>

                                <span class="relative z-10">{{ $column['label'] }}</span>
                                <span class="sort-icon relative z-10 ml-2"></span>
                            </th>
                        @endforeach
                    </tr>
                </thead>

                {{-- El cuerpo de la tabla se llenará con JavaScript --}}
                <tbody id="{{ $id }}-body" class="divide-y divide-white/5">
                    {{-- Fila de carga inicial --}}
                    <tr>
                        <td colspan="{{ count($columns) }}" class="px-6 py-16 text-center text-white/70">
                            <svg class="animate-spin h-6 w-6 mr-3 text-purple-400 inline-block" viewBox="0 0 24 24">
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
    {{-- Vincula la hoja de estilos específica para este componente --}}
    <link rel="stylesheet" href="{{ asset('css/glassmorphic-table.css') }}">
    <style>
        /* Estilos para los iconos de ordenación (sort) */
        .sort-header .sort-icon::after {
            content: '↕'; /* Icono por defecto (neutral) */
            opacity: 0.4;
            transition: opacity 0.2s;
        }
        .sort-header.sort-asc .sort-icon::after {
            content: '↑'; /* Icono ascendente */
            opacity: 1;
            color: #a78bfa; /* Morado claro */
        }
        .sort-header.sort-desc .sort-icon::after {
            content: '↓'; /* Icono descendente */
            opacity: 1;
            color: #a78bfa; /* Morado claro */
        }
        .sort-header:hover .sort-icon::after {
            opacity: 1;
        }
    </style>
@endpush

@push('scripts')
    {{-- Vincula el script de JavaScript y lo inicializa --}}
    <script src="{{ asset('js/glassmorphic-table.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa la tabla con su ID
            initGlassmorphicTable('{{ $id }}');
        });
    </script>
@endpush