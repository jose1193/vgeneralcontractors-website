<div class="space-y-6">
    <!-- Post Title -->
    <div>
        <x-label for="post_title" value="{{ __('Post Title') }}" required="true" />
        <x-input id="post_title" type="text" class="mt-1 block w-full" wire:model.lazy="post_title"
            x-model="form.post_title" @input="$wire.set('post_title', $event.target.value)" />
        <x-input-error for="post_title" class="mt-2" />
    </div>

    <!-- Category Selection -->
    <div>
        <x-label for="category_id" value="{{ __('Category') }}" required="true" />
        <select id="category_id"
            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            wire:model.lazy="category_id" x-model="form.category_id"
            @change="$wire.set('category_id', $event.target.value)">
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->blog_category_name }}</option>
            @endforeach
        </select>
        <x-input-error for="category_id" class="mt-2" />
    </div>

    <!-- Post Image -->
    <div>
        <x-label for="temp_image" value="{{ __('Post Image') }}" />
        <input id="temp_image" type="file" class="mt-1 block w-full" wire:model="temp_image" accept="image/*" />
        <div class="mt-2 text-sm text-gray-500">
            Recommended image size: 1200x630 pixels. Max size: 1MB.
        </div>
        <x-input-error for="temp_image" class="mt-2" />

        <!-- Preview Current Image -->
        @if ($modalAction == 'update' && $post_image)
            <div class="mt-2">
                <p class="text-sm font-medium text-gray-500">Current Image:</p>
                <img src="{{ Storage::url($post_image) }}" alt="Current Post Image"
                    class="mt-1 h-32 object-cover rounded">
            </div>
        @endif

        <!-- Preview New Image -->
        @if ($temp_image)
            <div class="mt-2">
                <p class="text-sm font-medium text-gray-500">New Image Preview:</p>
                <img src="{{ $temp_image->temporaryUrl() }}" alt="New Post Image"
                    class="mt-1 h-32 object-cover rounded">
            </div>
        @endif
    </div>

    <!-- Post Content -->
    <div>
        <x-label for="post_content" value="{{ __('Post Content') }}" required="true" />
        <textarea id="post_content"
            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            rows="8" wire:model.lazy="post_content" x-model="form.post_content"
            @input="$wire.set('post_content', $event.target.value)"></textarea>
        <x-input-error for="post_content" class="mt-2" />
    </div>

    <!-- Meta Fields Section -->
    <div class="border-t pt-4">
        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">SEO Information</h3>

        <!-- Meta Title -->
        <div class="mb-4">
            <x-label for="meta_title" value="{{ __('Meta Title') }}" />
            <x-input id="meta_title" type="text" class="mt-1 block w-full" wire:model.lazy="meta_title"
                x-model="form.meta_title" @input="$wire.set('meta_title', $event.target.value)" />
            <div class="mt-1 text-sm text-gray-500">
                If left empty, post title will be used.
            </div>
            <x-input-error for="meta_title" class="mt-2" />
        </div>

        <!-- Meta Description -->
        <div class="mb-4">
            <x-label for="meta_description" value="{{ __('Meta Description') }}" />
            <textarea id="meta_description"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                rows="2" wire:model.lazy="meta_description" x-model="form.meta_description"
                @input="$wire.set('meta_description', $event.target.value)"></textarea>
            <x-input-error for="meta_description" class="mt-2" />
        </div>

        <!-- Meta Keywords -->
        <div>
            <x-label for="meta_keywords" value="{{ __('Meta Keywords') }}" />
            <x-input id="meta_keywords" type="text" class="mt-1 block w-full" wire:model.lazy="meta_keywords"
                x-model="form.meta_keywords" @input="$wire.set('meta_keywords', $event.target.value)" />
            <div class="mt-1 text-sm text-gray-500">
                Separate keywords with commas (e.g., blog, news, article)
            </div>
            <x-input-error for="meta_keywords" class="mt-2" />
        </div>
    </div>


</div>
