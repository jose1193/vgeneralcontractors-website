<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        /* CSS Variables for Theming */
        :root {
            --primary-color: #4F46E5;
            --primary-dark: #4338CA;
            --secondary-color: #6c9bd0;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --text-muted: #9CA3AF;
            --background-light: #F9FAFB;
            --background-alt: #F3F4F6;
            --border-light: #E5E7EB;
            --success-bg: #D1FAE5;
            --success-text: #065F46;
            --error-bg: #FEE2E2;
            --error-text: #991B1B;
            --highlight-bg: #FEF3C7;
            --link-color: #3B82F6;
            --font-family: 'DejaVu Sans', Arial, sans-serif;
            --font-family-mono: 'Courier New', monospace;
        }

        /* Base PDF Styles */
        @page {
            margin: 15mm 10mm 20mm 10mm;
            size: {{ $options['paper_size'] ?? 'A4' }} {{ $options['orientation'] ?? 'portrait' }};

            /* Header que se repite en TODAS las páginas (estilo Word) */
            @top-left {
                content: element(page-header);
                margin-bottom: 5mm;
            }

            @bottom-left {
                content: "Generated: {{ $exportDate }}";
                font-size: 8px;
                color: #666;
            }

            @bottom-center {
                content: "Page " counter(page) " of " counter(pages);
                font-size: 9px;
                color: #666;
                font-weight: bold;
            }

            @bottom-right {
                content: "{{ $totalRecords }} records";
                font-size: 8px;
                color: #666;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-family);
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        /* Header que se repite en TODAS las páginas */
        .page-header {
            position: running(page-header);
            width: 100%;
            padding: 8px 0;
            border-bottom: 1px solid var(--primary-color);
            margin-bottom: 10px;
            background: white;
        }

        .page-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .page-header .company-logo {
            max-height: 35px;
            width: auto;
        }

        .page-header .report-title {
            font-size: 11px;
            font-weight: bold;
            color: var(--primary-color);
            text-transform: uppercase;
            text-align: center;
            flex: 1;
            margin: 0 15px;
        }

        .page-header .company-info-mini {
            text-align: right;
            font-size: 8px;
            color: var(--text-secondary);
            line-height: 1.2;
        }

        /* Primera página: Header principal más detallado */
        .main-header {
            width: 100%;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-color);
            page-break-inside: avoid;
        }

        .main-header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
            margin-bottom: 15px;
            padding: 15px 0;
        }

        .main-company-logo {
            flex-shrink: 0;
            max-width: 200px;
        }

        .main-company-logo img {
            max-width: 100%;
            height: auto;
            max-height: 80px;
            object-fit: contain;
        }

        .main-company-details {
            flex: 1;
            text-align: right;
            padding-left: 40px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-end;
        }

        .main-company-name {
            font-size: 14px;
            font-weight: bold;
            color: var(--text-primary);
            margin-bottom: 8px;
            text-align: right;
            line-height: 1.2;
        }

        .main-company-contact {
            font-size: 11px;
            color: var(--text-secondary);
            line-height: 1.4;
            text-align: right;
        }

        .main-report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: var(--text-primary);
            margin: 15px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Report Information */
        .report-info {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            font-size: 13px;
            color: var(--text-primary);
            font-weight: bold;
            padding: 0 5px;
        }

        .report-info-left {
            display: table-cell;
            width: 50%;
        }

        .report-info-right {
            display: table-cell;
            width: 50%;
            text-align: right;
        }

        /* Content area with proper spacing */
        .content {
            margin-top: 50px; /* Espacio para el header repetitivo */
        }

        /* Estilos de tabla base */
        .data-table {
            width: 100%;
            margin: 0 auto 50px auto;
            border-collapse: collapse;
            font-size: 11px;
        }

        .data-table th {
            background: var(--primary-color);
            color: white;
            padding: 6px 8px;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            border: 1px solid var(--primary-dark);
            line-height: 1.2;
        }

        .data-table td {
            padding: 6px 8px;
            border: 1px solid var(--border-light);
            vertical-align: middle;
            text-align: center;
            font-size: 10px;
            line-height: 1.3;
        }

        .data-table tbody tr:nth-child(even) {
            background: var(--background-light);
        }

        /* Control de headers repetitivos en tabla */
        @if (($options['repeat_headers'] ?? false) === true)
            .data-table thead {
                display: table-header-group !important;
            }
        @else
            .data-table thead {
                display: table-row-group;
            }
        @endif

        /* Status Styles */
        .status-active {
            background: var(--success-bg);
            color: var(--success-text);
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
            min-width: 50px;
        }

        .status-inactive {
            background: var(--error-bg);
            color: var(--error-text);
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
            min-width: 50px;
        }

        /* Text Alignment */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }

        /* Page breaks */
        .page-break {
            page-break-before: always;
            break-before: page;
        }

        .page-break-avoid {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* Summary Section */
        .summary {
            width: 100%;
            margin: 40px auto 50px auto;
            padding: 20px;
            background: var(--background-light);
            border-radius: 6px;
            border-left: 4px solid var(--primary-color);
        }

        .summary-title {
            font-size: 12px;
            font-weight: bold;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .summary-stats {
            display: table;
            width: 100%;
        }

        .summary-stat {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 5px;
        }

        .summary-stat-value {
            font-size: 14px;
            font-weight: bold;
            color: var(--primary-color);
            display: block;
        }

        .summary-stat-label {
            font-size: 8px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Custom column widths based on headers */
        @if (isset($headers))
            @foreach ($headers as $header => $config)
                .col-{{ $loop->index }} {
                    width: {{ $config['width'] ?? 'auto' }};
                    text-align: {{ $config['align'] ?? 'left' }};
                }
            @endforeach
        @endif

        @stack('pdf-theme-styles')
        @stack('pdf-custom-styles')
    </style>
    @stack('pdf-head')
</head>

<body>
    <!-- Header que se repite en TODAS las páginas (similar a Word) -->
    <div class="page-header">
        <div class="page-header-content">
            @if (isset($companyInfo['logo_path']) && file_exists($companyInfo['logo_path']))
                <img src="{{ $companyInfo['logo_path'] }}" alt="Logo" class="company-logo">
            @endif
            <div class="report-title">{{ $title }}</div>
            <div class="company-info-mini">
                {{ $companyInfo['name'] ?? 'V GENERAL CONTRACTORS' }}<br>
                {{ $companyInfo['phone'] ?? '+1 (713) 587-6423' }}<br>
                {{ $companyInfo['email'] ?? 'info@vgeneralcontractors.com' }}
            </div>
        </div>
    </div>

    @if ($options['show_main_header'] ?? true)
        <!-- Header principal solo en la primera página -->
        <div class="main-header">
            @if ($options['show_company_info'] ?? true)
                <div class="main-header-content">
                    @if (isset($companyInfo['logo_path']) && file_exists($companyInfo['logo_path']))
                        <div class="main-company-logo">
                            <img src="{{ $companyInfo['logo_path'] }}" alt="{{ $companyInfo['name'] ?? 'Company Logo' }}">
                        </div>
                    @endif
                    <div class="main-company-details">
                        <div class="main-company-name">{{ $companyInfo['name'] ?? 'V GENERAL CONTRACTORS' }}</div>
                        <div class="main-company-contact">
                            {{ $companyInfo['address'] ?? '1302 Waugh Dr # 810, Houston, TX 77019' }}<br>
                            {{ $companyInfo['phone'] ?? '+1 (713) 587-6423' }}<br>
                            {{ $companyInfo['email'] ?? 'info@vgeneralcontractors.com' }}<br>
                            {{ $companyInfo['website'] ?? 'https://vgeneralcontractors.com' }}
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="main-report-title">{{ $title }}</div>

            @if ($options['show_export_info'] ?? true)
                <div class="report-info">
                    <div class="report-info-left">
                        <div><strong>Generated:</strong> {{ $exportDate }}</div>
                        <div><strong>By:</strong> {{ $exportedBy }}</div>
                    </div>
                    <div class="report-info-right">
                        <div><strong>Total Records:</strong> {{ $totalRecords }}</div>
                        @if (isset($additionalData['date_range']))
                            <div><strong>Period:</strong> {{ $additionalData['date_range'] }}</div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Content Section -->
    <div class="content">
        @yield('content')
    </div>

    @if ($options['show_summary'] ?? false && isset($additionalData['summary']))
        <!-- Summary Section -->
        <div class="page-break-avoid">
            <div class="summary">
                <div class="summary-title">Report Summary</div>
                <div class="summary-stats">
                    @if (isset($additionalData['summary']['total_companies']))
                        <div class="summary-stat">
                            <span class="summary-stat-value">{{ $additionalData['summary']['total_companies'] }}</span>
                            <span class="summary-stat-label">Total</span>
                        </div>
                    @endif
                    @if (isset($additionalData['summary']['active_companies']))
                        <div class="summary-stat">
                            <span class="summary-stat-value">{{ $additionalData['summary']['active_companies'] }}</span>
                            <span class="summary-stat-label">Active</span>
                        </div>
                    @endif
                    @if (isset($additionalData['summary']['inactive_companies']))
                        <div class="summary-stat">
                            <span class="summary-stat-value">{{ $additionalData['summary']['inactive_companies'] }}</span>
                            <span class="summary-stat-label">Inactive</span>
                        </div>
                    @endif
                    @if (isset($additionalData['summary']['active_percentage']))
                        <div class="summary-stat">
                            <span class="summary-stat-value">{{ $additionalData['summary']['active_percentage'] }}%</span>
                            <span class="summary-stat-label">Active Rate</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($options['show_footer'] ?? true)
        <!-- Footer Section -->
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid var(--border-light); font-size: 8px; color: var(--text-muted); text-align: center;">
            <div>This report was generated automatically by {{ config('app.name') }}</div>
            @if (isset($additionalData['filters_applied']) && $additionalData['filters_applied'] !== 'No filters applied')
                <div style="margin-top: 5px; text-align: center; font-size: 8px;">
                    <strong>Applied Filters:</strong> {{ $additionalData['filters_applied'] }}
                </div>
            @endif
        </div>
    @endif
</body>
</html>
