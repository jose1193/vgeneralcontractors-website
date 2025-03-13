<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait EmailCache
{
    /**
     * Generate a cache key for email data queries
     *
     * @return string
     */
    public function generateEmailCacheKey()
    {
        return 'email_datas_' . $this->search . '_' . 
               $this->sortField . '_' . 
               $this->sortDirection . '_' . 
               $this->perPage . '_' . 
               $this->page . '_' . 
               ($this->showDeleted ? 'with_deleted' : 'active');
    }

    /**
     * Clear email data cache
     *
     * @return void
     */
    public function clearEmailCache()
    {
        // Get the current page
        $currentPage = request()->query('page', 1);
        
        // Clear cache for the current page
        $cacheKey = $this->generateEmailCacheKey();
        Cache::forget($cacheKey);
        
        // Clear count cache
        Cache::forget('email_datas_count_' . $this->search);
        
        // Clear cache for adjacent pages to ensure proper pagination updates
        Cache::forget('email_datas_' . $this->search . '_' . 
                      $this->sortField . '_' . 
                      $this->sortDirection . '_' . 
                      $this->perPage . '_' . 
                      ($currentPage - 1) . '_' . 
                      ($this->showDeleted ? 'with_deleted' : 'active'));
                      
        Cache::forget('email_datas_' . $this->search . '_' . 
                      $this->sortField . '_' . 
                      $this->sortDirection . '_' . 
                      $this->perPage . '_' . 
                      ($currentPage + 1) . '_' . 
                      ($this->showDeleted ? 'with_deleted' : 'active'));
    }
} 