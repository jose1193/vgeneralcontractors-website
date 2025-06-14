<div>
    <div class="max-w-7xl mx-auto py-2 sm:py-4 md:py-2 lg:py-2 px-4 sm:px-6 lg:px-8 pb-12">
        <!-- Success and error messages -->
        @if (session()->has('message'))
            <x-alerts.success :message="session('message')" />
        @endif
        @if (session()->has('error'))
            <x-alerts.error :message="session('error')" />
        @endif

        <!-- Main container -->
        <div class="dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg">
            <div class="p-6">
                <!-- Add post button -->
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 space-y-4 md:space-y-0">
                    <x-input-search />

                    <div
                        class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                        <!-- Toggle to show inactive posts -->
                        <x-toggle :label="__('_post_crud_show_inactive')" :isActive="$showDeleted" wireClick="toggleShowDeleted" />

                        <!-- Per page dropdown with better spacing -->
                        <x-select-input-per-pages name="perPage" wireModel="perPage" class="sm:w-32">
                            <option value="10">10 {{ __('per_page') }}</option>
                            <option value="25">25 {{ __('per_page') }}</option>
                            <option value="50">50 {{ __('per_page') }}</option>
                            <option value="100">100 {{ __('per_page') }}</option>
                        </x-select-input-per-pages>

                        <div class="w-full sm:w-auto">
                            <x-add-button :wireClick="'create'">
                                {{ __('_post_crud_add') }}
                            </x-add-button>
                        </div>
                    </div>
                </div>

                <!-- Posts table with extra spacing on mobile -->
                <div class="mt-8 sm:mt-6">
                    @include('components.livewire.posts.data-table', [
                        'posts' => $posts,
                        'sortField' => $sortField,
                        'sortDirection' => $sortDirection,
                    ])
                </div>

                <!-- Pagination -->
                <x-pagination :paginator="$posts" />
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div x-data="{ modalOpen: @entangle('isOpen') }" x-on:show-form-modal.window="modalOpen = true">
        @if ($isOpen)
            <x-modals.form-modal :isOpen="$isOpen" :modalTitle="$modalTitle" :modalAction="$modalAction">
                <div x-data="formValidation({
                    initialValues: {
                        post_title: '{{ addslashes($post_title) }}',
                        post_content: '{{ addslashes($post_content) }}',
                        meta_description: '{{ addslashes($meta_description) }}',
                        meta_title: '{{ addslashes($meta_title) }}',
                        meta_keywords: '{{ addslashes($meta_keywords) }}',
                        category_id: '{{ $category_id }}',
                    },
                    modalAction: '{{ $modalAction }}'
                })" x-init="modalAction = '{{ $modalAction }}';
                // Initialize form values
                form = {
                    post_title: '{{ addslashes($post_title) }}',
                    post_content: `{!! $post_content !!}`,
                    meta_description: '{{ addslashes($meta_description) }}',
                    meta_title: '{{ addslashes($meta_title) }}',
                    meta_keywords: '{{ addslashes($meta_keywords) }}',
                    category_id: '{{ $category_id }}',
                };
                
                // Listen for update events
                $wire.on('post-edit', (event) => {
                    const data = event.detail;
                    console.log('Received post data:', data);
                
                    // Update form with new data
                    if (data) {
                        form.post_title = data.post_title || '';
                        form.post_content = data.post_content || '';
                        form.meta_description = data.meta_description || '';
                        form.meta_title = data.meta_title || '';
                        form.meta_keywords = data.meta_keywords || '';
                        form.category_id = data.category_id || '';
                
                        // Sync with Livewire
                        $wire.set('post_title', form.post_title);
                        $wire.set('post_content', form.post_content);
                        $wire.set('meta_description', form.meta_description);
                        $wire.set('meta_title', form.meta_title);
                        $wire.set('meta_keywords', form.meta_keywords);
                        $wire.set('category_id', form.category_id);
                    }
                
                    clearErrors();
                });">
                    @include('components.livewire.posts.form-fields', [
                        'modalAction' => $modalAction,
                        'categories' => $categories,
                    ])
                </div>
            </x-modals.form-modal>
        @endif
    </div>

    <!-- Confirmation Modals -->
    <x-modals.delete-confirmation itemType="post" />
    <x-modals.restore-confirmation itemType="post" />
</div>
