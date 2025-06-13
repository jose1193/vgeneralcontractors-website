<div>
    <div class="max-w-7xl mx-auto py-2 sm:py-4 md:py-2 lg:py-2 sm:px-6 lg:px-8 pb-12">
        <!-- Success and error messages -->
        @if (session()->has('message'))
            <x-alerts.success :message="session('message')" />
        @endif
        @if (session()->has('error'))
            <x-alerts.error :message="session('error')" />
        @endif

        <!-- Main container -->
        <div class=" dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg mx-4 sm:mx-0 sm:rounded-lg">
            <div class="p-4 sm:p-6">
                <!-- Add category button -->
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 space-y-4 md:space-y-0">
                    <x-input-search />

                    <div
                        class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                        <!-- Toggle to show inactive categories -->
                        <x-toggle :label="__('show_inactive_blog_categories')" :isActive="$showDeleted" wireClick="toggleShowDeleted" />

                        <!-- Per page dropdown with better spacing -->
                        <x-select-input-per-pages name="perPage" wireModel="perPage" class="sm:w-32">
                            <option value="10">10 {{ __('per_page') }}</option>
                            <option value="25">25 {{ __('per_page') }}</option>
                            <option value="50">50 {{ __('per_page') }}</option>
                            <option value="100">100 {{ __('per_page') }}</option>
                        </x-select-input-per-pages>

                        <div class="w-full sm:w-auto">
                            <x-add-button :wireClick="'create'">
                                {{ __('add_blog_category') }}
                            </x-add-button>
                        </div>
                    </div>
                </div>

                <!-- Categories table -->
                @include('components.livewire.blog-categories.data-table', [
                    'categories' => $categories,
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                ])

                <!-- Pagination -->
                <x-pagination :paginator="$categories" />
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div x-data="{ modalOpen: @entangle('isOpen') }" x-on:show-form-modal.window="modalOpen = true">
        @if ($isOpen)
            <x-modals.form-modal :isOpen="$isOpen" :modalTitle="$modalTitle" :modalAction="$modalAction">
                <div x-data="formValidation({
                    initialValues: {
                        blog_category_name: '{{ $blog_category_name }}',
                        blog_category_description: '{{ $blog_category_description }}',
                    },
                    modalAction: '{{ $modalAction }}'
                })" x-init="modalAction = '{{ $modalAction }}';
                // Initialize form values
                form = {
                    blog_category_name: '{{ $blog_category_name }}',
                    blog_category_description: '{{ $blog_category_description }}',
                };
                
                // Listen for update events
                $wire.on('category-edit', (event) => {
                    const data = event.detail;
                    console.log('Received category data:', data);
                
                    // Update form with new data
                    if (data) {
                        form.blog_category_name = data.blog_category_name || '';
                        form.blog_category_description = data.blog_category_description || '';
                
                        // Sync with Livewire
                        $wire.set('blog_category_name', form.blog_category_name);
                        $wire.set('blog_category_description', form.blog_category_description);
                    }
                
                    clearErrors();
                });">
                    @include('components.livewire.blog-categories.form-fields', [
                        'modalAction' => $modalAction,
                    ])
                </div>
            </x-modals.form-modal>
        @endif
    </div>

    <!-- Confirmation Modals -->
    <x-modals.delete-confirmation itemType="blog_category" />
    <x-modals.restore-confirmation itemType="blog_category" />
</div>
