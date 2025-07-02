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

<div class="{{ $responsive ? 'overflow-x-auto' : '' }} table-container">
    <div class="rounded-lg overflow-hidden">
        <table id="{{ $id }}"
            class="min-w-full text-sm text-left text-gray-300 bg-gray-800/50 divide-y divide-gray-700">
            <thead class="bg-gradient-to-r from-gray-800 to-gray-700">
                <tr>
                    @foreach ($columns as $column)
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-200 uppercase tracking-wider transition-colors duration-200 {{ $sortable && ($column['sortable'] ?? true) ? 'cursor-pointer sort-header hover:bg-gray-700/50' : '' }}"
                            @if ($sortable && ($column['sortable'] ?? true)) data-field="{{ $column['field'] }}" @endif
                            style="text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">
                            {{ $column['label'] }}
                            @if ($sortable && ($column['sortable'] ?? true))
                                <span class="sort-icon"></span>
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="{{ $id }}-body" class="divide-y divide-gray-700">
                <!-- Loading row -->
                <tr id="loadingRow" class="bg-gray-800/30">
                    <td colspan="{{ count($columns) }}" class="px-6 py-12 text-center">
                        <div class="flex items-center justify-center space-x-3">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-400"></div>
                            <span class="text-gray-300 text-lg">{{ $loadingText }}</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@push('styles')
    <style>
        /* Table Container with Animated Border */
        .table-container {
            position: relative;
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(139, 92, 246, 0.2);
            overflow: hidden;
        }

        .table-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 1rem;
            border: 1px solid transparent;
            background: linear-gradient(90deg, #f59e0b, #eab308, #8b5cf6, #d946ef, #f59e0b) border-box;
            background-size: 200% auto;
            -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: destination-out;
            mask-composite: exclude;
            z-index: 1;
            animation: rotateGradient 3s linear infinite;
            pointer-events: none;
        }

        @keyframes rotateGradient {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 200% 50%;
            }
        }

        /* Enhanced Sort Icons */
        .sort-header .sort-icon::after {
            content: '↕️';
            font-size: 0.875rem;
            margin-left: 0.5rem;
            opacity: 0.5;
            transition: all 0.3s ease;
        }

        .sort-header.sort-asc .sort-icon::after {
            content: '↑';
            opacity: 1;
            color: #8b5cf6;
            text-shadow: 0 0 4px rgba(139, 92, 246, 0.5);
        }

        .sort-header.sort-desc .sort-icon::after {
            content: '↓';
            opacity: 1;
            color: #8b5cf6;
            text-shadow: 0 0 4px rgba(139, 92, 246, 0.5);
        }

        .sort-header:hover .sort-icon::after {
            opacity: 0.8;
            transform: scale(1.1);
        }

        /* Enhanced Table Row Styles */
        #{{ $id }}-body tr {
            transition: all 0.3s ease;
            position: relative;
        }

        #{{ $id }}-body tr:nth-child(even) {
            background: rgba(55, 65, 81, 0.3);
        }

        #{{ $id }}-body tr:hover {
            background: rgba(71, 85, 105, 0.5);
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.1);
        }

        /* Table Cell Enhancements */
        #{{ $id }} td {
            position: relative;
            transition: all 0.3s ease;
        }

        #{{ $id }} td:hover {
            background: rgba(139, 92, 246, 0.05);
        }

        /* Deleted Item Styles */
        .deleted-item {
            background: rgba(239, 68, 68, 0.1) !important;
            border-left: 4px solid #ef4444;
        }

        .deleted-item:hover {
            background: rgba(239, 68, 68, 0.2) !important;
        }

        .deleted-item td {
            color: #fca5a5 !important;
        }

        /* Loading Animation Enhancement */
        #loadingRow {
            background: linear-gradient(90deg, rgba(55, 65, 81, 0.3), rgba(75, 85, 99, 0.3), rgba(55, 65, 81, 0.3));
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        /* Enhanced Action Buttons */
        .table-action-btn {
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.3s ease;
            backdrop-filter: blur(4px);
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .table-action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .table-action-btn:hover::before {
            left: 100%;
        }

        .table-action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Edit Button */
        .btn-edit {
            background: rgba(34, 197, 94, 0.2);
            color: #bbf7d0;
            border-color: rgba(34, 197, 94, 0.3);
        }

        .btn-edit:hover {
            background: rgba(34, 197, 94, 0.3);
            color: #dcfce7;
            border-color: rgba(34, 197, 94, 0.5);
        }

        /* Delete Button */
        .btn-delete {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .btn-delete:hover {
            background: rgba(239, 68, 68, 0.3);
            color: #fecaca;
            border-color: rgba(239, 68, 68, 0.5);
        }

        /* Restore Button */
        .btn-restore {
            background: rgba(59, 130, 246, 0.2);
            color: #93c5fd;
            border-color: rgba(59, 130, 246, 0.3);
        }

        .btn-restore:hover {
            background: rgba(59, 130, 246, 0.3);
            color: #bfdbfe;
            border-color: rgba(59, 130, 246, 0.5);
        }

        /* Status Badges */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-active {
            background: rgba(34, 197, 94, 0.2);
            color: #bbf7d0;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .status-inactive {
            background: rgba(156, 163, 175, 0.2);
            color: #d1d5db;
            border: 1px solid rgba(156, 163, 175, 0.3);
        }

        /* Responsive Enhancements */
        @media (max-width: 768px) {

            #{{ $id }} th,
            #{{ $id }} td {
                padding: 0.75rem 0.5rem;
                font-size: 0.75rem;
            }

            .table-action-btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.625rem;
            }
        }
    </style>
@endpush
