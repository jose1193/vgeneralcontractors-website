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

<div class="{{ $responsive ? 'overflow-x-auto' : '' }} glassmorphism-container">
    <table id="{{ $id }}" class="min-w-full glassmorphism-table">
        <thead class="glassmorphism-header">
            <tr>
                @foreach ($columns as $column)
                    <th class="glassmorphism-th {{ $sortable && ($column['sortable'] ?? true) ? 'cursor-pointer sort-header' : '' }}"
                        @if ($sortable && ($column['sortable'] ?? true)) data-field="{{ $column['field'] }}" @endif>
                        <div class="th-content">
                            {{ $column['label'] }}
                            @if ($sortable && ($column['sortable'] ?? true))
                                <span class="sort-icon"></span>
                            @endif
                        </div>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody id="{{ $id }}-body" class="glassmorphism-body">
            <!-- Loading row -->
            <tr id="loadingRow" class="glassmorphism-row loading-row">
                <td colspan="{{ count($columns) }}" class="glassmorphism-td loading-cell">
                    <div class="loading-content">
                        <div class="loading-spinner"></div>
                        <span class="loading-text">{{ $loadingText }}</span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@push('styles')
    <style>
        /* Glassmorphism Container */
        .glassmorphism-container {
            background: linear-gradient(135deg,
                    rgba(0, 0, 0, 0.9) 0%,
                    rgba(20, 20, 20, 0.8) 50%,
                    rgba(0, 0, 0, 0.95) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
            animation: containerGlow 3s ease-in-out infinite alternate;
        }

        @keyframes containerGlow {
            from {
                box-shadow:
                    0 8px 32px rgba(0, 0, 0, 0.3),
                    inset 0 1px 0 rgba(255, 255, 255, 0.1),
                    0 0 0 1px rgba(255, 255, 255, 0.05);
            }

            to {
                box-shadow:
                    0 12px 40px rgba(0, 0, 0, 0.4),
                    inset 0 1px 0 rgba(255, 255, 255, 0.15),
                    0 0 0 1px rgba(255, 255, 255, 0.1),
                    0 0 20px rgba(59, 130, 246, 0.1);
            }
        }

        /* Table Base */
        .glassmorphism-table {
            background: transparent;
            border-collapse: separate;
            border-spacing: 0;
        }

        /* Header with Gradient */
        .glassmorphism-header {
            background: linear-gradient(135deg,
                    rgba(59, 130, 246, 0.3) 0%,
                    rgba(147, 51, 234, 0.3) 25%,
                    rgba(236, 72, 153, 0.3) 50%,
                    rgba(59, 130, 246, 0.3) 75%,
                    rgba(147, 51, 234, 0.3) 100%);
            background-size: 200% 200%;
            animation: gradientShift 4s ease infinite;
            position: relative;
        }

        @keyframes gradientShift {
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

        .glassmorphism-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg,
                    transparent 0%,
                    rgba(255, 255, 255, 0.1) 50%,
                    transparent 100%);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        /* Header Cells */
        .glassmorphism-th {
            padding: 1rem 1.5rem;
            text-align: center;
            font-size: 0.75rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glassmorphism-th:hover {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 1);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .th-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Body */
        .glassmorphism-body {
            background: rgba(0, 0, 0, 0.2);
        }

        /* Rows with Hover Shadow */
        .glassmorphism-row {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .glassmorphism-row:hover {
            background: linear-gradient(135deg,
                    rgba(59, 130, 246, 0.1) 0%,
                    rgba(147, 51, 234, 0.1) 50%,
                    rgba(236, 72, 153, 0.1) 100%);
            transform: translateY(-2px) scale(1.01);
            box-shadow:
                0 8px 25px rgba(59, 130, 246, 0.2),
                0 4px 10px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .glassmorphism-row:hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg,
                    transparent 0%,
                    rgba(255, 255, 255, 0.05) 50%,
                    transparent 100%);
            border-radius: 8px;
            animation: rowShimmer 1s ease-out;
        }

        @keyframes rowShimmer {
            0% {
                transform: translateX(-100%);
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Table Cells */
        .glassmorphism-td {
            padding: 1rem 1.5rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            transition: all 0.3s ease;
        }

        .glassmorphism-row:hover .glassmorphism-td {
            color: rgba(255, 255, 255, 1);
            text-shadow: 0 0 10px rgba(59, 130, 246, 0.3);
        }

        /* Sort Icons */
        .sort-header .sort-icon {
            position: relative;
            transition: all 0.3s ease;
        }

        .sort-header .sort-icon::after {
            content: '↕️';
            font-size: 0.75rem;
            opacity: 0.6;
            transition: all 0.3s ease;
            filter: drop-shadow(0 0 3px rgba(59, 130, 246, 0.5));
        }

        .sort-header.sort-asc .sort-icon::after {
            content: '↑';
            opacity: 1;
            color: #60A5FA;
            transform: scale(1.2);
            filter: drop-shadow(0 0 8px rgba(96, 165, 250, 0.8));
            animation: sortPulse 0.5s ease-out;
        }

        .sort-header.sort-desc .sort-icon::after {
            content: '↓';
            opacity: 1;
            color: #F472B6;
            transform: scale(1.2);
            filter: drop-shadow(0 0 8px rgba(244, 114, 182, 0.8));
            animation: sortPulse 0.5s ease-out;
        }

        @keyframes sortPulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }

            100% {
                transform: scale(1.2);
            }
        }

        .sort-header:hover .sort-icon::after {
            opacity: 1;
            transform: scale(1.1);
            filter: drop-shadow(0 0 6px rgba(59, 130, 246, 0.6));
        }

        /* Loading Styles */
        .loading-row {
            background: rgba(0, 0, 0, 0.1);
        }

        .loading-cell {
            padding: 2rem;
        }

        .loading-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .loading-spinner {
            width: 24px;
            height: 24px;
            border: 3px solid rgba(59, 130, 246, 0.2);
            border-top: 3px solid #3B82F6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            filter: drop-shadow(0 0 8px rgba(59, 130, 246, 0.4));
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.8;
            }

            50% {
                opacity: 1;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {

            .glassmorphism-th,
            .glassmorphism-td {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }

            .glassmorphism-container {
                border-radius: 16px;
                margin: 0.5rem;
            }
        }

        /* Dark mode enhancements */
        .glassmorphism-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 50% 50%,
                    rgba(59, 130, 246, 0.03) 0%,
                    transparent 70%);
            pointer-events: none;
            animation: ambientGlow 4s ease-in-out infinite alternate;
        }

        @keyframes ambientGlow {
            from {
                opacity: 0.3;
            }

            to {
                opacity: 0.7;
            }
        }
    </style>
@endpush
