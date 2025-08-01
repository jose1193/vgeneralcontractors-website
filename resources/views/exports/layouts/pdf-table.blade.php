@extends('exports.layouts.pdf-base')

@section('content')
    <!-- Check if we have data -->
    @if($data && $data->count() > 0)
        <!-- Data Table -->
        <table class="data-table">
            <!-- Table Headers -->
            <thead>
                <tr>
                    @foreach($headers as $header => $config)
                        <th class="col-{{ $loop->index }}">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            
            <!-- Table Body -->
            <tbody>
                @foreach($data as $row)
                    <tr>
                        @foreach($headers as $header => $config)
                            @php
                                $fieldKey = match($header) {
                                    '#' => 'number',
                                    'Company Name' => 'company_name',
                                    'Email' => 'email',
                                    'Phone' => 'phone',
                                    'Address' => 'address',
                                    'Website' => 'website',
                                    'Status' => 'status',
                                    'Created By' => 'assigned_user',
                                    'Created Date' => 'created_date',
                                    default => strtolower(str_replace(' ', '_', $header))
                                };
                                $value = $row[$fieldKey] ?? 'N/A';
                            @endphp
                            
                            <td class="col-{{ $loop->parent->index }} {{ $config['align'] ?? 'left' === 'center' ? 'text-center' : ($config['align'] ?? 'left' === 'right' ? 'text-right' : 'text-left') }}">
                                @if($fieldKey === 'status')
                                    <span class="status-{{ strtolower($value) }}">{{ $value }}</span>
                                @elseif($fieldKey === 'email' && $value !== 'N/A')
                                    <span class="email">{{ $value }}</span>
                                @elseif($fieldKey === 'website' && $value !== 'N/A')
                                    <span class="url">{{ $value }}</span>
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <!-- No Data Message -->
        <div class="no-data">
            <div class="no-data-icon">📊</div>
            <div class="no-data-title">No Data Available</div>
            <div class="no-data-text">There are no records to display for the selected criteria.</div>
        </div>
    @endif
@endsection

@push('pdf-custom-styles')
<style>
    /* No data message styling */
    .no-data {
        text-align: center;
        padding: 60px;
        color: var(--text-secondary);
    }

    .no-data-icon {
        font-size: 24px;
        margin-bottom: 15px;
    }

    .no-data-title {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 8px;
        color: var(--text-primary);
    }

    .no-data-text {
        font-size: 12px;
    }
</style>
@endpush
