<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Users;
use App\Livewire\EmailDatas;
use App\Livewire\CompanyData;
use App\Livewire\BlogCategories;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FacebookLeadFormController;
use App\Http\Controllers\ConfirmationController;
use App\Http\Controllers\ContactSupportController;
use App\Http\Controllers\LanguageController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use App\Http\Controllers\AppointmentController;

// Language Routes
Route::get('/language/switch/{language}', [LanguageController::class, 'switch'])->name('language.switch');
Route::post('/language/ajax-switch', [LanguageController::class, 'ajaxSwitch'])->name('language.ajax-switch');
Route::get('/api/language/current', [LanguageController::class, 'current'])->name('language.current');

// Test route for translation debugging
Route::get('/test-translation', function () {
    return view('test-translation');
})->name('test.translation');

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact-support', [ContactSupportController::class, 'showForm'])->name('contact-support');
Route::post('/contact-support', [ContactSupportController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact-support.store');
Route::post('/contact-support/validate', [ContactSupportController::class, 'validateField'])->name('contact-support.validate');

// Google Authentication Routes
Route::get('/google-auth/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('/google-auth/callback', function () {
    try {
        $googleUser = Socialite::driver('google')->user();
        
        // Check if user exists with the same email address
        $existingUser = User::where('email', $googleUser->email)->first();
        
        if ($existingUser) {
            // If user exists but email isn't verified, set verification to now
            if (!$existingUser->email_verified_at) {
                $existingUser->email_verified_at = now();
                $existingUser->save();
            }
            
            // Log in the existing user
            Auth::login($existingUser);
            return redirect(secure_url('/dashboard'));
        } else {
            // New users are not allowed, only login with existing emails
            return redirect(secure_url('/'))->with('error', 'This email is not registered in our system. Please contact the administrator for access.');
        }
    } catch (\Exception $e) {
        return redirect(secure_url('/'))->with('error', 'Error authenticating with Google: ' . $e->getMessage());
    }
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'throttle:60,1', // 60 requests per minute
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/users', function () {
        return view('users');
    })->name('users');

    Route::get('/email-datas', function () {
        return view('email-datas');
    })->name('email-datas');

    Route::get('/company-data', function () {
        return view('company-data');
    })->name('company-data');

    Route::get('/service-categories', function () {
        return view('service-categories');
    })->name('service-categories');
    
    Route::get('/blog-categories', function () {
        return view('blog-categories');
    })->name('blog-categories');

    Route::get('/portfolios', function () {
        return view('portfolios');
    })->name('portfolios');

    Route::get('/admin/posts', function () {
        return view('posts');
    })->name('admin.posts');

    // Call Records Routes
    Route::get('/call-records', [App\Http\Controllers\CallRecordsController::class, 'index'])->name('call-records');
    Route::get('/api/call-records', [App\Http\Controllers\CallRecordsController::class, 'getCalls'])->name('api.call-records');
    Route::get('/api/call-records/{callId}', [App\Http\Controllers\CallRecordsController::class, 'getCallDetails'])->name('api.call-records.details');
    Route::post('/api/call-records/clear-cache', [App\Http\Controllers\CallRecordsController::class, 'clearCallRecordsCache'])->name('api.call-records.clear-cache');

    // Appointment Resource Routes (CRUD)
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::get('/create', [AppointmentController::class, 'create'])->name('create');
        Route::post('/', [AppointmentController::class, 'store'])->name('store');
        Route::get('/{uuid}', [AppointmentController::class, 'show'])->name('show');
        Route::get('/{uuid}/edit', [AppointmentController::class, 'edit'])->name('edit');
        Route::put('/{uuid}', [AppointmentController::class, 'update'])->name('update');
        Route::delete('/{uuid}', [AppointmentController::class, 'destroy'])->name('destroy');
        Route::patch('/{uuid}/restore', [AppointmentController::class, 'restore'])->name('restore');
        Route::post('/check-email', [AppointmentController::class, 'checkEmailExists'])->name('check-email');
        Route::post('/send-rejection', [AppointmentController::class, 'sendRejection'])->name('send-rejection');
    });

    // Appointment Calendar Routes
    Route::get('/appointment-calendar', [\App\Http\Controllers\AppointmentCalendarController::class, 'index'])->name('appointment-calendar');
    Route::get('/appointment-calendar/events', [\App\Http\Controllers\AppointmentCalendarController::class, 'events'])->name('appointment-calendar.events');
    Route::get('/appointment-calendar/clients', [\App\Http\Controllers\AppointmentCalendarController::class, 'getClients'])->name('appointment-calendar.clients');
    Route::patch('/appointment-calendar/events/{id}', [\App\Http\Controllers\AppointmentCalendarController::class, 'update'])->name('appointment-calendar.update');
    Route::patch('/appointment-calendar/status/{id}', [\App\Http\Controllers\AppointmentCalendarController::class, 'updateStatus'])->name('appointment-calendar.status');
    Route::post('/appointment-calendar/create', [\App\Http\Controllers\AppointmentCalendarController::class, 'create'])->name('appointment-calendar.create');
});

Route::get('/new-roof', function () {
    return view('services.new-roof');
})->name('new-roof');

Route::get('/roof-repair', function () {
    return view('services.roof-repair');
})->name('roof-repair');

Route::get('/storm-damage', function () {
    return view('services.storm-damage');
})->name('storm-damage');

Route::get('/hail-damage', function () {
    return view('services.hail-damage');
})->name('hail-damage');

Route::get('/warranties', function () {
    return view('warranties');
})->name('warranties');

Route::get('/products', function () {
    return view('products');
})->name('products');

Route::get('/financing', function () {
    return view('financing');
})->name('financing');

Route::get('/virtual-remodeler', function () {
    return view('virtual-remodeler');
})->name('virtual-remodeler');

Route::get('/insurance-claims', function () {
    return view('insurance-claims');
})->name('insurance-claims');

Route::get('/portfolio', function () {
    return view('portfolio');
})->name('portfolio');

Route::get('/faqs', function () {
    return view('faqs');
})->name('faqs');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');

Route::get('/cookies-policy', function () {
    return view('cookies-policy');
})->name('cookies-policy');

Route::get('/terms-and-conditions', function () {
    return view('terms-and-conditions');
})->name('terms-and-conditions');

// Rutas públicas del blog
Route::get('/blog', [PostController::class, 'showLatestPosts'])->name('blog.index');
Route::get('/blog/search', [PostController::class, 'search'])->name('blog.search');
Route::get('/blog/category/{categorySlug}', [PostController::class, 'showPostsByCategory'])->name('blog.category');
Route::get('/blog/{slug}', [PostController::class, 'show'])->name('blog.show');
Route::get('/feed', [FeedController::class, 'rss'])->name('feeds.rss');

Route::get('/services', function () {
    return view('services');
})->name('services');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Standalone Facebook Lead Form Routes
Route::get('/facebook-lead-form', [FacebookLeadFormController::class, 'showForm'])->name('facebook.lead.form');
Route::post('/facebook-lead-form/submit', [FacebookLeadFormController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('facebook.lead.store');
Route::post('/facebook-lead-form/validate-field', [FacebookLeadFormController::class, 'validateField'])->name('facebook.lead.validate'); // Route for single field validation
Route::get('/facebook-lead-form/confirmation', [FacebookLeadFormController::class, 'showConfirmation'])->name('facebook.confirmation');

// Field validation routes - No throttling for real-time validation
Route::post('/contact-support/validate', [ContactSupportController::class, 'validateField'])
    ->name('contact-support.validate');
Route::post('/facebook-lead-form/validate-field', [FacebookLeadFormController::class, 'validateField'])
    ->name('facebook.lead.validate');

// Public API routes
Route::middleware(['throttle:api'])->group(function () {
    Route::get('/blog/search', [PostController::class, 'search'])->name('blog.search');
    Route::get('/feed', [FeedController::class, 'rss'])->name('feeds.rss');
});
