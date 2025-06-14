<div class="space-y-6">
    <!-- Post Title -->
    <div>
        <x-label for="post_title" value="{{ __('_post_crud_title') }}" required="true" />
        <x-input id="post_title" type="text" class="mt-1 block w-full" wire:model.lazy="post_title"
            x-model="form.post_title" @input="$wire.set('post_title', $event.target.value)" />
        <x-input-error for="post_title" class="mt-2" />
    </div>

    <!-- Category Selection -->
    <div>
        <x-label for="category_id" value="{{ __('_post_crud_table_category') }}" required="true" />
        <select id="category_id"
            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            wire:model.lazy="category_id" x-model="form.category_id"
            @change="$wire.set('category_id', $event.target.value)">
            <option value="">{{ __('_post_crud_select_category') }}</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->blog_category_name }}</option>
            @endforeach
        </select>
        <x-input-error for="category_id" class="mt-2" />
    </div>

    <!-- Post Image -->
    <div>
        <x-label for="temp_image" value="{{ __('_post_crud_image') }}" />
        <input id="temp_image" type="file" class="mt-1 block w-full" wire:model="temp_image" accept="image/*" />
        <div class="mt-2 text-sm text-gray-500">
            {{ __('_post_crud_recommended_image_size') }}
        </div>
        <x-input-error for="temp_image" class="mt-2" />

        <!-- Preview Current Image -->
        @if ($modalAction == 'update' && $post_image)
            <div class="mt-2">
                <p class="text-sm font-medium text-gray-500">{{ __('_post_crud_current_image') }}</p>
                <img src="{{ $post_image }}" alt="Current Post Image" class="mt-1 h-32 object-cover rounded">
            </div>
        @endif

        <!-- Preview New Image -->
        @if ($temp_image)
            <div class="mt-2">
                <p class="text-sm font-medium text-gray-500">{{ __('_post_crud_new_image_preview') }}</p>
                <img src="{{ $temp_image->temporaryUrl() }}" alt="New Post Image"
                    class="mt-1 h-32 object-cover rounded">
            </div>
        @endif
    </div>

    <!-- Post Content with TinyMCE -->
    <div wire:ignore>
        <x-label for="post_content" value="{{ __('_post_crud_content') }}" required="true" />
        <textarea id="post_content"
            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            rows="8" wire:model="post_content">{{ $post_content }}</textarea>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log('TinyMCE initialization started');
                if (typeof tinymce !== 'undefined') {
                    tinymce.init({
                        selector: '#post_content',
                        plugins: 'table lists link image code',
                        toolbar: 'bold italic | bullist numlist | link',
                        setup: function(editor) {
                            editor.on('init', function() {
                                console.log('TinyMCE init complete');
                            });
                            editor.on('change', function() {
                                @this.set('post_content', editor.getContent());
                            });
                        }
                    });
                } else {
                    console.error('TinyMCE not found');
                }
            });
        </script>
    </div>
    <x-input-error for="post_content" class="mt-2" />

    <!-- Agregar este div temporalmente para depuración -->
    <div class="mt-2 text-xs text-gray-500">
        <p>Content Length: {{ strlen($post_content ?? '') }}</p>
        <button type="button" class="px-2 py-1 bg-gray-200 rounded" onclick="console.log(@this.post_content)">
            Log Content
        </button>
    </div>

    <!-- Meta Fields Section -->
    <div class="border-t pt-4">
        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">{{ __('_post_crud_seo_information') }}
        </h3>

        <!-- Meta Title -->
        <div class="mb-4">
            <x-label for="meta_title" value="{{ __('_post_crud_meta_title') }}" />
            <x-input id="meta_title" type="text" class="mt-1 block w-full" wire:model.lazy="meta_title"
                x-model="form.meta_title" @input="$wire.set('meta_title', $event.target.value)" />
            <div class="mt-1 text-sm text-gray-500">
                {{ __('_post_crud_if_left_empty_title') }}
            </div>
            <x-input-error for="meta_title" class="mt-2" />
        </div>

        <!-- Meta Description -->
        <div class="mb-4">
            <x-label for="meta_description" value="{{ __('_post_crud_meta_description') }}" />
            <textarea id="meta_description"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                rows="2" wire:model.lazy="meta_description" x-model="form.meta_description"
                @input="$wire.set('meta_description', $event.target.value)"></textarea>
            <x-input-error for="meta_description" class="mt-2" />
        </div>

        <!-- Meta Keywords -->
        <div>
            <x-label for="meta_keywords" value="{{ __('_post_crud_meta_keywords') }}" />
            <x-input id="meta_keywords" type="text" class="mt-1 block w-full" wire:model.lazy="meta_keywords"
                x-model="form.meta_keywords" @input="$wire.set('meta_keywords', $event.target.value)" />
            <div class="mt-1 text-sm text-gray-500">
                {{ __('_post_crud_separate_keywords_commas') }}
            </div>
            <x-input-error for="meta_keywords" class="mt-2" />
        </div>
    </div>

    <!-- Post Scheduling Section -->
    <div class="border-t pt-4">
        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">{{ __('_post_crud_scheduling') }}</h3>

        <!-- Scheduled At -->
        <div>
            <x-label for="scheduled_at" value="{{ __('_post_crud_schedule_publication') }}" />
            <x-input id="scheduled_at" type="datetime-local" class="mt-1 block w-full" wire:model.lazy="scheduled_at"
                x-model="form.scheduled_at" @input="$wire.set('scheduled_at', $event.target.value)" />
            <div class="mt-1 text-sm text-gray-500">
                {{ __('_post_crud_leave_empty_publish_immediately') }}
            </div>
            <x-input-error for="scheduled_at" class="mt-2" />
        </div>
    </div>
</div>
