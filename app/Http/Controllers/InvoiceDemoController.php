<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseCrudController;
use App\Http\Resources\InvoiceDemoResource;
use App\Http\Requests\InvoiceDemoRequest;
use App\Models\InvoiceDemo;
use App\Services\InvoiceDemoService;
use App\Traits\CacheTraitCrud;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Services\TransactionService;
use Carbon\Carbon;
use Throwable;

class InvoiceDemoController extends BaseCrudController
{
    use CacheTraitCrud;
    
    protected $routePrefix = 'invoice-demos';
    protected $viewPrefix = 'invoice-demos';
    protected int $cacheTime = 300; // 5 minutes

    protected InvoiceDemoService $invoiceService;

    public function __construct(InvoiceDemoService $invoiceService, TransactionService $transactionService)
    {
        parent::__construct($transactionService);
        $this->invoiceService = $invoiceService;
        
        // Set properties for parent compatibility
        $this->modelClass = InvoiceDemo::class;
        $this->entityName = 'INVOICE_DEMO';
    }

    /**
     * Override index method to use service and resource
     */
    public function index(Request $request)
    {
        try {
            // Check permissions
            if (!$this->checkPermission("READ_{$this->entityName}", false)) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to view invoice demos',
                    ], 403);
                }
                
                return redirect()->route('dashboard')->with('error', 'You do not have permission to view invoice demos');
            }

            if ($request->ajax()) {
                $invoices = $this->invoiceService->getPaginatedInvoices(
                    page: (int) $request->get('page', 1),
                    perPage: (int) $request->get('per_page', 10),
                    search: $request->get('search', ''),
                    status: $request->get('status', ''),
                    sortBy: $request->get('sort_by', 'created_at'),
                    sortOrder: $request->get('sort_order', 'desc'),
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

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load invoice demos'
                ], 500);
            }

            return back()->with('error', 'Failed to load invoice demos');
        }
    }

    /**
     * Store a new invoice demo using InvoiceDemoRequest
     */
    public function store(Request $request)
    {
        try {
            // Check permissions
            if (!$this->checkPermissionWithMessage("CREATE_{$this->entityName}", "You don't have permission to create {$this->entityName}")) {
                return response()->json([
                    'success' => false,
                    'message' => "You don't have permission to create invoice demos"
                ], 403);
            }

            // Manual validation using InvoiceDemoRequest rules
            $invoiceDemoRequest = new InvoiceDemoRequest();
            $validator = Validator::make(
                $request->all(), 
                $invoiceDemoRequest->rules(),
                $invoiceDemoRequest->messages()
            );

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $invoice = $this->invoiceService->createInvoice(
                $validator->validated(),
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
     * Show a specific invoice demo
     */
    public function show($uuid)
    {
        try {
            // Check permissions
            if (!$this->checkPermissionWithMessage("READ_{$this->entityName}", "You don't have permission to view {$this->entityName}")) {
                return response()->json([
                    'success' => false,
                    'message' => "You don't have permission to view invoice demos"
                ], 403);
            }

            $invoice = InvoiceDemo::withTrashed()->where('uuid', $uuid)->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => new InvoiceDemoResource($invoice)
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to show invoice demo', [
                'error' => $e->getMessage(),
                'invoice_uuid' => $uuid,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load invoice demo'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified invoice demo
     */
    public function edit($uuid)
    {
        try {
            // Check permissions
            if (!$this->checkPermissionWithMessage("UPDATE_{$this->entityName}", "You don't have permission to edit {$this->entityName}")) {
                return response()->json([
                    'success' => false,
                    'message' => "You don't have permission to edit invoice demos"
                ], 403);
            }

            $invoice = InvoiceDemo::withTrashed()->where('uuid', $uuid)->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => new InvoiceDemoResource($invoice)
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to load invoice demo for editing', [
                'error' => $e->getMessage(),
                'invoice_uuid' => $uuid,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load invoice demo for editing'
            ], 404);
        }
    }

    /**
     * Update an existing invoice demo using InvoiceDemoRequest
     */
    public function update(Request $request, $uuid)
    {
        try {
            // Check permissions
            if (!$this->checkPermissionWithMessage("UPDATE_{$this->entityName}", "You don't have permission to update {$this->entityName}")) {
                return response()->json([
                    'success' => false,
                    'message' => "You don't have permission to update invoice demos"
                ], 403);
            }

            // Manual validation using InvoiceDemoRequest rules
            $invoiceDemoRequest = new InvoiceDemoRequest();
            $validator = Validator::make(
                $request->all(), 
                $invoiceDemoRequest->rules(),
                $invoiceDemoRequest->messages()
            );

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $invoice = InvoiceDemo::where('uuid', $uuid)->firstOrFail();
            
            $updatedInvoice = $this->invoiceService->updateInvoice(
                $invoice,
                $validator->validated(),
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
    public function destroy($uuid)
    {
        try {
            // Check permissions
            if (!$this->checkPermissionWithMessage("DELETE_{$this->entityName}", "You don't have permission to delete {$this->entityName}")) {
                return response()->json([
                    'success' => false,
                    'message' => "You don't have permission to delete invoice demos"
                ], 403);
            }

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
    public function restore($uuid)
    {
        try {
            // Check permissions
            if (!$this->checkPermissionWithMessage("RESTORE_{$this->entityName}", "You don't have permission to restore {$this->entityName}")) {
                return response()->json([
                    'success' => false,
                    'message' => "You don't have permission to restore invoice demos"
                ], 403);
            }

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
    public function getFormData()
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
    public function checkInvoiceNumberExists(Request $request)
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
    public function generateInvoiceNumber()
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

    /**
     * Get search fields
     */
    protected function getSearchFields(): array
    {
        return ['invoice_number', 'bill_to_name', 'bill_to_address', 'claim_number'];
    }

    /**
     * Get name field for display
     */
    protected function getNameField(): string
    {
        return 'invoice_number';
    }

    /**
     * Get validation rules (required by BaseCrudController)
     */
    protected function getValidationRules($id = null): array
    {
        // Since we use InvoiceDemoRequest, return empty array
        // The actual validation is handled by the Request class
        return [];
    }

    /**
     * Get validation messages (required by BaseCrudController)
     */
    protected function getValidationMessages(): array
    {
        // Since we use InvoiceDemoRequest, return empty array
        // The actual validation messages are handled by the Request class
        return [];
    }
}