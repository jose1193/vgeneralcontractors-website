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
    /* Tavily-inspired Modern Glassmorphism Table 2025+ */
    .glassmorphism-container {
        position: relative;
        margin: 1.5rem 0;
        animation: fadeInUp 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glassmorphism-table-wrapper {
        background: linear-gradient(135deg, rgba(40, 0, 80, 0.65) 0%, rgba(0, 0, 0, 0.82) 100%);
        border-radius: 22px;
        box-shadow: 0 8px 40px 0 rgba(128, 0, 255, 0.18), 0 0 0 6px rgba(128, 0, 255, 0.10), 0 1.5px 24px 0 rgba(0, 255, 255, 0.08);
        backdrop-filter: blur(8px) saturate(1.2);
        -webkit-backdrop-filter: blur(8px) saturate(1.2);
        border: 1.5px solid rgba(255, 255, 255, 0.13);
        overflow: hidden;
        position: relative;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glassmorphism-table-wrapper:hover {
        box-shadow: 0 16px 60px rgba(128, 0, 255, 0.22), 0 0 0 8px rgba(0, 255, 255, 0.10);
        transform: translateY(-4px) scale(1.01);
    }

    .glassmorphism-table-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2.5px;
        background: linear-gradient(90deg, rgba(0, 255, 255, 0.18) 0%, rgba(128, 0, 255, 0.25) 50%, rgba(255, 255, 255, 0.12) 100%);
        filter: blur(1.5px);
        animation: shimmer 2.5s infinite linear;
        z-index: 2;
    }

    .glassmorphism-table {
        width: 100%;
        border-collapse: collapse;
        color: rgba(255, 255, 255, 0.98);
        font-family: 'Segoe UI', Roboto, 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: 1.01rem;
    }

    .glassmorphism-header {
        background: linear-gradient(120deg, rgba(128, 0, 255, 0.13) 0%, rgba(0, 255, 255, 0.10) 100%);
        border-bottom: 1.5px solid rgba(255, 255, 255, 0.13);
        position: relative;
    }

    .glassmorphism-th {
        padding: 1.1rem 1.7rem;
        text-align: center;
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: rgba(255, 255, 255, 0.88);
        border-right: none;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 0 rgba(128, 0, 255, 0.07);
    }

    .glassmorphism-th:last-child {
        border-right: none;
    }

    .glassmorphism-th:hover {
        background: linear-gradient(90deg, rgba(128, 0, 255, 0.13) 0%, rgba(0, 255, 255, 0.10) 100%);
        color: #fff;
        box-shadow: 0 2px 8px rgba(128, 0, 255, 0.10);
    }

    .th-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
    }

    .sortable-header {
        cursor: pointer;
        user-select: none;
        filter: drop-shadow(0 0 2px #80f8ff);
    }

    .sortable-header:hover {
        background: linear-gradient(90deg, rgba(0, 255, 255, 0.13) 0%, rgba(128, 0, 255, 0.10) 100%);
        color: #fff;
    }

    .sort-indicator {
        display: flex;
        align-items: center;
        opacity: 0.7;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sort-icon {
        width: 18px;
        height: 18px;
        fill: url(#sort-gradient);
        filter: drop-shadow(0 0 6px #80f8ff) drop-shadow(0 0 2px #8000ff);
    }

    .sortable-header:hover .sort-indicator {
        opacity: 1;
        transform: scale(1.13);
    }

    .sortable-header.sort-asc .sort-indicator {
        opacity: 1;
        color: #80f8ff;
        filter: drop-shadow(0 0 8px #80f8ff);
        transform: rotate(0deg);
    }

    .sortable-header.sort-desc .sort-indicator {
        opacity: 1;
        color: #8000ff;
        filter: drop-shadow(0 0 8px #8000ff);
        transform: rotate(180deg);
    }

    .glassmorphism-body {
        background: linear-gradient(120deg, rgba(0, 0, 0, 0.32) 0%, rgba(128, 0, 255, 0.07) 100%);
    }

    .glassmorphism-body tr {
        border-bottom: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glassmorphism-body tr:hover {
        background: linear-gradient(90deg, rgba(128, 0, 255, 0.10) 0%, rgba(0, 255, 255, 0.10) 100%);
        transform: translateY(-2px) scale(1.01);
        box-shadow: 0 4px 24px rgba(128, 0, 255, 0.10);
    }

    .glassmorphism-body td {
        padding: 1.1rem 1.7rem;
        text-align: center;
        color: rgba(255, 255, 255, 0.93);
        font-size: 0.93rem;
        border-right: none;
        border-bottom: 1px solid rgba(128, 0, 255, 0.04);
    }

    .glassmorphism-body td:last-child {
        border-right: none;
    }

    .loading-row {
        animation: pulse 2s infinite;
    }

    .loading-cell {
        padding: 3.5rem 1.7rem !important;
    }

    .loading-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.2rem;
    }

    .loading-spinner {
        position: relative;
        width: 64px;
        height: 64px;
    }

    .spinner-ring {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 3.5px solid transparent;
        border-top: 3.5px solid #80f8ff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .spinner-ring:nth-child(2) {
        animation-delay: -0.15s;
        border-top-color: #8000ff;
    }

    .spinner-ring:nth-child(3) {
        animation-delay: -0.3s;
        border-top-color: #f59e0b;
    }

    .loading-text {
        font-size: 0.97rem;
        color: rgba(128, 0, 255, 0.7);
        font-weight: 600;
        letter-spacing: 0.04em;
    }

    .responsive-container {
        overflow-x: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(128, 0, 255, 0.18) transparent;
    }

    .responsive-container::-webkit-scrollbar {
        height: 8px;
    }

    .responsive-container::-webkit-scrollbar-track {
        background: rgba(128, 0, 255, 0.07);
        border-radius: 4px;
    }

    .responsive-container::-webkit-scrollbar-thumb {
        background: rgba(128, 0, 255, 0.22);
        border-radius: 4px;
        transition: background 0.3s ease;
    }

    .responsive-container::-webkit-scrollbar-thumb:hover {
        background: rgba(128, 0, 255, 0.35);
    }

    /* Modern Glassmorphism Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 2.2rem 0;
        gap: 0.7rem;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        background: linear-gradient(120deg, rgba(128, 0, 255, 0.13) 0%, rgba(0, 255, 255, 0.10) 100%);
        border-radius: 14px;
        padding: 0.7rem;
        box-shadow: 0 4px 30px rgba(128, 0, 255, 0.10);
        backdrop-filter: blur(2.5px);
        -webkit-backdrop-filter: blur(2.5px);
        border: 1.5px solid rgba(128, 0, 255, 0.13);
    }

    .pagination .page-item {
        margin: 0;
    }

    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        padding: 0;
        border: none;
        background: transparent;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        border-radius: 10px;
        font-size: 0.97rem;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 1px 0 rgba(128, 0, 255, 0.07);
    }

    .pagination .page-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(128, 0, 255, 0.13) 0%, rgba(0, 255, 255, 0.10) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .pagination .page-link:hover {
        color: #fff;
        transform: translateY(-2px) scale(1.04);
        box-shadow: 0 4px 20px rgba(128, 0, 255, 0.18);
    }

    .pagination .page-link:hover::before {
        opacity: 1;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #80f8ff 0%, #8000ff 100%);
        color: white;
        box-shadow: 0 4px 20px rgba(128, 0, 255, 0.22);
        transform: translateY(-1px) scale(1.03);
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
            transform: translateY(24px);
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
            padding: 0.8rem 1.1rem;
            font-size: 0.85rem;
        }

        .loading-cell {
            padding: 2.2rem 1.1rem !important;
        }

        .loading-spinner {
            width: 44px;
            height: 44px;
        }

        .pagination .page-link {
            width: 38px;
            height: 38px;
            font-size: 0.85rem;
        }
    }

    /* Dark mode enhancements */
    @media (prefers-color-scheme: dark) {
        .glassmorphism-table-wrapper {
            background: linear-gradient(135deg, rgba(40, 0, 80, 0.85) 0%, rgba(0, 0, 0, 0.92) 100%);
            border: 1.5px solid rgba(255, 255, 255, 0.13);
        }

        .pagination {
            background: linear-gradient(120deg, rgba(128, 0, 255, 0.18) 0%, rgba(0, 255, 255, 0.13) 100%);
            border: 1.5px solid rgba(255, 255, 255, 0.13);
        }
    }
</style>
