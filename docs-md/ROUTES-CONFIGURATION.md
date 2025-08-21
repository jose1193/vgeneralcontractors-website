# 🛣️ Configuración de Rutas - V General Contractors CRUD

## 🌐 **Rutas Web (Recomendado)**

Para portal interno con interfaz web:

```php
// routes/web.php

use App\Http\Controllers\Claims\ClaimController;

Route::middleware(['auth', 'verified'])->group(function () {

    // ===== CLAIMS MANAGEMENT =====
    Route::prefix('claims')->name('claims.')->group(function () {
        // CRUD básico
        Route::get('/', [ClaimController::class, 'index'])->name('index');
        Route::get('/create', [ClaimController::class, 'create'])->name('create');
        Route::post('/', [ClaimController::class, 'store'])->name('store');
        Route::get('/{claim}', [ClaimController::class, 'show'])->name('show');
        Route::get('/{claim}/edit', [ClaimController::class, 'edit'])->name('edit');
        Route::put('/{claim}', [ClaimController::class, 'update'])->name('update');
        Route::delete('/{claim}', [ClaimController::class, 'destroy'])->name('destroy');

        // Funcionalidades adicionales
        Route::get('/search', [ClaimController::class, 'search'])->name('search');
        Route::get('/dashboard', [ClaimController::class, 'dashboard'])->name('dashboard');
        Route::patch('/{claim}/status', [ClaimController::class, 'updateStatus'])->name('update-status');
        Route::post('/{claim}/restore', [ClaimController::class, 'restore'])->name('restore');

        // Exportar
        Route::get('/export/excel', [ClaimController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [ClaimController::class, 'exportPdf'])->name('export.pdf');
    });

    // ===== APPOINTMENTS MANAGEMENT =====
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::resource('/', AppointmentController::class)->parameters(['' => 'appointment']);
        Route::get('/calendar', [AppointmentController::class, 'calendar'])->name('calendar');
        Route::patch('/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('confirm');
        Route::patch('/{appointment}/reschedule', [AppointmentController::class, 'reschedule'])->name('reschedule');
    });

    // ===== DASHBOARD ROUTES =====
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
});

// ===== PUBLIC ROUTES (sin auth) =====
Route::prefix('public')->name('public.')->group(function () {
    Route::post('/claims/submit', [PublicClaimController::class, 'submit'])->name('claims.submit');
    Route::get('/claims/status/{uuid}', [PublicClaimController::class, 'checkStatus'])->name('claims.status');
});
```

## 🔌 **Rutas API (Opcional)**

Si necesitas API para móvil o integraciones:

```php
// routes/api.php

use App\Http\Controllers\Api\ClaimApiController;

Route::middleware(['auth:sanctum'])->group(function () {

    // ===== CLAIMS API =====
    Route::prefix('v1')->group(function () {
        // RESTful API
        Route::apiResource('claims', ClaimApiController::class);

        // Endpoints específicos
        Route::get('claims/search', [ClaimApiController::class, 'search']);
        Route::get('claims/stats', [ClaimApiController::class, 'getStats']);
        Route::patch('claims/{claim}/status', [ClaimApiController::class, 'updateStatus']);

        // Bulk operations
        Route::post('claims/bulk-update', [ClaimApiController::class, 'bulkUpdate']);
        Route::delete('claims/bulk-delete', [ClaimApiController::class, 'bulkDelete']);
    });
});

// ===== PUBLIC API (sin auth) =====
Route::prefix('public/v1')->group(function () {
    Route::post('claims', [PublicClaimApiController::class, 'store']);
    Route::get('claims/{uuid}/status', [PublicClaimApiController::class, 'getStatus']);
});
```

## 🎯 **Controladores Específicos**

### **Web Controller (para web.php)**

```php
// app/Http/Controllers/Claims/ClaimController.php
class ClaimController extends BaseCrudController
{
    public function index()
    {
        $claims = $this->claimService->paginate(request()->all());
        return view('claims.index', compact('claims'));
    }

    public function store(StoreClaimRequest $request)
    {
        $claim = $this->claimService->create($request->getClaimData());
        return redirect()->route('claims.show', $claim)
            ->with('success', 'Claim created successfully');
    }

    public function show(Claim $claim)
    {
        return view('claims.show', compact('claim'));
    }
}
```

### **API Controller (para api.php)**

```php
// app/Http/Controllers/Api/ClaimApiController.php
class ClaimApiController extends Controller
{
    public function index()
    {
        $claims = $this->claimService->paginate(request()->all());
        return ClaimResource::collection($claims);
    }

    public function store(StoreClaimRequest $request)
    {
        $claim = $this->claimService->create($request->getClaimData());
        return new ClaimResource($claim);
    }

    public function show(Claim $claim)
    {
        return new ClaimResource($claim);
    }
}
```

## 🛡️ **Middleware Recomendado**

### **Para Web Routes:**

```php
Route::middleware([
    'auth',           // Usuario autenticado
    'verified',       // Email verificado (Jetstream)
    'throttle:60,1',  // Rate limiting
])->group(function () {
    // Rutas protegidas
});
```

### **Para API Routes:**

```php
Route::middleware([
    'auth:sanctum',   // API authentication
    'throttle:api',   // API rate limiting
])->group(function () {
    // API endpoints
});
```

## 📋 **Permisos por Ruta**

```php
// En el Controller
class ClaimController extends BaseCrudController
{
    public function index()
    {
        $this->checkPermission('claims.view');
        // ...
    }

    public function store(StoreClaimRequest $request)
    {
        $this->checkPermission('claims.create');
        // ...
    }

    public function update(UpdateClaimRequest $request, Claim $claim)
    {
        $this->checkPermission('claims.edit');
        // ...
    }

    public function destroy(Claim $claim)
    {
        $this->checkPermission('claims.delete');
        // ...
    }
}
```

## 🎯 **Recomendación Final**

Para **V General Contractors**:

### ✅ **Usar WEB.php** para:

-   Portal de gestión interno
-   Interfaz de usuario web
-   Autenticación con Jetstream
-   Vistas Blade

### 🔌 **Agregar API.php** solo si necesitas:

-   App móvil
-   Integraciones externas
-   Microservicios
-   Frontend SPA (React/Vue)

**¿Empezamos con web.php o necesitas ambos?**
