@props([
    'id' => 'crud-advanced-table-glass',
    'columns' => [],
    'managerName' => 'crudManager',
    'loadingText' => 'Loading...',
    'noDataText' => 'No records found',
    'responsive' => true,
    'sortable' => true,
    'darkMode' => true,
])

<div
    class="{{ $responsive ? 'overflow-x-auto' : '' }} glassmorphic-table rounded-2xl shadow-2xl border border-gray-300/20 dark:border-gray-700/40 backdrop-blur-xl">
    <table id="{{ $id }}" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="glassmorphic-header">
            <tr>
                @foreach ($columns as $column)
                    <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider select-none transition-all duration-200 {{ $sortable && ($column['sortable'] ?? true) ? 'cursor-pointer sort-header-glass' : '' }}"
                        @if ($sortable && ($column['sortable'] ?? true)) data-field="{{ $column['field'] }}" @endif>
                        <span class="inline-flex items-center">
                            {{ $column['label'] }}
                            @if ($sortable && ($column['sortable'] ?? true))
                                <span class="sort-icon-glass ml-2"></span>
                            @endif
                        </span>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody id="{{ $id }}-body" class="glassmorphic-body divide-y divide-gray-200 dark:divide-gray-700">
            <!-- Loading row -->
            <tr id="loadingRow">
                <td colspan="{{ count($columns) }}" class="px-6 py-4 text-center">
                    <svg class="animate-spin h-5 w-5 mr-3 text-blue-400 dark:text-blue-300 inline-block"
                        viewBox="0 0 24 24">
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

@push('styles')
    <style>
        /* Glassmorphic background */
        .glassmorphic-table {
            background: rgba(20, 20, 20, 0.55);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.25);
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            transition: background 0.3s, box-shadow 0.3s;
        }

        .glassmorphic-header {
            background: rgba(30, 30, 30, 0.65);
            color: #e5e7eb;
            border-top-left-radius: 1.5rem;
            border-top-right-radius: 1.5rem;
        }

        .glassmorphic-body {
            background: rgba(24, 24, 24, 0.45);
            color: #f3f4f6;
        }

        /* Light mode support */
        @media (prefers-color-scheme: light) {
            .glassmorphic-table {
                background: rgba(255, 255, 255, 0.65);
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
                border: 1px solid rgba(0, 0, 0, 0.08);
            }

            .glassmorphic-header {
                background: rgba(245, 245, 245, 0.85);
                color: #222;
            }

            .glassmorphic-body {
                background: rgba(255, 255, 255, 0.55);
                color: #222;
            }
        }

        /* Sort icons glass style */
        .sort-header-glass .sort-icon-glass::after {
            content: '\21C5';
            /* up-down arrow */
            font-size: 0.9rem;
            margin-left: 0.15rem;
            opacity: 0.5;
            transition: color 0.2s, opacity 0.2s;
        }

        .sort-header-glass.sort-asc .sort-icon-glass::after {
            content: '\2191';
            /* up arrow */
            color: #60a5fa;
            opacity: 1;
        }

        .sort-header-glass.sort-desc .sort-icon-glass::after {
            content: '\2193';
            /* down arrow */
            color: #60a5fa;
            opacity: 1;
        }

        .sort-header-glass:hover .sort-icon-glass::after {
            opacity: 0.8;
            color: #38bdf8;
        }

        /* Row hover effect */
        .glassmorphic-body tr:hover {
            background: rgba(59, 130, 246, 0.10);
            transition: background 0.2s;
        }

        /* Responsive tweaks */
        @media (max-width: 640px) {
            .glassmorphic-table {
                font-size: 0.95rem;
            }

            .glassmorphic-header th,
            .glassmorphic-body td {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }
    </style>
@endpush
