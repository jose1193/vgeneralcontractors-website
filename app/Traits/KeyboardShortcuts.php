<?php

namespace App\Traits;

trait KeyboardShortcuts
{
    /**
     * Mount keyboard shortcuts for user management
     */
    public function mountKeyboardShortcuts()
    {
        $this->dispatch('register-shortcuts', [
            'n' => [
                'callback' => 'createNew',
                'description' => 'Create new user'
            ],
            'f' => [
                'callback' => 'focusSearch',
                'description' => 'Focus search box'
            ],
            'Escape' => [
                'callback' => 'escapeAction',
                'description' => 'Close modal or clear search'
            ],
            'Delete' => [
                'callback' => 'confirmDelete',
                'description' => 'Delete selected user'
            ],
            'r' => [
                'callback' => 'toggleShowDeleted',
                'description' => 'Toggle show deleted users'
            ]
        ]);
    }
    
    /**
     * Create new user shortcut handler
     */
    public function createNew()
    {
        if (!$this->isOpen) {
            $this->create();
        }
    }
    
    /**
     * Focus search shortcut handler
     */
    public function focusSearch()
    {
        if (!$this->isOpen) {
            $this->dispatch('focus-search');
        }
    }
    
    /**
     * Escape action shortcut handler
     */
    public function escapeAction()
    {
        if ($this->isOpen) {
            $this->closeModal();
        } else if ($this->search) {
            $this->search = '';
            $this->resetPage();
        }
    }
} 