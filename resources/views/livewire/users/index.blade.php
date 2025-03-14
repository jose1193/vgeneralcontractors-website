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
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <!-- Add user button -->
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 space-y-4 md:space-y-0">
                    <x-inputs.input-search />

                    <div
                        class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                        <!-- Toggle to show inactive users -->
                        <x-inputs.toggle-input id="showDeleted" label="Show Inactive Users" :checked="$showDeleted"
                            wire:click="toggleShowDeleted" />

                        <!-- Per page dropdown with better spacing -->
                        <div class="w-full sm:w-32">
                            <x-inputs.select-input id="perPage" wire:model.live="perPage" :options="[
                                '10' => '10 per page',
                                '25' => '25 per page',
                                '50' => '50 per page',
                                '100' => '100 per page',
                            ]" />
                        </div>

                        <div class="w-full sm:w-auto">
                            <x-add-button wire:click="create" text="Add User" />
                        </div>
                    </div>
                </div>

                <!-- Users table -->
                @include('livewire.users.data-table')

                <!-- Pagination -->
                <x-pagination :paginator="$users" />
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <x-modals.form-modal :title="$modalTitle" :isOpen="$isOpen" submitAction="{{ $modalAction }}">
        <div x-data="formValidation()" x-init="modalAction = '{{ $modalAction }}';
        
        // Inicializar valores del formulario según la acción
        if (modalAction === 'store') {
            form = {
                name: '',
                last_name: '',
                email: '',
                phone: '',
                address: '',
                city: '',
                zip_code: '',
                country: '',
                gender: '',
                date_of_birth: '',
                username: '',
                password: '',
                password_confirmation: '',
                send_password_reset: false
            };
        } else {
            form = {
                name: '{{ $name }}',
                last_name: '{{ $last_name }}',
                email: '{{ $email }}',
                phone: '{{ $phone }}',
                address: '{{ $address }}',
                city: '{{ $city }}',
                zip_code: '{{ $zip_code }}',
                country: '{{ $country }}',
                gender: '{{ $gender }}',
                date_of_birth: '{{ $date_of_birth }}',
                username: '{{ $username }}',
                password: '',
                password_confirmation: '',
                send_password_reset: false
            };
        
            console.log('Initializing form with username:', '{{ $username }}');
        }
        
        // Escuchar eventos de actualización
        $wire.on('user-edit', (event) => {
            const data = event.detail;
            console.log('Received user-edit event:', data);
        
            if (!data) return;
        
            // Si la acción es 'store', limpiar el formulario
            if (data.action === 'store') {
                form = {
                    name: '',
                    last_name: '',
                    email: '',
                    phone: '',
                    address: '',
                    city: '',
                    zip_code: '',
                    country: '',
                    gender: '',
                    date_of_birth: '',
                    username: '',
                    password: '',
                    password_confirmation: '',
                    send_password_reset: false
                };
            } else {
                // Actualizar el formulario con los datos recibidos
                form.name = data.name || '';
                form.last_name = data.last_name || '';
                form.email = data.email || '';
                form.phone = data.phone || '';
                form.address = data.address || '';
                form.city = data.city || '';
                form.zip_code = data.zip_code || '';
                form.country = data.country || '';
                form.gender = data.gender || '';
                form.username = data.username || '';
                form.date_of_birth = data.date_of_birth || '';
        
                console.log('Updated form with username:', data.username);
            }
        
            clearErrors();
        });">

            @include('livewire.users.form')
        </div>

        <x-slot name="footer">
            <button type="submit"
                x-on:click="
                if (!isSubmitting && validateForm()) {
                    isSubmitting = true;
                    $wire.{{ $modalAction }}().then(() => {
                        isSubmitting = false;
                    }).catch(() => {
                        isSubmitting = false;
                    });
                }
                "
                @validation-failed.window="isSubmitting = false"
                class="sm:w-auto w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-900 dark:bg-gray-800 text-base font-medium text-white hover:bg-gray-700 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-gray-600"
                x-bind:disabled="isSubmitting" :class="{ 'opacity-75 cursor-not-allowed': isSubmitting }">
                <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span x-show="!isSubmitting">{{ $modalAction === 'store' ? 'Create' : 'Update' }}</span>
                <span x-show="isSubmitting">Saving...</span>
            </button>
            <button type="button" wire:click="closeModal"
                class="mr-3 hidden lg:inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Cancel
            </button>
        </x-slot>
    </x-modals.form-modal>

    <!-- Delete Confirmation Modal -->
    <div x-data="setupDeleteConfirmation()" x-show="showDeleteModal" x-cloak>
        <x-modals.delete-confirmation title="Delete User" :message="'Are you sure you want to delete the user ' + (userToDelete?.name || '') + '? This action cannot be undone.'">
            <x-slot name="closeButton">
                <button @click="showDeleteModal = false; userToDelete = null;"
                    class="absolute right-0 mr-4 text-white hover:text-gray-200 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </x-slot>

            <x-slot name="footer">
                <button type="button"
                    x-on:click="
                    isDeleting = true;
                    @this.delete(userToDelete.uuid).then(() => {
                        showDeleteModal = false;
                        isDeleting = false;
                    }).catch(error => {
                        console.error('Error deleting user:', error);
                        isDeleting = false;
                    });"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                    :disabled="isDeleting" :class="{ 'opacity-75 cursor-not-allowed': isDeleting }">
                    <span x-show="!isDeleting">Delete</span>
                    <span x-show="isDeleting" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Deleting...
                    </span>
                </button>
                <button type="button" @click="showDeleteModal = false; userToDelete = null;"
                    class="hidden lg:inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    :disabled="isDeleting" :class="{ 'opacity-75 cursor-not-allowed': isDeleting }">
                    Cancel
                </button>
            </x-slot>
        </x-modals.delete-confirmation>
    </div>

    <!-- Restore Confirmation Modal -->
    <div x-data="setupRestoreConfirmation()" x-show="showRestoreModal" x-cloak>
        <x-modals.restore-confirmation title="Restore User" :message="'Are you sure you want to restore the user ' + (userToRestore?.name || '') + '?'">
            <x-slot name="closeButton">
                <button @click="showRestoreModal = false; userToRestore = null;"
                    class="absolute right-0 mr-4 text-white hover:text-gray-200 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </x-slot>

            <x-slot name="footer">
                <button type="button"
                    x-on:click="
                    isRestoring = true;
                    @this.restore(userToRestore.uuid).then(() => {
                        showRestoreModal = false;
                        isRestoring = false;
                    }).catch(error => {
                        console.error('Error restoring user:', error);
                        isRestoring = false;
                    });"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                    :disabled="isRestoring" :class="{ 'opacity-75 cursor-not-allowed': isRestoring }">
                    <span x-show="!isRestoring">Restore</span>
                    <span x-show="isRestoring" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Restoring...
                    </span>
                </button>
                <button type="button" @click="showRestoreModal = false; userToRestore = null;"
                    class="hidden lg:inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    :disabled="isRestoring" :class="{ 'opacity-75 cursor-not-allowed': isRestoring }">
                    Cancel
                </button>
            </x-slot>
        </x-modals.restore-confirmation>
    </div>

    <!-- Keyboard shortcuts help -->
    <div id="keyboard-shortcuts" class="fixed inset-0 bg-black bg-opacity-60 z-50 items-center justify-center hidden"
        x-data="{ show: false }" x-show="show" x-on:keydown.escape.window="show = false">
        <div class="bg-white rounded-lg p-8 max-w-2xl w-full">
            <h2 class="text-xl font-bold mb-4">Keyboard Shortcuts</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex items-center">
                    <span class="px-2 py-1 border rounded mr-3 text-sm font-mono bg-gray-100">n</span>
                    <span>Create new user</span>
                </div>
                <div class="flex items-center">
                    <span class="px-2 py-1 border rounded mr-3 text-sm font-mono bg-gray-100">f</span>
                    <span>Focus search box</span>
                </div>
                <div class="flex items-center">
                    <span class="px-2 py-1 border rounded mr-3 text-sm font-mono bg-gray-100">Esc</span>
                    <span>Close modal or clear search</span>
                </div>
                <div class="flex items-center">
                    <span class="px-2 py-1 border rounded mr-3 text-sm font-mono bg-gray-100">Del</span>
                    <span>Delete selected user</span>
                </div>
                <div class="flex items-center">
                    <span class="px-2 py-1 border rounded mr-3 text-sm font-mono bg-gray-100">r</span>
                    <span>Toggle show deleted users</span>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button @click="show = false" class="px-4 py-2 bg-gray-200 rounded">Close</button>
            </div>
        </div>
    </div>

    <style>
        .dot {
            transition: transform 0.3s ease-in-out;
        }

        input:checked~.dot {
            transform: translateX(100%);
        }

        input:checked~.block {
            background-color: #4F46E5;
        }
    </style>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('refreshComponent', () => {
                // Refresh the component
                @this.dispatch('$refresh');
            });

            Livewire.on('closeModal', () => {
                // Close the modal
                document.getElementById('closeModalButton').click();
            });

            // Listen for validation errors from Livewire
            Livewire.on('validationErrors', (errors) => {
                // Dispatch a custom event to handle validation errors in Alpine.js
                window.dispatchEvent(new CustomEvent('validation-errors', {
                    detail: errors
                }));
            });

            // Listen for form data from Livewire
            Livewire.on('formData', (data) => {
                // Dispatch a custom event to handle form data in Alpine.js
                window.dispatchEvent(new CustomEvent('form-data', {
                    detail: data
                }));
            });

            @this.on('confirmDelete', (userData) => {
                window.dispatchEvent(new CustomEvent('delete-confirmation', {
                    detail: userData
                }));
            });
        });
    </script>
</div>
