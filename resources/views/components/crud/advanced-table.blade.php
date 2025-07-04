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

<!-- Animated gradient border container -->
<div class="relative overflow-hidden rounded-[5px]">
    <!-- Animated gradient border -->
    <div class="absolute inset-0 rounded-[5px] p-[3px] animate-border-glow">
        <div class="absolute inset-0 rounded-[5px] bg-gradient-to-r from-yellow-400 via-purple-500 via-orange-500 to-yellow-400 bg-[length:300%_300%] animate-gradient-border opacity-80"></div>
        <div class="relative w-full h-full bg-black/90 backdrop-blur-xl rounded-[2px] border border-white/5"></div>
    </div>

    <!-- Table container with animated shadows -->
    <div class="relative backdrop-blur-xl bg-black/40 border-0 rounded-[2px] overflow-hidden m-[3px] animate-table-shadow {{ $responsive ? 'overflow-x-auto' : '' }}">
        <div class="overflow-x-auto">
            <table id="{{ $id }}" class="w-full">
                <thead>
                    <tr class="border-b border-white/10 relative">
                        @foreach ($columns as $column)
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white/90 backdrop-blur-sm relative {{ $sortable && ($column['sortable'] ?? true) ? 'cursor-pointer sort-header' : '' }}"
                                @if ($sortable && ($column['sortable'] ?? true)) data-field="{{ $column['field'] }}" @endif>
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-{{ ['yellow-500/5', 'purple-500/5', 'orange-500/5', 'blue-500/5', 'green-500/5', 'pink-500/5'][$loop->index % 6] }} to-transparent animate-shimmer-delay-{{ $loop->index % 6 }}"></div>
                                <span class="relative z-10">{{ $column['label'] }}</span>
                                @if ($sortable && ($column['sortable'] ?? true))
                                    <span class="sort-icon"></span>
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="{{ $id }}-body">
                    <!-- Loading row -->
                    <tr id="loadingRow" class="border-b border-white/5 transition-all duration-500 ease-out relative hover:bg-white/8 hover:backdrop-blur-md hover:border-white/20 hover:transform hover:scale-[1.01] animate-pulse-glow">
                        <td colspan="{{ count($columns) }}" class="px-6 py-4 text-center text-sm text-white/80 font-mono relative">
                            <svg class="animate-spin h-5 w-5 mr-3 text-blue-500 inline-block animate-gradient-spin" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span class="text-white/70 animate-text-glow">{{ $loadingText }}</span>
                        </td>
                    </tr>
                    <!-- No data row -->
                    <tr id="noDataRow" class="border-b border-white/5 transition-all duration-500 ease-out relative hover:bg-white/8 hover:backdrop-blur-md hover:border-white/20 hover:transform hover:scale-[1.01]" style="display: none;">
                        <td colspan="{{ count($columns) }}" class="px-6 py-4 text-center text-sm text-white/70 relative">
                            {{ $noDataText }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
    <style>
        /* Advanced Glassmorphic Table Animations */
        @keyframes border-glow {
            0%, 100% {
                opacity: 0.6;
                filter: hue-rotate(0deg) brightness(1);
            }
            25% {
                opacity: 0.8;
                filter: hue-rotate(90deg) brightness(1.2);
            }
            50% {
                opacity: 1;
                filter: hue-rotate(180deg) brightness(1.4);
            }
            75% {
                opacity: 0.8;
                filter: hue-rotate(270deg) brightness(1.2);
            }
        }

        @keyframes gradient-border {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes table-shadow {
            0%, 100% {
                box-shadow: 
                    0 0 30px rgba(255, 215, 0, 0.3),
                    0 0 60px rgba(138, 43, 226, 0.2),
                    0 0 90px rgba(255, 165, 0, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.1);
            }
            33% {
                box-shadow: 
                    0 0 40px rgba(138, 43, 226, 0.4),
                    0 0 80px rgba(255, 165, 0, 0.3),
                    0 0 120px rgba(255, 215, 0, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.15);
            }
            66% {
                box-shadow: 
                    0 0 35px rgba(255, 165, 0, 0.4),
                    0 0 70px rgba(255, 215, 0, 0.3),
                    0 0 105px rgba(138, 43, 226, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.12);
            }
        }

        @keyframes shimmer-delay-0 {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.8; }
        }

        @keyframes shimmer-delay-1 {
            0%, 100% { opacity: 0.3; }
            60% { opacity: 0.8; }
        }

        @keyframes shimmer-delay-2 {
            0%, 100% { opacity: 0.3; }
            70% { opacity: 0.8; }
        }

        @keyframes shimmer-delay-3 {
            0%, 100% { opacity: 0.3; }
            40% { opacity: 0.8; }
        }

        @keyframes shimmer-delay-4 {
            0%, 100% { opacity: 0.3; }
            80% { opacity: 0.8; }
        }

        @keyframes shimmer-delay-5 {
            0%, 100% { opacity: 0.3; }
            30% { opacity: 0.8; }
        }

        @keyframes pulse-glow {
            0%, 100% {
                opacity: 0.8;
                transform: scale(1);
            }
            50% {
                opacity: 1;
                transform: scale(1.01);
            }
        }

        @keyframes gradient-spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes text-glow {
            0%, 100% {
                text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
            }
            50% {
                text-shadow: 0 0 20px rgba(255, 215, 0, 0.8), 0 0 30px rgba(138, 43, 226, 0.4);
            }
        }

        /* Animation Classes */
        .animate-border-glow {
            animation: border-glow 4s ease-in-out infinite;
        }

        .animate-gradient-border {
            animation: gradient-border 3s ease-in-out infinite;
        }

        .animate-table-shadow {
            animation: table-shadow 6s ease-in-out infinite;
        }

        .animate-shimmer-delay-0 {
            animation: shimmer-delay-0 2s ease-in-out infinite;
        }

        .animate-shimmer-delay-1 {
            animation: shimmer-delay-1 2.2s ease-in-out infinite;
        }

        .animate-shimmer-delay-2 {
            animation: shimmer-delay-2 2.4s ease-in-out infinite;
        }

        .animate-shimmer-delay-3 {
            animation: shimmer-delay-3 2.6s ease-in-out infinite;
        }

        .animate-shimmer-delay-4 {
            animation: shimmer-delay-4 2.8s ease-in-out infinite;
        }

        .animate-shimmer-delay-5 {
            animation: shimmer-delay-5 3s ease-in-out infinite;
        }

        .animate-pulse-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }

        .animate-gradient-spin {
            animation: gradient-spin 2s linear infinite;
        }

        .animate-text-glow {
            animation: text-glow 3s ease-in-out infinite;
        }

        /* Enhanced Row Hover Effects */
        tbody tr {
            position: relative;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        tbody tr::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(45deg, #ffd700, #ff8c00, #9932cc);
            transform: scaleY(0);
            transition: transform 0.3s ease;
            border-radius: 0 2px 2px 0;
        }

        tbody tr:hover::before {
            transform: scaleY(1);
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            transform: translateX(8px) scale(1.01);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 8px 32px rgba(255, 215, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        tbody tr:hover td {
            color: rgba(255, 255, 255, 0.95);
        }

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
            color: #ffd700;
            text-shadow: 0 0 10px #ffd700;
        }

        .sort-header.sort-desc .sort-icon::after {
            content: '↓';
            opacity: 1;
            color: #ffd700;
            text-shadow: 0 0 10px #ffd700;
        }

        .sort-header:hover .sort-icon::after {
            opacity: 0.8;
            transform: scale(1.1);
        }

        /* Header hover effects */
        thead th:hover {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 1);
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }

        /* Dark mode adjustments */
        @media (prefers-color-scheme: dark) {
            tbody tr:hover {
                background-color: rgba(255, 255, 255, 0.05) !important;
            }
        }
    </style>
@endpush
