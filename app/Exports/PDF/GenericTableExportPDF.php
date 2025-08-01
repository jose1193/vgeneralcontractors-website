<?php

namespace App\Exports\PDF;

use Illuminate\Support\Collection;

/**
 * Generic Table Export PDF
 * Base class for simple table-based PDF exports that can be reused across modules
 */
class GenericTableExportPDF extends BaseExportPDF
{
    private array $customHeaders;
    private string $customTemplate;

    public function __construct(
        Collection $data,
        string $title,
        array $headers,
        array $companyInfo = [],
        array $options = [],
        string $template = 'exports.generic-table-pdf'
    ) {
        $this->customHeaders = $headers;
        $this->customTemplate = $template;

        // Set default options for generic table exports
        $defaultOptions = [
            'orientation' => 'portrait',
            'paper_size' => 'letter',
            'dpi' => 150,
            'show_borders' => true,
            'alternate_row_colors' => true,
            'show_summary' => false,
            'repeat_headers' => false,
        ];

        $options = array_merge($defaultOptions, $options);

        parent::__construct(
            $data,
            $title,
            $companyInfo,
            $options
        );
    }

    /**
     * Get template path
     */
    protected function getTemplatePath(): string
    {
        return $this->customTemplate;
    }

    /**
     * Get table headers
     */
    protected function getHeaders(): array
    {
        return $this->customHeaders;
    }

    /**
     * Static factory method for quick PDF generation
     */
    public static function create(
        Collection $data,
        string $title,
        array $headers,
        array $options = []
    ): self {
        return new self($data, $title, $headers, [], $options);
    }

    /**
     * Static factory method with custom template
     */
    public static function createWithTemplate(
        Collection $data,
        string $title,
        array $headers,
        string $template,
        array $options = []
    ): self {
        return new self($data, $title, $headers, [], $options, $template);
    }
}
