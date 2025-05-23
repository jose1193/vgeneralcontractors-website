<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Post;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Traits\CacheTrait;
use App\Traits\ChecksPermissions;
use App\Services\PostImageService;

class Posts extends Component
{
    use WithPagination;
    use WithFileUploads;
    use CacheTrait;
    use ChecksPermissions;

    // Basic properties
    public $uuid;
    public $post_title;
    public $post_content;
    public $post_image;
    public $temp_image;
    public $meta_description;
    public $meta_title;
    public $meta_keywords;
    public $category_id;
    public $scheduled_at;
    public $post_status;
    
    // Component control properties
    public $isOpen = false;
    public $modalTitle = 'Create Post';
    public $modalAction = 'store';
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $showDeleted = false;
    public $categories = [];

    // Añadir esta propiedad
    protected $significantDataChange = false;

    protected $listeners = [
        'delete',
        'restore', 
        'closeModal', 
        'refreshComponent' => '$refresh',
        'postDeleteError',
        'postRestoreError'
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
        'showDeleted' => ['except' => false]
    ];

    protected $rules = [
        'post_title' => 'required|string|min:3|max:255',
        'post_content' => 'required|string',
        'temp_image' => 'nullable|image|max:1024',
        'meta_description' => 'nullable|string|max:255',
        'meta_title' => 'nullable|string|max:100',
        'meta_keywords' => 'nullable|string|max:255',
        'category_id' => 'required|exists:blog_categories,id',
        'scheduled_at' => 'nullable|date|after_or_equal:now',
    ];

    protected $postImageService;

    public function boot(PostImageService $postImageService)
    {
        $this->postImageService = $postImageService;
    }

    public function mount()
    {
        $this->resetPage();
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = BlogCategory::orderBy('blog_category_name')->get();
    }

    public function render()
    {
        if (!$this->checkPermission('READ_POST', true)) {
            return;
        }
        
        $searchTerm = '%' . $this->search . '%';
        
        // Use CacheTrait's generic method
        $cacheKey = $this->generateCacheKey('posts');
        
        $posts = Cache::remember($cacheKey, 300, function () use ($searchTerm) {
            return $this->getPostsQuery($searchTerm)->paginate($this->perPage);
        });

        return view('livewire.posts', [
            'posts' => $posts
        ]);
    }

    protected function getPostsQuery($searchTerm)
    {
        $query = Post::with(['category', 'user']);
        
        if ($this->showDeleted) {
            $query->withTrashed();
        }
        
        $query->where(function ($query) use ($searchTerm) {
            $query->where('post_title', 'like', $searchTerm)
                  ->orWhere('post_content', 'like', $searchTerm)
                  ->orWhere('meta_description', 'like', $searchTerm);
        })
        ->orderBy($this->sortField, $this->sortDirection);
        
        return $query;
    }

    public function sort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function create()
    {
        if (!$this->checkPermissionWithMessage('CREATE_POST', 'No tienes permiso para crear publicaciones')) {
            return;
        }
        
        // Make sure all fields are clean
        $this->resetInputFields();
        
        // Set the title and action of the modal
        $this->modalTitle = 'Create New Post';
        $this->modalAction = 'store';
        
        // Open the modal
        $this->isOpen = true;
        
        // Emit event to update Alpine.js
        $this->dispatch('post-edit', [
            'post_title' => '',
            'post_content' => '',
            'meta_description' => '',
            'meta_title' => '',
            'meta_keywords' => '',
            'category_id' => '',
            'scheduled_at' => '',
            'action' => 'store'
        ]);
    }

    public function store()
    {
        try {
            if (!$this->checkPermissionWithMessage('CREATE_POST', 'No tienes permiso para crear publicaciones')) {
                return;
            }

            \Log::info('Content before validation', [
                'content' => $this->post_content,
                'content_length' => strlen($this->post_content ?? ''),
                'is_null' => is_null($this->post_content),
                'is_empty' => empty($this->post_content)
            ]);
            
            $this->validate();

            // Asegurarnos de que el contenido se procese correctamente antes de guardar
            $formattedContent = $this->formatPostContentForSaving($this->post_content);
            if (is_null($formattedContent)) {
                $this->addError('post_content', 'The post content field cannot be empty.');
                \Log::error('Content is empty after formatting');
                return;
            }

            // Generate slug from title
            $slug = Str::slug($this->post_title);
            
            // Check if slug exists, if so, append a random string
            if (Post::where('post_title_slug', $slug)->exists()) {
                $slug = $slug . '-' . Str::random(5);
            }

            // Handle image upload with S3
            $imageUrl = null;
            if ($this->temp_image) {
                $imageUrl = $this->postImageService->storePostImage($this->temp_image);
            }

            // Determine post status based on scheduled_at
            $post_status = 'published';
            if ($this->scheduled_at) {
                $post_status = 'scheduled';
            }

            \Log::info('Storing post with data:', [
                'post_title' => $this->post_title,
                'category_id' => $this->category_id,
                'image_url' => $imageUrl,
                'content_length' => strlen($formattedContent),
                'post_status' => $post_status,
                'scheduled_at' => $this->scheduled_at
            ]);

            Post::create([
                'uuid' => Str::uuid(),
                'post_title' => $this->post_title,
                'post_content' => $formattedContent,
                'post_image' => $imageUrl,
                'meta_description' => $this->meta_description,
                'meta_title' => $this->meta_title ?? $this->post_title,
                'meta_keywords' => $this->meta_keywords,
                'post_title_slug' => $slug,
                'category_id' => $this->category_id,
                'user_id' => auth()->id(),
                'post_status' => $post_status,
                'scheduled_at' => $this->scheduled_at
            ]);
            
            // Clear cache using the generic method
            $this->clearCache('posts');

            session()->flash('message', 'Post Created Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
            $this->dispatch('post-created-success');
        } catch (\Exception $e) {
            \Log::error('Error creating post', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error creating post: ' . $e->getMessage());
            $this->dispatch('post-created-error');
        }
    }

    public function edit($uuid)
    {
        try {
            if (!$this->checkPermissionWithMessage('UPDATE_POST', 'No tienes permiso para editar publicaciones')) {
                return;
            }
            
            \Log::info('Attempting to edit post', ['uuid' => $uuid]);
            
            $post = Post::where('uuid', $uuid)->firstOrFail();
            $this->uuid = $post->uuid;
            $this->post_title = $post->post_title;
            $this->post_content = $post->post_content;
            $this->post_image = $post->post_image;
            $this->meta_description = $post->meta_description;
            $this->meta_title = $post->meta_title;
            $this->meta_keywords = $post->meta_keywords;
            $this->category_id = $post->category_id;
            $this->scheduled_at = $post->scheduled_at;
            $this->post_status = $post->post_status;
            
            $this->modalTitle = 'Edit Post';
            $this->modalAction = 'update';
            $this->openModal();
            
            // Dispatch event with post data
            $this->dispatch('post-edit', [
                'post_title' => $this->post_title,
                'post_content' => $this->post_content,
                'meta_description' => $this->meta_description,
                'meta_title' => $this->meta_title,
                'meta_keywords' => $this->meta_keywords,
                'category_id' => $this->category_id,
                'scheduled_at' => $this->scheduled_at,
                'action' => 'update'
            ]);
            
            \Log::info('Post data loaded successfully', [
                'uuid' => $this->uuid,
                'post_title' => $this->post_title,
                'post_status' => $this->post_status,
                'scheduled_at' => $this->scheduled_at
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading post data', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error loading post data: ' . $e->getMessage());
            $this->dispatch('post-edit-error');
        }
    }

    public function update()
    {
        try {
            if (!$this->checkPermissionWithMessage('UPDATE_POST', 'No tienes permiso para actualizar publicaciones')) {
                return;
            }

            $this->validate();

            $post = Post::where('uuid', $this->uuid)->firstOrFail();
            
            // Generate slug if title changed
            $slug = $post->post_title_slug;
            if ($post->post_title !== $this->post_title) {
                $slug = Str::slug($this->post_title);
                if (Post::where('post_title_slug', $slug)->where('uuid', '!=', $this->uuid)->exists()) {
                    $slug = $slug . '-' . Str::random(5);
                }
            }
            
            // Handle image upload with S3
            $imageUrl = $post->post_image;
            if ($this->temp_image) {
                // Delete old image if exists
                if ($post->post_image) {
                    $this->postImageService->deletePostImage($post->post_image);
                }
                
                // Store new image
                $imageUrl = $this->postImageService->storePostImage($this->temp_image);
            }
            
            // Determine post status based on scheduled_at
            $post_status = 'published';
            if ($this->scheduled_at) {
                $post_status = 'scheduled';
            }

            $post->update([
                'post_title' => $this->post_title,
                'post_content' => $this->formatPostContentForSaving($this->post_content),
                'post_image' => $imageUrl,
                'meta_description' => $this->meta_description,
                'meta_title' => $this->meta_title ?? $this->post_title,
                'meta_keywords' => $this->meta_keywords,
                'post_title_slug' => $slug,
                'category_id' => $this->category_id,
                'user_id' => auth()->id(),
                'post_status' => $post_status,
                'scheduled_at' => $this->scheduled_at
            ]);

            // Clear cache
            $this->clearCache('posts');

            session()->flash('message', 'Post Updated Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
            $this->dispatch('post-updated-success');
        } catch (\Exception $e) {
            \Log::error('Error updating post', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error updating post: ' . $e->getMessage());
            $this->dispatch('post-updated-error');
        }
    }

    public function delete($uuid)
    {
        try {
            if (!$this->checkPermissionWithMessage('DELETE_POST', 'No tienes permiso para eliminar publicaciones')) {
                return false;
            }
            
            \Log::info('Attempting to delete post', ['uuid' => $uuid]);
            
            $post = Post::where('uuid', $uuid)->first();
            
            if (!$post) {
                \Log::warning('Post not found for deletion', ['uuid' => $uuid]);
                session()->flash('error', 'Post not found.');
                $this->dispatch('postDeleteError', ['message' => 'Post not found.']);
                return false;
            }
            
            // Delete post image if exists
            if ($post->post_image) {
                $this->postImageService->deletePostImage($post->post_image);
            }
            
            $deleted = $post->delete();
            
            \Log::info('Post deletion result', [
                'uuid' => $uuid,
                'deleted' => $deleted ? 'success' : 'failed'
            ]);
            
            $this->clearCache('posts');
            
            session()->flash('message', 'Post deleted successfully.');
            $this->dispatch('postDeleted');
            return true;
        } catch (\Exception $e) {
            \Log::error('Error deleting post', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error deleting post: ' . $e->getMessage());
            $this->dispatch('postDeleteError', ['message' => $e->getMessage()]);
            return false;
        }
    }

    public function restore($uuid)
    {
        try {
            if (!$this->checkPermissionWithMessage('RESTORE_POST', 'No tienes permiso para restaurar publicaciones')) {
                return false;
            }
            
            \Log::info('Attempting to restore post', ['uuid' => $uuid]);
            
            $post = Post::withTrashed()->where('uuid', $uuid)->first();
            
            if (!$post) {
                \Log::warning('Post not found for restoration', ['uuid' => $uuid]);
                session()->flash('error', 'Post not found.');
                $this->dispatch('postRestoreError', ['message' => 'Post not found.']);
                return false;
            }
            
            $restored = $post->restore();
            
            \Log::info('Post restoration result', [
                'uuid' => $uuid,
                'restored' => $restored ? 'success' : 'failed'
            ]);
            
            $this->clearCache('posts');
            
            session()->flash('message', 'Post restored successfully.');
            $this->dispatch('postRestored');
            return true;
        } catch (\Exception $e) {
            \Log::error('Error restoring post', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error restoring post: ' . $e->getMessage());
            $this->dispatch('postRestoreError', ['message' => $e->getMessage()]);
            return false;
        }
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->dispatch('post-edit', [
            'post_title' => $this->post_title,
            'post_content' => $this->post_content,
            'meta_description' => $this->meta_description,
            'meta_title' => $this->meta_title,
            'meta_keywords' => $this->meta_keywords,
            'category_id' => $this->category_id,
            'action' => $this->modalAction
        ]);
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
        $this->resetValidation();
        $this->dispatch('post-edit');
    }

    private function resetInputFields()
    {
        $this->reset([
            'uuid',
            'post_title',
            'post_content',
            'temp_image',
            'meta_description',
            'meta_title',
            'meta_keywords',
            'category_id',
            'scheduled_at'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updating($name, $value)
    {
        if ($name === 'search') {
            $this->resetPage();
        }
    }

    public function toggleShowDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
        $this->resetPage();
        $this->clearCache('posts');
    }

    protected function formatPostContentForSaving($content)
    {
        // Permitir etiquetas HTML seguras
        $allowedTags = '<p><br><strong><b><em><i><u><ul><ol><li><a><h1><h2><h3><h4><h5><h6><blockquote><table><thead><tbody><tr><td><th><img><div><span>';
        
        // Limpiar el contenido pero conservar las etiquetas permitidas
        $content = strip_tags($content, $allowedTags);
        
        // Asegurarse de que no esté vacío después de limpiar
        if (empty(trim(strip_tags($content)))) {
            return null; // Esto disparará el error de validación
        }
        
        return $content;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (empty(trim(strip_tags($this->post_content)))) {
                $validator->errors()->add('post_content', 'The post content field cannot contain only HTML tags.');
            }
        });
    }
}
