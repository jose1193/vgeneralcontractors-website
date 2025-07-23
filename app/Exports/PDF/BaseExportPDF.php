<?php

namespace App\Exports\PDF;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;

abstract class BaseExportPDF
{
    protected Collection $data;
    protected string $title;
    protected array $companyInfo;
    protected array $options;
    protected string $orientation;
    protected string $paperSize;

    public function __construct(
        ?Collection $data, 
        string $title = 'PDF Export',
        array $companyInfo = [],
        array $options = []
    ) {
        $this->data = $data ?? collect();
        $this->title = $title;
        $this->companyInfo = $this->getCompanyInfo($companyInfo);
        $this->options = array_merge($this->getDefaultOptions(), $options);
        $this->orientation = $options['orientation'] ?? 'portrait';
        $this->paperSize = $options['paper_size'] ?? 'letter';
    }

    /**
     * Generate and return PDF
     */
    public function generate(): \Barryvdh\DomPDF\PDF
    {
        $pdf = Pdf::loadView($this->getTemplatePath(), [
            'data' => $this->data,
            'title' => $this->title,
            'companyInfo' => $this->companyInfo,
            'headers' => $this->getHeaders(),
            'options' => $this->options,
            'exportDate' => now()->format('Y-m-d H:i:s'),
            'exportedBy' => auth()->user()->name ?? 'System',
            'totalRecords' => $this->data->count(),
            'additionalData' => $this->getAdditionalData()
        ]);

        // Set PDF options
        $pdf->setPaper($this->paperSize, $this->orientation);
        
        // Set additional options if needed
        if (isset($this->options['dpi'])) {
            $pdf->setOption('dpi', $this->options['dpi']);
        }
        
        if (isset($this->options['defaultFont'])) {
            $pdf->setOption('defaultFont', $this->options['defaultFont']);
        }

        return $pdf;
    }

    /**
     * Download PDF file
     */
    public function download(string $filename = null): \Illuminate\Http\Response
    {
        $filename = $filename ?: $this->getDefaultFilename();
        return $this->generate()->download($filename);
    }

    /**
     * Stream PDF to browser
     */
    public function stream(string $filename = null): \Illuminate\Http\Response
    {
        $filename = $filename ?: $this->getDefaultFilename();
        return $this->generate()->stream($filename);
    }

    /**
     * Save PDF to storage
     */
    public function save(string $path): bool
    {
        $pdf = $this->generate();
        return file_put_contents(storage_path($path), $pdf->output()) !== false;
    }

    /**
     * Get PDF as base64 string
     */
    public function toBase64(): string
    {
        return base64_encode($this->generate()->output());
    }

    /**
     * Get template path - must be implemented by child classes
     */
    abstract protected function getTemplatePath(): string;

    /**
     * Get table headers - must be implemented by child classes
     */
    abstract protected function getHeaders(): array;

    /**
     * Get default filename
     */
    protected function getDefaultFilename(): string
    {
        $sanitizedTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $this->title);
        return $sanitizedTitle . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
    }

    /**
     * Get company information with defaults
     */
    protected function getCompanyInfo(array $companyInfo = []): array
    {
        $defaults = [
            'name' => config('app.name', 'Company Name'),
            'address' => 'Company Address',
            'phone' => 'Company Phone',
            'email' => 'info@company.com',
            'website' => 'www.company.com',
            'logo' => null, // Path to logo file
        ];

        return array_merge($defaults, $companyInfo);
    }

    /**
     * Get default PDF options
     */
    protected function getDefaultOptions(): array
    {
        return [
            'orientation' => 'portrait',
            'paper_size' => 'letter',
            'dpi' => 96,
            'defaultFont' => 'serif',
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => false,
            'isFontSubsettingEnabled' => true,
            'margin_top' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'show_header' => true,
            'show_footer' => true,
            'show_page_numbers' => true,
            'show_company_info' => true,
            'show_export_info' => true,
        ];
    }

    /**
     * Get additional data for the template
     */
    protected function getAdditionalData(): array
    {
        return [];
    }

    /**
     * Format data for PDF display - can be overridden by child classes
     */
    protected function formatDataForPDF(Collection $data): Collection
    {
        return $data;
    }

    /**
     * Apply data filters before PDF generation
     */
    protected function applyFilters(Collection $data, array $filters = []): Collection
    {
        if (empty($filters)) {
            return $data;
        }

        return $data->filter(function ($item) use ($filters) {
            foreach ($filters as $field => $value) {
                if (isset($item[$field]) && $item[$field] != $value) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Group data by specified field
     */
    protected function groupData(Collection $data, string $groupBy): Collection
    {
        return $data->groupBy($groupBy);
    }

    /**
     * Sort data by specified field
     */
    protected function sortData(Collection $data, string $sortBy, string $direction = 'asc'): Collection
    {
        return $direction === 'desc' 
            ? $data->sortByDesc($sortBy) 
            : $data->sortBy($sortBy);
    }

    /**
     * Calculate summary statistics
     */
    protected function calculateSummary(Collection $data): array
    {
        return [
            'total_records' => $data->count(),
            'export_date' => now()->format('Y-m-d H:i:s'),
            'exported_by' => auth()->user()->name ?? 'System',
        ];
    }

    /**
     * Chunk data for large datasets
     */
    protected function chunkData(Collection $data, int $chunkSize = 50): Collection
    {
        return $data->chunk($chunkSize);
    }
}
