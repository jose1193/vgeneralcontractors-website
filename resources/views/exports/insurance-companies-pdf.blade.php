@extends('exports.base-pdf-template')

@push('pdf-theme-styles')
    <style>
        /* Insurance Companies Theme - Override CSS Variables */
        :root {
            --primary-color: #6c9bd0;
            --primary-dark: #5a87c4;
            --font-family: 'Roboto', Arial, sans-serif;
        }

        /* Theme-specific table header styling */
        .data-table th {
            background-color: #e6f0fa;
            color: var(--primary-color);
            font-weight: bold;
            text-align: center;
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 11px;
            line-height: 1.2;
            height: auto;
        }

        /* Enhanced report title for this theme */
        .report-title {
            color: var(--primary-color);
            font-size: 18px;
        }

        /* Theme-specific table data styling */
        .data-table td {
            font-weight: 600;
            padding: 6px 8px;
            font-size: 11px;
            line-height: 1.3;
            height: auto;
        }

        /* Insurance-specific column widths - Optimized for better fit */
        .col-0 {
            width: 6%;
            text-align: center;
        }

        /* Nro */
        .col-1 {
            width: 22%;
            text-align: left;
        }

        /* Company Name */
        .col-2 {
            width: 18%;
            text-align: left;
        }

        /* Email */
        .col-3 {
            width: 12%;
            text-align: center;
        }

        /* Phone */
        .col-4 {
            width: 28%;
            text-align: left;
        }

        /* Address */
        .col-5 {
            width: 14%;
            text-align: center;
        }

        /* Created By */

        /* Enhanced number formatting for this template */
        .number {
            text-align: center;
            font-family: var(--font-family-mono);
            font-weight: bold;
        }

        /* Use moderately wider container for insurance companies */
        .header {
            width: 92%;
            border-bottom: 2px solid var(--primary-color);
        }

        .data-table {
            width: 92%;
        }

        .summary {
            width: 92%;
            border-left: 4px solid var(--primary-color);
        }

        .footer {
            width: 92%;
        }

        /* Enhanced company info layout for insurance companies */
        .header-content {
            padding: 25px 0;
        }

        .company-logo {
            width: 1000px;
        }

        .company-logo img {
            max-width: 1000px;
            max-height: 300px;
        }
    </style>
@endpush

@section('content')
    <!-- Data Table -->
    @if ($data->count() > 0)
        <table class="data-table allow-page-break">
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
        <div class="no-data">
            <div class="no-data-icon">📄</div>
            <div class="no-data-title">No Data Available</div>
            <div class="no-data-text">No records match the current filters or selection criteria.</div>
        </div>
    @endif
@endsection
