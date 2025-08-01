<?php

use Illuminate\Support\Facades\Route;
use App\Exports\PDF\InsuranceCompanyExportPDF;
use App\Exports\PDF\GenericTableExportPDF;
use App\Models\InsuranceCompany;

/*
|--------------------------------------------------------------------------
| Test PDF Routes
|--------------------------------------------------------------------------
| Rutas temporales para probar el nuevo sistema de PDF exports
*/

Route::get('/test-pdf/insurance-companies', function () {
    try {
        // Obtener algunas insurance companies para prueba
        $companies = InsuranceCompany::with('user')->take(10)->get();
        
        // Usar el nuevo sistema de export
        $export = new InsuranceCompanyExportPDF($companies);
        
        return $export->stream('test-insurance-companies.pdf');
        
    } catch (Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
    }
})->name('test.pdf.insurance');

Route::get('/test-pdf/generic', function () {
    try {
        // Crear datos de prueba
        $testData = collect([
            [
                'number' => 1,
                'name' => 'Test Company 1',
                'email' => 'test1@example.com',
                'phone' => '(713) 123-4567',
                'status' => 'active',
                'created_date' => '2025-01-01'
            ],
            [
                'number' => 2,
                'name' => 'Test Company 2',
                'email' => 'test2@example.com',
                'phone' => '(713) 234-5678',
                'status' => 'inactive',
                'created_date' => '2025-01-02'
            ],
            [
                'number' => 3,
                'name' => 'Test Company 3',
                'email' => 'test3@example.com',
                'phone' => '(713) 345-6789',
                'status' => 'active',
                'created_date' => '2025-01-03'
            ]
        ]);
        
        // Headers para el test
        $headers = [
            '#' => ['width' => '5%', 'align' => 'center'],
            'Name' => ['width' => '30%', 'align' => 'left'],
            'Email' => ['width' => '25%', 'align' => 'left'],
            'Phone' => ['width' => '15%', 'align' => 'center'],
            'Status' => ['width' => '10%', 'align' => 'center'],
            'Created' => ['width' => '15%', 'align' => 'center']
        ];
        
        // Usar el sistema genérico
        $pdf = GenericTableExportPDF::create(
            $testData,
            'Generic Test Report',
            $headers,
            ['orientation' => 'portrait']
        );
        
        return $pdf->stream('test-generic-report.pdf');
        
    } catch (Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
    }
})->name('test.pdf.generic');
