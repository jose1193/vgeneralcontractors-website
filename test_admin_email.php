<?php

// Test the Admin Rejection Notification directly

use App\Models\Appointment;
use App\Models\CompanyData;
use App\Models\EmailData;
use App\Notifications\AdminRejectionNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "======= TESTING ADMIN REJECTION EMAIL =======\n\n";

// Get a sample appointment
$appointment = Appointment::first();

if (!$appointment) {
    echo "❌ No appointments found in the database.\n";
    exit(1);
}

// Get company data
$companyData = CompanyData::first();

if (!$companyData) {
    echo "❌ No company data found in the database.\n";
    exit(1);
}

// Get admin email
$adminEmail = EmailData::where('type', 'Admin')->first();

if (!$adminEmail) {
    echo "❌ No admin email found in the database.\n";
    
    // Try case-insensitive search
    $adminEmail = EmailData::whereRaw('LOWER(type) = ?', [strtolower('Admin')])->first();
    
    if (!$adminEmail) {
        echo "❌ No admin email found even with case-insensitive search.\n";
        exit(1);
    }
    
    echo "✅ Admin email found with case-insensitive search: {$adminEmail->email}\n";
} else {
    echo "✅ Admin email found: {$adminEmail->email}\n";
}

// Validate email
$isValid = filter_var($adminEmail->email, FILTER_VALIDATE_EMAIL);
echo "Admin email validation: " . ($isValid ? "✅ Valid" : "❌ Invalid") . "\n";

if (!$isValid) {
    echo "❌ Admin email is invalid: {$adminEmail->email}\n";
    exit(1);
}

echo "\nSending test admin rejection notification to: {$adminEmail->email}\n";

try {
    // Try both templates
    echo "Testing English template...\n";
    app()->setLocale('en');
    
    // Send notification directly to admin email
    Notification::route('mail', $adminEmail->email)
        ->notify(new AdminRejectionNotification(
            $appointment,
            true, // noContact
            false, // noInsurance
            "Test admin rejection notification", // otherReason
            $companyData
        ));
    
    echo "✅ Test email sent to admin using English template!\n";
    
    // Wait a bit to avoid mail server throttling
    sleep(2);
    
    echo "Testing Spanish template...\n";
    app()->setLocale('es');
    
    // Send notification directly to admin email
    Notification::route('mail', $adminEmail->email)
        ->notify(new AdminRejectionNotification(
            $appointment,
            false, // noContact
            true, // noInsurance
            "Prueba de notificación de rechazo para administrador", // otherReason
            $companyData
        ));
    
    echo "✅ Test email sent to admin using Spanish template!\n";
    
    echo "\n✅ Test completed successfully!\n";
    
} catch (\Exception $e) {
    echo "❌ Error sending test email: " . $e->getMessage() . "\n";
    echo "Exception type: " . get_class($e) . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n======= TEST COMPLETED =======\n";
