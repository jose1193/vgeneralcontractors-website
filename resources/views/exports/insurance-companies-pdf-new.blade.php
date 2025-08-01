@extends('exports.layouts.pdf-table')

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
        font-size: 10px;
        line-height: 1.2;
    }

    /* Enhanced report title for this theme */
    .main-report-title {
        color: var(--primary-color);
        font-size: 18px;
    }

    .report-title {
        color: var(--primary-color);
    }

    /* Theme-specific table data styling */
    .data-table td {
        font-weight: 600;
        padding: 6px 8px;
        font-size: 10px;
        line-height: 1.3;
    }

    /* Insurance-specific column widths - Optimized for better fit */
    .col-0 { width: 5%; text-align: center; }     /* # */
    .col-1 { width: 22%; text-align: left; }      /* Company Name */
    .col-2 { width: 18%; text-align: left; }      /* Email */
    .col-3 { width: 12%; text-align: center; }    /* Phone */
    .col-4 { width: 25%; text-align: left; }      /* Address */
    .col-5 { width: 10%; text-align: center; }    /* Status */
    .col-6 { width: 8%; text-align: center; }     /* Created By */

    /* Status enhancements for insurance companies */
    .status-active {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-inactive {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Enhanced table borders for professional look */
    .data-table {
        border: 2px solid var(--primary-color);
    }

    .data-table th {
        border-bottom: 2px solid var(--primary-dark);
    }

    /* Better spacing for landscape orientation */
    @media (orientation: landscape) {
        .data-table {
            font-size: 9px;
        }
        
        .data-table th {
            font-size: 9px;
            padding: 5px 6px;
        }
        
        .data-table td {
            font-size: 9px;
            padding: 5px 6px;
        }
    }
</style>
@endpush
