<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
                <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </button>
            </div>
        @endif
        @if (session()->has('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </button>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between mb-4">
                    <div class="flex space-x-2">
                        <select wire:model.live="selectedCategory"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select wire:model.live="selectedProjectType"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">All Project Types</option>
                            @foreach ($projectTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button wire:click="create()"
                        class="flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-800 focus:bg-gray-700 dark:focus:bg-gray-800 active:bg-gray-900 dark:active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Add Portfolio
                    </button>
                </div>

                <div class="mb-4">
                    <input type="text" wire:model.debounce.300ms="search" placeholder="Search portfolios..."
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                </div>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 overflow-x-auto block md:table">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer"
                                wire:click="sort('title')">
                                Title
                                @if ($sortField === 'title')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer"
                                wire:click="sort('service_category_id')">
                                Category
                                @if ($sortField === 'service_category_id')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer"
                                wire:click="sort('project_type_id')">
                                Project Type
                                @if ($sortField === 'project_type_id')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer"
                                wire:click="sort('status')">
                                Status
                                @if ($sortField === 'status')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer"
                                wire:click="sort('created_at')">
                                Created At
                                @if ($sortField === 'created_at')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th
                                class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($portfolios as $portfolio)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap text-center">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $portfolio->title }}
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-no-wrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    {{ $portfolio->serviceCategory->name ?? 'N/A' }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-no-wrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    {{ $portfolio->projectType->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-center">
                                    @if ($portfolio->status == 'active')
                                        <span
                                            class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-green-500 text-white">
                                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $portfolio->status }}
                                        </span>
                                    @else
                                        <span
                                            class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-red-500 text-white">
                                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $portfolio->status }}
                                        </span>
                                    @endif
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-no-wrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    {{ $portfolio->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm font-medium text-center">
                                    <div class="inline-flex items-center justify-center space-x-4">
                                        <button wire:click="edit({{ $portfolio->id }})"
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-600 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button
                                            @click="$dispatch('confirmDelete', { id: '{{ $portfolio->id }}', title: '{{ addslashes($portfolio->title) }}' })"
                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-600 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-4 text-center" colspan="6">No portfolios available</td>
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

    <!-- Modal Form -->
    @if ($showModal)
        <div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <!-- Modal panel -->
                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full md:w-3/4 sm:w-full">
                    <form wire:key="portfolio-form-{{ $portfolioId ?? 'create' }}" x-data="formValidation()">
                        <!-- Modal header -->
                        <div class="bg-gray-900 px-4 py-3 sm:px-6">
                            <div class="flex items-center justify-center relative">
                                <h3 class="text-lg leading-6 font-medium text-white text-center" id="modal-title">
                                    {{ $isEditing ? 'Edit Portfolio' : 'Create Portfolio' }}
                                </h3>
                                <button wire:click="closeModal()" type="button"
                                    class="absolute right-0 text-white hover:text-gray-200 focus:outline-none">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal body -->
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label for="title"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Title:</label>
                                    <input type="text" x-model="form.title" @input="validateField('title')"
                                        wire:model="title" id="title"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <span x-show="errors.title" x-text="errors.title"
                                        class="text-red-500 text-xs mt-1"></span>
                                    @error('title')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="service_category_id"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Category:</label>
                                    <select x-model="form.service_category_id"
                                        @change="validateField('service_category_id')"
                                        wire:model="service_category_id" id="service_category_id"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <span x-show="errors.service_category_id" x-text="errors.service_category_id"
                                        class="text-red-500 text-xs mt-1"></span>
                                    @error('service_category_id')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="project_type_id"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Project
                                        Type:</label>
                                    <select x-model="form.project_type_id" @change="validateField('project_type_id')"
                                        wire:model="project_type_id" id="project_type_id"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Select Project Type</option>
                                        @foreach ($projectTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    <span x-show="errors.project_type_id" x-text="errors.project_type_id"
                                        class="text-red-500 text-xs mt-1"></span>
                                    @error('project_type_id')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="status"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Status:</label>
                                    <select x-model="form.status" @change="validateField('status')"
                                        wire:model="status" id="status"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    <span x-show="errors.status" x-text="errors.status"
                                        class="text-red-500 text-xs mt-1"></span>
                                    @error('status')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4 col-span-1 md:col-span-2">
                                    <label for="description"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Description:</label>
                                    <textarea x-model="form.description" @input="validateField('description')" wire:model="description" id="description"
                                        rows="3"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                                    <span x-show="errors.description" x-text="errors.description"
                                        class="text-red-500 text-xs mt-1"></span>
                                    @error('description')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4 col-span-1 md:col-span-2">
                                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                        {{ $isEditing ? 'Update Images:' : 'Upload Images:' }}
                                    </label>
                                    <div
                                        class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4">
                                        <input type="file" wire:model="{{ $isEditing ? 'tempImages' : 'images' }}"
                                            multiple class="hidden" id="image-upload" accept="image/*">
                                        <label for="image-upload"
                                            class="cursor-pointer flex flex-col items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                                Click to upload images (max 10 files, 20MB total)
                                            </p>
                                        </label>
                                    </div>
                                    @error('images.*')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                    @error('tempImages.*')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Preview Images -->
                                @if ($isEditing && $portfolio && ($portfolio->image || !empty($portfolio->additional_images)))
                                    <div class="mb-4 col-span-1 md:col-span-2">
                                        <label
                                            class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Current
                                            Images:</label>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            @if ($portfolio->image)
                                                <div class="relative group">
                                                    <img src="{{ Storage::url($portfolio->image) }}" alt="Main Image"
                                                        class="h-24 w-full object-cover rounded">
                                                    <div
                                                        class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded flex items-center justify-center">
                                                        <span class="text-white text-xs font-bold">Main Image</span>
                                                    </div>
                                                </div>
                                            @endif
                                            @if (!empty($portfolio->additional_images))
                                                @foreach ($portfolio->additional_images as $index => $image)
                                                    <div class="relative group">
                                                        <img src="{{ Storage::url($image) }}"
                                                            alt="Additional Image {{ $index + 1 }}"
                                                            class="h-24 w-full object-cover rounded">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- New Image Previews -->
                                @if (($isEditing && !empty($tempImages)) || (!$isEditing && !empty($images)))
                                    <div class="mb-4 col-span-1 md:col-span-2">
                                        <label
                                            class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">New
                                            Images:</label>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            @if ($isEditing)
                                                @foreach ($tempImages as $index => $image)
                                                    <div class="relative group">
                                                        <img src="{{ $image->temporaryUrl() }}"
                                                            alt="New Image {{ $index + 1 }}"
                                                            class="h-24 w-full object-cover rounded">
                                                        <div
                                                            class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded flex items-center justify-center">
                                                            <button type="button"
                                                                wire:click="removeTemporaryImage({{ $index }})"
                                                                class="text-white hover:text-red-500">
                                                                <svg class="w-6 h-6" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                @foreach ($images as $index => $image)
                                                    <div class="relative group">
                                                        <img src="{{ $image->temporaryUrl() }}"
                                                            alt="New Image {{ $index + 1 }}"
                                                            class="h-24 w-full object-cover rounded">
                                                        <div
                                                            class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded flex items-center justify-center">
                                                            <button type="button"
                                                                wire:click="removeImage({{ $index }})"
                                                                class="text-white hover:text-red-500">
                                                                <svg class="w-6 h-6" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" wire:click="save" x-data="{ isSubmitting: false }"
                                x-on:click="isSubmitting = true" @validation-failed.window="isSubmitting = false"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-900 dark:bg-gray-800 text-base font-medium text-white hover:bg-gray-700 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:ml-3 sm:w-auto sm:text-sm transition-opacity duration-200"
                                :class="{ 'opacity-50 cursor-not-allowed': isSubmitting }" :disabled="isSubmitting">
                                <svg wire:loading wire:target="save"
                                    class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Save
                            </button>
                        </div>
                    </form>
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
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
