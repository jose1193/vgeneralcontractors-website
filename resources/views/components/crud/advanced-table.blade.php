@props([
    'id' => 'crud-advanced-table',
    'columns' => [],
    'managerName' => 'crudManager',
    'loadingText' => 'Loading...',
    'noDataText' => 'No records found',
    'responsive' => true,
    'sortable' => true,
    'darkMode' => true,
])

<div class="table-wrapper">
    <table id="{{ $id }}" class="min-w-full glassmorphic-table">
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th class="{{ $sortable && ($column['sortable'] ?? true) ? 'cursor-pointer sort-header' : '' }}"
                        @if ($sortable && ($column['sortable'] ?? true)) 
                            data-field="{{ $column['field'] }}" 
                            onclick="{{ $managerName }}.sortBy('{{ $column['field'] }}')"
                        @endif>
                        {{ $column['label'] }}
                        @if ($sortable && ($column['sortable'] ?? true))
                            <span class="sort-icon"></span>
                        @endif
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody id="{{ $id }}-body">
            <!-- Loading row -->
            <tr id="{{ $id }}-loading" class="hidden">
                <td colspan="{{ count($columns) }}">
                    <div class="glassmorphic-loading">
                        <div class="flex justify-center items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-white/70" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-white/80 font-medium">{{ $loadingText }}</span>
                        </div>
                    </div>
                </td>
            </tr>
            <!-- No data row -->
            <tr id="{{ $id }}-no-data" class="hidden">
                <td colspan="{{ count($columns) }}">
                    <div class="glassmorphic-no-data">
                        <svg class="mx-auto h-12 w-12 text-white/50 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-lg font-medium">{{ $noDataText }}</p>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@push('styles')
    <style>
        /* Glassmorphic Table Styles */
        .glassmorphic-table {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.03));
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            overflow: hidden;
        }
        
        .glassmorphic-table thead {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.06));
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        
        .glassmorphic-table th {
            background: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            padding: 16px 20px;
            text-align: left;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .glassmorphic-table th:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.08));
            transform: translateY(-1px);
        }
        
        .glassmorphic-table tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .glassmorphic-table tbody tr:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(31, 38, 135, 0.3);
        }
        
        .glassmorphic-table tbody tr:hover::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, rgba(255, 107, 107, 0.1), rgba(78, 205, 196, 0.1), rgba(69, 183, 209, 0.1));
            pointer-events: none;
        }
        
        .glassmorphic-table td {
            padding: 16px 20px;
            color: rgba(255, 255, 255, 0.8);
            position: relative;
            z-index: 1;
        }
        
        /* Sort icons with glassmorphic effect */
        .sort-icon {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 8px;
            vertical-align: middle;
            transition: all 0.3s ease;
        }
        
        .sort-icon.asc {
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-bottom: 6px solid rgba(255, 255, 255, 0.7);
            filter: drop-shadow(0 2px 4px rgba(255, 255, 255, 0.3));
        }
        
        .sort-icon.desc {
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 6px solid rgba(255, 255, 255, 0.7);
            filter: drop-shadow(0 2px 4px rgba(255, 255, 255, 0.3));
        }
        
        .sort-icon:hover {
            border-bottom-color: rgba(255, 255, 255, 0.9);
            border-top-color: rgba(255, 255, 255, 0.9);
            filter: drop-shadow(0 4px 8px rgba(255, 255, 255, 0.5));
            transform: scale(1.1);
        }
        
        /* Loading spinner with glassmorphic effect */
        .glassmorphic-loading {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        
        .glassmorphic-loading svg {
            filter: drop-shadow(0 2px 4px rgba(255, 255, 255, 0.3));
        }
        
        /* No data message */
        .glassmorphic-no-data {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.03));
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
        }
        
        /* Responsive table wrapper */
        .table-wrapper {
            overflow-x: auto;
            border-radius: 12px;
        }
        
        .table-wrapper::-webkit-scrollbar {
            height: 8px;
        }
        
        .table-wrapper::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        
        .table-wrapper::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.1));
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .table-wrapper::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.2));
        }
    </style>
@endpush
