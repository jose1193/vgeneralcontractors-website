<?php

namespace App\Http\Controllers;

use App\Models\CompanyData;
use App\Models\EmailData;
use Illuminate\Support\Facades\View;

class CompanyDataController extends Controller
{
    public function __construct()
    {
        // Obtener los datos de la compañía
        $companyData = CompanyData::first();
        $emailData = EmailData::all();

        // Compartir los datos con todas las vistas
        View::share('companyData', $companyData);
        View::share('emailData', $emailData);
    }
} 