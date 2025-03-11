<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Portfolio;
use App\Models\ServiceCategory;
use App\Models\ProjectType;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Portfolios extends Component
{
    use WithFileUploads, WithPagination;

    protected $paginationTheme = 'tailwind';

    public $title;
    public $description;
    public $images = [];
    public $service_category_id;
    public $project_type_id;
    public $status = 'active';
    public $editingId;
    public $isEditing = false;
    public $search = '';
    public $tempImages = [];
    public $selectedCategory = '';
    public $selectedProjectType = '';
    public $showModal = false;
    public $portfolio;
    public $portfolioId;
    public $imageOrder = [];
    public $maxTotalSize = 20480; // 20MB in KB
    public $maxFiles = 10; // Maximum number of files allowed

    protected $rules = [
        'title' => 'required|min:3',
        'description' => 'required|min:10',
        'images.*' => 'image|max:20480',
        'service_category_id' => 'required|exists:service_categories,id',
        'project_type_id' => 'required|exists:project_types,id',
        'status' => 'required|in:active,inactive',
        'tempImages.*' => 'image|max:20480'
    ];

    protected $listeners = [
        'delete' => 'delete',
        'confirmDelete' => 'confirmDelete',
        'imageReordered' => 'handleImageReorder'
    ];

    public function mount()
    {
        $this->resetValidation();
        $this->resetExcept('search', 'selectedCategory', 'selectedProjectType');
        $this->portfolio = null;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if (str_contains($propertyName, 'images') || str_contains($propertyName, 'tempImages')) {
            $files = str_contains($propertyName, 'images') ? $this->images : $this->tempImages;
            
            // Validar número máximo de archivos
            if (count($files) > $this->maxFiles) {
                $this->addError($propertyName, "Maximum {$this->maxFiles} files allowed.");
                $this->reset($propertyName);
                return;
            }

            // Validar tamaño total
            $totalSize = 0;
            foreach ($files as $file) {
                $totalSize += $file->getSize() / 1024; // Convertir a KB
            }

            if ($totalSize > $this->maxTotalSize) {
                $this->addError($propertyName, "Total size cannot exceed 20MB.");
                $this->reset($propertyName);
                return;
            }
        }
    }

    public function render()
    {
        $query = Portfolio::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('service_category_id', $this->selectedCategory);
            })
            ->when($this->selectedProjectType, function ($query) {
                $query->where('project_type_id', $this->selectedProjectType);
            })
            ->latest();

        $portfolios = $query->paginate(10);

        $categories = Cache::remember('service_categories_list', 3600, function () {
            return ServiceCategory::where('status', 'active')->get();
        });

        $projectTypes = Cache::remember('project_types_list', 3600, function () {
            return ProjectType::where('status', 'active')->get();
        });

        return view('livewire.portfolios', [
            'portfolios' => $portfolios,
            'categories' => $categories,
            'projectTypes' => $projectTypes
        ]);
    }

    public function create()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->portfolio = null;
        $this->isEditing = false;
        $this->reset(['title', 'description', 'service_category_id', 'project_type_id', 'status', 'images', 'tempImages']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->portfolio = Portfolio::findOrFail($id);
        $this->portfolioId = $id;
        $this->title = $this->portfolio->title;
        $this->description = $this->portfolio->description;
        $this->service_category_id = $this->portfolio->service_category_id;
        $this->project_type_id = $this->portfolio->project_type_id;
        $this->status = $this->portfolio->status;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        try {
            if (!$this->isEditing) {
                $portfolio = new Portfolio();
                $portfolio->uuid = Str::uuid();
                $portfolio->title = $this->title;
                $portfolio->description = $this->description;
                $portfolio->service_category_id = $this->service_category_id;
                $portfolio->project_type_id = $this->project_type_id;
                $portfolio->status = $this->status;
                $portfolio->user_id = Auth::id();

                if (!empty($this->images)) {
                    // Store the first image as the main image
                    $portfolio->image = ImageHelper::storeAndResize(
                        $this->images[0],
                        'portfolios'
                    );

                    // Store additional images in the images JSON column
                    if (count($this->images) > 1) {
                        $additionalImages = [];
                        foreach (array_slice($this->images, 1) as $image) {
                            $additionalImages[] = ImageHelper::storeAndResize(
                                $image,
                                'portfolios'
                            );
                        }
                        $portfolio->additional_images = $additionalImages;
                    }
                }

                $portfolio->save();

                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'Portfolio created successfully!'
                ]);

                $this->clearCache();
                $this->closeModal();
            } else {
                $portfolio = Portfolio::findOrFail($this->portfolioId);
                $portfolio->title = $this->title;
                $portfolio->description = $this->description;
                $portfolio->service_category_id = $this->service_category_id;
                $portfolio->project_type_id = $this->project_type_id;
                $portfolio->status = $this->status;

                if (!empty($this->tempImages)) {
                    // Delete old images if they exist
                    if ($portfolio->image) {
                        ImageHelper::deleteImage($portfolio->image);
                    }
                    if (!empty($portfolio->additional_images)) {
                        foreach ($portfolio->additional_images as $oldImage) {
                            ImageHelper::deleteImage($oldImage);
                        }
                    }

                    // Store new images
                    $portfolio->image = ImageHelper::storeAndResize(
                        $this->tempImages[0],
                        'portfolios'
                    );

                    // Store additional images
                    if (count($this->tempImages) > 1) {
                        $additionalImages = [];
                        foreach (array_slice($this->tempImages, 1) as $image) {
                            $additionalImages[] = ImageHelper::storeAndResize(
                                $image,
                                'portfolios'
                            );
                        }
                        $portfolio->additional_images = $additionalImages;
                    } else {
                        $portfolio->additional_images = [];
                    }
                }

                $portfolio->save();

                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'Portfolio updated successfully!'
                ]);

                $this->clearCache();
                $this->closeModal();
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $portfolio = Portfolio::findOrFail($id);

            // Delete main image
            if ($portfolio->image) {
                ImageHelper::deleteImage($portfolio->image);
            }

            // Delete additional images
            if (!empty($portfolio->additional_images)) {
                foreach ($portfolio->additional_images as $image) {
                    ImageHelper::deleteImage($image);
                }
            }

            $portfolio->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Portfolio deleted successfully!'
            ]);

            $this->clearCache();
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error deleting portfolio: ' . $e->getMessage()
            ]);
        }
    }

    public function confirmDelete($id)
    {
        $this->dispatch('confirm-delete', [
            'id' => $id,
            'message' => 'Are you sure you want to delete this portfolio?'
        ]);
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->resetExcept('search', 'selectedCategory', 'selectedProjectType');
        $this->showModal = true;
        $this->dispatch('open-modal');
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->resetExcept('search', 'selectedCategory', 'selectedProjectType');
        $this->showModal = false;
        $this->dispatch('close-modal');
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    public function removeTemporaryImage($index)
    {
        unset($this->tempImages[$index]);
        $this->tempImages = array_values($this->tempImages);
    }

    public function deleteImage($type, $index = null)
    {
        $portfolio = Portfolio::findOrFail($this->editingId);

        if ($type === 'main') {
            // Delete main image
            if ($portfolio->image) {
                ImageHelper::deleteImage($portfolio->image);
                $portfolio->image = null;
            }
        } else {
            // Delete additional image
            $additionalImages = $portfolio->additional_images ?? [];
            if (isset($additionalImages[$index])) {
                ImageHelper::deleteImage($additionalImages[$index]);
                unset($additionalImages[$index]);
                $portfolio->additional_images = array_values($additionalImages);
            }
        }

        $portfolio->save();
        $this->clearCache();
    }

    public function makeMainImage($index)
    {
        $portfolio = Portfolio::findOrFail($this->editingId);
        $additionalImages = $portfolio->additional_images ?? [];

        if (isset($additionalImages[$index])) {
            // Store current main image
            $oldMainImage = $portfolio->image;

            // Set new main image
            $portfolio->image = $additionalImages[$index];
            
            // Remove from additional images and add old main image
            unset($additionalImages[$index]);
            if ($oldMainImage) {
                $additionalImages[] = $oldMainImage;
            }

            $portfolio->additional_images = array_values($additionalImages);
            $portfolio->save();
            
            $this->clearCache();
        }
    }

    public function handleImageReorder()
    {
        if ($this->isEditing) {
            if (!empty($this->tempImages)) {
                $this->tempImages = array_values($this->tempImages);
            }
        } else {
            if (!empty($this->images)) {
                $this->images = array_values($this->images);
            }
        }
    }

    private function clearCache()
    {
        Cache::tags(['portfolios'])->flush();
    }
} 