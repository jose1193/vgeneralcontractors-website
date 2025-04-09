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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact-support', function () {
    return view('contact-support');
})->name('contact-support');

Route::get('/contact-form', App\Livewire\ContactSupport::class)->name('contact-form');

// Google Authentication Routes
Route::get('/google-auth/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('/google-auth/callback', function () {
    try {
        $googleUser = Socialite::driver('google')->user();
        
        // Generate a username from the name
        $randomNumber = rand(100, 999);
        $nameWithoutSpaces = strtolower(str_replace(' ', '', $googleUser->name));
        $username = $nameWithoutSpaces . $randomNumber;
        
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
            return redirect('/dashboard');
        } else {
            // Create a new user
            $user = User::create([
                'uuid' => Str::uuid()->toString(),
                'name' => $googleUser->name,
                'username' => $username,
                'email' => $googleUser->email,
                'email_verified_at' => now(),
                'password' => bcrypt(Str::random(16)),
                'terms_and_conditions' => true,
            ]);
            
            // Assign default role if you're using Spatie Permission
            if (class_exists('\Spatie\Permission\Models\Role')) {
                $defaultRole = \Spatie\Permission\Models\Role::where('name', 'User')->first();
                if ($defaultRole) {
                    $user->assignRole($defaultRole);
                }
            }
            
            // Log in the newly created user
            Auth::login($user);
            return redirect('/dashboard');
        }
    } catch (\Exception $e) {
        return redirect('/')->with('error', 'Error al autenticar con Google: ' . $e->getMessage());
    }
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
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
Route::post('/facebook-lead-form/submit', [FacebookLeadFormController::class, 'store'])->name('facebook.lead.store');
Route::post('/facebook-lead-form/validate-field', [FacebookLeadFormController::class, 'validateField'])->name('facebook.lead.validate'); // Route for single field validation
