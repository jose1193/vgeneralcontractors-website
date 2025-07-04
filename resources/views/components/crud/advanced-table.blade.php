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

<div class="{{ $responsive ? 'overflow-x-auto' : '' }} glassmorphic-scrollbar">
    <table id="{{ $id }}" class="glassmorphic-table w-full border-separate border-spacing-0 rounded-lg overflow-hidden backdrop-blur-xl bg-black/20 border border-white/10 shadow-2xl animate-shimmer-subtle">
        <thead class="glassmorphic-thead">
            <tr class="glassmorphic-thead-row bg-white/5 backdrop-blur-md border-b border-white/10">
                @foreach ($columns as $column)
                    <th class="glassmorphic-th px-6 py-4 text-left text-sm font-semibold text-white/90 uppercase tracking-wider border-r border-white/5 last:border-r-0 transition-all duration-300 hover:bg-white/10 hover:text-white {{ $sortable && ($column['sortable'] ?? true) ? 'cursor-pointer sort-header' : '' }}"
                        @if ($sortable && ($column['sortable'] ?? true)) data-field="{{ $column['field'] }}" @endif>
                        {{ $column['label'] }}
                        @if ($sortable && ($column['sortable'] ?? true))
                            <span class="sort-icon"></span>
                        @endif
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody id="{{ $id }}-body" class="glassmorphic-tbody">
            <!-- Loading row -->
            <tr id="loadingRow" class="glassmorphic-tbody-row bg-black/10 backdrop-blur-sm border-b border-white/5 hover:bg-white/5 transition-all duration-300">
                <td colspan="{{ count($columns) }}" class="glassmorphic-td px-6 py-4 text-center text-white/80 border-r border-white/5 last:border-r-0">
                    <div class="flex items-center justify-center space-x-3">
                        <svg class="animate-spin h-5 w-5 text-white/60" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-white/80">{{ $loadingText }}</span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@push('styles')
    <style>
        /* Glassmorphic Sort icons */
        .sort-header .sort-icon::after {
            content: '↕️';
            font-size: 0.75rem;
            margin-left: 0.25rem;
            opacity: 0.6;
            color: rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }

        .sort-header.sort-asc .sort-icon::after {
            content: '↑';
            opacity: 1;
            color: #fbbf24;
            text-shadow: 0 0 8px rgba(251, 191, 36, 0.5);
        }

        .sort-header.sort-desc .sort-icon::after {
            content: '↓';
            opacity: 1;
            color: #a855f7;
            text-shadow: 0 0 8px rgba(168, 85, 247, 0.5);
        }

        .sort-header:hover .sort-icon::after {
            opacity: 0.9;
            transform: scale(1.1);
        }

        /* Glassmorphic table enhancements */
        .glassmorphic-table {
            position: relative;
        }

        .glassmorphic-table::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.02) 50%, transparent 70%);
            pointer-events: none;
            animation: shimmer-overlay 3s ease-in-out infinite;
        }

        @keyframes shimmer-overlay {
            0%, 100% { transform: translateX(-100%); }
            50% { transform: translateX(100%); }
        }

        /* Enhanced hover effects */
        .glassmorphic-th:hover {
            background: rgba(255, 255, 255, 0.1);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .glassmorphic-tbody-row:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
    </style>
@endpush
