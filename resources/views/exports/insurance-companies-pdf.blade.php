<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        /* Base PDF Styles - Consistent with invoice PDF */
        @page {
            margin: 15mm 10mm 15mm 10mm;

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
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        .container {
            width: 90%;
            max-width: 90%;
            margin: 0 auto;
            padding: 0;
        }

        /* Header Section - Similar to invoice */
        .header {
            width: 100%;
            display: table;
            margin-bottom: 20px;
        }

        .header-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: left;
        }

        .header-right {
            display: table-cell;
            width: 50%;
            text-align: left;
            vertical-align: top;
            padding-left: 200px;
            padding-top: 23px;
        }

        .logo {
            max-width: 164px;
            height: auto;
            margin-top: 10px;
            vertical-align: top;
        }

        .company-info {
            font-size: 10px;
            line-height: 1.2;
            margin-top: 5px;
        }

        .company-name {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .report-title {
            color: #6c9bd0;
            font-size: 18px;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 10px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Report Information */
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

        /* Table Styles - Enhanced from invoice style */
        .data-table {
            width: 100%;
            margin: 0 auto 20px auto;
            border-collapse: collapse;
            font-size: 12px;
        }

        .data-table thead {
            display: table-header-group;
        }

        .data-table th {
            background-color: #e6f0fa;
            color: #6c9bd0;
            font-weight: bold;
            text-align: center;
            padding: 10px 8px;
            border: 1px solid #ddd;
            font-size: 12px;
        }

        .data-table td {
            padding: 8px;
            border: 1px solid #E5E7EB;
            vertical-align: top;
            font-weight: 600;
            text-align: center;
            font-size: 12px;
        }

        .data-table tbody tr:nth-child(even) {
            background: #F9FAFB;
        }

        .data-table tbody tr:hover {
            background: #F3F4F6;
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

        /* Summary Section */
        .summary {
            width: 100%;
            margin: 20px auto 0 auto;
            padding: 15px;
            background: #F9FAFB;
            border-radius: 6px;
            border-left: 4px solid #6c9bd0;
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
            color: #6c9bd0;
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
            width: 100%;
            margin: 30px auto 0 auto;
            padding-top: 15px;
            border-top: 1px solid #E5E7EB;
            font-size: 8px;
            color: #9CA3AF;
            text-align: center;
        }

        /* Email and URL styling */
        .email,
        .url {
            color: #3B82F6;
            text-decoration: none;
        }

        /* Number formatting */
        .number {
            text-align: center;
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }

        /* Column specific styles */
        .col-0 {
            width: 8%;
            text-align: center;
        }

        /* Nro */
        .col-1 {
            width: 25%;
            text-align: center;
        }

        /* Company Name */
        .col-2 {
            width: 20%;
            text-align: center;
        }

        /* Email */
        .col-3 {
            width: 15%;
            text-align: center;
        }

        /* Phone */
        .col-4 {
            width: 20%;
            text-align: center;
        }

        /* Address */
        .col-5 {
            width: 12%;
            text-align: center;
        }

        /* Created By */

        /* Page handling for headers */
        .page-header {
            position: running(page-header);
        }

        .data-table thead tr th {
            background-color: #e6f0fa;
            color: #6c9bd0;
            font-weight: bold;
            text-align: center;
            padding: 10px 8px;
            border: 1px solid #ddd;
            font-size: 12px;
        }

        /* Prevent header repetition on page breaks */
        .data-table thead {
            display: table-header-group;
        }

        /* Page counter styling */
        .page-counter::after {
            content: counter(page) " / " counter(pages);
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section - Similar to invoice -->
        <div class="header">
            <div class="header-left">
                @if (file_exists(public_path('assets/logo/logo-png.png')))
                    <img src="{{ public_path('assets/logo/logo-png.png') }}"
                        alt="{{ $companyInfo['name'] ?? 'V General Contractors' }}" class="logo">
                @endif
            </div>
            <div class="header-right">
                <div class="company-name">{{ $companyInfo['name'] ?? 'V GENERAL CONTRACTORS' }}</div>
                <div class="company-info">
                    {{ $companyInfo['address'] ?? '1522 Waugh Dr # 510, Houston, TX 77019' }}<br>
                    {{ $companyInfo['phone'] ?? '+1 (713) 364-6240' }}<br>
                    {{ $companyInfo['email'] ?? 'info@vgeneralcontractors.com' }}<br>
                    {{ $companyInfo['website'] ?? 'https://vgeneralcontractors.com/' }}
                </div>
            </div>
        </div>

        <!-- Report Title -->
        <div class="report-title">{{ $title }}</div>

        <!-- Report Information -->
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

        <!-- Filters Information -->
        @if (isset($additionalData['filters_applied']) && $additionalData['filters_applied'] !== 'No filters applied')
            <div class="filters-info">
                <div class="filters-title">Applied Filters:</div>
                <div>{{ $additionalData['filters_applied'] }}</div>
            </div>
        @endif

        <!-- Data Table -->
        @if ($data->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="col-0">Nro</th>
                        <th class="col-1">Company Name</th>
                        <th class="col-2">Email</th>
                        <th class="col-3">Phone</th>
                        <th class="col-4">Address</th>
                        <th class="col-5">Created By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td class="col-0">
                                <span class="number">{{ $row['number'] }}</span>
                            </td>
                            <td class="col-1">
                                {{ $row['company_name'] }}
                            </td>
                            <td class="col-2">
                                @if ($row['email'] !== 'N/A')
                                    <span class="email">{{ $row['email'] }}</span>
                                @else
                                    {{ $row['email'] }}
                                @endif
                            </td>
                            <td class="col-3">
                                {{ $row['phone'] }}
                            </td>
                            <td class="col-4">
                                {{ $row['address'] }}
                            </td>
                            <td class="col-5">
                                {{ $row['assigned_user'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 40px; color: #6B7280;">
                <div style="font-size: 16px; margin-bottom: 10px;">📄</div>
                <div style="font-size: 14px; font-weight: bold; margin-bottom: 5px;">No Data Available</div>
                <div style="font-size: 10px;">No records match the current filters or selection criteria.</div>
            </div>
        @endif

        <!-- Record Count -->
        @if ($data->count() > 0)
            <div style="margin-top: 15px; text-align: center; font-size: 9px; color: #6B7280;">
                Showing {{ $data->count() }} record{{ $data->count() !== 1 ? 's' : '' }}
                @if (isset($additionalData['filters_applied']) && $additionalData['filters_applied'] !== 'No filters applied')
                    with applied filters
                @endif
            </div>
        @endif

        <!-- Summary Section -->
        @if (isset($additionalData['summary']) && ($options['show_summary'] ?? false))
            <div class="summary">
                <div class="summary-title">Report Summary</div>
                <div class="summary-stats">
                    @if (isset($additionalData['summary']['total_companies']))
                        <div class="summary-stat">
                            <span class="summary-stat-value">{{ $additionalData['summary']['total_companies'] }}</span>
                            <span class="summary-stat-label">Total Companies</span>
                        </div>
                    @endif
                    @if (isset($additionalData['summary']['active_companies']))
                        <div class="summary-stat">
                            <span
                                class="summary-stat-value">{{ $additionalData['summary']['active_companies'] }}</span>
                            <span class="summary-stat-label">Active</span>
                        </div>
                    @endif
                    @if (isset($additionalData['summary']['inactive_companies']))
                        <div class="summary-stat">
                            <span
                                class="summary-stat-value">{{ $additionalData['summary']['inactive_companies'] }}</span>
                            <span class="summary-stat-label">Inactive</span>
                        </div>
                    @endif
                    @if (isset($additionalData['summary']['active_percentage']))
                        <div class="summary-stat">
                            <span
                                class="summary-stat-value">{{ $additionalData['summary']['active_percentage'] }}%</span>
                            <span class="summary-stat-label">Active Rate</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div>This report was generated automatically by {{ config('app.name') }} on {{ $exportDate }}</div>
            <div style="margin-top: 5px; text-align: center; font-size: 10px; font-weight: bold;">
                <span class="page-counter"></span>
            </div>
        </div>
    </div>
</body>

</html>
