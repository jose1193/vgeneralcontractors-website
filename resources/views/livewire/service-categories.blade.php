<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-end mb-4">
                    <button wire:click="openModal"
                        class="flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-800 focus:bg-gray-700 dark:focus:bg-gray-800 active:bg-gray-900 dark:active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Add Category
                    </button>
                </div>

                <div class="mb-4">
                    <input type="text" wire:model.debounce.300ms="search" placeholder="Search categories..."
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                </div>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th
                                class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Name</th>
                            <th
                                class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Type</th>
                            <th
                                class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($categories as $category)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap text-center">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $category->name }}
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-no-wrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    {{ $category->type }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-center">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $category->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $category->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm font-medium text-center">
                                    <button wire:click="edit({{ $category->id }})"
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-600 mr-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $category->id }})"
                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-600"
                                        onclick="return confirm('Are you sure you want to delete this category?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div x-data="{ open: false }" x-show="open" @open-modal.window="open = true" @close-modal.window="open = false"
        x-on:livewire:load="$watch('showModal', value => { open = value })" x-cloak
        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div x-show="open" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">

                <!-- Modal content -->
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <!-- Close button -->
                    <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
                        <button @click="open = false" wire:click="closeModal" type="button"
                            class="text-white hover:text-gray-200 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>

                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <div class="bg-gray-900 -mx-4 -mt-5 sm:-mx-6 sm:-mt-6 px-4 py-4 sm:px-6 rounded-t-lg">
                                <h3 class="text-lg leading-6 font-medium text-white text-center" id="modal-title">
                                    {{ $isEditing ? 'Edit Category' : 'Add New Category' }}
                                </h3>
                            </div>

                            <div class="mt-6 space-y-4" x-data="formValidation(
                                @js($name),
                                @js($type),
                                @js($description),
                                @js($status)
                            )">
                                <!-- Name -->
                                <div>
                                    <x-label for="name" value="Name" />
                                    <x-input id="name" type="text" class="mt-1 block w-full"
                                        x-model="form.name" @input="validateField('name')" wire:model="name" />
                                    <span x-show="errors.name" x-text="errors.name"
                                        class="text-red-500 text-xs mt-1"></span>
                                </div>

                                <!-- Type -->
                                <div>
                                    <x-label for="type" value="Type" />
                                    <select id="type" x-model="form.type" @change="validateField('type')"
                                        wire:model="type"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Select Type</option>
                                        <option value="Roof Repair">Roof Repair</option>
                                        <option value="New Roof">New Roof</option>
                                        <option value="Storm Damage">Storm Damage</option>
                                        <option value="Mold Remediation">Mold Remediation</option>
                                        <option value="Mitigation">Mitigation</option>
                                        <option value="Tarp">Tarp</option>
                                        <option value="ReTarp">ReTarp</option>
                                        <option value="Rebuild">Rebuild</option>
                                        <option value="Roof Paint">Roof Paint</option>
                                    </select>
                                    <span x-show="errors.type" x-text="errors.type"
                                        class="text-red-500 text-xs mt-1"></span>
                                </div>

                                <!-- Description -->
                                <div>
                                    <x-label for="description" value="Description" />
                                    <textarea id="description" x-model="form.description" @input="validateField('description')" wire:model="description"
                                        rows="3"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
                                    <span x-show="errors.description" x-text="errors.description"
                                        class="text-red-500 text-xs mt-1"></span>
                                </div>

                                <!-- Status -->
                                <div>
                                    <x-label for="status" value="Status" />
                                    <select id="status" x-model="form.status" @change="validateField('status')"
                                        wire:model="status"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Select Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    <span x-show="errors.status" x-text="errors.status"
                                        class="text-red-500 text-xs mt-1"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button
                        @click.prevent="if(validateForm()) { $wire.set('name', form.name); $wire.set('type', form.type); $wire.set('description', form.description); $wire.set('status', form.status); $wire.{{ $isEditing ? 'update' : 'create' }}(); }"
                        wire:loading.attr="disabled" x-data="{ loading: false }"
                        @click="if(validateForm()) loading = true" @notification.window="loading = false"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-900 dark:bg-gray-800 text-base font-medium text-white hover:bg-gray-800 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:w-auto sm:text-sm">
                        <span x-show="!loading">
                            {{ $isEditing ? 'Update' : 'Create' }}
                        </span>
                        <span x-show="loading" class="inline-flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="fixed inset-0 z-50 overflow-y-auto" style="display: none" x-data="{ show: false, category: null }" x-show="show"
        x-on:delete-confirmation.window="show = true; category = $event.detail">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Header -->
                <div class="bg-red-600 px-4 py-3 sm:px-6 flex justify-between items-center">
                    <div class="flex-grow text-center">
                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                            Confirm Delete
                        </h3>
                    </div>
                    <button @click="show = false" class="text-white hover:text-gray-200">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900"
                                x-text="'Delete Category: ' + (category ? category.name : '')"></h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to delete this service category?
                                    This action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="$wire.delete(category.id); show = false;" type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button @click="show = false" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        function formValidation(initialName = '', initialType = '', initialDescription = '', initialStatus = 'active') {
            return {
                form: {
                    name: initialName,
                    type: initialType,
                    description: initialDescription,
                    status: initialStatus
                },
                errors: {
                    name: '',
                    type: '',
                    description: '',
                    status: ''
                },
                validateField(field) {
                    this.errors[field] = '';

                    switch (field) {
                        case 'name':
                            if (!this.form.name) {
                                this.errors.name = 'The name is required';
                                return false;
                            } else if (this.form.name.length < 3) {
                                this.errors.name = 'The name must be at least 3 characters';
                                return false;
                            }
                            return true;
                        case 'type':
                            if (!this.form.type) {
                                this.errors.type = 'Please select a type';
                                return false;
                            }
                            return true;
                        case 'description':
                            if (!this.form.description) {
                                this.errors.description = 'The description is required';
                                return false;
                            } else if (this.form.description.length < 10) {
                                this.errors.description = 'The description must be at least 10 characters';
                                return false;
                            }
                            return true;
                        case 'status':
                            if (!this.form.status) {
                                this.errors.status = 'Please select a status';
                                return false;
                            }
                            return true;
                    }
                },
                validateForm() {
                    let isValid = true;

                    if (!this.validateField('name')) isValid = false;
                    if (!this.validateField('type')) isValid = false;
                    if (!this.validateField('description')) isValid = false;
                    if (!this.validateField('status')) isValid = false;

                    return isValid;
                },
                init() {
                    // Inicializamos las validaciones
                    this.$watch('form.name', value => this.validateField('name'));
                    this.$watch('form.type', value => this.validateField('type'));
                    this.$watch('form.description', value => this.validateField('description'));
                    this.$watch('form.status', value => this.validateField('status'));
                }
            }
        }

        document.addEventListener('livewire:initialized', () => {
            @this.on('confirmDelete', (categoryData) => {
                window.dispatchEvent(new CustomEvent('delete-confirmation', {
                    detail: categoryData
                }));
            });
        });
    </script>
</div>
