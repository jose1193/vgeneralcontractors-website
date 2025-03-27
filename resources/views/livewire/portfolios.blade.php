{{-- resources/views/livewire/portfolios.blade.php --}}
<div>
    {{-- START: Added Outer Container --}}
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">


        {{-- Flash Messages Container --}}
        <div class="mb-4 space-y-3">
            @if (session()->has('message'))
                <x-alerts.success :message="session('message')" />
            @endif
            @if (session()->has('error'))
                <x-alerts.error :message="session('error')" />
            @endif
        </div>

        {{-- START: Added Card Container --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            {{-- START: Added Inner Padding --}}
            <div class="p-6">

                {{-- Barra de Controles: Búsqueda, Añadir Nuevo, Mostrar Borrados --}}
                <div
                    class="mb-4 flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0 sm:space-x-4">
                    {{-- Input de Búsqueda --}}
                    <div class="relative w-full sm:w-1/3 md:w-1/4">
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search portfolios..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:focus:ring-indigo-600 dark:focus:border-indigo-600 sm:text-sm">
                        <div wire:loading wire:target="search"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            {{-- Loading Spinner SVG --}}
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

                    {{-- Botones de Acción --}}
                    <div class="flex items-center space-x-3">
                        <x-toggle label="Show Inactive" :isActive="$showDeleted" wireClick="toggleShowDeleted" />
                        @can('CREATE_PORTFOLIO')
                            <x-add-button wireClick="create">
                                Add New
                            </x-add-button>
                        @endcan
                    </div>
                </div>

                {{-- Incluir la Tabla Refactorizada --}}
                {{-- The table partial itself now has its own bg/shadow/rounded --}}
                @include('components.livewire.portfolios.data-table', [
                    'portfolios' => $portfolios,
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                    'search' => $search,
                ])

                {{-- Paginación --}}
                <div class="mt-6">
                    {{ $portfolios->links() }}
                </div>

            </div> {{-- END: Added Inner Padding --}}
        </div> {{-- END: Added Card Container --}}

    </div> {{-- END: Added Outer Container --}}


    {{-- ========================================================== --}}
    {{-- ============ Modal para Crear/Editar Portfolio =========== --}}
    {{-- ========================================================== --}}
    {{-- Modal structure remains outside the main content containers --}}
    @if ($showModal)
        <div x-data="{ show: @entangle('showModal').live }" x-show="show" x-cloak x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-[100] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Overlay --}}
                <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    x-on:click="show = false; $wire.call('closeModal')"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-900 dark:bg-opacity-80"
                    aria-hidden="true">
                </div>

                {{-- Modal Content Container --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>

                <div x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl md:max-w-2xl lg:max-w-3xl sm:w-full">

                    {{-- Form --}}
                    <form wire:submit.prevent="save">
                        {{-- Header --}}
                        <div
                            class="bg-gray-50 dark:bg-gray-700 px-4 py-4 sm:px-6 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                {{ $isEditing ? 'Edit Portfolio' : 'Add New Portfolio' }}
                            </h3>
                        </div>

                        {{-- Body --}}
                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[75vh] overflow-y-auto">
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

                        {{-- Footer --}}
                        <div
                            class="bg-gray-100 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-600">
                            {{-- Save Button --}}
                            <button type="submit" wire:loading.attr="disabled"
                                wire:target="save, image_files, removePendingNewImage, markImageForDeletion, unmarkImageForDeletion"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                                <span wire:loading.remove wire:target="save, image_files">
                                    {{ $isEditing ? 'Update Portfolio' : 'Create Portfolio' }} </span> <span
                                    wire:loading wire:target="save, image_files" class="flex items-center"> <svg
                                        class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg> {{ $isEditing ? 'Updating...' : 'Creating...' }} </span> </button>
                            {{-- Cancel Button --}}
                            <button type="button" wire:click="closeModal" wire:loading.attr="disabled"
                                wire:target="save, image_files"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-500 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                                Cancel </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Confirmation Modals --}}
    <x-modals.delete-confirmation itemType="portfolio" />
    <x-modals.restore-confirmation itemType="portfolio" />

</div> {{-- end root component div --}}
