<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
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
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
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
                        Add User
                    </button>
                </div>

                <div class="mb-4">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users..."
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                </div>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer"
                                wire:click="sort('name')">
                                Name
                                @if ($sortField === 'name')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer"
                                wire:click="sort('last_name')">
                                Last Name
                                @if ($sortField === 'last_name')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer"
                                wire:click="sort('email')">
                                Email
                                @if ($sortField === 'email')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th
                                class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Phone
                            </th>
                            <th
                                class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap text-center">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-no-wrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    {{ $user->last_name }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-no-wrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    {{ $user->email }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-no-wrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    <a href="tel:{{ $user->phone }}">
                                        {{ \App\Helpers\PhoneHelper::format($user->phone) }}</a>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm font-medium text-center">
                                    <button wire:click="edit('{{ $user->uuid }}')"
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-600 mr-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete('{{ $user->uuid }}')"
                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-4 text-center" colspan="5">No users available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $users->links() }}
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
                    <form wire:key="user-form-{{ $uuid ?? 'create' }}">
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
                                <!-- Campos del formulario -->
                                <div class="mb-4">
                                    <label for="name"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Name:</label>
                                    <input type="text" x-model="form.name"
                                        @input="
                                            let words = $event.target.value.toLowerCase().split(' ');
                                            words = words.map(word => word.charAt(0).toUpperCase() + word.slice(1));
                                            $event.target.value = words.join(' ');
                                            validateField('name');
                                        "
                                        wire:model="name" id="name"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <span x-show="errors.name" x-text="errors.name"
                                        class="text-red-500 text-xs mt-1"></span>
                                    @error('name')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="last_name"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Last
                                        Name:</label>
                                    <input type="text" x-model="form.last_name"
                                        @input="validateField('last_name')" wire:model="last_name" id="last_name"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <span x-show="errors.last_name" x-text="errors.last_name"
                                        class="text-red-500 text-xs mt-1"></span>
                                    @error('last_name')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4" style="display: none;">
                                    <label for="username"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Username:</label>
                                    <input type="text" x-model="form.username" @input="validateField('username')"
                                        wire:model="username" id="username"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <span x-show="errors.username" x-text="errors.username"
                                        class="text-red-500 text-xs mt-1"></span>
                                    @error('username')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <input type="hidden" wire:model="date_of_birth" id="date_of_birth">

                                <div class="mb-4">
                                    <label for="email"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Email:</label>
                                    <input type="email" x-model="form.email" @input="validateField('email')"
                                        wire:model="email" id="email"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <span x-show="errors.email" x-text="errors.email"
                                        class="text-red-500 text-xs mt-1"></span>
                                    @error('email')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
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
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="address"
                                        class="block text-gray-700 text-sm font-bold mb-2">Address:</label>
                                    <input type="text" wire:model="address" id="address"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('address')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="zip_code" class="block text-gray-700 text-sm font-bold mb-2">Zip
                                        Code:</label>
                                    <input type="text" wire:model="zip_code" id="zip_code"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('zip_code')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="city"
                                        class="block text-gray-700 text-sm font-bold mb-2">City:</label>
                                    <input type="text" wire:model="city" id="city"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('city')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="country"
                                        class="block text-gray-700 text-sm font-bold mb-2">Country:</label>
                                    <input type="text" wire:model="country" id="country"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('country')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="gender"
                                        class="block text-gray-700 text-sm font-bold mb-2">Gender:</label>
                                    <select wire:model="gender" id="gender"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Select</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('gender')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4" style="display: none;">
                                    <input type="hidden" wire:model="latitude" id="latitude">
                                    @error('latitude')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4" style="display: none;">
                                    <input type="hidden" wire:model="longitude" id="longitude">
                                    @error('longitude')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" wire:click="{{ $modalAction }}" x-data="{ isSubmitting: false }"
                                x-on:click="if(validateForm()) { isSubmitting = true }"
                                @validation-failed.window="isSubmitting = false"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-900 dark:bg-gray-800 text-base font-medium text-white hover:bg-gray-700 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:ml-3 sm:w-auto sm:text-sm transition-opacity duration-200"
                                :class="{ 'opacity-50 cursor-not-allowed': isSubmitting }" :disabled="isSubmitting">
                                <svg wire:loading wire:target="store,update"
                                    class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span wire:loading.remove wire:target="store,update">Save</span>
                                <span wire:loading wire:target="store,update">Saving...</span>
                            </button>
                            <button wire:click="closeModal()" type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div class="fixed inset-0 z-50 overflow-y-auto" style="display: none" x-data="{ show: false, user: null }" x-show="show"
        x-on:delete-confirmation.window="show = true; user = $event.detail">
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
                                x-text="'Delete User: ' + (user ? user.name + ' ' + user.last_name : '')"></h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to delete this user? This action
                                    cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="$wire.delete(user.uuid); show = false;" type="button"
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

    <!-- Script para validaciones con Alpine.js -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('confirmDelete', (userData) => {
                window.dispatchEvent(new CustomEvent('delete-confirmation', {
                    detail: userData
                }));
            });

            // Listen for user-edit event
            @this.on('user-edit', () => {
                // This will ensure the form is properly initialized with the user data
                Alpine.store('formData', {
                    refreshData: true
                });
            });
        });
    </script>

    <script>
        function formValidation() {
            return {
                form: {
                    name: '',
                    last_name: '',
                    username: '',
                    date_of_birth: '',
                    email: '',
                    phone: '',
                    address: '',
                    zip_code: '',
                    city: '',
                    country: '',
                    gender: '',
                },
                errors: {
                    name: '',
                    last_name: '',
                    username: '',
                    date_of_birth: '',
                    email: '',
                    phone: '',
                    address: '',
                    zip_code: '',
                    city: '',
                    country: '',
                    gender: '',
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
                        case 'name':
                            if (!this.form.name) {
                                this.errors.name = 'Name is required';
                                return false;
                            }
                            return true;
                        case 'last_name':
                            if (!this.form.last_name) {
                                this.errors.last_name = 'Last name is required';
                                return false;
                            }
                            return true;
                        case 'username':
                            if (!this.form.username) {
                                this.errors.username = 'Username is required';
                                return false;
                            } else if (this.form.username.length < 3) {
                                this.errors.username = 'Username must be at least 3 characters';
                                return false;
                            }
                            return true;
                        case 'email':
                            if (!this.form.email) {
                                this.errors.email = 'Email is required';
                                return false;
                            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) {
                                this.errors.email = 'Please enter a valid email address';
                                return false;
                            }
                            return true;
                        case 'phone':
                            if (this.form.phone && !/^\(\d{3}\)\s\d{3}\s-\s\d{4}$/.test(this.form.phone)) {
                                this.errors.phone = 'Please enter a valid phone number format: (xxx) xxx - xxxx';
                                return false;
                            }
                            return true;
                        case 'address':
                            if (!this.form.address) {
                                this.errors.address = 'Address is required';
                                return false;
                            }
                            return true;
                        case 'zip_code':
                            if (!this.form.zip_code) {
                                this.errors.zip_code = 'Zip code is required';
                                return false;
                            }
                            return true;
                        case 'city':
                            if (!this.form.city) {
                                this.errors.city = 'City is required';
                                return false;
                            }
                            return true;
                        case 'country':
                            if (!this.form.country) {
                                this.errors.country = 'Country is required';
                                return false;
                            }
                            return true;
                        case 'gender':
                            if (!this.form.gender) {
                                this.errors.gender = 'Gender is required';
                                return false;
                            }
                            return true;
                    }
                    return true;
                },
                validateForm() {
                    let isValid = true;

                    if (!this.validateField('name')) isValid = false;
                    if (!this.validateField('last_name')) isValid = false;
                    // Don't validate username on edit since it's hidden and auto-generated
                    if (this.$wire.modalAction === 'store' && !this.validateField('username')) isValid = false;
                    if (!this.validateField('email')) isValid = false;

                    this.validateField('phone');
                    this.validateField('date_of_birth');
                    this.validateField('address');
                    this.validateField('zip_code');
                    this.validateField('city');
                    this.validateField('country');
                    this.validateField('gender');

                    return isValid;
                },
                init() {
                    // Initialize form values from Livewire component
                    this.updateFormValues();

                    // Listen for Livewire events
                    this.$wire.on('user-edit', () => {
                        // Update form values when user-edit event is triggered
                        this.updateFormValues();
                    });

                    // Setup watchers for validation
                    this.$watch('form.name', value => this.validateField('name'));
                    this.$watch('form.last_name', value => this.validateField('last_name'));
                    this.$watch('form.username', value => this.validateField('username'));
                    this.$watch('form.date_of_birth', value => this.validateField('date_of_birth'));
                    this.$watch('form.email', value => this.validateField('email'));
                    this.$watch('form.phone', value => this.validateField('phone'));
                    this.$watch('form.address', value => this.validateField('address'));
                    this.$watch('form.zip_code', value => this.validateField('zip_code'));
                    this.$watch('form.city', value => this.validateField('city'));
                    this.$watch('form.country', value => this.validateField('country'));
                    this.$watch('form.gender', value => this.validateField('gender'));
                },
                updateFormValues() {
                    this.form.name = this.$wire.get('name') || '';
                    this.form.last_name = this.$wire.get('last_name') || '';
                    this.form.username = this.$wire.get('username') || '';
                    this.form.date_of_birth = this.$wire.get('date_of_birth') || '';
                    this.form.email = this.$wire.get('email') || '';
                    this.form.phone = this.$wire.get('phone') || '';
                    this.form.address = this.$wire.get('address') || '';
                    this.form.zip_code = this.$wire.get('zip_code') || '';
                    this.form.city = this.$wire.get('city') || '';
                    this.form.country = this.$wire.get('country') || '';
                    this.form.gender = this.$wire.get('gender') || '';
                }
            };
        }
    </script>
</div>
