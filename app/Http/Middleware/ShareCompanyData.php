<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CompanyData;
use App\Models\EmailData;
use Illuminate\Support\Facades\View;

class ShareCompanyData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Obtener los datos de la compañía
            $companyData = CompanyData::first();
            
            // Si no hay datos de la compañía, crear un registro por defecto
            if (!$companyData) {
                $companyData = CompanyData::create([
                    'uuid' => \Illuminate\Support\Str::uuid(),
                    'name' => 'V General Contractors',
                    'company_name' => 'V General Contractors',
                    'email' => 'info@vgeneralcontractors.com',
                    'phone' => '+13466920767',
                    'address' => '1302 Waugh Dr # 810, Houston, TX 77019',
                    'website' => 'https://vgeneralcontractors.com',
                    'user_id' => 1, // Default to first user or null
                ]);
                
                \Log::info('ShareCompanyData: Created default company data', [
                    'company_data_id' => $companyData->id
                ]);
            }
            
            $emailData = EmailData::all();

            // Compartir los datos con todas las vistas
            View::share('companyData', $companyData);
            View::share('emailData', $emailData);
            
        } catch (\Exception $e) {
            \Log::error('ShareCompanyData middleware error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_url' => $request->url()
            ]);
            
            // Crear un objeto por defecto para evitar errores en las vistas
            $defaultCompanyData = (object) [
                'name' => 'V General Contractors',
                'company_name' => 'V General Contractors',
                'email' => 'info@vgeneralcontractors.com',
                'phone' => '+13466920757',
                'address' => '1302 Waugh Dr # 810, Houston, TX 77019',
                'website' => 'https://vgeneralcontractors.com',
            ];
            
            View::share('companyData', $defaultCompanyData);
            View::share('emailData', collect([]));
        }

        return $next($request);
    }
} 