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

<div class="glassmorphism-container {{ $responsive ? 'responsive-container' : '' }}">
    <div class="glassmorphism-table-wrapper">
        <table id="{{ $id }}" class="glassmorphism-table">
            <thead class="glassmorphism-header">
                <tr>
                    @foreach ($columns as $column)
                        <th class="glassmorphism-th {{ $sortable && ($column['sortable'] ?? true) ? 'sortable-header' : '' }}"
                            @if ($sortable && ($column['sortable'] ?? true)) data-field="{{ $column['field'] }}" @endif>
                            <div class="th-content">
                                <span class="column-text">{{ $column['label'] }}</span>
                                @if ($sortable && ($column['sortable'] ?? true))
                                    <span class="sort-indicator">
                                        <svg class="sort-icon" viewBox="0 0 24 24">
                                            <path d="M7 14l5-5 5 5z" />
                                            <path d="M7 10l5 5 5-5z" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="{{ $id }}-body" class="glassmorphism-body">
                <!-- Loading row -->
                <tr id="loadingRow" class="loading-row">
                    <td colspan="{{ count($columns) }}" class="loading-cell">
                        <div class="loading-content">
                            <div class="loading-spinner">
                                <div class="spinner-ring"></div>
                                <div class="spinner-ring"></div>
                                <div class="spinner-ring"></div>
                            </div>
                            <span class="loading-text">{{ $loadingText }}</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Modern Glassmorphism Table 2025 - Box Shadow Mejorado */
    .glassmorphism-container {
        position: relative;
        margin: 1rem 0;
        animation: fadeInUp 0.6s ease-out;
    }

    .glassmorphism-table-wrapper {
        background: rgba(0, 0, 0, 0.76);
        border-radius: 16px;
        /* Box shadow mejorado - más sutil y elegante */
        box-shadow:
            0 4px 20px rgba(0, 0, 0, 0.25),
            0 2px 8px rgba(0, 0, 0, 0.15),
            0 0 0 1px rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(1.5px);
        -webkit-backdrop-filter: blur(1.5px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        overflow: hidden;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glassmorphism-table-wrapper:hover {
        /* Hover shadow mejorado */
        box-shadow:
            0 8px 32px rgba(0, 0, 0, 0.3),
            0 4px 16px rgba(0, 0, 0, 0.2),
            0 0 0 1px rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }

    .glassmorphism-table-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg,
                transparent 0%,
                rgba(255, 255, 255, 0.15) 50%,
                transparent 100%);
        animation: shimmer 2s infinite;
    }

    .glassmorphism-table {
        width: 100%;
        border-collapse: collapse;
        color: rgba(255, 255, 255, 0.95);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .glassmorphism-header {
        background: linear-gradient(135deg,
                rgba(255, 255, 255, 0.08) 0%,
                rgba(255, 255, 255, 0.03) 100%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        position: relative;
    }

    .glassmorphism-th {
        padding: 1rem 1.5rem;
        text-align: center;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: rgba(255, 255, 255, 0.8);
        border-right: none;
        position: relative;
        transition: all 0.3s ease;
    }

    .glassmorphism-th:last-child {
        border-right: none;
    }

    .glassmorphism-th:hover {
        background: rgba(255, 255, 255, 0.06);
        color: rgba(255, 255, 255, 0.95);
    }

    .th-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .sortable-header {
        cursor: pointer;
        user-select: none;
    }

    .sortable-header:hover {
        background: rgba(255, 255, 255, 0.08);
    }

    .sort-indicator {
        display: flex;
        align-items: center;
        opacity: 0.5;
        transition: all 0.3s ease;
    }

    .sort-icon {
        width: 16px;
        height: 16px;
        fill: currentColor;
        filter: drop-shadow(0 0 4px rgba(255, 255, 255, 0.2));
    }

    .sortable-header:hover .sort-indicator {
        opacity: 1;
        transform: scale(1.1);
    }

    .sortable-header.sort-asc .sort-indicator {
        opacity: 1;
        color: #60A5FA;
        transform: rotate(0deg);
    }

    .sortable-header.sort-desc .sort-indicator {
        opacity: 1;
        color: #60A5FA;
        transform: rotate(180deg);
    }

    .glassmorphism-body {
        background: rgba(0, 0, 0, 0.3);
    }

    .glassmorphism-body tr {
        border-bottom: none;
        transition: all 0.3s ease;
        animation: fadeIn 0.5s ease-out;
    }

    .glassmorphism-body tr:hover {
        background: rgba(255, 255, 255, 0.04);
        transform: translateY(-1px);
        /* Shadow sutil para hover de filas */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .glassmorphism-body td {
        padding: 1rem 1.5rem;
        text-align: center;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.875rem;
        border-right: none;
    }

    .glassmorphism-body td:last-child {
        border-right: none;
    }

    .loading-row {
        animation: pulse 2s infinite;
    }

    .loading-cell {
        padding: 3rem 1.5rem !important;
    }

    .loading-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .loading-spinner {
        position: relative;
        width: 60px;
        height: 60px;
    }

    .spinner-ring {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 3px solid transparent;
        border-top: 3px solid #60A5FA;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .spinner-ring:nth-child(2) {
        animation-delay: -0.15s;
        border-top-color: #34D399;
    }

    .spinner-ring:nth-child(3) {
        animation-delay: -0.3s;
        border-top-color: #F59E0B;
    }

    .loading-text {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
    }

    .responsive-container {
        overflow-x: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
    }

    .responsive-container::-webkit-scrollbar {
        height: 8px;
    }

    .responsive-container::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 4px;
    }

    .responsive-container::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 4px;
        transition: background 0.3s ease;
    }

    .responsive-container::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Modern Glassmorphism Pagination - Shadow mejorado */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 2rem 0;
        gap: 0.5rem;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        background: rgba(0, 0, 0, 0.76);
        border-radius: 12px;
        padding: 0.5rem;
        /* Shadow mejorado para paginación */
        box-shadow:
            0 4px 16px rgba(0, 0, 0, 0.2),
            0 2px 8px rgba(0, 0, 0, 0.1),
            0 0 0 1px rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(1.5px);
        -webkit-backdrop-filter: blur(1.5px);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .pagination .page-item {
        margin: 0;
    }

    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        padding: 0;
        border: none;
        background: transparent;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .pagination .page-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg,
                rgba(255, 255, 255, 0.08) 0%,
                rgba(255, 255, 255, 0.03) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .pagination .page-link:hover {
        color: rgba(255, 255, 255, 0.95);
        transform: translateY(-2px);
        /* Shadow sutil para hover de páginas */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .pagination .page-link:hover::before {
        opacity: 1;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #60A5FA 0%, #3B82F6 100%);
        color: white;
        /* Shadow para página activa */
        box-shadow: 0 4px 16px rgba(96, 165, 250, 0.2);
        transform: translateY(-1px);
    }

    .pagination .page-item.active .page-link::before {
        opacity: 0;
    }

    .pagination .page-item.disabled .page-link {
        color: rgba(255, 255, 255, 0.3);
        cursor: not-allowed;
        transform: none;
    }

    .pagination .page-item.disabled .page-link:hover {
        transform: none;
        box-shadow: none;
    }

    .pagination .page-link span {
        position: relative;
        z-index: 1;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(100%);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {

        .glassmorphism-th,
        .glassmorphism-body td {
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
        }

        .loading-cell {
            padding: 2rem 1rem !important;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
        }

        .pagination .page-link {
            width: 36px;
            height: 36px;
            font-size: 0.8rem;
        }
    }

    /* Dark mode enhancements */
    @media (prefers-color-scheme: dark) {
        .glassmorphism-table-wrapper {
            background: rgba(0, 0, 0, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow:
                0 4px 24px rgba(0, 0, 0, 0.3),
                0 2px 12px rgba(0, 0, 0, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.03);
        }

        .pagination {
            background: rgba(0, 0, 0, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow:
                0 4px 20px rgba(0, 0, 0, 0.25),
                0 2px 10px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.03);
        }
    }
</style>
