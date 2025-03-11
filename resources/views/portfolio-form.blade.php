<?php
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ isset($portfolio) ? 'Edit Portfolio' : 'Create Portfolio' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ isset($portfolio) ? route('portfolios.update', $portfolio) : route('portfolios.store') }}" 
                          method="POST" 
                          enctype="multipart/form-data"
                          class="space-y-6">
                        @csrf
                        @if(isset($portfolio))
                            @method('PUT')
                        @endif

                        <div>
                            <x-label for="title" value="Title" />
                            <x-input id="title" 
                                    type="text" 
                                    name="title"
                                    class="mt-1 block w-full" 
                                    value="{{ old('title', $portfolio->title ?? '') }}" 
                                    required />
                            @error('title')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-label for="service_category_id" value="Service Category" />
                            <select id="service_category_id" 
                                    name="service_category_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('service_category_id', $portfolio->service_category_id ?? '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('service_category_id')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-label for="project_type_id" value="Project Type" />
                            <select id="project_type_id" 
                                    name="project_type_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required>
                                <option value="">Select Project Type</option>
                                @foreach($projectTypes as $type)
                                    <option value="{{ $type->id }}"
                                            {{ old('project_type_id', $portfolio->project_type_id ?? '') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_type_id')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-label for="description" value="Description" />
                            <textarea id="description" 
                                    name="description"
                                    rows="3"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $portfolio->description ?? '') }}</textarea>
                            @error('description')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-label for="images" value="Images" />
                            <div class="mt-2 space-y-4">
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" 
                                                  stroke-width="2" 
                                                  stroke-linecap="round" 
                                                  stroke-linejoin="round" />
                                        </svg>
                                        <div class="mt-4 flex text-sm justify-center">
                                            <label class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload images</span>
                                                <input type="file" 
                                                       name="images[]" 
                                                       class="sr-only"
                                                       multiple 
                                                       accept="image/*">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </div>

                                @if(isset($portfolio) && $portfolio->image)
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="relative group">
                                            <img src="{{ $portfolio->image }}" 
                                                 alt="Main image"
                                                 class="h-24 w-full object-cover rounded-lg">
                                            <div class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                                                Main
                                            </div>
                                        </div>
                                        @if(!empty($portfolio->additional_images))
                                            @foreach($portfolio->additional_images as $image)
                                                <div class="relative group">
                                                    <img src="{{ $image }}" 
                                                         alt="Additional image"
                                                         class="h-24 w-full object-cover rounded-lg">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @error('images')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-label for="status" value="Status" />
                            <select id="status" 
                                    name="status"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="active" {{ old('status', $portfolio->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $portfolio->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button type="button" 
                                     onclick="window.location='{{ route('portfolios.index') }}'"
                                     class="bg-gray-600 hover:bg-gray-700 mr-3">
                                Cancel
                            </x-button>
                            <x-button type="submit" 
                                     class="relative"
                                     x-data="{ loading: false }"
                                     x-on:click="loading = true"
                                     x-bind:disabled="loading">
                                <span x-show="!loading">{{ isset($portfolio) ? 'Update' : 'Create' }}</span>
                                <span x-show="loading" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 