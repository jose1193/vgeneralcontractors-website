@extends('exports.layouts.pdf-table')

@push('pdf-theme-styles')
<style>
    /* Generic Theme - Default styling */
    :root {
        --primary-color: #4F46E5;
        --primary-dark: #4338CA;
    }

    /* Standard table styling */
    .data-table th {
        background: var(--primary-color);
        color: white;
        font-weight: bold;
        text-align: center;
        padding: 6px 8px;
        border: 1px solid var(--primary-dark);
        font-size: 10px;
        line-height: 1.2;
    }

    .data-table td {
        padding: 6px 8px;
        font-size: 10px;
        line-height: 1.3;
    }

    /* Generic column styling - will be overridden by specific modules */
    .col-0 { width: 5%; text-align: center; }
    .col-1 { width: 20%; text-align: left; }
    .col-2 { width: 15%; text-align: left; }
    .col-3 { width: 15%; text-align: center; }
    .col-4 { width: 20%; text-align: left; }
    .col-5 { width: 12%; text-align: center; }
    .col-6 { width: 13%; text-align: center; }

    /* Standard status styling */
    .status-active {
        background: var(--success-bg);
        color: var(--success-text);
    }

    .status-inactive {
        background: var(--error-bg);
        color: var(--error-text);
    }
</style>
@endpush
