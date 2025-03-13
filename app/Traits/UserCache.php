<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait UserCache
{
    /**
     * Generate a cache key for user queries
     * 
     * @param int $page Current page number
     * @param bool $includeTrashed Whether to include soft-deleted records
     * @return string
     */
    protected function generateUserCacheKey($page = null, $includeTrashed = null)
    {
        $page = $page ?? request()->query('page', 1);
        $includeTrashed = $includeTrashed ?? $this->showDeleted;
        
        return 'users_' . 
               $this->search . '_' . 
               $this->sortField . '_' . 
               $this->sortDirection . '_' . 
               $this->perPage . '_' . 
               $page . '_' . 
               ($includeTrashed ? 'with_deleted' : 'active');
    }
    
    /**
     * Clear user-related cache
     */
    protected function clearUserCache()
    {
        $currentPage = request()->query('page', 1);
        
        // Clear cache for current page
        Cache::forget($this->generateUserCacheKey($currentPage));
        
        // Clear count cache
        Cache::forget('users_count_' . $this->search);
        
        // Clear cache for adjacent pages
        Cache::forget($this->generateUserCacheKey($currentPage - 1));
        Cache::forget($this->generateUserCacheKey($currentPage + 1));
        
        // Clear all cache if significant change
        if ($this->significantDataChange) {
            for ($i = 1; $i <= 5; $i++) {
                Cache::forget($this->generateUserCacheKey($i));
                Cache::forget($this->generateUserCacheKey($i, true));
            }
        }
    }
} 