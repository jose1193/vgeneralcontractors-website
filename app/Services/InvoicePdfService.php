<?php

namespace App\Services;

use App\Models\InvoiceDemo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;
use Throwable;

class InvoicePdfService
{
    /**
     * Generate and store PDF for an invoice
     *
     * @param InvoiceDemo $invoice
     * @return string|null URL of the stored PDF
     */
    public function generateAndStorePdf(InvoiceDemo $invoice): ?string
    {
        try {
            // Generate PDF content
            $pdf = $this->generatePdf($invoice);
            
            if (!$pdf) {
                return null;
            }
            
            // Store PDF in S3
            $pdfUrl = $this->storePdf($pdf, $invoice);
            
            return $pdfUrl;
        } catch (Throwable $e) {
            Log::error('Error generating and storing invoice PDF', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
    
    /**
     * Generate PDF for an invoice
     *
     * @param InvoiceDemo $invoice
     * @return \Barryvdh\DomPDF\PDF|null
     */
    public function generatePdf(InvoiceDemo $invoice)
    {
        try {
            // Load invoice with its items
            $invoice->load(['items']);
            
            // Generate PDF using Laravel DomPDF
            $pdf = PDF::loadView('invoice-demos.pdf', [
                'invoice' => $invoice
            ]);
            
            // Set paper size and orientation
            $pdf->setPaper('a4', 'portrait');
            
            return $pdf;
        } catch (Throwable $e) {
            Log::error('Error generating invoice PDF', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
    
    /**
     * Store PDF in S3
     *
     * @param \Barryvdh\DomPDF\PDF $pdf
     * @param InvoiceDemo $invoice
     * @return string|null URL of the stored PDF
     */
    public function storePdf($pdf, InvoiceDemo $invoice): ?string
    {
        try {
            // Create directory structure based on date: invoices/2023/05/25/
            $now = Carbon::now();
            $storagePath = 'invoices/' . $now->year . '/' . $now->format('m') . '/' . $now->format('d');
            
            // Generate unique filename with invoice number and claim number
            $uniqueId = $this->generateUniqueFilename($invoice);
            $filename = $uniqueId . '.pdf';
            
            // Full path in S3
            $pdfS3Path = $storagePath . '/' . $filename;
            
            // Get PDF content
            $pdfContent = $pdf->output();
            
            // Upload to S3
            Storage::disk('s3')->put($pdfS3Path, $pdfContent);
            
            // Return the PDF URL
            return Storage::disk('s3')->url($pdfS3Path);
        } catch (Throwable $e) {
            Log::error('Error storing invoice PDF', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
    
    /**
     * Generate a unique filename for the invoice PDF
     * Format: vg-{invoice_number}-{date}-{claim_number}
     *
     * @param InvoiceDemo $invoice
     * @return string
     */
    private function generateUniqueFilename(InvoiceDemo $invoice): string
    {
        // Clean invoice number (remove VG- prefix if present)
        $invoiceNumber = str_replace('VG-', '', $invoice->invoice_number);
        
        // Format date
        $date = Carbon::parse($invoice->invoice_date)->format('Ymd');
        
        // Clean claim number (remove special characters)
        $claimNumber = preg_replace('/[^a-zA-Z0-9]/', '', $invoice->claim_number ?? '');
        
        // Create base filename
        $baseFilename = 'vg-' . $invoiceNumber . '-' . $date;
        
        // Add claim number if available
        if (!empty($claimNumber)) {
            $baseFilename .= '-' . $claimNumber;
        }
        
        // Encrypt the filename for security
        $encryptedFilename = Str::slug($baseFilename);
        
        return $encryptedFilename;
    }
    
    /**
     * Get PDF URL for an invoice if it exists, or generate a new one
     *
     * @param InvoiceDemo $invoice
     * @param bool $forceRegenerate Whether to force regeneration of the PDF
     * @return string|null URL of the PDF
     */
    public function getPdfUrl(InvoiceDemo $invoice, bool $forceRegenerate = false): ?string
    {
        try {
            // Check if we need to regenerate the PDF
            if ($forceRegenerate) {
                return $this->generateAndStorePdf($invoice);
            }
            
            // Try to find existing PDF
            $existingPdfUrl = $this->findExistingPdf($invoice);
            
            // If found, return it
            if ($existingPdfUrl) {
                return $existingPdfUrl;
            }
            
            // Otherwise generate a new one
            return $this->generateAndStorePdf($invoice);
        } catch (Throwable $e) {
            Log::error('Error getting PDF URL', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
    
    /**
     * Find existing PDF for an invoice
     *
     * @param InvoiceDemo $invoice
     * @return string|null URL of the existing PDF
     */
    public function findExistingPdf(InvoiceDemo $invoice): ?string
    {
        try {
            // Generate the filename pattern
            $filenamePattern = $this->generateUniqueFilename($invoice);
            
            // Search in S3 for matching files
            $files = Storage::disk('s3')->files('invoices');
            
            foreach ($files as $file) {
                if (Str::contains($file, $filenamePattern) && Str::endsWith($file, '.pdf')) {
                    return Storage::disk('s3')->url($file);
                }
            }
            
            return null;
        } catch (Throwable $e) {
            Log::error('Error finding existing PDF', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
    
    /**
     * Delete PDF for an invoice
     *
     * @param InvoiceDemo $invoice
     * @return bool
     */
    public function deletePdf(InvoiceDemo $invoice): bool
    {
        try {
            // Find existing PDF
            $existingPdfUrl = $this->findExistingPdf($invoice);
            
            if (!$existingPdfUrl) {
                return false;
            }
            
            // Extract the path from URL
            $path = $this->getRelativePathFromUrl($existingPdfUrl);
            
            // Delete from S3
            $deleted = Storage::disk('s3')->exists($path) ? Storage::disk('s3')->delete($path) : false;
            
            Log::info('Deleted invoice PDF', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'path' => $path,
                'deleted' => $deleted
            ]);
            
            return $deleted;
        } catch (Throwable $e) {
            Log::error('Error deleting invoice PDF', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Extract relative path from S3 URL
     *
     * @param string $url
     * @return string
     */
    private function getRelativePathFromUrl(string $url): string
    {
        // Remove any query parameters
        $url = strtok($url, '?');
        
        // Parse the URL
        $parsedUrl = parse_url($url);
        
        // Get the path component
        $path = $parsedUrl['path'] ?? '';
        
        // Remove leading slash and bucket name if present
        $path = ltrim($path, '/');
        $bucketName = env('AWS_BUCKET');
        $path = preg_replace("/^{$bucketName}\//", '', $path);
        
        return $path;
    }
}