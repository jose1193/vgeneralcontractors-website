<?php

namespace App\Services;

use App\Models\InvoiceDemo;
use App\Models\InvoiceDemoItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class InvoiceDemoService
{
    /**
     * Cache duration in seconds (5 minutes)
     */
    protected int $cacheTime = 300;

    /**
     * Get paginated invoices with filters and search
     */
    public function getPaginatedInvoices(
        int $page = 1,
        int $perPage = 10,
        string $search = '',
        string $status = '',
        string $sortBy = 'created_at',
        string $sortOrder = 'desc',
        bool $includeDeleted = false
    ): LengthAwarePaginator {
        $cacheKey = $this->generateCacheKey('invoices', [
            'page' => $page,
            'per_page' => $perPage,
            'search' => $search,
            'status' => $status,
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
            'include_deleted' => $includeDeleted
        ]);

        return Cache::remember($cacheKey, $this->cacheTime, function () use (
            $page, $perPage, $search, $status, $sortBy, $sortOrder, $includeDeleted
        ) {
            $query = InvoiceDemo::with(['user', 'items']);

            // Include soft deleted records if requested
            if ($includeDeleted) {
                $query->withTrashed();
            }

            // Apply search filters
            if (!empty($search)) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('invoice_number', 'LIKE', "%{$search}%")
                      ->orWhere('bill_to_name', 'LIKE', "%{$search}%")
                      ->orWhere('bill_to_address', 'LIKE', "%{$search}%")
                      ->orWhere('bill_to_phone', 'LIKE', "%{$search}%")
                      ->orWhere('claim_number', 'LIKE', "%{$search}%")
                      ->orWhere('policy_number', 'LIKE', "%{$search}%")
                      ->orWhere('insurance_company', 'LIKE', "%{$search}%")
                      ->orWhere('type_of_loss', 'LIKE', "%{$search}%")
                      ->orWhere('notes', 'LIKE', "%{$search}%");
                });
            }

            // Apply status filter
            if (!empty($status)) {
                $query->where('status', $status);
            }

            // Apply sorting
            $allowedSortFields = [
                'invoice_number', 'bill_to_name', 'balance_due', 
                'status', 'invoice_date', 'date_of_loss', 
                'created_at', 'updated_at'
            ];

            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder === 'desc' ? 'desc' : 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            return $query->paginate($perPage, ['*'], 'page', $page);
        });
    }

    /**
     * Create a new invoice demo
     */
    public function createInvoice(array $data, int $userId): InvoiceDemo
    {
        try {
            DB::beginTransaction();

            // Prepare invoice data
            $invoiceData = $this->prepareInvoiceData($data, $userId);
            
            // Separate items data if present
            $itemsData = $data['items'] ?? [];
            unset($invoiceData['items']);

            // Create invoice
            $invoice = InvoiceDemo::create($invoiceData);

            // Create items if provided
            if (!empty($itemsData)) {
                $this->createInvoiceItems($invoice, $itemsData);
            }

            // Calculate totals
            $invoice->calculateTotals();

            // Load relationships for response
            $invoice->load(['user', 'items']);

            DB::commit();

            // Clear related caches
            $this->clearInvoiceCaches();

            // Log activity
            Log::info('Invoice demo created', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'user_id' => $userId
            ]);

            return $invoice;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create invoice demo', [
                'error' => $e->getMessage(),
                'data' => $data,
                'user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing invoice demo
     */
    public function updateInvoice(InvoiceDemo $invoice, array $data, int $userId): InvoiceDemo
    {
        try {
            DB::beginTransaction();

            // Store original data for comparison
            $originalData = $invoice->toArray();

            // Prepare invoice data
            $invoiceData = $this->prepareInvoiceData($data, $userId, $invoice->id);
            
            // Separate items data if present
            $itemsData = $data['items'] ?? null;
            unset($invoiceData['items']);

            // Update invoice
            $invoice->update($invoiceData);

            // Update items if provided
            if ($itemsData !== null) {
                $this->updateInvoiceItems($invoice, $itemsData);
            }

            // Calculate totals
            $invoice->calculateTotals();

            // Load relationships for response
            $invoice->load(['user', 'items']);

            DB::commit();

            // Clear related caches
            $this->clearInvoiceCaches();

            // Log activity
            $this->logInvoiceChanges($invoice, $originalData, $userId);

            return $invoice;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update invoice demo', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoice->id,
                'data' => $data,
                'user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Delete an invoice demo (soft delete)
     */
    public function deleteInvoice(InvoiceDemo $invoice, int $userId): bool
    {
        try {
            DB::beginTransaction();

            $result = $invoice->delete();

            DB::commit();

            // Clear related caches
            $this->clearInvoiceCaches();

            // Log activity
            Log::info('Invoice demo deleted', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'user_id' => $userId
            ]);

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete invoice demo', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoice->id,
                'user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Restore a deleted invoice demo
     */
    public function restoreInvoice(InvoiceDemo $invoice, int $userId): bool
    {
        try {
            DB::beginTransaction();

            $result = $invoice->restore();

            DB::commit();

            // Clear related caches
            $this->clearInvoiceCaches();

            // Log activity
            Log::info('Invoice demo restored', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'user_id' => $userId
            ]);

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to restore invoice demo', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoice->id,
                'user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Get form data for dropdowns
     */
    public function getFormData(): array
    {
        $cacheKey = 'invoice_demo_form_data';

        return Cache::remember($cacheKey, $this->cacheTime, function () {
            return [
                'statuses' => $this->getStatusOptions(),
                'type_of_loss_options' => $this->getTypeOfLossOptions(),
                'common_insurance_companies' => $this->getCommonInsuranceCompanies()
            ];
        });
    }

    /**
     * Check if invoice number exists
     */
    public function invoiceNumberExists(string $invoiceNumber, ?string $excludeId = null): bool
    {
        $query = InvoiceDemo::where('invoice_number', $invoiceNumber);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Generate next available invoice number
     */
    public function generateInvoiceNumber(): string
    {
        return InvoiceDemo::generateInvoiceNumber();
    }

    /**
     * Get invoice statistics
     */
    public function getInvoiceStatistics(): array
    {
        $cacheKey = 'invoice_demo_statistics';

        return Cache::remember($cacheKey, $this->cacheTime, function () {
            $total = InvoiceDemo::count();
            $byStatus = InvoiceDemo::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
            
            $totalAmount = InvoiceDemo::sum('balance_due');
            $averageAmount = InvoiceDemo::avg('balance_due');
            
            $overdue = InvoiceDemo::overdue()->count();
            
            return [
                'total' => $total,
                'by_status' => $byStatus,
                'total_amount' => $totalAmount,
                'average_amount' => $averageAmount,
                'overdue' => $overdue,
                'completion_rate' => $total > 0 ? (($byStatus['paid'] ?? 0) / $total) * 100 : 0
            ];
        });
    }

    /**
     * Create invoice items
     */
    protected function createInvoiceItems(InvoiceDemo $invoice, array $itemsData): void
    {
        foreach ($itemsData as $index => $itemData) {
            $itemData['invoice_demo_id'] = $invoice->id;
            $itemData['sort_order'] = $itemData['sort_order'] ?? $index;
            
            InvoiceDemoItem::create($itemData);
        }
    }

    /**
     * Update invoice items
     */
    protected function updateInvoiceItems(InvoiceDemo $invoice, array $itemsData): void
    {
        // Delete existing items
        $invoice->items()->delete();
        
        // Create new items
        $this->createInvoiceItems($invoice, $itemsData);
    }

    /**
     * Prepare invoice data for storage
     */
    protected function prepareInvoiceData(array $data, int $userId, ?string $excludeId = null): array
    {
        // Set user ID
        $data['user_id'] = $userId;
        
        // Format phone number
        if (isset($data['bill_to_phone'])) {
            $data['bill_to_phone'] = $this->formatPhoneNumber($data['bill_to_phone']);
        }
        
        // Ensure financial amounts are properly formatted
        $financialFields = ['subtotal', 'tax_amount', 'balance_due'];
        foreach ($financialFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = (float) $data[$field];
            }
        }
        
        // Format dates
        $dateFields = ['invoice_date', 'date_of_loss', 'date_received', 'date_inspected', 'date_entered'];
        foreach ($dateFields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                $data[$field] = Carbon::parse($data[$field])->format('Y-m-d');
            }
        }
        
        // Handle datetime fields
        $datetimeFields = ['date_received', 'date_inspected', 'date_entered'];
        foreach ($datetimeFields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                $data[$field] = Carbon::parse($data[$field])->format('Y-m-d H:i:s');
            }
        }
        
        return $data;
    }

    /**
     * Format phone number
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $cleaned = preg_replace('/[^\d]/', '', $phone);
        
        // Add country code if missing for US numbers
        if (strlen($cleaned) === 10) {
            $cleaned = '1' . $cleaned;
        }
        
        return $cleaned;
    }

    /**
     * Get status options
     */
    protected function getStatusOptions(): array
    {
        return [
            ['value' => 'draft', 'label' => 'Draft'],
            ['value' => 'sent', 'label' => 'Sent'],
            ['value' => 'paid', 'label' => 'Paid'],
            ['value' => 'cancelled', 'label' => 'Cancelled']
        ];
    }

    /**
     * Get type of loss options
     */
    protected function getTypeOfLossOptions(): array
    {
        return [
            'Hail Damage',
            'Wind Damage', 
            'Fire Damage',
            'Water Damage',
            'Storm Damage',
            'Vandalism',
            'Theft',
            'Other'
        ];
    }

    /**
     * Get common insurance companies
     */
    protected function getCommonInsuranceCompanies(): array
    {
        return [
            'State Farm',
            'Allstate',
            'GEICO',
            'Progressive',
            'USAA',
            'Liberty Mutual',
            'Farmers',
            'Nationwide',
            'American Family',
            'Travelers'
        ];
    }

    /**
     * Check if invoice number exists
     */
    public function checkInvoiceNumberExists(string $invoiceNumber, ?string $excludeId = null): bool
    {
        $query = InvoiceDemo::where('invoice_number', $invoiceNumber);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Generate cache key
     */
    protected function generateCacheKey(string $prefix, array $params = []): string
    {
        $key = 'invoice_demo_' . $prefix;
        
        if (!empty($params)) {
            $key .= '_' . md5(serialize($params));
        }
        
        return $key;
    }

    /**
     * Clear invoice-related caches
     */
    protected function clearInvoiceCaches(): void
    {
        $patterns = [
            'invoice_demo_invoices_*',
            'invoice_demo_form_data',
            'invoice_demo_statistics'
        ];
        
        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
        }
        
        // Clear paginated results cache
        $cacheKeys = Cache::get('invoice_demo_cache_keys', []);
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
        Cache::forget('invoice_demo_cache_keys');
    }

    /**
     * Log invoice changes
     */
    protected function logInvoiceChanges(InvoiceDemo $invoice, array $originalData, int $userId): void
    {
        $changes = [];
        $currentData = $invoice->toArray();
        
        foreach ($currentData as $key => $value) {
            if (isset($originalData[$key]) && $originalData[$key] !== $value) {
                $changes[$key] = [
                    'from' => $originalData[$key],
                    'to' => $value
                ];
            }
        }
        
        if (!empty($changes)) {
            Log::info('Invoice demo updated', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'changes' => $changes,
                'user_id' => $userId
            ]);
        }
    }
}