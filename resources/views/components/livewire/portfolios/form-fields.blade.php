@props(['modalAction', 'categories' => [], 'portfolio' => null])

<div class="p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Title Field -->
        <div class="col-span-1 md:col-span-2">
            <x-label for="title" value="{{ __('Title') }}" />
            <x-input id="title" type="text" class="mt-1 block w-full" wire:model.live="title" x-model="form.title"
                @input="validateField('title')" />
            <div x-show="errors.title" class="text-red-500 text-sm mt-1" x-text="errors.title"></div>
            @error('title')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Description Field -->
        <div class="col-span-1 md:col-span-2">
            <x-textarea-input name="description" label="Description" model="form.description"
                required></x-textarea-input>
            <div x-show="errors.description" class="text-red-500 text-sm mt-1" x-text="errors.description"></div>
            @error('description')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Service Category Select -->
        <div class="col-span-1 md:col-span-2">
            <x-label for="service_category_id" value="{{ __('Service Category') }}" />
            <select id="service_category_id" wire:model.live="service_category_id" x-model="form.service_category_id"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 
                dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 
                dark:focus:border-indigo-600 focus:ring-indigo-500 
                dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">Select Service Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->category }}</option>
                @endforeach
            </select>
            @error('service_category_id')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Images Upload with Dropzone -->
        <div class="col-span-1 md:col-span-2">
            <x-label for="images" value="{{ __('Images') }}" />

            <!-- Dropzone area -->
            <div x-data="dropzone()"
                class="mt-1 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4"
                :class="{ 'bg-gray-100 dark:bg-gray-700': isDragging }" @dragover.prevent="dragOver"
                @dragleave.prevent="dragLeave" @drop.prevent="drop">

                <input type="file" wire:model.live="{{ $isEditing ? 'tempImages' : 'images' }}" multiple
                    id="image-upload" class="hidden" accept="image/*">

                <label for="image-upload" class="cursor-pointer flex flex-col items-center justify-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                        viewBox="0 0 48 48">
                        <path
                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4h-12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                    <div class="mt-4 flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                        <span
                            class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none px-4 py-2">
                            Upload files
                        </span>
                        <p class="pl-1 pt-2">or drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        Max total size: 30MB. Max files: 15. The first image will be used as the main image.
                    </p>
                </label>
            </div>

            @error('images.*')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
            @error('images')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror

            <!-- Image Preview Section - Livewire Images -->
            @if (isset($images) && count($images) > 0)
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Upload Preview</h3>
                    <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4" x-data="imageReorder()">
                        @foreach ($images as $index => $image)
                            <div class="relative group border rounded" draggable="true"
                                @dragstart="dragStart($event, {{ $index }})" @dragend="dragEnd($event)"
                                @dragover.prevent @drop="drop($event, {{ $index }})">
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                    class="h-32 w-full object-cover rounded">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded">
                                    <button type="button" wire:click="removeImage({{ $index }})"
                                        class="text-white hover:text-red-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                @if ($index === 0)
                                    <div
                                        class="absolute top-0 left-0 bg-blue-500 text-white text-xs px-2 py-1 rounded-bl rounded-tr">
                                        Main
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Temp Images in Edit Mode -->
            @if ($modalAction === 'update' && isset($tempImages) && count($tempImages) > 0)
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">New Images</h3>
                    <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4" x-data="imageReorder()">
                        @foreach ($tempImages as $index => $image)
                            <div class="relative group border rounded" draggable="true"
                                @dragstart="dragStart($event, {{ $index }})" @dragend="dragEnd($event)"
                                @dragover.prevent @drop="drop($event, {{ $index }})">
                                <img src="{{ $image->temporaryUrl() }}" alt="New Image"
                                    class="h-32 w-full object-cover rounded">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded">
                                    <button type="button" wire:click="removeTemporaryImage({{ $index }})"
                                        class="text-white hover:text-red-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                @if ($index === 0)
                                    <div
                                        class="absolute top-0 left-0 bg-blue-500 text-white text-xs px-2 py-1 rounded-bl rounded-tr">
                                        Main
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Existing Image (for Edit) -->
            @if ($modalAction === 'update' && isset($portfolio) && $portfolio->image)
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Images</h3>
                    <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                        <div class="relative group">
                            <img src="{{ asset($portfolio->image) }}" alt="Main Image"
                                class="h-32 w-full object-cover rounded">
                            <div
                                class="absolute top-0 left-0 bg-blue-500 text-white text-xs px-2 py-1 rounded-bl rounded-tr">
                                Main
                            </div>
                        </div>
                        @if (!empty($portfolio->additional_images))
                            @foreach ($portfolio->additional_images as $index => $image)
                                <div class="relative group">
                                    <img src="{{ asset($image) }}" alt="Additional Image {{ $index + 1 }}"
                                        class="h-32 w-full object-cover rounded">
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Upload new images to replace the current ones.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Alpine.js scripts -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dropzone', () => ({
            isDragging: false,

            dragOver(e) {
                e.preventDefault();
                this.isDragging = true;
            },

            dragLeave(e) {
                e.preventDefault();
                this.isDragging = false;
            },

            drop(e) {
                e.preventDefault();
                this.isDragging = false;
                const files = e.dataTransfer.files;
                if (files.length) {
                    const input = this.$el.querySelector('input[type="file"]');
                    input.files = files;
                    input.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }
            },

            init() {
                // Agregar listener para el input file
                const input = this.$el.querySelector('input[type="file"]');
                input.addEventListener('change', (e) => {
                    if (e.target.files.length) {
                        const isEditMode =
                            '{{ isset($modalAction) && $modalAction === 'update' }}' ===
                            '1';
                        const propertyName = isEditMode ? 'tempImages' : 'images';

                        // Livewire manejará la subida automáticamente debido al wire:model
                    }
                });
            }
        }));

        Alpine.data('imageReorder', () => ({
            draggingIndex: null,
            dragStart(e, index) {
                this.draggingIndex = index;
                e.target.classList.add('opacity-50');
            },
            dragEnd(e) {
                e.target.classList.remove('opacity-50');
            },
            drop(e, index) {
                e.preventDefault();
                const isEditMode = '{{ isset($modalAction) && $modalAction === 'update' }}' ===
                    '1';
                const items = this.$wire.get(isEditMode ? 'tempImages' : 'images');

                if (items && this.draggingIndex !== null) {
                    const draggedItem = items[this.draggingIndex];

                    // Reorder array
                    items.splice(this.draggingIndex, 1);
                    items.splice(index, 0, draggedItem);

                    // Update Livewire
                    this.$wire.set(isEditMode ? 'tempImages' : 'images', items);
                }

                this.draggingIndex = null;

                // Notify Livewire about the reordering
                this.$wire.call('handleImageReorder');
            },

            init() {
                // Escuchar el evento de cierre del modal
                window.addEventListener('close-modal', () => {
                    this.draggingIndex = null;
                });
            }
        }));
    });
</script>

<button wire:click="closeModal" type="button"
    class="absolute right-0 text-white hover:text-gray-200 focus:outline-none" @click="$wire.closeModal()">
    <span class="sr-only">Close</span>
    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
</button>
