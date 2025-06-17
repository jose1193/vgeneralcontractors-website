<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Traits\CacheTraitCrud;
use Illuminate\Support\Facades\Validator;
use Throwable;

class PortfolioCrudController extends BaseCrudController
{
    use CacheTraitCrud;

    // Constructor: inicializar propiedades necesarias
    public function __construct()
    {
        parent::__construct();
        $this->modelClass = Portfolio::class;
        $this->entityName = 'PORTFOLIO';
        $this->viewPrefix = 'portfolios-crud';
        $this->routePrefix = 'portfolios-crud';
    }

    /**
     * Display a listing of the portfolios
     */
    public function index(Request $request)
    {
        // ...
    }

    /**
     * Show the form for creating a new portfolio
     */
    public function create()
    {
        // ...
    }

    /**
     * Store a newly created portfolio
     */
    public function store(Request $request)
    {
        // ...
    }

    /**
     * Display the specified portfolio
     */
    public function show($uuid)
    {
        // ...
    }

    /**
     * Show the form for editing the specified portfolio
     */
    public function edit($uuid)
    {
        // ...
    }

    /**
     * Update the specified portfolio
     */
    public function update(Request $request, $uuid)
    {
        // ...
    }

    /**
     * Remove the specified portfolio (soft delete)
     */
    public function destroy($uuid)
    {
        // ...
    }

    /**
     * Restore a soft-deleted portfolio
     */
    public function restore($uuid)
    {
        // ...
    }

    /**
     * Check if a title already exists for real-time validation
     */
    public function checkTitleExists(Request $request)
    {
        // ...
    }
} 