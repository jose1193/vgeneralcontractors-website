<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        /* Base PDF Styles */
        @page {
            margin: 20mm 15mm 20mm 15mm;

            @top-center {
                content: "{{ $title }}";
                font-size: 10px;
                color: #666;
            }

            @bottom-center {
                content: "Page " counter(page) " of " counter(pages);
                font-size: 9px;
                color: #666;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        .header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #4F46E5;
        }

        .company-info {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .company-logo {
            display: table-cell;
            width: 80px;
            vertical-align: top;
        }

        .company-logo img {
            max-width: 164px;
            height: auto;
            margin-top: 10px;
            vertical-align: top;
        }

        .company-details {
            display: table-cell;
            vertical-align: top;
            padding-left: 120px;
            padding-top: 23px;
        }

        .company-name {
            font-size: 12px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 5px;
        }

        .company-contact {
            font-size: 10px;
            color: #6B7280;
            line-height: 1.2;
        }

        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #1F2937;
            margin: 15px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-info {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            font-size: 9px;
            color: #6B7280;
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

        .filters-info {
            background: #F9FAFB;
            padding: 8px 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 9px;
            color: #374151;
        }

        .filters-title {
            font-weight: bold;
            margin-bottom: 3px;
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9px;
        }

        .data-table th {
            background: #4F46E5;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
            border: 1px solid #4338CA;
        }

        .data-table td {
            padding: 6px;
            border: 1px solid #E5E7EB;
            vertical-align: top;
        }

        .data-table tbody tr:nth-child(even) {
            background: #F9FAFB;
        }

        .data-table tbody tr:hover {
            background: #F3F4F6;
        }

        /* Status Styles */
        .status-active {
            background: #D1FAE5;
            color: #065F46;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
            min-width: 50px;
        }

        .status-inactive {
            background: #FEE2E2;
            color: #991B1B;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
            min-width: 50px;
        }

        /* Text Alignment */
        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        /* Summary Section */
        .summary {
            margin-top: 20px;
            padding: 15px;
            background: #F9FAFB;
            border-radius: 6px;
            border-left: 4px solid #4F46E5;
        }

        .summary-title {
            font-size: 12px;
            font-weight: bold;
            color: #1F2937;
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
            color: #4F46E5;
            display: block;
        }

        .summary-stat-label {
            font-size: 8px;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #E5E7EB;
            font-size: 8px;
            color: #9CA3AF;
            text-align: center;
        }

        /* Page Break */
        .page-break {
            page-break-before: always;
        }

        /* Responsive adjustments for PDF */
        @media print {
            .no-print {
                display: none;
            }
        }

        /* Long text handling */
        .text-wrap {
            word-wrap: break-word;
            word-break: break-all;
        }

        .text-ellipsis {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Number formatting */
        .number {
            text-align: right;
            font-family: 'Courier New', monospace;
        }

        /* Highlight important data */
        .highlight {
            background: #FEF3C7;
            padding: 1px 3px;
            border-radius: 2px;
        }

        /* Email and URL styling */
        .email,
        .url {
            color: #3B82F6;
            text-decoration: none;
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
    </style>
</head>

<body>
    @if ($options['show_header'] ?? true)
        <!-- Header Section -->
        <div class="header">
            @if ($options['show_company_info'] ?? true)
                <div class="company-info">
                    @if (isset($companyInfo['logo_path']) && file_exists($companyInfo['logo_path']))
                        <div class="company-logo">
                            <img src="{{ $companyInfo['logo_path'] }}" alt="{{ $companyInfo['name'] ?? 'Company Logo' }}">
                        </div>
                    @endif
                    <div class="company-details">
                        <div class="company-name">{{ $companyInfo['name'] ?? 'V GENERAL CONTRACTORS' }}</div>
                        <div class="company-contact">
                            {{ $companyInfo['address'] ?? '1522 Waugh Dr # 510, Houston, TX 77019' }}<br>
                            {{ $companyInfo['phone'] ?? '+1 (713) 364-6240' }}<br>
                            {{ $companyInfo['email'] ?? 'info@vgeneralcontractors.com' }}<br>
                            {{ $companyInfo['website'] ?? 'https://vgeneralcontractors.com/' }}
                        </div>
                    </div>
                </div>
            @endif

            <div class="report-title">{{ $title }}</div>

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

            @if (isset($additionalData['filters_applied']) && $additionalData['filters_applied'] !== 'No filters applied')
                <div class="filters-info">
                    <div class="filters-title">Applied Filters:</div>
                    <div>{{ $additionalData['filters_applied'] }}</div>
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
    @endif

    @if ($options['show_footer'] ?? true)
        <!-- Footer Section -->
        <div class="footer">
            <div>This report was generated automatically by {{ config('app.name') }} on {{ $exportDate }}</div>
            @if ($options['show_page_numbers'] ?? true)
                <div>Page information will be handled by @page CSS rules</div>
            @endif
        </div>
    @endif
</body>

</html>
