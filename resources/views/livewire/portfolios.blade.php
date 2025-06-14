{{-- resources/views/livewire/portfolios.blade.php --}}
<div>
    {{-- Outer Container --}}
    <div class="max-w-7xl mx-auto py-4 px-4 sm:py-10 sm:px-6 lg:px-8">

        {{-- Flash Messages --}}
        <div class="mb-4 space-y-3">
            @if (session()->has('message'))
                <x-alerts.success :message="session('message')" />
            @endif
            @if (session()->has('error'))
                <x-alerts.error :message="session('error')" />
            @endif
        </div>

        {{-- Card Container --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg">
            <div class="p-4 sm:p-6">

                {{-- Controls Bar --}}
                <div
                    class="mb-4 flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                    {{-- Search Input --}}
                    <div class="relative w-full md:w-1/3 lg:w-1/4">
                        <input wire:model.live.debounce.300ms="search" type="text"
                            placeholder="{{ __('search_portfolios') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:focus:ring-indigo-600 dark:focus:border-indigo-600 sm:text-sm">
                        <div wire:loading wire:target="search"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            {{-- Loading Spinner --}}
                            <svg class="animate-spin h-5 w-5 text-gray-500 dark:text-gray-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div
                        class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                        {{-- Toggle Show Deleted --}}
                        <x-toggle label="{{ __('show_inactive') }}" :isActive="$showDeleted" wireClick="toggleShowDeleted" />
                        {{-- Per Page Selector (Optional - Add if needed) --}}
                        {{-- <x-select-input-per-pages name="perPage" wireModel="perPage" class="sm:w-32"> ... </x-select-input-per-pages> --}}
                        @can('CREATE_PORTFOLIO')
                            <div class="w-full sm:w-auto">
                                <x-add-button wireClick="create">
                                    {{ __('add_new') }}
                                </x-add-button>
                            </div>
                        @endcan
                    </div>
                </div>

                {{-- Data Table with extra spacing on mobile --}}
                <div class="mt-8 sm:mt-6">
                    @include('components.livewire.portfolios.data-table', [
                        'portfolios' => $portfolios,
                        'sortField' => $sortField,
                        'sortDirection' => $sortDirection,
                        'search' => $search,
                    ])
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $portfolios->links() }}
                </div>

            </div> {{-- End Inner Padding --}}
        </div> {{-- End Card Container --}}

    </div> {{-- End Outer Container --}}


    {{-- ========================================================== --}}
    {{-- ============ Modal using x-modals.form-modal =========== --}}
    {{-- ========================================================== --}}
    @if ($showModal)
        {{--
            We pass 'save' as the modalAction. The form-modal component uses this
            for the wire:submit target. Your Livewire component's save() method
            handles both create and update logic based on the $isEditing flag.
            The button text inside the form-modal component might need adjustment
            if it solely relies on modalAction, or you could pass a separate prop for button text.
        --}}
        <x-modals.form-modal :isOpen="$showModal" :modalTitle="$isEditing ? __('edit_portfolio') : __('add_new_portfolio')" :modalAction="'save'">
            {{--
                Optional x-data wrapper. Useful if you add client-side validation
                or need Alpine state within the modal body later.
                We initialize 'form' based on current Livewire state when the modal renders.
                Use @js for safety with potentially complex strings like descriptions.
            --}}
            <div x-data="{
                form: {
                    title: @js($title),
                    description: @js($description),
                    service_category_id: {{ $service_category_id ?? 'null' }}
                },
                // You might add validation logic here later, like in the service-categories example
                errors: {}
            }" x-init="// Re-sync Alpine state if Livewire state changes while modal might be open
            // (e.g., after validation error or background updates) - use with caution
            $watch('$wire.title', value => form.title = value);
            $watch('$wire.description', value => form.description = value);
            $watch('$wire.service_category_id', value => form.service_category_id = value);
            
            // Optional: Clear Alpine errors when the modal is re-opened/data changes
            $wire.on('portfolio-modal-opened', () => {
                // Reset Alpine form state based on current Livewire props
                form.title = $wire.title;
                form.description = $wire.description;
                form.service_category_id = $wire.service_category_id;
                errors = {}; // Clear Alpine errors
                console.log('Alpine form state synced on portfolio-modal-opened');
            });">

                {{-- Include the actual form fields --}}
                @include('components.livewire.portfolios.form-fields', [
                    'isEditing' => $isEditing,
                    'serviceCategoriesList' => $serviceCategoriesList ?? $serviceCategories,
                    'existing_images' => $existing_images,
                    'images_to_delete' => $images_to_delete,
                    'pendingNewImages' => $pendingNewImages,
                    'maxFiles' => App\Livewire\Portfolios::MAX_FILES,
                    'maxSizeKb' => App\Livewire\Portfolios::MAX_SIZE_KB,
                    'maxTotalSizeKb' => App\Livewire\Portfolios::MAX_TOTAL_SIZE_KB,
                ])
            </div>
        </x-modals.form-modal>
    @endif
    {{-- ==================== End Modal ==================== --}}


    {{-- Confirmation Modals --}}
    <x-modals.delete-confirmation itemType="portfolio" />
    <x-modals.restore-confirmation itemType="portfolio" />

</div> {{-- end root component div --}}
