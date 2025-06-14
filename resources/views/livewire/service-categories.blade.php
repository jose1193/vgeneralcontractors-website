<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <!-- Success and error messages -->
        @if (session()->has('message'))
            <x-alerts.success :message="session('message')" />
        @endif
        @if (session()->has('error'))
            <x-alerts.error :message="session('error')" />
        @endif

        <!-- Main container -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg">
            <div class="p-6">
                <!-- Add category button -->
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 space-y-4 md:space-y-0">
                    <x-input-search />

                    <div
                        class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                        <!-- Toggle to show inactive categories -->
                        <x-toggle label="{{ __('show_inactive_categories') }}" :isActive="$showDeleted"
                            wireClick="toggleShowDeleted" />

                        <!-- Per page dropdown with better spacing -->
                        <x-select-input-per-pages name="perPage" wireModel="perPage" class="sm:w-32">
                            <option value="10">10 {{ __('per_page') }}</option>
                            <option value="25">25 {{ __('per_page') }}</option>
                            <option value="50">50 {{ __('per_page') }}</option>
                            <option value="100">100 {{ __('per_page') }}</option>
                        </x-select-input-per-pages>

                        <div class="w-full sm:w-auto">
                            <x-add-button :wireClick="'create'">
                                {{ __('add_category') }}
                            </x-add-button>
                        </div>
                    </div>
                </div>

                <!-- Categories table with extra spacing on mobile -->
                <div class="mt-8 sm:mt-6">
                    @include('components.livewire.service-categories.data-table', [
                        'categories' => $categories,
                        'sortField' => $sortField,
                        'sortDirection' => $sortDirection,
                    ])
                </div>

                <!-- Pagination -->
                <x-pagination :paginator="$categories" />
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if ($isOpen)
        <x-modals.form-modal :isOpen="$isOpen" :modalTitle="$modalTitle" :modalAction="$modalAction">
            <div x-data="formValidation({
                initialValues: {
                    category: '{{ $category }}',
                },
                modalAction: '{{ $modalAction }}'
            })" x-init="modalAction = '{{ $modalAction }}';
            // Initialize form values
            form = {
                category: '{{ $category }}',
            };
            
            // Listen for update events
            $wire.on('category-edit', (event) => {
                const data = event.detail;
                console.log('Received category data:', data);
            
                // Update form with new data
                if (data) {
                    form.category = data.category || '';
            
                    // Sync with Livewire
                    $wire.set('category', form.category);
                }
            
                clearErrors();
            });">
                @include('components.livewire.service-categories.form-fields', [
                    'modalAction' => $modalAction,
                ])
            </div>
        </x-modals.form-modal>
    @endif

    <!-- Confirmation Modals -->
    <x-modals.delete-confirmation itemType="category" />
    <x-modals.restore-confirmation itemType="category" />
</div>
