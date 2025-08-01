<?php
namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheTrait
{
    /**
     * Generate a cache key based on model and query parameters.
     *
     * @param string $modelName Name of the model
     * @param int|null $page Current page number
     * @param bool|null $includeTrashed Whether to include soft-deleted records
     * @return string
     */
    protected function generateCacheKey($modelName, $page = null, $includeTrashed = null)
    {
        $page = $page ?? request()->query('page', 1);
        $includeTrashed = $includeTrashed ?? $this->showDeleted;

        return strtolower($modelName) . '_' .
               $this->search . '_' .
               $this->sortField . '_' .
               $this->sortDirection . '_' .
               $this->perPage . '_' .
               $page . '_' .
               ($includeTrashed ? 'with_deleted' : 'active');
    }

    /**
     * Clear cache for the specified model.
     *
     * @param string $modelName Name of the model
     */
    protected function clearCache($modelName)
    {
        // Siempre considerar como cambio significativo para limpiar todas las páginas
        $this->significantDataChange = true;
        
        $currentPage = request()->query('page', 1);

        // Siempre limpiar la página actual y las páginas adyacentes
        Cache::forget($this->generateCacheKey($modelName, $currentPage));
        Cache::forget(strtolower($modelName) . '_count_' . $this->search);
        Cache::forget($this->generateCacheKey($modelName, $currentPage - 1));
        Cache::forget($this->generateCacheKey($modelName, $currentPage + 1));

        // Para cambios significativos, limpiar más páginas
        if ($this->significantDataChange) {
            // Limpiar las primeras 10 páginas para asegurar que los cambios sean visibles
            for ($i = 1; $i <= 10; $i++) {
                Cache::forget($this->generateCacheKey($modelName, $i));
                Cache::forget($this->generateCacheKey($modelName, $i, true));
            }
            
            // También limpiar cualquier cache con filtros comunes
            $searchTerms = ['', 'New', 'Called', 'Pending', 'Declined'];
            foreach ($searchTerms as $term) {
                $this->search = $term;
                for ($i = 1; $i <= 3; $i++) {
                    Cache::forget($this->generateCacheKey($modelName, $i));
                    Cache::forget($this->generateCacheKey($modelName, $i, true));
                }
            }
            
            // Resetear la búsqueda
            $this->search = request()->input('search', '');
            
            // Limpiar el caché de cuentas
            Cache::forget(strtolower($modelName) . '_count');
        }
    }
}