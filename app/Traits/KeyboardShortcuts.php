<?php

namespace App\Traits;

trait KeyboardShortcuts
{
    /**
     * Setup keyboard shortcuts
     *
     * @return void
     */
    public function mountKeyboardShortcuts()
    {
        $this->dispatch('setup-shortcuts', [
            'shortcuts' => [
                'n' => 'create',
                'f' => 'focusSearch',
                'r' => 'toggleShowDeleted'
            ]
        ]);
    }

    /**
     * Focus the search input
     *
     * @return void
     */
    public function focusSearch()
    {
        $this->dispatch('focus-search');
    }
} 