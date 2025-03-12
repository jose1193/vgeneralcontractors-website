<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
                <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </button>
            </div>
        @endif
        @if (session()->has('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </button>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-end mb-4">
                    <button wire:click="create()"
                        class="flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-800 focus:bg-gray-700 dark:focus:bg-gray-800 active:bg-gray-900 dark:active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Add Email
                    </button>
                </div>

                <div class="mb-4">
                    <input type="text" wire:model.debounce.300ms="search" placeholder="Search emails..."
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                </div>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 overflow-x-auto block md:table">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer"
                                wire:click="sort('email')">
                                Email
                                @if ($sortField === 'email')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer"
                                wire:click="sort('type')">
                                Type
                                @if ($sortField === 'type')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer"
                                wire:click="sort('phone')">
                                Phone
                                @if ($sortField === 'phone')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer"
                                wire:click="sort('created_at')">
                                Created At
                                @if ($sortField === 'created_at')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th
                                class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($emailDatas as $emailData)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap text-center">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $emailData->email }}
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-no-wrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    {{ $emailData->type }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-no-wrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    {{ $emailData->phone }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-no-wrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    {{ $emailData->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm font-medium text-center">
                                    <div class="inline-flex items-center justify-center space-x-4">
                                        <button wire:click="edit('{{ $emailData->uuid }}')"
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-600 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button
                                            @click="$dispatch('confirmDelete', { uuid: '{{ $emailData->uuid }}', email: '{{ addslashes($emailData->email) }}' })"
                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-600 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-4 text-center" colspan="5">No email data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $emailDatas->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if ($isOpen)
        <div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <!-- Modal panel -->
                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full md:w-3/4 sm:w-full">
                    <form wire:key="email-form-{{ $uuid ?? 'create' }}">
                        <!-- Modal header -->
                        <div class="bg-gray-900 px-4 py-3 sm:px-6">
                            <div class="flex items-center justify-center relative">
                                <h3 class="text-lg leading-6 font-medium text-white text-center" id="modal-title">
                                    {{ $modalTitle }}
                                </h3>
                                <button wire:click="closeModal()" type="button"
                                    class="absolute right-0 text-white hover:text-gray-200 focus:outline-none">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal body -->
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="formValidation()">
                                <div class="mb-4">
                                    <label for="description"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Description:</label>
                                    <textarea x-model="form.description" @input="validateField('description')" wire:model="description" id="description"
                                        rows="3"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                                    <span x-show="errors.description" x-text="errors.description"
                                        class="text-red-500 text-xs mt-1"></span>
                                    @error('description')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="email"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Email:</label>
                                    <input type="email" x-model="form.email" @input="validateField('email')"
                                        wire:model="email" id="email"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <span x-show="errors.email" x-text="errors.email"
                                        class="text-red-500 text-xs mt-1"></span>
                                    @error('email')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="phone"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Phone:</label>
                                    <input type="text" x-model="form.phone" @input="formatPhone($event)"
                                        wire:model="phone" id="phone"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <span x-show="errors.phone" x-text="errors.phone"
                                        class="text-red-500 text-xs mt-1"></span>
                                    @error('phone')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="type"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Type:</label>
                                    <select x-model="form.type" @change="validateField('type')" wire:model="type"
                                        id="type"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Select</option>
                                        <option value="collections">Collections</option>
                                        <option value="info">Information</option>
                                        <option value="appointment">Appointments</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <span x-show="errors.type" x-text="errors.type"
                                        class="text-red-500 text-xs mt-1"></span>
                                    @error('type')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="user_id"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">User:</label>
                                    <select x-model="form.user_id" @change="validateField('user_id')"
                                        wire:model="user_id" id="user_id"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Select</option>
                                        @foreach (\App\Models\User::all() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}
                                                ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <span x-show="errors.user_id" x-text="errors.user_id"
                                        class="text-red-500 text-xs mt-1"></span>
                                    @error('user_id')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" wire:click="{{ $modalAction }}" x-data="{ isSubmitting: false }"
                                x-on:click="isSubmitting = true" @validation-failed.window="isSubmitting = false"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-900 dark:bg-gray-800 text-base font-medium text-white hover:bg-gray-700 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:ml-3 sm:w-auto sm:text-sm transition-opacity duration-200"
                                :class="{ 'opacity-50 cursor-not-allowed': isSubmitting }" :disabled="isSubmitting">
                                <svg wire:loading wire:target="{{ $modalAction }}"
                                    class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span wire:loading.remove wire:target="{{ $modalAction }}">Save</span>
                                <span wire:loading wire:target="{{ $modalAction }}">Saving...</span>
                            </button>
                            <button wire:click="closeModal()" type="button" wire:loading.attr="disabled"
                                wire:target="{{ $modalAction }}"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-opacity duration-200"
                                wire:loading.class="opacity-50 cursor-not-allowed" wire:target="{{ $modalAction }}">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div x-data="{ showDeleteModal: false, emailToDelete: null, isDeleting: false }" x-init="window.addEventListener('confirmDelete', event => {
        showDeleteModal = true;
        emailToDelete = event.detail;
    });
    window.addEventListener('emailDeleted', () => {
        showDeleteModal = false;
        isDeleting = false;
        emailToDelete = null;
    });" x-show="showDeleteModal" x-cloak
        class="fixed inset-0 overflow-y-auto z-50">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-show="showDeleteModal"
                @click="showDeleteModal = false; emailToDelete = null;">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full md:w-3/4 sm:w-full"
                x-show="showDeleteModal">
                <div class="bg-red-600 px-4 py-3">
                    <div class="flex items-center justify-center">
                        <h3 class="text-lg font-medium text-white text-center" id="modal-title">Confirm Delete</h3>
                        <button @click="showDeleteModal = false; emailToDelete = null;"
                            class="absolute right-4 text-white hover:text-gray-200">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
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
                                x-text="'Delete Email: ' + (emailToDelete ? emailToDelete.email : '')">
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to delete this email? This
                                    action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                        @click="if(!isDeleting && emailToDelete) {
                        isDeleting = true;
                        $wire.call('delete', emailToDelete.uuid).then((response) => {
                            showDeleteModal = false;
                            emailToDelete = null;
                            isDeleting = false;
                        }).catch((error) => {
                            isDeleting = false;
                            console.error('Error deleting email:', error);
                        });
                    }"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-opacity duration-200"
                        :class="{ 'opacity-50 cursor-not-allowed': isDeleting }" :disabled="isDeleting">
                        <svg x-show="isDeleting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span x-text="isDeleting ? 'Deleting...' : 'Delete'"></span>
                    </button>
                    <button type="button" @click="showDeleteModal = false; emailToDelete = null;"
                        :disabled="isDeleting"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-opacity duration-200"
                        :class="{ 'opacity-50 cursor-not-allowed': isDeleting }">
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

        /* Add responsive table styles */
        @media (max-width: 768px) {
            .overflow-x-auto {
                max-width: 100%;
                overflow-x: auto;
            }
        }
    </style>

    <script>
        function formValidation() {
            return {
                form: {
                    description: '',
                    email: '',
                    phone: '',
                    type: '',
                    user_id: ''
                },
                errors: {
                    description: '',
                    email: '',
                    phone: '',
                    type: '',
                    user_id: ''
                },
                formatPhone(e) {
                    if (e.inputType === 'deleteContentBackward') {
                        let value = this.form.phone.replace(/\D/g, '');
                        value = value.substring(0, value.length - 1);

                        if (value.length === 0) {
                            this.form.phone = '';
                        } else if (value.length <= 3) {
                            this.form.phone = `(${value}`;
                        } else if (value.length <= 6) {
                            this.form.phone = `(${value.substring(0,3)}) ${value.substring(3)}`;
                        } else {
                            this.form.phone = `(${value.substring(0,3)}) ${value.substring(3,6)} - ${value.substring(6)}`;
                        }
                        this.$wire.set('phone', this.form.phone);
                        this.validateField('phone');
                        return;
                    }

                    let value = e.target.value.replace(/\D/g, '').substring(0, 10);
                    if (value.length >= 6) {
                        this.form.phone = `(${value.substring(0,3)}) ${value.substring(3,6)} - ${value.substring(6)}`;
                    } else if (value.length >= 3) {
                        this.form.phone = `(${value.substring(0,3)}) ${value.substring(3)}`;
                    } else if (value.length > 0) {
                        this.form.phone = `(${value}`;
                    }
                    this.$wire.set('phone', this.form.phone);
                    this.validateField('phone');
                },
                validateField(field) {
                    this.errors[field] = '';

                    switch (field) {
                        case 'email':
                            if (!this.form.email) {
                                this.errors.email = 'Email is required';
                                return false;
                            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) {
                                this.errors.email = 'Please enter a valid email address';
                                return false;
                            }
                            return true;
                        case 'type':
                            if (!this.form.type) {
                                this.errors.type = 'Type is required';
                                return false;
                            }
                            return true;
                        case 'user_id':
                            if (!this.form.user_id) {
                                this.errors.user_id = 'User is required';
                                return false;
                            }
                            return true;
                        case 'description':
                            if (this.form.description && this.form.description.length < 5) {
                                this.errors.description = 'Description must be at least 5 characters';
                                return false;
                            }
                            return true;
                        case 'phone':
                            if (this.form.phone && !/^\(\d{3}\)\s\d{3}\s-\s\d{4}$/.test(this.form.phone)) {
                                this.errors.phone = 'Please enter a valid phone number format: (xxx) xxx - xxxx';
                                return false;
                            }
                            return true;
                    }
                    return true;
                },
                validateForm() {
                    let isValid = true;

                    if (!this.validateField('email')) isValid = false;
                    if (!this.validateField('type')) isValid = false;
                    if (!this.validateField('user_id')) isValid = false;
                    if (!this.validateField('description')) isValid = false;
                    if (!this.validateField('phone')) isValid = false;

                    return isValid;
                },
                init() {
                    this.form.description = this.$wire.get('description') || '';
                    this.form.email = this.$wire.get('email') || '';
                    this.form.phone = this.$wire.get('phone') || '';
                    this.form.type = this.$wire.get('type') || '';
                    this.form.user_id = this.$wire.get('user_id') || '';

                    // Escuchar el evento de edición para actualizar los valores del formulario
                    this.$wire.on('email-edit', (data) => {
                        if (data) {
                            this.form.description = data.description || '';
                            this.form.email = data.email || '';
                            this.form.phone = data.phone || '';
                            this.form.type = data.type || '';
                            this.form.user_id = data.user_id || '';
                        } else {
                            this.form.description = this.$wire.get('description') || '';
                            this.form.email = this.$wire.get('email') || '';
                            this.form.phone = this.$wire.get('phone') || '';
                            this.form.type = this.$wire.get('type') || '';
                            this.form.user_id = this.$wire.get('user_id') || '';
                        }
                    });

                    this.$watch('form.description', value => this.validateField('description'));
                    this.$watch('form.email', value => this.validateField('email'));
                    this.$watch('form.phone', value => this.validateField('phone'));
                    this.$watch('form.type', value => this.validateField('type'));
                    this.$watch('form.user_id', value => this.validateField('user_id'));
                }
            };
        }
    </script>

    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('confirmDelete', (emailData) => {
                window.dispatchEvent(new CustomEvent('delete-confirmation', {
                    detail: emailData
                }));
            });
        });
    </script>
</div>
