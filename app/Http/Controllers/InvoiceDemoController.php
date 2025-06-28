<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Resources\InvoiceDemoResource;
use App\Http\Requests\InvoiceDemoRequest;
use App\Models\InvoiceDemo;
use App\Services\InvoiceDemoService;
use App\Services\TransactionService;
use App\Traits\CacheTraitCrud;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Throwable;

class InvoiceDemoController extends BaseController
{
    use CacheTraitCrud;
    
    protected int $cacheTime = 300; // 5 minutes
    protected InvoiceDemoService $invoiceService;

    public function __construct(InvoiceDemoService $invoiceService, TransactionService $transactionService)
    {
        parent::__construct($transactionService);
        $this->invoiceService = $invoiceService;
        
        // Set properties for parent compatibility
        $this->modelClass = InvoiceDemo::class;
        $this->entityName = 'INVOICE_DEMO';
        $this->viewPrefix = 'invoices';
        $this->routePrefix = 'invoices';
    }

    /**
     * Override index method to use service and resource
     */
    public function index(Request $request): View|JsonResponse|RedirectResponse
    {
        try {
            // Check permissions using the correct method from BaseController
            if (!$this->checkPermissionWithMessage("READ_{$this->entityName}", "You don't have permission to view invoice demos")) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to view invoice demos',
                    ], 403);
                }
                
                return redirect()->route('dashboard')->with('error', 'You do not have permission to view invoice demos');
            }

            if ($request->ajax() || $request->wantsJson()) {
                $invoices = $this->invoiceService->getPaginatedInvoices(
                    page: (int) $request->get('page', 1),
                    perPage: (int) $request->get('per_page', 10),
                    search: (string) $request->get('search', ''),
                    status: (string) $request->get('status', ''),
                    sortBy: (string) $request->get('sort_by', 'created_at'),
                    sortOrder: (string) $request->get('sort_order', 'desc'),
                    includeDeleted: $request->boolean('include_deleted')
                );

                return response()->json([
                    'success' => true,
                    'data' => [
                        'data' => InvoiceDemoResource::collection($invoices->items()),
                        'current_page' => $invoices->currentPage(),
                        'last_page' => $invoices->lastPage(),
                        'per_page' => $invoices->perPage(),
                        'total' => $invoices->total(),
                        'from' => $invoices->firstItem(),
                        'to' => $invoices->lastItem()
                    ]
                ]);
            }

            return view($this->viewPrefix . '.index');
        } catch (Throwable $e) {
            Log::error('Failed to load invoice demos', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load invoice demos'
                ], 500);
            }

            return back()->with('error', 'Failed to load invoice demos');
        }
    }

    /**
     * Store a new invoice demo
     */
    public function store(Request $request): JsonResponse
    {
        if (!$this->checkPermissionWithMessage("CREATE_{$this->entityName}", "You don't have permission to create invoice demos")) {
            return response()->json([
                'success' => false,
                'message' => "You don't have permission to create invoice demos"
            ], 403);
        }

        try {
            $this->validateRequest($request);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $invoice = $this->invoiceService->createInvoice(
                $request->all(),
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Invoice demo created successfully',
                'data' => new InvoiceDemoResource($invoice)
            ], 201);
        } catch (Throwable $e) {
            Log::error('Failed to create invoice demo', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create invoice demo'
            ], 500);
        }
    }

    /**
     * Update an existing invoice demo
     */
    public function update(Request $request, string $uuid): JsonResponse
    {
        if (!$this->checkPermissionWithMessage("UPDATE_{$this->entityName}", "You don't have permission to update invoice demos")) {
            return response()->json([
                'success' => false,
                'message' => "You don't have permission to update invoice demos"
            ], 403);
        }

        try {
            $invoice = InvoiceDemo::where('uuid', $uuid)->firstOrFail();
            
            try {
                $this->validateRequest($request, $invoice->id);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            
            $updatedInvoice = $this->invoiceService->updateInvoice(
                $invoice,
                $request->all(),
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Invoice demo updated successfully',
                'data' => new InvoiceDemoResource($updatedInvoice)
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to update invoice demo', [
                'error' => $e->getMessage(),
                'invoice_uuid' => $uuid,
                'data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update invoice demo'
            ], 500);
        }
    }

    /**
     * Delete invoice demo (soft delete)
     */
    public function destroy(string $uuid): JsonResponse
    {
        if (!$this->checkPermissionWithMessage("DELETE_{$this->entityName}", "You don't have permission to delete invoice demos")) {
            return response()->json([
                'success' => false,
                'message' => "You don't have permission to delete invoice demos"
            ], 403);
        }

        try {
            $invoice = InvoiceDemo::where('uuid', $uuid)->firstOrFail();
            
            $this->invoiceService->deleteInvoice($invoice, auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Invoice demo deleted successfully'
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to delete invoice demo', [
                'error' => $e->getMessage(),
                'invoice_uuid' => $uuid,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete invoice demo'
            ], 500);
        }
    }

    /**
     * Restore deleted invoice demo
     */
    public function restore(string $uuid): JsonResponse
    {
        if (!$this->checkPermissionWithMessage("RESTORE_{$this->entityName}", "You don't have permission to restore invoice demos")) {
            return response()->json([
                'success' => false,
                'message' => "You don't have permission to restore invoice demos"
            ], 403);
        }

        try {
            $invoice = InvoiceDemo::withTrashed()->where('uuid', $uuid)->firstOrFail();
            
            $this->invoiceService->restoreInvoice($invoice, auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Invoice demo restored successfully'
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to restore invoice demo', [
                'error' => $e->getMessage(),
                'invoice_uuid' => $uuid,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to restore invoice demo'
            ], 500);
        }
    }

    /**
     * Get dropdown data for form selects
     */
    public function getFormData(): JsonResponse
    {
        try {
            $formData = $this->invoiceService->getFormData();
            
            return response()->json([
                'success' => true,
                'data' => $formData
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to get form data for invoice demo', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load form data'
            ], 500);
        }
    }

    /**
     * Check if invoice number exists (for real-time validation)
     */
    public function checkInvoiceNumberExists(Request $request): JsonResponse
    {
        try {
            $invoiceNumber = $request->input('invoice_number');
            $excludeId = $request->input('exclude_id'); // For edit mode
            
            if (!$invoiceNumber) {
                return response()->json(['exists' => false]);
            }
            
            $exists = $this->invoiceService->checkInvoiceNumberExists($invoiceNumber, $excludeId);
            
            return response()->json([
                'exists' => $exists
            ]);
        } catch (Throwable $e) {
            Log::error('Error checking invoice number existence', [
                'error' => $e->getMessage(),
                'invoice_number' => $request->input('invoice_number')
            ]);
            
            return response()->json([
                'exists' => false,
                'error' => 'Unable to check invoice number'
            ], 500);
        }
    }

    /**
     * Generate next invoice number
     */
    public function generateInvoiceNumber(): JsonResponse
    {
        try {
            $invoiceNumber = $this->invoiceService->generateInvoiceNumber();
            
            return response()->json([
                'success' => true,
                'invoice_number' => $invoiceNumber
            ]);
        } catch (Throwable $e) {
            Log::error('Error generating invoice number', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to generate invoice number'
            ], 500);
        }
    }

    // Required abstract methods from BaseController
    protected function getValidationRules(?int $id = null): array
    {
        $invoiceDemoRequest = new InvoiceDemoRequest();
        return $invoiceDemoRequest->rules();
    }

    protected function getValidationMessages(): array
    {
        $invoiceDemoRequest = new InvoiceDemoRequest();
        return $invoiceDemoRequest->messages();
    }

    // Override methods from BaseController for invoice-specific behavior
    protected function getSearchField(): string
    {
        return 'invoice_number';
    }
    
    protected function getNameField(): string
    {
        return 'invoice_number';
    }

    protected function getEntityDisplayName($entity): string
    {
        return $entity->invoice_number;
    }

    protected function prepareStoreData(Request $request): array
    {
        $data = $request->all();
        $data['user_id'] = auth()->id();
        return $data;
    }
    
    protected function prepareUpdateData(Request $request): array
    {
        return $request->all();
    }
}