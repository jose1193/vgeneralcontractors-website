<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CompanyCache
{
    /**
     * Generate a cache key for company data
     * 
     * @return string
     */
    protected function generateCompanyCacheKey()
    {
        return 'companies_' . $this->search . '_' . $this->sortField . '_' . $this->sortDirection . '_' . $this->perPage;
    }
    
    /**
     * Clear company data cache
     */
    protected function clearCompanyCache()
    {
        $cacheKey = $this->generateCompanyCacheKey();
        Cache::forget($cacheKey);
    }
}
