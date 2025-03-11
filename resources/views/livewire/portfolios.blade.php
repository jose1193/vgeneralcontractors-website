<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="mb-5">

        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-end mb-4">
                    <button wire:click="openModal"
                        class="flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-800 focus:bg-gray-700 dark:focus:bg-gray-800 active:bg-gray-900 dark:active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Add Portfolio
                    </button>
                </div>

                <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <input type="text" wire:model.debounce.300ms="search" placeholder="Search portfolios..."
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    </div>
                    <div>
                        <select wire:model="selectedCategory"
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select wire:model="selectedProjectType"
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">All Project Types</option>
                            @foreach ($projectTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th
                                class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Image</th>
                            <th
                                class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Title</th>
                            <th
                                class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Category</th>
                            <th
                                class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Project Type</th>
                            <th
                                class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($portfolios as $portfolio)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap text-center">
                                    <div class="relative group">
                                        <img src="{{ $portfolio->image }}" alt="{{ $portfolio->title }}"
                                            class="h-12 w-12 object-cover rounded-lg mx-auto cursor-pointer">
                                        @if (!empty($portfolio->additional_images))
                                            <div
                                                class="absolute hidden group-hover:flex -top-2 -right-2 bg-blue-500 text-white rounded-full w-5 h-5 items-center justify-center text-xs">
                                                +{{ count($portfolio->additional_images) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-center">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $portfolio->title }}
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-no-wrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    {{ $portfolio->serviceCategory->name }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-no-wrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    {{ $portfolio->projectType->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-center">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $portfolio->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $portfolio->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm font-medium text-center">
                                    <button wire:click="edit({{ $portfolio->id }})"
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-600 mr-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $portfolio->id }})"
                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-600"
                                        onclick="return confirm('Are you sure you want to delete this portfolio item?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                            </path>
                                        </svg>
                                        <span class="font-medium">No records found</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $portfolios->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div x-data="{ open: false }" x-show="open" @open-modal.window="open = true" @close-modal.window="open = false"
        x-on:livewire:load="$watch('showModal', value => { open = value })" x-cloak
        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div x-show="open" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">

                <!-- Modal content -->
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <!-- Close button -->
                    <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
                        <button @click="open = false" wire:click="closeModal" type="button"
                            class="text-white hover:text-gray-200 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>

                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <div class="bg-gray-900 -mx-4 -mt-5 sm:-mx-6 sm:-mt-6 px-4 py-4 sm:px-6 rounded-t-lg">
                                <h3 class="text-lg leading-6 font-medium text-white text-center" id="modal-title">
                                    {{ $isEditing ? 'Edit Portfolio' : 'Add New Portfolio' }}
                                </h3>
                            </div>

                            <div class="mt-6 space-y-4" x-data="formValidation()">
                                <!-- Title -->
                                <div>
                                    <x-label for="title" value="Title" />
                                    <x-input id="title" type="text" class="mt-1 block w-full"
                                        x-model="form.title" @blur="validateField('title')" wire:model="title" />
                                    <span x-show="errors.title" x-text="errors.title"
                                        class="text-red-500 text-xs mt-1"></span>
                                </div>

                                <!-- Service Category -->
                                <div>
                                    <x-label for="service_category_id" value="Service Category" />
                                    <select id="service_category_id" x-model="form.service_category_id"
                                        @change="validateField('service_category_id')"
                                        wire:model="service_category_id"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <span x-show="errors.service_category_id" x-text="errors.service_category_id"
                                        class="text-red-500 text-xs mt-1"></span>
                                </div>

                                <!-- Project Type -->
                                <div>
                                    <x-label for="project_type_id" value="Project Type" />
                                    <select id="project_type_id" x-model="form.project_type_id"
                                        @change="validateField('project_type_id')" wire:model="project_type_id"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Select Project Type</option>
                                        @foreach ($projectTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    <span x-show="errors.project_type_id" x-text="errors.project_type_id"
                                        class="text-red-500 text-xs mt-1"></span>
                                </div>

                                <!-- Description -->
                                <div>
                                    <x-label for="description" value="Description" />
                                    <textarea id="description" x-model="form.description" @blur="validateField('description')" wire:model="description"
                                        rows="3"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
                                    <span x-show="errors.description" x-text="errors.description"
                                        class="text-red-500 text-xs mt-1"></span>
                                </div>

                                <!-- Images -->
                                <div>
                                    <x-label for="images" value="Images" />
                                    <div class="mt-2">
                                        <div x-data="dropzone()" @dragover.prevent="dragOver($event)"
                                            @dragleave.prevent="dragLeave($event)" @drop.prevent="drop($event)"
                                            class="border-2 border-dashed rounded-lg p-6 bg-gray-50 dark:bg-gray-700 transition-colors"
                                            :class="{ 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900': isDragging }">
                                            <div class="text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-300"
                                                    stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path
                                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <div class="mt-2 flex text-sm justify-center">
                                                    <label
                                                        class="relative cursor-pointer bg-indigo-600 text-white px-3 py-1 rounded-md font-medium hover:bg-indigo-500">
                                                        <span>Select Files</span>
                                                        <input type="file" @change="handleFileSelect($event)"
                                                            class="sr-only" multiple accept="image/*">
                                                    </label>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    Drag and drop images here or click to select<br>
                                                    PNG, JPG, GIF up to 20MB (Max 10 files)
                                                </p>
                                            </div>

                                            <!-- Vista previa de imágenes -->
                                            <div x-show="images.length > 0"
                                                class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4"
                                                x-ref="imageContainer">
                                                <template x-for="(image, index) in images" :key="index">
                                                    <div class="relative group cursor-move" draggable="true"
                                                        @dragstart="dragStart($event, index)"
                                                        @dragend="dragEnd($event)" @dragover.prevent
                                                        @drop="dropReorder($event, index)">
                                                        <img :src="image.preview"
                                                            class="h-24 w-full object-cover rounded-lg">
                                                        <div
                                                            class="absolute inset-0 bg-black bg-opacity-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center space-x-2">
                                                            <button type="button" @click="removeImage(index)"
                                                                class="text-white p-1 hover:text-red-500">
                                                                <svg class="w-6 h-6" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div x-show="index === 0"
                                                            class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                                                            Main
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Indicador de carga -->
                                            <div wire:loading wire:target="{{ $isEditing ? 'tempImages' : 'images' }}"
                                                class="mt-2 flex justify-center">
                                                <svg class="animate-spin h-5 w-5 text-indigo-500"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                <span class="ml-2 text-sm text-gray-500">Uploading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    @error('images.*')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <x-label for="status" value="Status" />
                                    <select id="status" x-model="form.status" @change="validateField('status')"
                                        wire:model="status"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Select Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    <span x-show="errors.status" x-text="errors.status"
                                        class="text-red-500 text-xs mt-1"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="validateForm() && $wire.{{ $isEditing ? 'update' : 'create' }}()"
                        wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-900 dark:bg-gray-800 text-base font-medium text-white hover:bg-gray-800 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:w-auto sm:text-sm">
                        <span wire:loading.remove wire:target="{{ $isEditing ? 'update' : 'create' }}">
                            {{ $isEditing ? 'Update' : 'Create' }}
                        </span>
                        <span wire:loading wire:target="{{ $isEditing ? 'update' : 'create' }}"
                            class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="fixed inset-0 z-50 overflow-y-auto" style="display: none" x-data="{ show: false, portfolio: null }" x-show="show"
        x-on:delete-confirmation.window="show = true; portfolio = $event.detail">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Header -->
                <div class="bg-red-600 px-4 py-3 sm:px-6 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                        Confirm Delete
                    </h3>
                    <button @click="show = false" class="text-white hover:text-gray-200">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900"
                                x-text="'Delete Portfolio: ' + (portfolio ? portfolio.title : '')"></h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to delete this portfolio item?
                                    This action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="$wire.delete(portfolio.id); show = false;" type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button @click="show = false" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dropzone', () => ({
            isDragging: false,
            images: [],

            init() {
                // Cargar imágenes existentes si estás editando
                @if ($isEditing && $portfolio && $portfolio->image)
                    this.images = [{
                            preview: '{{ $portfolio->image }}',
                            file: null
                        },
                        @if (!empty($portfolio->additional_images))
                            @foreach ($portfolio->additional_images as $image)
                                {
                                    preview: '{{ $image }}',
                                    file: null
                                },
                            @endforeach
                        @endif
                    ];
                @endif
            },

            dragOver(event) {
                this.isDragging = true;
            },

            dragLeave(event) {
                this.isDragging = false;
            },

            drop(event) {
                this.isDragging = false;
                const files = event.dataTransfer.files;
                this.processFiles(files);
            },

            handleFileSelect(event) {
                const files = event.target.files;
                this.processFiles(files);
            },

            processFiles(files) {
                if (this.images.length + files.length > 10) {
                    alert('Maximum of 10 images allowed.');
                    return;
                }

                for (let file of files) {
                    if (file.size > 20 * 1024 * 1024) {
                        alert('File size exceeds 20MB: ' + file.name);
                        continue;
                    }

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.images.push({
                            preview: e.target.result,
                            file: file
                        });
                        this.uploadFiles();
                    };
                    reader.readAsDataURL(file);
                }
            },

            removeImage(index) {
                this.images.splice(index, 1);
                this.uploadFiles();
            },

            dragStart(event, index) {
                event.dataTransfer.setData('text/plain', index);
                event.target.classList.add('opacity-50');
            },

            dragEnd(event) {
                event.target.classList.remove('opacity-50');
            },

            dropReorder(event, targetIndex) {
                const sourceIndex = event.dataTransfer.getData('text/plain');
                const draggedItem = this.images.splice(sourceIndex, 1)[0];
                this.images.splice(targetIndex, 0, draggedItem);
                this.uploadFiles();
            },

            uploadFiles() {
                const files = this.images.map(img => img.file).filter(file => file !== null);
                if (files.length > 0) {
                    @this.uploadMultiple('{{ $isEditing ? 'tempImages' : 'images' }}', files);
                }
            }
        }));
    });
</script>
<script>
    function imageReorder() {
        return {
            draggingIndex: null,
            dragStart(e, index) {
                this.draggingIndex = index;
                e.target.classList.add('opacity-50');
            },
            dragEnd(e) {
                e.target.classList.remove('opacity-50');
            },
            dragOver(e) {
                e.preventDefault();
            },
            drop(e, index) {
                e.preventDefault();
                const items = this.$wire.get('{{ $isEditing ? 'tempImages' : 'images' }}');
                const draggedItem = items[this.draggingIndex];

                // Reorder array
                items.splice(this.draggingIndex, 1);
                items.splice(index, 0, draggedItem);

                // Update Livewire
                this.$wire.set('{{ $isEditing ? 'tempImages' : 'images' }}', items);

                this.draggingIndex = null;
            }
        }
    }
</script>
<script>
    function formValidation() {
        return {
            form: {
                title: '',
                service_category_id: '',
                project_type_id: '',
                description: '',
                status: ''
            },
            errors: {
                title: '',
                service_category_id: '',
                project_type_id: '',
                description: '',
                status: ''
            },
            validateField(field) {
                this.errors[field] = '';

                switch (field) {
                    case 'title':
                        if (!this.form.title) {
                            this.errors.title = 'The title is required';
                        } else if (this.form.title.length < 3) {
                            this.errors.title = 'The title must be at least 3 characters';
                        }
                        break;
                    case 'service_category_id':
                        if (!this.form.service_category_id) {
                            this.errors.service_category_id = 'Please select a category';
                        }
                        break;
                    case 'project_type_id':
                        if (!this.form.project_type_id) {
                            this.errors.project_type_id = 'Please select a project type';
                        }
                        break;
                    case 'description':
                        if (!this.form.description) {
                            this.errors.description = 'The description is required';
                        } else if (this.form.description.length < 10) {
                            this.errors.description = 'The description must be at least 10 characters';
                        }
                        break;
                    case 'status':
                        if (!this.form.status) {
                            this.errors.status = 'Please select a status';
                        }
                        break;
                }

                return !this.errors[field];
            },
            validateForm() {
                let isValid = true;

                ['title', 'service_category_id', 'project_type_id', 'description', 'status'].forEach(field => {
                    if (!this.validateField(field)) {
                        isValid = false;
                    }
                });

                return isValid;
            }
        }
    }
</script>
<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('confirmDelete', (portfolioData) => {
            window.dispatchEvent(new CustomEvent('delete-confirmation', {
                detail: portfolioData
            }));
        });
    });
</script>
