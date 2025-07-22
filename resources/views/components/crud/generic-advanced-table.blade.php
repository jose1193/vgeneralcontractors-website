@props([
    'id' => 'crud-advanced-table',
    'columns' => [],
    'managerName' => 'crudManager',
    'loadingText' => __('loading'),
    'noDataText' => __('no_data'),
    'responsive' => true,
    'sortable' => true,
    'darkMode' => true,
])

<div class="glassmorphism-container {{ $responsive ? 'responsive-container' : '' }}">
    <!-- Contenedor de la tabla con scroll -->
    <div class="glassmorphism-table-wrapper glassmorphism-scroll-wrapper">
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

        <!-- Protector invisible para prevenir efectos en las primeras 4 filas -->
        <div class="rows-protection-overlay"></div>
    </div>

    <!-- Shimmer efecto completamente separado de la estructura de la tabla -->
    <div class="header-shimmer-overlay"></div>
</div>

<style>
    /* Premium Glassmorphism Table 2025 with Purple Shadows */
    .glassmorphism-container {
        position: relative;
        margin: 1rem 0;
        border-radius: 20px;
        padding: 1.5rem;

        /* Crystal Glass Background with Premium Transparency */
        background: rgba(0, 0, 0, 0.78);

        /* Premium Purple Box Shadow System */
        box-shadow:
            0 8px 32px 0 rgba(138, 43, 226, 0.25),
            0 16px 64px 0 rgba(128, 0, 255, 0.18),
            0 4px 16px 0 rgba(75, 0, 130, 0.3),
            0 2px 8px 0 rgba(147, 51, 234, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.15),
            inset 0 -1px 0 rgba(255, 255, 255, 0.08);

        /* Advanced Blur Effects */
        backdrop-filter: blur(20px) saturate(1.3);
        -webkit-backdrop-filter: blur(20px) saturate(1.3);

        /* Refined Border for Glass Effect */
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-top: 1px solid rgba(255, 255, 255, 0.25);
    }

    .glassmorphism-container::before {
        display: none;
        /* Elimina completamente el pseudo-elemento */
    }

    .glassmorphism-container:hover {
        box-shadow:
            0 12px 48px 0 rgba(138, 43, 226, 0.35),
            0 24px 80px 0 rgba(128, 0, 255, 0.25),
            0 6px 24px 0 rgba(75, 0, 130, 0.4),
            0 3px 12px 0 rgba(147, 51, 234, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.2),
            inset 0 -1px 0 rgba(255, 255, 255, 0.1);
    }

    .glassmorphism-container:hover::before {
        display: none;
        opacity: 0;
    }

    .glassmorphism-table-wrapper {
        /* Enhanced Crystal Glass Background */
        background: rgba(0, 0, 0, 0.82);
        border-radius: 16px;

        /* Premium Purple Box Shadow System */
        box-shadow:
            0 6px 24px 0 rgba(138, 43, 226, 0.22),
            0 12px 48px 0 rgba(128, 0, 255, 0.15),
            0 2px 12px 0 rgba(75, 0, 130, 0.25),
            0 1px 6px 0 rgba(147, 51, 234, 0.18),
            inset 0 1px 0 rgba(255, 255, 255, 0.12),
            inset 0 -1px 0 rgba(255, 255, 255, 0.06);

        /* Enhanced Blur Effects */
        backdrop-filter: blur(16px) saturate(1.2);
        -webkit-backdrop-filter: blur(16px) saturate(1.2);

        /* Premium Glass Border */
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-top: 1px solid rgba(255, 255, 255, 0.18);

        /* Configuración de scroll fija */
        overflow-x: auto;
        overflow-y: auto;
        max-height: 70vh;
        width: 100%;
        position: relative;
    }

    /* Forzar scroll horizontal en responsive */
    .responsive-container {
        overflow-x: auto;
        overflow-y: visible;
        width: 100%;
        /* Asegurar que el scroll horizontal es visible */
        scrollbar-width: thin;
        scrollbar-color: rgba(138, 43, 226, 0.5) transparent;
    }

    /* Extra: Si quieres scroll vertical en mobile también */
    .glassmorphism-scroll-wrapper {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 70vh;
        width: 100%;
    }

    @media (max-width: 768px) {

        .glassmorphism-table-wrapper,
        .glassmorphism-scroll-wrapper {
            max-height: 50vh;
            overflow-x: auto !important;
            overflow-y: auto;
        }

        .header-shimmer-container {
            overflow: visible;
        }

        .glassmorphism-container {
            padding: 1rem;
            margin: 0.5rem 0;
        }
    }

    /* Elimina el efecto de cuadro transparente al hacer hover */
    .glassmorphism-table-wrapper::after {
        display: none;
        /* Eliminamos completamente este efecto */
    }

    .glassmorphism-table-wrapper:hover::after {
        opacity: 0;
        /* Desactivado */
    }

    .glassmorphism-table {
        width: 100%;
        min-width: 100%;
        /* Asegura que la tabla ocupe al menos el 100% del ancho */
        border-collapse: collapse;
        color: rgba(255, 255, 255, 0.95);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        table-layout: auto;
        /* Permite que la tabla se expanda según el contenido */
    }

    /* Nuevo contenedor para aislar el shimmer solo al header */
    .header-shimmer-overlay {
        position: absolute;
        top: 1.5rem;
        /* Alineado con el padding del contenedor padre */
        left: 1.5rem;
        right: 1.5rem;
        height: 60px;
        /* Altura fija para el header */
        border-radius: 16px 16px 0 0;
        pointer-events: none;
        /* No intercepta eventos de mouse */
        z-index: 5;
        /* Sobre todo lo demás */
        overflow: hidden;
        /* Contiene el efecto shimmer */
        background-color: transparent;
    }

    /* Protector invisible para prevenir efectos en las primeras 4 filas */
    .rows-protection-overlay {
        position: absolute;
        top: 60px;
        /* Justo debajo del header */
        left: 0;
        right: 0;
        height: 200px;
        /* Altura aproximada para cubrir 4 filas */
        z-index: 10;
        /* Por encima de todo */
        background-color: transparent;
        pointer-events: none;
        /* No intercepta eventos de mouse */
        user-select: none;
        /* No permite selección de texto */
    }

    /* El shimmer solo afecta al encabezado */
    .header-shimmer-overlay::after {
        content: '';
        position: absolute;
        top: 0;
        left: -150px;
        width: 150px;
        height: 100%;
        background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.3) 25%,
                rgba(138, 43, 226, 0.4) 50%,
                rgba(255, 255, 255, 0.3) 75%,
                transparent 100%);
        animation: shimmer-fixed 3s infinite;
        pointer-events: none;
    }

    @keyframes shimmer-fixed {
        0% {
            left: -150px;
        }

        100% {
            left: 100%;
        }
    }

    .glassmorphism-header {
        /* Premium Glass Header Background */
        background: rgba(0, 0, 0, 0.85);

        /* Enhanced Purple Shadow for Header */
        box-shadow:
            0 2px 16px 0 rgba(138, 43, 226, 0.15),
            0 4px 24px 0 rgba(128, 0, 255, 0.1),
            0 1px 6px 0 rgba(75, 0, 130, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);

        /* Advanced Blur for Header */
        backdrop-filter: blur(12px) saturate(1.1);
        -webkit-backdrop-filter: blur(12px) saturate(1.1);

        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 16px 16px 0 0;
        position: relative;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    /* Shimmer animated effect for table header - removed to prevent conflicts */
    .glassmorphism-header::after {
        display: none;
        /* Desactivado para evitar conflictos con el overlay */
    }

    .glassmorphism-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg,
                rgba(255, 255, 255, 0.12) 0%,
                rgba(255, 255, 255, 0.06) 25%,
                transparent 50%,
                rgba(138, 43, 226, 0.08) 75%,
                rgba(128, 0, 255, 0.12) 100%);
        border-radius: 16px 16px 0 0;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .glassmorphism-header:hover::before {
        opacity: 1;
    }

    .glassmorphism-th {
        padding: 1rem 1.5rem;
        text-align: center;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: rgba(255, 255, 255, 0.85);
        border-right: none;
        position: relative;
        transition: all 0.3s ease;
    }

    .glassmorphism-th:last-child {
        border-right: none;
    }

    .glassmorphism-th:hover {
        background: rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.98);
        box-shadow:
            0 2px 8px 0 rgba(138, 43, 226, 0.2),
            0 4px 16px 0 rgba(128, 0, 255, 0.1);
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
        background: rgba(255, 255, 255, 0.15);
        box-shadow:
            0 2px 12px 0 rgba(138, 43, 226, 0.25),
            0 4px 20px 0 rgba(128, 0, 255, 0.15);
    }

    .sort-indicator {
        display: flex;
        align-items: center;
        opacity: 0.6;
        transition: all 0.3s ease;
    }

    .sort-icon {
        width: 16px;
        height: 16px;
        fill: currentColor;
        filter: drop-shadow(0 0 6px rgba(138, 43, 226, 0.5));
    }

    .sortable-header:hover .sort-indicator {
        opacity: 1;
        transform: scale(1.1);
        filter: drop-shadow(0 0 8px rgba(138, 43, 226, 0.7));
    }

    .sortable-header.sort-asc .sort-indicator {
        opacity: 1;
        color: #A855F7;
        transform: rotate(0deg);
        filter: drop-shadow(0 0 8px rgba(168, 85, 247, 0.8));
    }

    .sortable-header.sort-desc .sort-indicator {
        opacity: 1;
        color: #A855F7;
        transform: rotate(180deg);
        filter: drop-shadow(0 0 8px rgba(168, 85, 247, 0.8));
    }

    /* Glassmorphism Body and Rows */
    .glassmorphism-body {
        background: rgba(0, 0, 0, 0.4);
        position: relative;
        z-index: 2;
        isolation: isolate;
        /* Aisla completamente del resto */
    }

    /* Base style para todas las filas */
    .glassmorphism-body tr {
        border-bottom: none;
        transition: all 0.3s ease;
        animation: fadeIn 0.5s ease-out;
        position: relative;
        isolation: isolate;
        /* Cada fila aislada */
        background: rgba(0, 0, 0, 0.4);
        /* Color de fondo fijo para cada fila */
        z-index: 2;
        /* Asegura que las filas estén sobre cualquier efecto */
    }

    /* Solución específica para las primeras 4 filas - elimina todo tipo de efecto hover */
    .glassmorphism-body tr:nth-child(-n+4) {
        isolation: isolate;
        z-index: 3;
        position: relative;
    }

    /* Prevenir CUALQUIER efecto en hover para las primeras 4 filas */
    .glassmorphism-body tr:nth-child(-n+4):hover {
        background: rgba(0, 0, 0, 0.4) !important;
        /* Mismo color que el fondo normal */
        transform: none !important;
        /* Sin transformación */
        box-shadow: none !important;
        /* Sin sombra */
    }

    /* Prevenir específicamente cualquier pseudo-elemento en hover */
    .glassmorphism-body tr:nth-child(-n+4):hover::before,
    .glassmorphism-body tr:nth-child(-n+4):hover::after {
        display: none !important;
        opacity: 0 !important;
    }

    /* Efecto hover controlado solo para filas a partir de la 5 */
    .glassmorphism-body tr:nth-child(n+5):hover {
        background: rgba(255, 255, 255, 0.08);
        transform: translateY(-1px);
        box-shadow:
            0 4px 20px rgba(138, 43, 226, 0.15),
            0 8px 32px rgba(128, 0, 255, 0.1);
    }

    /* Estilo base para todas las celdas - sin efectos de hover */
    .glassmorphism-body td {
        padding: 1rem 1.5rem;
        text-align: center;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.875rem;
        border-right: none;
        background: transparent;
        /* Sin fondo propio */
        transition: none;
        /* Sin transiciones */
        position: static;
        /* Evita posicionamiento que podría causar problemas */
    }

    /* Elimina completamente cualquier efecto visual para celdas en las primeras 4 filas */
    .glassmorphism-body tr:nth-child(-n+4) td {
        transition: none !important;
        transform: none !important;
        box-shadow: none !important;
        background: transparent !important;
    }

    /* Tratamiento especial para columnas con fechas */
    .glassmorphism-body td[data-field="created_at"],
    .glassmorphism-body td[data-field="created"],
    .glassmorphism-body td:last-child {
        background: transparent !important;
        transition: none !important;
        transform: none !important;
    }

    /* Eliminar cualquier efecto hover en todas las celdas */
    .glassmorphism-body td:hover {
        background: transparent !important;
        transform: none !important;
        box-shadow: none !important;
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
        border-top: 3px solid #A855F7;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        filter: drop-shadow(0 0 8px rgba(168, 85, 247, 0.6));
    }

    .spinner-ring:nth-child(2) {
        animation-delay: -0.15s;
        border-top-color: #8B5CF6;
        filter: drop-shadow(0 0 8px rgba(139, 92, 246, 0.6));
    }

    .spinner-ring:nth-child(3) {
        animation-delay: -0.3s;
        border-top-color: #7C3AED;
        filter: drop-shadow(0 0 8px rgba(124, 58, 237, 0.6));
    }

    .loading-text {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
        text-shadow: 0 0 10px rgba(168, 85, 247, 0.3);
    }

    .responsive-container {
        overflow-x: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(138, 43, 226, 0.5) transparent;
    }

    .responsive-container::-webkit-scrollbar {
        height: 8px;
    }

    .responsive-container::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }

    .responsive-container::-webkit-scrollbar-thumb {
        background: linear-gradient(90deg, rgba(138, 43, 226, 0.6), rgba(128, 0, 255, 0.8));
        border-radius: 4px;
        transition: background 0.3s ease;
    }

    .responsive-container::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(90deg, rgba(138, 43, 226, 0.8), rgba(128, 0, 255, 1));
    }

    /* Enhanced Glassmorphism Pagination */
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
        background: rgba(0, 0, 0, 0.8);
        border-radius: 12px;
        padding: 0.5rem;
        box-shadow:
            0 4px 24px rgba(138, 43, 226, 0.2),
            0 8px 40px rgba(128, 0, 255, 0.1);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.1);
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
        cursor: pointer;
    }

    .pagination .page-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg,
                rgba(255, 255, 255, 0.1) 0%,
                rgba(138, 43, 226, 0.1) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .pagination .page-link:hover:not(:disabled) {
        color: rgba(255, 255, 255, 0.95);
        transform: translateY(-2px);
        box-shadow:
            0 4px 16px rgba(138, 43, 226, 0.3),
            0 8px 24px rgba(128, 0, 255, 0.2);
    }

    .pagination .page-link:hover:not(:disabled)::before {
        opacity: 1;
    }

    /* Estilo para página activa */
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #A855F7 0%, #8B5CF6 100%);
        color: white !important;
        box-shadow:
            0 4px 20px rgba(168, 85, 247, 0.4),
            0 8px 32px rgba(139, 92, 246, 0.3);
        transform: translateY(-1px);
    }

    .pagination .page-item.active .page-link::before {
        opacity: 0;
    }

    .pagination .page-item.active .page-link:hover {
        transform: translateY(-1px);
        box-shadow:
            0 6px 24px rgba(168, 85, 247, 0.5),
            0 12px 40px rgba(139, 92, 246, 0.4);
    }

    /* Estilo para páginas deshabilitadas */
    .pagination .page-item.disabled .page-link {
        color: rgba(255, 255, 255, 0.3) !important;
        cursor: not-allowed;
        transform: none;
        background: transparent !important;
    }

    .pagination .page-item.disabled .page-link:hover {
        transform: none;
        box-shadow: none;
    }

    .pagination .page-item.disabled .page-link::before {
        opacity: 0;
    }

    .pagination .page-link span {
        position: relative;
        z-index: 1;
    }

    /* Enhanced Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
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

    @keyframes shimmer-header {
        0% {
            left: -40%;
        }

        100% {
            left: 100%;
        }
    }

    @keyframes shimmer-fixed {
        0% {
            left: -150px;
        }

        100% {
            left: 100%;
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
            opacity: 0.8;
        }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .glassmorphism-container {
            padding: 1rem;
            margin: 0.5rem 0;
        }

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

        .pagination-wrapper {
            margin: 1rem 0;
        }
    }

    /* Dark mode enhancements */
    @media (prefers-color-scheme: dark) {
        .glassmorphism-container {
            background: rgba(0, 0, 0, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .glassmorphism-table-wrapper {
            background: rgba(0, 0, 0, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .glassmorphism-header {
            background: rgba(0, 0, 0, 0.9);
        }

        .pagination {
            background: rgba(0, 0, 0, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }
    }
</style>
