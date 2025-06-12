{{-- resources/views/livewire/portfolios.blade.php --}}
<div>
    {{-- Page Title (Optional) --}}
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Manage Portfolios</h1>

    {{-- Flash Messages Container --}}
    <div class="mb-4 space-y-3">
        {{-- Mensaje de éxito Flash --}}
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg @click="show = false" class="fill-current h-6 w-6 text-green-500 cursor-pointer" role="button"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
        @endif

        {{-- Mensaje de error Flash --}}
        @if (session()->has('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg @click="show = false" class="fill-current h-6 w-6 text-red-500 cursor-pointer" role="button"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
        @endif
    </div>

    {{-- Barra de Controles: Búsqueda, Añadir Nuevo, Mostrar Borrados --}}
    <div class="mb-4 flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0 sm:space-x-4">
        {{-- Input de Búsqueda --}}
        <div class="relative w-full sm:w-1/3 md:w-1/4">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search portfolios..."
                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:focus:ring-indigo-600 dark:focus:border-indigo-600 sm:text-sm">
            <div wire:loading wire:target="search" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                <svg class="animate-spin h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
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
            {{-- Botón Mostrar/Ocultar Borrados --}}
            <button wire:click="toggleShowDeleted" type="button"
                class="px-3 py-2 text-sm font-medium rounded-md transition duration-150 ease-in-out
                           {{ $showDeleted
                               ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-700 dark:text-yellow-100 dark:hover:bg-yellow-600'
                               : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500' }}">
                {{ $showDeleted ? 'Hide' : 'Show' }} Trashed
            </button>

            {{-- Botón para abrir el modal de creación --}}
            @can('CREATE_PORTFOLIO')
                {{-- Opcional: usar directiva @can si tienes Gate/Policies --}}
                <button wire:click="create"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded dark:bg-blue-600 dark:hover:bg-blue-800 transition duration-150 ease-in-out">
                    <svg class="inline-block w-4 h-4 mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New
                </button>
            @endcan
        </div>
    </div>

    {{-- Tabla para mostrar los portfolios --}}
    <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    {{-- Imagen --}}
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Image
                    </th>
                    {{-- Project Name --}}
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Project Name
                    </th>
                    {{-- Service Category --}}
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Service Category
                    </th>
                    {{-- Created At (Ordenable) --}}
                    <th scope="col" wire:click="sort('created_at')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        <span class="flex items-center">
                            Created
                            @if ($sortField === 'created_at')
                                @if ($sortDirection === 'asc')
                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @else
                                <svg class="w-3 h-3 ml-1 opacity-30" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                </svg>
                            @endif
                        </span>
                    </th>
                    {{-- Estado (Normal/Trashed) --}}
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Status
                    </th>
                    {{-- Actions --}}
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($portfolios as $portfolio)
                    <tr wire:key="portfolio-{{ $portfolio->id }}"
                        class="{{ $portfolio->trashed() ? 'bg-red-50 dark:bg-red-900/20 opacity-70' : '' }}">
                        {{-- Imagen (Primera imagen) --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($portfolio->images->isNotEmpty())
                                <img src="{{ $portfolio->images->first()->path }}"
                                    alt="{{ $portfolio->projectType?->title ?? 'Portfolio Image' }}"
                                    class="h-12 w-12 rounded-md object-cover border dark:border-gray-600 shadow-sm">
                            @else
                                {{-- Placeholder si no hay imagen --}}
                                <div
                                    class="h-12 w-12 rounded-md bg-gray-100 dark:bg-gray-700 flex items-center justify-center border dark:border-gray-600">
                                    <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </td>
                        {{-- Project Name (Desde ProjectType) --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $portfolio->projectType?->title ?? 'N/A' }}
                            </div>
                            {{-- Opcional: mostrar descripción corta --}}
                            {{-- <div class="text-xs text-gray-500 dark:text-gray-400 truncate w-48">{{ $portfolio->projectType?->description ?? '' }}</div> --}}
                        </td>
                        {{-- Service Category --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                {{ $portfolio->projectType?->serviceCategory?->category ?? 'N/A' }}
                            </span>
                        </td>
                        {{-- Created At --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $portfolio->created_at->format('M d, Y') }}
                                {{-- {{ $portfolio->created_at->diffForHumans() }} --}}
                            </div>
                        </td>
                        {{-- Status --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($portfolio->trashed())
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Trashed
                                </span>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Active
                                </span>
                            @endif
                        </td>
                        {{-- Actions --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                            @if ($portfolio->trashed())
                                {{-- Botón Restaurar --}}
                                @can('RESTORE_PORTFOLIO')
                                    <button wire:click="restore('{{ $portfolio->id }}')" wire:loading.attr="disabled"
                                        wire:target="restore('{{ $portfolio->id }}')"
                                        class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 transition duration-150 ease-in-out"
                                        title="Restore">
                                        <svg class="inline-block w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m-1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        <span wire:loading wire:target="restore('{{ $portfolio->id }}')"
                                            class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full ml-1"
                                            role="status" aria-hidden="true"></span>
                                    </button>
                                @endcan
                            @else
                                {{-- Botón Editar --}}
                                @can('UPDATE_PORTFOLIO')
                                    <button wire:click="edit('{{ $portfolio->id }}')" wire:loading.attr="disabled"
                                        wire:target="edit('{{ $portfolio->id }}')"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition duration-150 ease-in-out"
                                        title="Edit">
                                        <svg class="inline-block w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                @endcan
                                {{-- Botón Borrar (Mover a Papelera) --}}
                                @can('DELETE_PORTFOLIO')
                                    <button wire:click="delete('{{ $portfolio->id }}')"
                                        wire:confirm="Are you sure you want to move '{{ $portfolio->projectType?->title ?? 'this item' }}' to trash?"
                                        wire:loading.attr="disabled" wire:target="delete('{{ $portfolio->id }}')"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition duration-150 ease-in-out"
                                        title="Trash">
                                        <svg class="inline-block w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        <span wire:loading wire:target="delete('{{ $portfolio->id }}')"
                                            class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full ml-1"
                                            role="status" aria-hidden="true"></span>
                                    </button>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" {{-- Ajustar colspan al número total de columnas --}}
                            class="px-6 py-12 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                            @if ($search)
                                No portfolios found matching "{{ $search }}".
                            @else
                                No portfolios found. @can('CREATE_PORTFOLIO')
                                    <button wire:click="create" class="text-blue-500 hover:underline ml-1">Add one
                                        now?</button>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-6">
        {{ $portfolios->links() }}
    </div>


    {{-- ========================================================== --}}
    {{-- ============ Modal para Crear/Editar Portfolio =========== --}}
    {{-- ========================================================== --}}
    @if ($showModal)
        <div x-data="{ show: @entangle('showModal').live }" x-show="show" x-cloak x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-[100] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Fondo oscuro (Overlay) --}}
                <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    x-on:click="show = false; $wire.call('closeModal')"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-900 dark:bg-opacity-80"
                    aria-hidden="true">
                </div>

                {{-- Contenido del Modal --}}
                {{-- This element is to trick the browser into centering the modal contents. --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>

                <div x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl md:max-w-2xl lg:max-w-3xl sm:w-full">

                    {{-- Formulario --}}
                    <form wire:submit.prevent="save">
                        {{-- Encabezado del Modal --}}
                        <div
                            class="bg-gray-50 dark:bg-gray-700 px-4 py-4 sm:px-6 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100"
                                id="modal-title">
                                {{ $isEditing ? 'Edit Portfolio' : 'Add New Portfolio' }}
                            </h3>
                        </div>

                        {{-- Cuerpo del Modal (con scroll si es necesario) --}}
                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[75vh] overflow-y-auto">
                            <div class="space-y-6">

                                {{-- Campo Title --}}
                                <div>
                                    <label for="title"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Project Name <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model.lazy="title" type="text" id="title" autocomplete="off"
                                        class="mt-1 block w-full   dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('title') border-red-500 dark:border-red-500 @enderror">
                                    @error('title')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Campo Description --}}
                                <div>
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Description <span class="text-red-500">*</span>
                                    </label>
                                    <textarea wire:model.lazy="description" id="description" rows="4"
                                        class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-500 dark:border-red-500 @enderror"></textarea>
                                    @error('description')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Campo Service Category Select --}}
                                <div>
                                    <label for="service_category_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Service Category <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="service_category_id" id="service_category_id"
                                        class="mt-1 block w-full  dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('service_category_id') border-red-500 dark:border-red-500 @enderror">
                                        <option value="">Select a Service Category</option>
                                        @foreach ($serviceCategoriesList ?? $serviceCategories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->category ?? $category->type }} {{-- Ajustar segun nombre real del campo --}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_category_id')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>


                                {{-- ========== SECCIÓN IMÁGENES ========== --}}
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Images
                                        @php
                                            // Determinar si se requiere al menos una imagen en total
                                            $isImageRequiredForDisplay = false;
                                            if (!$isEditing) {
                                                $isImageRequiredForDisplay = true;
                                            } elseif (
                                                $isEditing &&
                                                $existing_images instanceof \Illuminate\Support\Collection
                                            ) {
                                                $isImageRequiredForDisplay =
                                                    $existing_images->whereNotIn('id', $images_to_delete)->isEmpty() &&
                                                    empty($pendingNewImages);
                                            }
                                        @endphp
                                        @if ($isImageRequiredForDisplay)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                                        (Max {{ App\Livewire\Portfolios::MAX_FILES }} total images.
                                        Max {{ App\Livewire\Portfolios::MAX_SIZE_KB / 1024 }}MB per image.
                                        Max {{ App\Livewire\Portfolios::MAX_TOTAL_SIZE_KB / 1024 }}MB for all new
                                        images combined.)
                                    </p>

                                    {{-- Input File Múltiple --}}
                                    <input wire:model="image_files" type="file" id="image_files" multiple
                                        accept="image/jpeg,image/png,image/gif,image/webp"
                                        class="block w-full text-sm text-gray-500 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800
                                                     file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded-l-md
                                                     file:text-sm file:font-semibold file:cursor-pointer
                                                     file:bg-indigo-50 dark:file:bg-gray-600
                                                     file:text-indigo-700 dark:file:text-indigo-200
                                                     hover:file:bg-indigo-100 dark:hover:file:bg-gray-500"
                                        {{-- Deshabilitar si se alcanza el límite total --}}
                                        @php
$currentVisibleExistingCount = $isEditing && $existing_images instanceof \Illuminate\Support\Collection ? $existing_images->whereNotIn('id', $images_to_delete)->count() : 0;
                                               $newPendingCount = count($pendingNewImages);
                                               $canAddMore = ($currentVisibleExistingCount + $newPendingCount) < App\Livewire\Portfolios::MAX_FILES; @endphp
                                        {{ $canAddMore ? '' : 'disabled' }}
                                        title="{{ $canAddMore ? 'Select images to add' : 'Maximum number of images reached (' . App\Livewire\Portfolios::MAX_FILES . ')' }}">

                                    {{-- Indicador de carga para el input --}}
                                    <div wire:loading wire:target="image_files"
                                        class="mt-2 text-sm text-indigo-600 dark:text-indigo-400 animate-pulse">
                                        Processing selection...
                                    </div>

                                    {{-- Errores de Validación Específicos del Input (`image_files.*`) --}}
                                    @error('image_files.*')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror

                                    {{-- Errores de Validación Globales (`pendingNewImages` - totales, requerimiento) --}}
                                    @error('pendingNewImages')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror

                                    {{-- Previsualización de NUEVAS Imágenes PENDIENTES (ACUMULADAS) --}}
                                    @if (!empty($pendingNewImages))
                                        <div class="mt-4">
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">
                                                New Images Pending Upload ({{ count($pendingNewImages) }}):
                                            </p>
                                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                                                @foreach ($pendingNewImages as $index => $image)
                                                    @if (
                                                        $image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile &&
                                                            method_exists($image, 'temporaryUrl'))
                                                        <div wire:key="pending-new-image-{{ $index }}"
                                                            class="relative group aspect-square">
                                                            <img src="{{ $image->temporaryUrl() }}"
                                                                alt="New image {{ $index + 1 }} preview"
                                                                class="h-full w-full object-cover rounded-md border border-gray-300 dark:border-gray-600 shadow-sm">
                                                            {{-- Botón quitar PENDIENTE --}}
                                                            <button type="button"
                                                                wire:click="removePendingNewImage({{ $index }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="removePendingNewImage({{ $index }})"
                                                                class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white rounded-full p-1 shadow-md transition-all duration-150 ease-in-out opacity-75 hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 dark:focus:ring-offset-gray-800"
                                                                title="Remove this pending image">
                                                                {{-- Icono X --}}
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                                {{-- Loading Spinner (dentro del botón) --}}
                                                                <span wire:loading
                                                                    wire:target="removePendingNewImage({{ $index }})"
                                                                    class="absolute inset-0 flex items-center justify-center bg-red-600 bg-opacity-50 rounded-full">
                                                                    <svg class="animate-spin h-3 w-3 text-white"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="none" viewBox="0 0 24 24">
                                                                        <circle class="opacity-25" cx="12"
                                                                            cy="12" r="10"
                                                                            stroke="currentColor" stroke-width="4">
                                                                        </circle>
                                                                        <path class="opacity-75" fill="currentColor"
                                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                        </path>
                                                                    </svg>
                                                                </span>
                                                            </button>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Visualización de Imágenes EXISTENTES (Solo en modo Edición) --}}
                                    @if ($isEditing && $existing_images instanceof \Illuminate\Support\Collection && $existing_images->isNotEmpty())
                                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">
                                                Current Images
                                                ({{ $existing_images->whereNotIn('id', $images_to_delete)->count() }}
                                                visible):
                                            </p>
                                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                                                @foreach ($existing_images as $image)
                                                    <div wire:key="existing-image-{{ $image->id }}"
                                                        class="relative group aspect-square {{ in_array($image->id, $images_to_delete) ? 'opacity-40' : '' }} transition-opacity duration-200">
                                                        <img src="{{ $image->path }}"
                                                            alt="Existing image {{ $loop->iteration }}"
                                                            class="h-full w-full object-cover rounded-md border border-gray-300 dark:border-gray-600 shadow-sm">

                                                        {{-- Overlay y Botones --}}
                                                        <div
                                                            class="absolute inset-0 flex items-center justify-center rounded-md
                                                                    {{ in_array($image->id, $images_to_delete) ? 'bg-gray-800 bg-opacity-70' : 'bg-black bg-opacity-0 group-hover:bg-opacity-60' }}
                                                                    transition-all duration-200">

                                                            {{-- Botón si NO está marcada para borrar --}}
                                                            @if (!in_array($image->id, $images_to_delete))
                                                                <button type="button"
                                                                    wire:click="markImageForDeletion({{ $image->id }})"
                                                                    wire:loading.attr="disabled"
                                                                    wire:target="markImageForDeletion({{ $image->id }})"
                                                                    class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-full shadow-lg opacity-0 group-hover:opacity-100 scale-90 group-hover:scale-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                                                                    title="Mark for Deletion">
                                                                    {{-- Icono Papelera --}}
                                                                    <svg class="w-5 h-5" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                        </path>
                                                                    </svg>
                                                                    {{-- Loading Spinner --}}
                                                                    <span wire:loading
                                                                        wire:target="markImageForDeletion({{ $image->id }})"
                                                                        class="absolute inset-0 flex items-center justify-center bg-red-600 bg-opacity-50 rounded-full">
                                                                        <svg class="animate-spin h-4 w-4 text-white"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 24 24">
                                                                            <circle class="opacity-25" cx="12"
                                                                                cy="12" r="10"
                                                                                stroke="currentColor"
                                                                                stroke-width="4"></circle>
                                                                            <path class="opacity-75"
                                                                                fill="currentColor"
                                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                </button>
                                                            @else
                                                                {{-- Botón/Indicador si SÍ está marcada --}}
                                                                <button type="button"
                                                                    wire:click="unmarkImageForDeletion({{ $image->id }})"
                                                                    wire:loading.attr="disabled"
                                                                    wire:target="unmarkImageForDeletion({{ $image->id }})"
                                                                    class="p-2 bg-yellow-500 hover:bg-yellow-600 text-gray-800 rounded-full shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                                                                    title="Undo Mark for Deletion">
                                                                    {{-- Icono Deshacer (Refresh/Undo) --}}
                                                                    <svg class="w-5 h-5" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 00-15.357-2m15.357 2H15">
                                                                        </path>
                                                                    </svg>
                                                                    {{-- Loading Spinner --}}
                                                                    <span wire:loading
                                                                        wire:target="unmarkImageForDeletion({{ $image->id }})"
                                                                        class="absolute inset-0 flex items-center justify-center bg-yellow-500 bg-opacity-50 rounded-full">
                                                                        <svg class="animate-spin h-4 w-4 text-gray-800"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 24 24">
                                                                            <circle class="opacity-25" cx="12"
                                                                                cy="12" r="10"
                                                                                stroke="currentColor"
                                                                                stroke-width="4"></circle>
                                                                            <path class="opacity-75"
                                                                                fill="currentColor"
                                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Mensaje si no hay imágenes y se está creando --}}
                                    @if (!$isEditing && empty($pendingNewImages))
                                        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400 italic">Please select
                                            at least one image to upload.</p>
                                    @endif
                                    {{-- Mensaje si no quedarán imágenes y se está editando --}}
                                    @if (
                                        $isEditing &&
                                            $existing_images instanceof \Illuminate\Support\Collection &&
                                            $existing_images->whereNotIn('id', $images_to_delete)->isEmpty() &&
                                            empty($pendingNewImages))
                                        <p class="mt-4 text-sm text-yellow-600 dark:text-yellow-400">Warning: No images
                                            will remain after saving. If an image is required, please add a new one or
                                            unmark an existing one.</p>
                                    @endif

                                </div>
                                {{-- ========== FIN SECCIÓN IMÁGENES ========== --}}

                            </div> {{-- end space-y-6 --}}
                        </div> {{-- end modal body --}}

                        {{-- Footer del Modal (Botones) --}}
                        <div
                            class="bg-gray-100 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-600">
                            {{-- Botón Guardar/Actualizar --}}
                            <button type="submit" wire:loading.attr="disabled"
                                wire:target="save, image_files, removePendingNewImage, markImageForDeletion, unmarkImageForDeletion"
                                {{-- Target more actions --}}
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                                {{-- Texto normal --}}
                                <span wire:loading.remove wire:target="save, image_files">
                                    {{ $isEditing ? 'Update Portfolio' : 'Create Portfolio' }}
                                </span>
                                {{-- Texto e icono de carga --}}
                                <span wire:loading wire:target="save, image_files" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    {{ $isEditing ? 'Updating...' : 'Creating...' }}
                                </span>
                            </button>
                            {{-- Botón Cancelar --}}
                            <button type="button" wire:click="closeModal" wire:loading.attr="disabled"
                                wire:target="save, image_files" {{-- Disable while saving --}}
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-500 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                                Cancel
                            </button>
                        </div>
                    </form> {{-- end form --}}
                </div> {{-- end modal content --}}
            </div> {{-- end modal container --}}
        </div> {{-- end modal root --}}
    @endif
    {{-- ==================== Fin del Modal ==================== --}}

</div> {{-- end root component div --}}
