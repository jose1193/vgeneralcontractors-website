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

<div class="relative overflow-hidden rounded-[6px]">
    {{-- Animated gradient border with enhanced glow --}}
    <div class="absolute inset-0 rounded-[6px] p-[2px] animate-border-glow">
        <div class="absolute inset-0 rounded-[6px] bg-gradient-to-r from-yellow-400 via-purple-500 via-orange-500 to-yellow-400 bg-[length:300%_300%] animate-gradient-border opacity-70"></div>
        <div class="relative w-full h-full bg-black/80 filter blur-[1px] rounded-[4px] border border-white/5"></div>
    </div>

    {{-- Table container with enhanced animated shadows --}}
    <div class="relative filter blur-[0.5px] bg-black/40 border-0 rounded-[4px] overflow-hidden m-[2px] animate-table-shadow shadow-lg shadow-purple-500/30">
        <div class="{{ $responsive ? 'overflow-x-auto' : '' }}">
            <table id="{{ $id }}" class="w-full">
                <thead>
                    <tr class="border-b border-white/5 relative">
                        @foreach ($columns as $index => $column)
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-300 filter blur-[0px] relative {{ $sortable && ($column['sortable'] ?? true) ? 'cursor-pointer sort-header' : '' }}"
                                @if ($sortable && ($column['sortable'] ?? true)) data-field="{{ $column['field'] }}" @endif>
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-{{ ['yellow', 'purple', 'orange', 'yellow', 'purple'][($index % 5)]}}-500/5 to-transparent animate-shimmer{{ $index > 0 ? '-delay-' . $index : '' }}"></div>
                                <span class="relative z-10 flex items-center justify-center space-x-1">
                                    <!-- Iconos UX/UI según el tipo de columna -->
                                    @if ($column['field'] === 'id')
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                                    @elseif ($column['field'] === 'name' || $column['field'] === 'nombre')
                                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    @elseif ($column['field'] === 'email')
                                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    @elseif ($column['field'] === 'status' || $column['field'] === 'estado')
                                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                    @elseif (str_contains($column['field'], 'date') || str_contains($column['field'], 'fecha') || $column['field'] === 'created_at')
                                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @elseif ($column['field'] === 'actions' || $column['field'] === 'acciones')
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    @endif
                                    <span>{{ $column['label'] }}</span>
                                </span>
                                @if ($sortable && ($column['sortable'] ?? true))
                                    <span class="sort-icon relative z-10"></span>
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="{{ $id }}-body" class="divide-y divide-white/5 bg-black/90 filter blur-[0.5px]">
                    {{-- Loading row --}}
                    <tr id="loadingRow">
                        <td colspan="{{ count($columns) }}" class="px-6 py-4 text-center text-white">
                            <svg class="animate-spin h-5 w-5 mr-3 text-blue-500 inline-block" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
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
    <link rel="stylesheet" href="{{ asset('css/glassmorphic-table.css') }}">
    <style>
        /* Sort icons with improved visibility */
        .sort-header .sort-icon::after {
            content: '↕️';
            font-size: 0.85rem;
            margin-left: 0.35rem;
            opacity: 0.7;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.2);
            display: inline-block;
            vertical-align: middle;
        }

        .sort-header.sort-asc .sort-icon::after {
            content: '↑';
            opacity: 1;
            color: #60A5FA; /* Azul más brillante */
            text-shadow: 0 0 8px rgba(96, 165, 250, 0.5);
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }

        .sort-header.sort-desc .sort-icon::after {
            content: '↓';
            opacity: 1;
            color: #60A5FA; /* Azul más brillante */
            text-shadow: 0 0 8px rgba(96, 165, 250, 0.5);
            transform: translateY(1px);
            transition: all 0.2s ease;
        }

        .sort-header:hover .sort-icon::after {
            opacity: 1;
            transform: scale(1.1);
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