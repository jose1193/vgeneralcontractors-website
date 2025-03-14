<?php

namespace App\Traits;

trait EmailCache
{
    protected function generateEmailCacheKey()
    {
        return 'email_data_' . md5(json_encode([
            'search' => $this->search,
            'perPage' => $this->perPage,
            'sortField' => $this->sortField,
            'sortDirection' => $this->sortDirection,
            'showDeleted' => $this->showDeleted,
            'page' => $this->page,
        ]));
    }

    protected function clearEmailCache()
    {
        Cache::forget($this->generateEmailCacheKey());
    }
}