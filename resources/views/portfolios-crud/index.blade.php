<x-app-layout>
    <!-- Fondo oscuro principal -->
    <div class="min-h-screen bg-gray-900" style="background-color: #141414;">
        <!-- Header -->
        <div class="p-4 sm:p-6">
            <div class="mb-4 sm:mb-8 text-center sm:text-center md:text-left lg:text-left">
                <h2 class="text-base sm:text-base md:text-2xl lg:text-2xl font-bold text-white mb-2">
                    {{ __('Portfolio Management') }}
                </h2>
                <p class="text-base sm:text-base md:text-base lg:text-base text-gray-400">
                    {{ __('Manage your portfolios and showcase your projects.') }}
                </p>
            </div>
        </div>
        <!-- Main content area -->
        <div class="max-w-7xl mx-auto py-2 sm:py-4 md:py-2 lg:py-2 px-4 sm:px-6 lg:px-8 pb-12">
            <!-- Alert Message -->
            <div id="alertMessage" class="hidden mb-4 rounded border px-4 py-3" role="alert">
                <span></span>
            </div>
            <!-- Filtros y botón crear -->
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
                <!-- Search bar -->
                <div class="relative flex-1 max-w-md">
                    <input id="searchInput" type="text" placeholder="{{ __('Search portfolios...') }}"
                        class="flex-1 text-gray-300 placeholder-gray-500 rounded-l-lg px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-yellow-500 border-0"
                        style="background-color: #2C2E36;" />
                    <svg class="w-5 h-5 text-gray-500 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <!-- Controls -->
                <div
                    class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                    <!-- Show deleted toggle -->
                    <label class="flex items-center text-gray-300">
                        <input id="showDeleted" type="checkbox"
                            class="mr-2 rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500 focus:ring-offset-gray-800">
                        {{ __('Show Inactive') }}
                    </label>
                    <!-- Per page dropdown -->
                    <select id="perPage"
                        class="text-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 border-0"
                        style="background-color: #2C2E36;">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <!-- Create button -->
                    <button id="addEntityBtn" type="button"
                        class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 px-4 py-2 rounded-lg font-medium transition-colors inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Add New Portfolio') }}
                    </button>
                </div>
            </div>
            <!-- Tabla -->
            <div class="dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table id="dataTable" class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        {{ __('Title') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        {{ __('Service Category') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        {{ __('Images') }}</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        {{ __('Created At') }}</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        {{ __('Status') }}</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        {{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700">
                                <!-- Rows rendered by JS -->
                                <tr id="loadingRow">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                                        {{ __('Loading...') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div id="pagination" class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    </div>
                </div>
            </div>
            <!-- Modal Placeholder (se implementará después) -->
            <div id="entityModal"
                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow-xl w-full max-w-2xl mx-auto p-6 relative">
                    <!-- Modal Header -->
                    <div id="modalHeader"
                        class="flex items-center justify-between mb-4 bg-blue-500 dark:bg-blue-600 rounded-t-lg px-4 py-2">
                        <h3 id="modalTitle" class="text-lg font-semibold text-white">{{ __('Create Portfolio') }}</h3>
                        <button id="closeModal" type="button"
                            class="text-white hover:bg-blue-700 rounded-full p-1 focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <!-- Modal Form -->
                    <form id="entityForm" enctype="multipart/form-data" class="space-y-4">
                        <input type="hidden" id="entityUuid" name="uuid" />
                        <!-- Title -->
                        <div>
                            <label for="title"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Title') }}</label>
                            <input id="title" name="title" type="text" maxlength="255"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500"
                                required />
                            <span id="titleValidationMessage" class="validation-message hidden text-xs"></span>
                            <span id="titleError" class="error-message hidden text-xs text-red-500"></span>
                        </div>
                        <!-- Description -->
                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Description') }}</label>
                            <textarea id="description" name="description" rows="3" maxlength="1000"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500"
                                required></textarea>
                            <span id="descriptionError" class="error-message hidden text-xs text-red-500"></span>
                        </div>
                        <!-- Service Category -->
                        <div>
                            <label for="service_category_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Service Category') }}</label>
                            <select id="service_category_id" name="service_category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500"
                                required>
                                <option value="">-- {{ __('Select Category') }} --</option>
                                <!-- Opciones cargadas por JS -->
                            </select>
                            <span id="service_category_idError"
                                class="error-message hidden text-xs text-red-500"></span>
                        </div>
                        <!-- Images -->
                        <div>
                            <label for="images"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Images') }}</label>
                            <input id="images" name="images[]" type="file" accept="image/*" multiple
                                class="mt-1 block w-full text-gray-900 dark:text-gray-100" />
                            <span id="imagesError" class="error-message hidden text-xs text-red-500"></span>
                            <!-- Previews -->
                            <div id="imagePreviews" class="flex flex-wrap gap-2 mt-2"></div>
                        </div>
                        <!-- Modal Actions -->
                        <div class="flex justify-end space-x-2 mt-6">
                            <button id="cancelBtn" type="button"
                                class="px-4 py-2 rounded-md bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-400 dark:hover:bg-gray-600">{{ __('Cancel') }}</button>
                            <button id="saveBtn" type="submit"
                                class="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <span class="button-text">{{ __('Save') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    <!-- Incluir CrudManager base primero -->
    <script src="{{ asset('js/crud-manager.js') }}"></script>

    <script>
        window.translations = window.translations || {};
        window.translations.no_portfolios_found = "{{ __('no_portfolios_found') }}";
        window.translations.error_loading_portfolios = "{{ __('error_loading_portfolios') }}";
    </script>

    <!-- Incluir PortfolioCrud directamente -->
    <script src="{{ asset('js/portfolioCrud.js') }}"></script>

    <script>
        $(document).ready(function() {
            const routes = {
                index: '{{ route('portfolios-crud.index') }}',
                store: '{{ route('portfolios-crud.store') }}',
                edit: '{{ route('portfolios-crud.edit', [':id']) }}',
                update: '{{ route('portfolios-crud.update', [':id']) }}',
                destroy: '{{ route('portfolios-crud.destroy', [':id']) }}',
                restore: '{{ route('portfolios-crud.restore', [':id']) }}',
                checkTitle: '{{ route('portfolios-crud.check-title') }}',
            };

            // Inicializar el manager
            window.portfolioCrudManager = new PortfolioCrudManager({
                routes: routes
            });
        });
    </script>
@endpush
