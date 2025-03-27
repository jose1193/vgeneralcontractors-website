{{-- resources/views/components/livewire/portfolios/form-fields.blade.php --}}

@props([
    'isEditing',
    'serviceCategoriesList', // Collection of ServiceCategory models
    'existing_images', // Collection of PortfolioImage models when editing (ASSUMED ORDERED BY 'order')
    'images_to_delete', // Array of existing image IDs marked for deletion
    'pendingNewImages', // Array of TemporaryUploadedFile for new images
    // Pass constants explicitly
    'maxFiles',
    'maxSizeKb',
    'maxTotalSizeKb',
])

{{-- Add CSS for dragging effect --}}
@once
    <style>
        [wire\:sortable-dragging] {
            cursor: grabbing !important;
            /* Indicate active drag */
            opacity: 0.8;
            /* Make it slightly transparent */
            transform: scale(1.03);
            /* Slightly enlarge */
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            /* Tailwind shadow-lg */
            z-index: 50;
            /* Ensure it's above other items */
            /* Prevent text selection during drag */
            -webkit-user-select: none;
            /* Safari */
            -ms-user-select: none;
            /* IE 10 and IE 11 */
            user-select: none;
            /* Standard syntax */
        }

        /* Optional: Style the placeholder element if desired */
        /* .livewire-sortable-ghost { */
        /* background-color: #e5e7eb; */
        /* bg-gray-200 */
        /* border-radius: 0.375rem; */
        /* rounded-md */
        /* } */
    </style>
@endonce

{{-- Wrapper div for consistent spacing --}}
<div class="space-y-6">

    <x-input-2 name="title" label="Project Name" {{-- Explicitly add wire:model.live to control update frequency --}} wire:model.live="title" {{-- Keep 'model' if x-input-2 uses it for other things like id/name/label linking --}}
        model="title" :required="true" :error="$errors->first('title')" autocomplete="off" placeholder="Enter project name"
        {{-- The @input for capitalization can remain --}}
        @input="$event.target.value = $event.target.value.charAt(0).toUpperCase() + $event.target.value.slice(1)" />
    {{-- Campo Description --}}
    <x-text-area-2 name="description" label="Description" model="description" :required="true" :error="$errors->first('description')"
        rows="4" placeholder="Enter project description"
        @input="$event.target.value = $event.target.value.charAt(0).toUpperCase() + $event.target.value.slice(1)" />

    {{-- Campo Service Category Select --}}
    <x-select-input-2 name="service_category_id" label="Service Category" model="service_category_id" :required="true"
        :error="$errors->first('service_category_id')" :options="$serviceCategoriesList" placeholder="Select a Service Category" value-field="id"
        text-field="category" />

    {{-- ========== SECCIÓN IMÁGENES ========== --}}
    <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Images
        </label>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
            (Max {{ $maxFiles }} total. Max {{ $maxSizeKb / 1024 }}MB/image. Max {{ $maxTotalSizeKb / 1024 }}MB
            total new. Drag to reorder.)
        </p>

        {{-- Input File Múltiple --}}
        <input wire:model="image_files" type="file" id="image_files" multiple
            accept="image/jpeg,image/png,image/gif,image/webp"
            class="block w-full text-sm text-gray-500 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800
                   file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded-l-md
                   file:text-sm file:font-semibold file:cursor-pointer
                   file:bg-indigo-50 dark:file:bg-gray-600
                   file:text-indigo-700 dark:file:text-indigo-200
                   hover:file:bg-indigo-100 dark:hover:file:bg-gray-500"
            @php
$currentVisibleExistingCount = $isEditing && $existing_images instanceof \Illuminate\Support\Collection ? $existing_images->whereNotIn('id', $images_to_delete)->count() : 0;
                $newPendingCount = count($pendingNewImages);
                $canAddMore = ($currentVisibleExistingCount + $newPendingCount) < $maxFiles; @endphp
            {{ $canAddMore ? '' : 'disabled' }}
            title="{{ $canAddMore ? 'Select images to add' : 'Maximum number of images reached (' . $maxFiles . ')' }}">

        {{-- Indicador de carga for file input --}}
        <div wire:loading wire:target="image_files"
            class="mt-2 text-sm text-indigo-600 dark:text-indigo-400 animate-pulse">
            Processing selection...
        </div>

        {{-- Errores Específicos del Input (`image_files.*`) --}}
        @error('image_files.*')
            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
        @enderror

        {{-- Errores Globales (`pendingNewImages`, `image_files`) - e.g., total count/size limits, required --}}
        @error('pendingNewImages')
            {{-- Catches validation rule on the array property itself --}}
            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
        @enderror
        @error('image_files')
            {{-- Catches validation rule on the file input property itself --}}
            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
        @enderror

        {{-- Previsualización de NUEVAS Imágenes PENDIENTES --}}
        @if (!empty($pendingNewImages))
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">
                    New Images Pending Upload ({{ count($pendingNewImages) }}):
                </p>
                {{-- wire:sortable pointing to the method for pending images --}}
                <div wire:sortable="updatePendingImageOrder"
                    class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                    @foreach ($pendingNewImages as $index => $image)
                        @if (
                            $image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile &&
                                method_exists($image, 'temporaryUrl'))
                            {{-- wire:sortable.item with $index, cursor-grab --}}
                            <div wire:sortable.item="{{ $index }}"
                                wire:key="pending-new-image-{{ $index }}"
                                class="relative group aspect-square cursor-grab"> {{-- Added cursor-grab --}}
                                {{-- Optional: Add wire:sortable.handle here if needed --}}
                                {{-- <span wire:sortable.handle class="absolute top-0 left-0 p-1 bg-gray-400 z-20">Drag</span> --}}
                                <img src="{{ $image->temporaryUrl() }}" alt="New image {{ $index + 1 }} preview"
                                    draggable="false" {{-- PREVENT BROWSER DRAG --}}
                                    class="h-full w-full object-cover rounded-md border border-gray-300 dark:border-gray-600 shadow-sm">
                                {{-- Botón quitar PENDIENTE --}}
                                <button type="button" wire:click="$parent.removePendingNewImage({{ $index }})"
                                    wire:loading.attr="disabled"
                                    wire:target="$parent.removePendingNewImage({{ $index }})"
                                    class="absolute -top-2 -right-2 z-10 bg-red-600 hover:bg-red-700 text-white rounded-full p-1 shadow-md transition-all duration-150 ease-in-out opacity-75 hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed"
                                    title="Remove this pending image">
                                    <span wire:loading.remove
                                        wire:target="$parent.removePendingNewImage({{ $index }})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </span>
                                    <span wire:loading wire:target="$parent.removePendingNewImage({{ $index }})"
                                        class="absolute inset-0 flex items-center justify-center bg-red-600 bg-opacity-50 rounded-full">
                                        <svg class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Visualización de Imágenes EXISTENTES --}}
        {{-- Ensure $existing_images is ordered by 'order' column from the parent component --}}
        @if ($isEditing && $existing_images instanceof \Illuminate\Support\Collection && $existing_images->isNotEmpty())
            <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">
                    Current Images ({{ $existing_images->whereNotIn('id', $images_to_delete)->count() }} visible):
                </p>
                {{-- wire:sortable pointing to the method for existing images --}}
                <div wire:sortable="updateExistingImageOrder"
                    class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                    @php $visibleImageIndex = 0; @endphp {{-- Counter for visible images --}}
                    @foreach ($existing_images as $image)
                        {{-- wire:sortable.item with $image->id, cursor-grab --}}
                        <div wire:sortable.item="{{ $image->id }}" wire:key="existing-image-{{ $image->id }}"
                            class="relative group aspect-square {{ in_array($image->id, $images_to_delete) ? 'opacity-40' : '' }} cursor-grab">
                            {{-- Added cursor-grab --}}

                            {{-- Optional: Add wire:sortable.handle here if needed --}}
                            {{-- <span wire:sortable.handle class="absolute top-0 left-0 p-1 bg-gray-400 z-20">Drag</span> --}}

                            {{-- Label MAIN - Check if it's the first *visible* image in the loop --}}
                            @if (!in_array($image->id, $images_to_delete))
                                @if ($visibleImageIndex === 0)
                                    <span
                                        class="absolute top-1 left-1 z-10 bg-indigo-600 text-white text-[10px] font-semibold px-1.5 py-0.5 rounded shadow">
                                        MAIN
                                    </span>
                                @endif
                                @php $visibleImageIndex++; @endphp {{-- Increment only for visible images --}}
                            @endif

                            <img src="{{ $image->path }}" alt="Existing image {{ $loop->iteration }}"
                                draggable="false" {{-- PREVENT BROWSER DRAG --}}
                                class="h-full w-full object-cover rounded-md border border-gray-300 dark:border-gray-600 shadow-sm">

                            {{-- Overlay y Botones --}}
                            <div
                                class="absolute inset-0 flex items-center justify-center rounded-md {{ in_array($image->id, $images_to_delete) ? 'bg-gray-800 bg-opacity-70' : 'bg-black bg-opacity-0 group-hover:bg-opacity-60' }} transition-all duration-200">
                                @if (!in_array($image->id, $images_to_delete))
                                    {{-- Botón Marcar para Borrar --}}
                                    <button type="button" wire:click="markImageForDeletion({{ $image->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="markImageForDeletion({{ $image->id }})"
                                        class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-full shadow-lg opacity-0 group-hover:opacity-100 scale-90 group-hover:scale-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed"
                                        title="Mark for Deletion">
                                        <span wire:loading.remove
                                            wire:target="markImageForDeletion({{ $image->id }})">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </span>
                                        <span wire:loading wire:target="markImageForDeletion({{ $image->id }})"
                                            class="absolute inset-0 flex items-center justify-center bg-red-600 bg-opacity-50 rounded-full">
                                            <svg class="animate-spin h-4 w-4 text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </span>
                                    </button>
                                @else
                                    {{-- Botón Desmarcar --}}
                                    <button type="button" wire:click="unmarkImageForDeletion({{ $image->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="unmarkImageForDeletion({{ $image->id }})"
                                        class="p-2 bg-yellow-500 hover:bg-yellow-600 text-gray-800 rounded-full shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed"
                                        title="Undo Mark for Deletion">
                                        <span wire:loading.remove
                                            wire:target="unmarkImageForDeletion({{ $image->id }})">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                </path>
                                            </svg>
                                        </span>
                                        <span wire:loading wire:target="unmarkImageForDeletion({{ $image->id }})"
                                            class="absolute inset-0 flex items-center justify-center bg-yellow-500 bg-opacity-50 rounded-full">
                                            <svg class="animate-spin h-4 w-4 text-gray-800"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Mensajes informativos sobre estado de imágenes --}}
        @if (!$isEditing && empty($pendingNewImages))
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400 italic"> Please select at least one image. </p>
        @endif
        @if (
            $isEditing &&
                $existing_images instanceof \Illuminate\Support\Collection &&
                $existing_images->whereNotIn('id', $images_to_delete)->isEmpty() &&
                empty($pendingNewImages))
            <p class="mt-4 text-sm text-yellow-600 dark:text-yellow-400"> Warning: No images will remain after saving.
                Please add or unmark an image if one is required. </p>
        @endif

    </div>
    {{-- ========== FIN SECCIÓN IMÁGENES ========== --}}

</div> {{-- end wrapper div --}}
