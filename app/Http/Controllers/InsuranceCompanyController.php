<?php

namespace App\Http\Controllers;

use App\Models\InsuranceCompany;
use App\Models\User;
use App\Http\Requests\InsuranceCompanyRequest;
use App\Services\TransactionService;
use App\Services\InsuranceCompanyService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Traits\CacheTraitCrud;
use Throwable;
use App\Http\DTOs\InsuranceCompanyDTO;

class InsuranceCompanyController extends BaseCrudController
{
    use CacheTraitCrud;
    
    protected $modelClass = InsuranceCompany::class;
    protected $entityName = 'INSURANCE_COMPANY';
    protected $routePrefix = 'insurance-companies';
    protected $viewPrefix = 'insurance-companies';
    
    // Cache time for insurance companies - 5 minutes (default is good for this data)
    protected $cacheTime = 300;

    public function __construct(TransactionService $transactionService)
    {
        parent::__construct($transactionService);
        
        // Initialize cache properties with defaults
        $this->initializeCacheProperties();
    }

    /**
     * Display a listing of insurance companies with users for modal.
     */
    public function index(Request $request): View|JsonResponse|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            // For AJAX requests, use the parent implementation but with user relationship
            $query = InsuranceCompany::with('user');
            
            // Apply search filter if provided
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('insurance_company_name', 'like', $searchTerm)
                      ->orWhere('address', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('phone', 'like', $searchTerm)
                      ->orWhere('website', 'like', $searchTerm);
                });
            }
            
            // Apply sorting
            $sortField = $request->input('sort_field', 'insurance_company_name');
            $sortDirection = $request->input('sort_direction', 'asc');
            $query->orderBy($sortField, $sortDirection);
            
            // Show deleted items if requested
            if ($request->has('show_deleted') && $request->show_deleted === 'true') {
                $query->withTrashed();
            }
            
            // Paginate results
            $perPage = (int) $request->input('per_page', 10);
            $entities = $query->paginate($perPage);
            
            return response()->json($entities);
        }
        
        // For view requests, get users for the modal
        $users = User::orderBy('name')->get();
        
        return view($this->viewPrefix . '.index', [
            'users' => $users
        ]);
    }

    /**
     * Check if insurance company name already exists.
     */
    public function checkNameExists(Request $request): JsonResponse
    {
        $name = $request->input('insurance_company_name');
        $excludeUuid = $request->input('uuid');
        
        $exists = $this->insuranceCompanyService->nameExists($name, $excludeUuid);
        
        return response()->json(['exists' => $exists]);
    }

    /**
     * Check if email already exists.
     */
    public function checkEmailExists(Request $request): JsonResponse
    {
        $email = $request->input('email');
        $excludeUuid = $request->input('uuid');
        
        $exists = $this->insuranceCompanyService->emailExists($email, $excludeUuid);
        
        return response()->json(['exists' => $exists]);
    }

    /**
     * Check if phone already exists.
     */
    public function checkPhoneExists(Request $request): JsonResponse
    {
        $phone = $request->input('phone');
        $excludeUuid = $request->input('uuid');
        
        // Format phone for checking
        $formattedPhone = $this->formatPhoneForStorage($phone);
        $exists = $this->insuranceCompanyService->phoneExists($formattedPhone, $excludeUuid);
        
        return response()->json(['exists' => $exists]);
    }

    /**
     * Get form data for dropdowns
     */
    public function getFormData(): JsonResponse
    {
        $users = User::orderBy('name')
                    ->select('id', 'name', 'email')
                    ->get()
                    ->map(function ($user) {
                        return [
                            'value' => $user->id,
                            'text' => $user->name . ' (' . $user->email . ')'
                        ];
                    });

        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * Get validation rules for insurance company
     */
    protected function getValidationRules($id = null)
    {
        $nameRule = 'required|string|max:255|unique:insurance_companies,insurance_company_name';
        $emailRule = 'required|email|max:255|unique:insurance_companies,email';
        $phoneRule = 'required|string|max:20|unique:insurance_companies,phone';
        
        // If we have an ID (UUID in this case), exclude it from the unique check
        if ($id) {
            $nameRule .= ',' . $id . ',uuid';
            $emailRule .= ',' . $id . ',uuid';
            $phoneRule .= ',' . $id . ',uuid';
        }
        
        return [
            'insurance_company_name' => $nameRule . '|regex:/^[a-zA-Z\s\-\.\&\,\']+$/',
            'address' => 'required|string|max:500',
            'phone' => $phoneRule . '|regex:/^[\+]?[1-9][\d\-\(\)\s]{8,20}$/',
            'email' => $emailRule,
            'website' => 'nullable|url|max:255',
            'user_id' => 'nullable|exists:users,id',
        ];
    }

    /**
     * Get validation messages for insurance company
     */
    protected function getValidationMessages()
    {
        return [
            'insurance_company_name.required' => 'The insurance company name is required.',
            'insurance_company_name.unique' => 'This insurance company name is already taken.',
            'insurance_company_name.max' => 'The insurance company name may not be greater than 255 characters.',
            'insurance_company_name.regex' => 'The insurance company name contains invalid characters.',
            'address.required' => 'The address is required.',
            'address.max' => 'The address may not be greater than 500 characters.',
            'phone.required' => 'The phone number is required.',
            'phone.unique' => 'This phone number is already taken.',
            'phone.max' => 'The phone number may not be greater than 20 characters.',
            'phone.regex' => 'The phone number format is invalid.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'This email is already taken.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'website.url' => 'The website must be a valid URL.',
            'website.max' => 'The website may not be greater than 255 characters.',
            'user_id.exists' => 'The selected user does not exist.',
        ];
    }

    /**
     * Get the search field for the entity
     */
    protected function getSearchField()
    {
        return 'insurance_company_name';
    }

    /**
     * Get the name field for the entity
     */
    protected function getNameField()
    {
        return 'insurance_company_name';
    }

    /**
     * Get entity display name
     */
    protected function getEntityDisplayName($entity)
    {
        return $entity->insurance_company_name . ' (' . $entity->email . ')';
    }

    /**
     * Prepare data for storing
     */
    protected function prepareStoreData(Request $request)
    {
        $formattedPhone = $this->formatPhoneForStorage($request->phone);
        $formattedWebsite = $this->formatWebsite($request->website);
        
        Log::info('InsuranceCompanyController::prepareStoreData', [
            'original_phone' => $request->phone,
            'formatted_phone' => $formattedPhone,
            'original_website' => $request->website,
            'formatted_website' => $formattedWebsite
        ]);

        return [
            'uuid' => (string) Str::uuid(),
            'insurance_company_name' => trim($request->insurance_company_name),
            'address' => trim($request->address),
            'phone' => $formattedPhone,
            'email' => strtolower(trim($request->email)),
            'website' => $formattedWebsite,
            'user_id' => $request->user_id,
        ];
    }

    /**
     * Prepare data for updating
     */
    protected function prepareUpdateData(Request $request)
    {
        $formattedPhone = $this->formatPhoneForStorage($request->phone);
        $formattedWebsite = $this->formatWebsite($request->website);
        
        Log::info('InsuranceCompanyController::prepareUpdateData', [
            'original_phone' => $request->phone,
            'formatted_phone' => $formattedPhone,
            'original_website' => $request->website,
            'formatted_website' => $formattedWebsite
        ]);

        return array_filter([
            'insurance_company_name' => trim($request->insurance_company_name),
            'address' => trim($request->address),
            'phone' => $formattedPhone,
            'email' => strtolower(trim($request->email)),
            'website' => $formattedWebsite,
            'user_id' => $request->user_id,
        ], fn ($value) => !is_null($value));
    }

    /**
     * Format phone number for storage
     */
    private function formatPhoneForStorage(string $phone): string
    {
        // Remove all non-digits
        $cleaned = preg_replace('/\D/', '', $phone);
        
        // If it's 10 digits, add +1 prefix for US numbers
        if (strlen($cleaned) === 10) {
            return '+1' . $cleaned;
        }
        
        // If it's 11 digits and starts with 1, add + prefix
        if (strlen($cleaned) === 11 && str_starts_with($cleaned, '1')) {
            return '+' . $cleaned;
        }
        
        return $cleaned;
    }

    /**
     * Format website URL
     */
    private function formatWebsite(?string $website): ?string
    {
        if (empty($website)) {
            return null;
        }
        
        $website = trim($website);
        
        // Add https:// if no protocol specified
        if (!preg_match('/^https?:\/\//', $website)) {
            $website = 'https://' . $website;
        }
        
        return $website;
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->getValidationRules(), $this->getValidationMessages());
        $dto = InsuranceCompanyDTO::fromArray($validated);
        $insuranceCompany = $this->insuranceCompanyService->create($dto);
        return response()->json([
            'success' => true,
            'message' => __('Insurance company created successfully'),
            'data' => $insuranceCompany,
        ]);
    }

    public function update(Request $request, $uuid)
    {
        $validated = $request->validate($this->getValidationRules($uuid), $this->getValidationMessages());
        $dto = InsuranceCompanyDTO::fromArray(array_merge($validated, ['uuid' => $uuid]));
        $insuranceCompany = $this->insuranceCompanyService->update($dto);
        return response()->json([
            'success' => true,
            'message' => __('Insurance company updated successfully'),
            'data' => $insuranceCompany,
        ]);
    }

    public function show($uuid)
    {
        $insuranceCompany = $this->insuranceCompanyService->findByUuid($uuid);
        if (!$insuranceCompany) {
            return response()->json(['success' => false, 'message' => 'Insurance company not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $insuranceCompany]);
    }

    public function destroy($uuid)
    {
        $deleted = $this->insuranceCompanyService->delete($uuid);
        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Unable to delete insurance company'], 404);
        }
        return response()->json(['success' => true, 'message' => 'Insurance company deleted successfully']);
    }

    public function restore($uuid)
    {
        $restored = $this->insuranceCompanyService->restore($uuid);
        if (!$restored) {
            return response()->json(['success' => false, 'message' => 'Unable to restore insurance company'], 404);
        }
        return response()->json(['success' => true, 'message' => 'Insurance company restored successfully']);
    }
}
