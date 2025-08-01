<?php

// Check if admin email is correctly set up in the database

use App\Models\EmailData;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "======= VERIFICANDO EMAILS DE ADMINISTRADOR =======\n\n";

// Check database connection
try {
    DB::connection()->getPdo();
    echo "✅ Conexión a la base de datos exitosa.\n";
} catch (\Exception $e) {
    echo "❌ Error de conexión a la base de datos: " . $e->getMessage() . "\n";
    exit(1);
}

// Check if EmailData table exists
if (!Schema::hasTable('email_data')) {
    echo "❌ La tabla 'email_data' no existe en la base de datos.\n";
    exit(1);
}

// Get all emails
$allEmails = EmailData::all();
echo "Emails encontrados en la base de datos: " . $allEmails->count() . "\n\n";

foreach ($allEmails as $email) {
    $isValid = filter_var($email->email, FILTER_VALIDATE_EMAIL);
    $validString = $isValid ? '✅ Válido' : '❌ Inválido';
    
    echo "ID: {$email->id}\n";
    echo "UUID: {$email->uuid}\n";
    echo "Tipo: {$email->type}\n";
    echo "Email: {$email->email} - {$validString}\n";
    echo "Descripción: {$email->description}\n";
    echo "Teléfono: {$email->phone}\n";
    echo "Usuario ID: {$email->user_id}\n";
    echo "Creado: {$email->created_at}\n";
    echo "Actualizado: {$email->updated_at}\n";
    echo "------------------------\n";
}

// Specifically check for admin email
$adminEmail = EmailData::where('type', 'Admin')->first();

if ($adminEmail) {
    $isValid = filter_var($adminEmail->email, FILTER_VALIDATE_EMAIL);
    echo "\n✅ Email de administrador encontrado:\n";
    echo "Email: {$adminEmail->email}\n";
    echo "Validación: " . ($isValid ? "✅ Válido" : "❌ Inválido") . "\n";
} else {
    echo "\n❌ No se encontró email de administrador (tipo 'Admin').\n";
    
    // Try case-insensitive search
    $adminEmail = EmailData::whereRaw('LOWER(type) = ?', [strtolower('Admin')])->first();
    
    if ($adminEmail) {
        $isValid = filter_var($adminEmail->email, FILTER_VALIDATE_EMAIL);
        echo "\n✅ Email de administrador encontrado (búsqueda insensible a mayúsculas/minúsculas):\n";
        echo "Tipo exacto: {$adminEmail->type}\n";
        echo "Email: {$adminEmail->email}\n";
        echo "Validación: " . ($isValid ? "✅ Válido" : "❌ Inválido") . "\n";
    } else {
        echo "\n❌ No se encontró email de administrador incluso con búsqueda insensible a mayúsculas/minúsculas.\n";
    }
}

echo "\n======= VERIFICACIÓN COMPLETA =======\n";
