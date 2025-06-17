<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-8 px-4 sm:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-1">{{ __('Portfolio Management') }}</h2>
                    <p class="text-gray-400">{{ __('Manage your portfolios and showcase your projects.') }}</p>
                </div>
                <button id="addEntityBtn" type="button"
                    class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md shadow focus:outline-none focus:ring-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>{{ __('Add New Portfolio') }}</span>
                </button>
            </div>

            <!-- Alert Message -->
            <div id="alertMessage" class="hidden mb-4 rounded border px-4 py-3" role="alert">
                <span></span>
            </div>

            <!-- Filters -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 mb-4">
                <div class="flex-1 mb-2 sm:mb-0">
                    <input id="searchInput" type="text" placeholder="{{ __('Search portfolios...') }}"
                        class="w-full px-4 py-2 rounded-md border border-gray-600 bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-yellow-500" />
                </div>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center text-gray-300">
                        <input id="showDeleted" type="checkbox"
                            class="form-checkbox h-5 w-5 text-yellow-500 bg-gray-700 border-gray-600 rounded" />
                        <span class="ml-2">{{ __('Show Inactive') }}</span>
                    </label>
                    <select id="perPage"
                        class="ml-2 px-2 py-1 rounded-md border border-gray-600 bg-gray-800 text-gray-200 focus:outline-none">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
                <table id="dataTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Title') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Service Category') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Images') }}</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Created At') }}</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Status') }}</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Rows rendered by JS -->
                        <tr id="loadingRow">
                            <td colspan="6" class="px-6 py-4 text-center text-gray-400">{{ __('Loading...') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="pagination" class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between"></div>

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
    <script type="module">
        import PortfolioCrudManager from '/resources/js/components/portfolioCrud.js';

        const routes = {
            index: '{{ route('portfolios-crud.index') }}',
            store: '{{ route('portfolios-crud.store') }}',
            edit: '{{ route('portfolios-crud.edit', [':id']) }}',
            update: '{{ route('portfolios-crud.update', [':id']) }}',
            destroy: '{{ route('portfolios-crud.destroy', [':id']) }}',
            restore: '{{ route('portfolios-crud.restore', [':id']) }}',
            checkTitle: '{{ route('portfolios-crud.check-title') }}',
        };

        window.portfolioCrudManager = new PortfolioCrudManager({
            entityName: 'Portfolio',
            routes: routes,
            tableSelector: '#dataTable',
            modalSelector: '#entityModal',
            formSelector: '#entityForm',
            alertSelector: '#alertMessage',
            addButtonSelector: '#addEntityBtn',
            paginationSelector: '#pagination',
            searchSelector: '#searchInput',
            perPageSelector: '#perPage',
            showDeletedSelector: '#showDeleted',
            // ...otros selectores si es necesario...
        });
    </script>
@endpush
