<?php

namespace App\Http\Controllers;

use App\Models\CompanyData;
use App\Models\EmailData;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

class CompanyDataController extends Controller
{
    public function __construct()
    {
        // Verificar si las tablas existen antes de intentar acceder a ellas
        if (Schema::hasTable('company_data') && Schema::hasTable('email_data')) {
            try {
                // Obtener los datos de la compañía
                $companyData = CompanyData::first();
                $emailData = EmailData::all();

                // Compartir los datos con todas las vistas
                View::share('companyData', $companyData);
                View::share('emailData', $emailData);
            } catch (\Exception $e) {
                \Log::error('Error loading company data: ' . $e->getMessage());
                View::share('companyData', null);
                View::share('emailData', collect([]));
            }
        } else {
            // Si las tablas no existen, compartir valores nulos
            View::share('companyData', null);
            View::share('emailData', collect([]));
        }
    }
} 