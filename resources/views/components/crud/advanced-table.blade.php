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

<div
    class="{{ $responsive ? 'overflow-x-auto' : '' }} backdrop-blur-xl bg-white/10 dark:bg-gray-900/20 rounded-xl border border-white/20 dark:border-gray-700/30 animate-table-shadow">
    <table id="{{ $id }}" class="min-w-full divide-y divide-white/10 dark:divide-gray-700/30">
        <thead class="backdrop-blur-md bg-white/5 dark:bg-gray-800/30 animate-border-glow">
            <tr>
                @foreach ($columns as $column)
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 dark:text-gray-200 uppercase tracking-wider transition-all duration-300 hover:text-blue-600 dark:hover:text-blue-400 animate-shimmer {{ $sortable && ($column['sortable'] ?? true) ? 'cursor-pointer sort-header' : '' }}"
                        @if ($sortable && ($column['sortable'] ?? true)) data-field="{{ $column['field'] }}" @endif>
                        {{ $column['label'] }}
                        @if ($sortable && ($column['sortable'] ?? true))
                            <span class="sort-icon"></span>
                        @endif
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody id="{{ $id }}-body" class="backdrop-blur-sm bg-white/5 dark:bg-gray-800/20 divide-y divide-white/10 dark:divide-gray-700/30">
            <!-- Loading row -->
            <tr id="loadingRow" class="animate-pulse-glow">
                <td colspan="{{ count($columns) }}" class="px-6 py-4 text-center backdrop-blur-sm bg-white/5 dark:bg-gray-800/20">
                    <svg class="animate-spin h-5 w-5 mr-3 text-blue-500 inline-block animate-gradient-spin" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span class="text-gray-600 dark:text-gray-300 animate-text-glow">{{ $loadingText }}</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@push('styles')
    <style>
        /* Sort icons */
        .sort-header .sort-icon::after {
            content: '↕️';
            font-size: 0.75rem;
            margin-left: 0.25rem;
            opacity: 0.5;
            transition: all 0.3s ease;
        }

        .sort-header.sort-asc .sort-icon::after {
            content: '↑';
            opacity: 1;
            color: #3B82F6;
            text-shadow: 0 0 8px rgba(59, 130, 246, 0.5);
        }

        .sort-header.sort-desc .sort-icon::after {
            content: '↓';
            opacity: 1;
            color: #3B82F6;
            text-shadow: 0 0 8px rgba(59, 130, 246, 0.5);
        }

        .sort-header:hover .sort-icon::after {
            opacity: 0.8;
            transform: scale(1.1);
        }

        /* Glassmorphic Animations */
        @keyframes table-shadow {
            0%, 100% {
                box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37),
                           0 0 0 1px rgba(255, 255, 255, 0.18),
                           inset 0 1px 0 rgba(255, 255, 255, 0.1);
            }
            50% {
                box-shadow: 0 12px 40px rgba(31, 38, 135, 0.5),
                           0 0 0 1px rgba(255, 255, 255, 0.25),
                           inset 0 1px 0 rgba(255, 255, 255, 0.15);
            }
        }

        @keyframes border-glow {
            0%, 100% {
                border-color: rgba(255, 255, 255, 0.2);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1);
            }
            50% {
                border-color: rgba(59, 130, 246, 0.4);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2),
                           0 0 20px rgba(59, 130, 246, 0.3);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }

        @keyframes pulse-glow {
            0%, 100% {
                background-color: rgba(255, 255, 255, 0.05);
                transform: scale(1);
            }
            50% {
                background-color: rgba(255, 255, 255, 0.1);
                transform: scale(1.01);
            }
        }

        @keyframes gradient-spin {
            0% {
                filter: hue-rotate(0deg) brightness(1);
            }
            50% {
                filter: hue-rotate(180deg) brightness(1.2);
            }
            100% {
                filter: hue-rotate(360deg) brightness(1);
            }
        }

        @keyframes text-glow {
            0%, 100% {
                text-shadow: 0 0 5px rgba(59, 130, 246, 0.3);
            }
            50% {
                text-shadow: 0 0 15px rgba(59, 130, 246, 0.6),
                           0 0 25px rgba(59, 130, 246, 0.4);
            }
        }

        /* Animation Classes */
        .animate-table-shadow {
            animation: table-shadow 4s ease-in-out infinite;
        }

        .animate-border-glow {
            animation: border-glow 3s ease-in-out infinite;
        }

        .animate-shimmer {
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.1),
                transparent
            );
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .animate-gradient-spin {
            animation: gradient-spin 3s linear infinite;
        }

        .animate-text-glow {
            animation: text-glow 2s ease-in-out infinite;
        }

        /* Hover Effects for Table Rows */
        tbody tr {
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(20px);
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* Dark mode adjustments */
        @media (prefers-color-scheme: dark) {
            tbody tr:hover {
                background-color: rgba(255, 255, 255, 0.05) !important;
            }
        }
    </style>
@endpush
