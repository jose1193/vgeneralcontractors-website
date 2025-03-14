<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <!-- Success and error messages -->
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
                <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </button>
            </div>
        @endif
        @if (session()->has('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </button>
            </div>
        @endif

        <!-- Main container -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <!-- Controls: Search, Toggle Deleted, Per Page, Add Company -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 space-y-4 md:space-y-0">
                    <div class="w-full md:w-1/3">
                        <label for="search" class="sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input id="search" wire:model.live.debounce.300ms="search"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-800 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-500 focus:border-blue-300 dark:focus:border-blue-600 focus:ring focus:ring-blue-200 dark:focus:ring-blue-700 focus:ring-opacity-50 sm:text-sm"
                                placeholder="Search companies..." type="search">
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                        <!-- Toggle to show deleted companies -->
<div class="flex items-center w-full sm:w-auto justify-between sm:justify-start">
    <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">Show Inactive Companies</span>
    <button type="button" wire:click="toggleShowDeleted"
        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 focus:ring-offset-2"
        :class="{ 'bg-blue-600': {{ $showDeleted ? 'true' : 'false' }}, 'bg-gray-200 dark:bg-gray-700': !{{ $showDeleted ? 'true' : 'false' }} }">
        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out flex items-center justify-center"
            :class="{ 'translate-x-5': {{ $showDeleted ? 'true' : 'false' }}, 'translate-x-0': !{{ $showDeleted ? 'true' : 'false' }} }">
            <svg x-show="{{ $showDeleted ? 'true' : 'false' }}" class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg x-show="!{{ $showDeleted ? 'true' : 'false' }}" class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </span>
    </button>
</div>

                        <!-- Per page dropdown -->
                        <div class="w-full sm:w-32">
                            <select wire:model.live="perPage"
                                class="block w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring focus:ring-blue-200 dark:focus:ring-blue-700 focus:ring-opacity-50 focus:border-blue-300 dark:focus:border-blue-600 sm:text-sm">
                                <option value="10">10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                                <option value="100">100 per page</option>
                            </select>
                        </div>

                        <!-- Add company button -->
                        <div class="w-full sm:w-auto">
                            <button wire:click="create()"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 dark:bg-indigo-700 hover:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Company
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Companies table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sort('company_name')">
                                    <div class="flex items-center justify-center">
                                        COMPANY NAME
                                        @if ($sortField === 'company_name')
                                            @if ($sortDirection === 'asc')
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sort('name')">
                                    <div class="flex items-center justify-center">
                                        CEO
                                        @if ($sortField === 'name')
                                            @if ($sortDirection === 'asc')
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sort('email')">
                                    <div class="flex items-center justify-center">
                                        EMAIL
                                        @if ($sortField === 'email')
                                            @if ($sortDirection === 'asc')
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sort('phone')">
                                    <div class="flex items-center justify-center">
                                        PHONE
                                        @if ($sortField === 'phone')
                                            @if ($sortDirection === 'asc')
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sort('address')">
                                    <div class="flex items-center justify-center">
                                        ADDRESS
                                        @if ($sortField === 'address')
                                            @if ($sortDirection === 'asc')
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sort('created_at')">
                                    <div class="flex items-center justify-center">
                                        CREATED AT
                                        @if ($sortField === 'created_at')
                                            @if ($sortDirection === 'asc')
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sort('deleted_at')">
                                    <div class="flex items-center justify-center">
                                        STATUS
                                        @if ($sortField === 'deleted_at')
                                            @if ($sortDirection === 'asc')
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    ACTIONS
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                            @forelse ($companies as $company)
                                <tr class="{{ $company->trashed() ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100 capitalize">
                                            {{ $company->company_name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900 dark:text-gray-100 capitalize">{{ $company->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $company->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            <a href="tel:{{ $company->phone }}">
                                                {{ \App\Helpers\PhoneHelper::format($company->phone) }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900 dark:text-gray-100 capitalize">{{ $company->address }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                        {{ $company->created_at->format('F d, Y h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        @if ($company->trashed())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                Deleted
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                Active
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        <div class="inline-flex items-center justify-center space-x-4">
                                            <!-- Edit button -->
                                            <button wire:click="edit({{ $company->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 focus:outline-none">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>

                                            @if ($company->trashed())
                                                <!-- Restore button -->
                                                <button @click="window.dispatchEvent(new CustomEvent('restore-confirmation', {detail: {id: {{ $company->id }}, company_name: '{{ addslashes($company->company_name) }}'}}))"
                                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 focus:outline-none">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                </button>
                                            @else
                                                <!-- Delete button -->
                                                <button @click="window.dispatchEvent(new CustomEvent('delete-confirmation', {detail: {id: {{ $company->id }}, company_name: '{{ addslashes($company->company_name) }}'}}))"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 focus:outline-none">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-4 text-center" colspan="8">No companies available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                    <div class="text-sm text-gray-700 dark:text-gray-300 w-full sm:w-auto text-center sm:text-left">
                        Showing {{ $companies->firstItem() ?? 0 }} to {{ $companies->lastItem() ?? 0 }} of {{ $companies->total() }} entries
                    </div>
                    <div class="w-full sm:w-auto flex justify-center sm:justify-end">
                        {{ $companies->links() }}
                    </div>
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
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full md:w-3/4 sm:w-full"
                    x-data="formValidation()" x-init="modalAction = '{{ $modalAction }}';
                    form = {
                        company_name: '{{ $company_name }}',
                        name: '{{ $name }}',
                        email: '{{ $email }}',
                        phone: '{{ $phone }}',
                        address: '{{ $address }}',
                        website: '{{ $website }}',
                        latitude: '{{ $latitude }}',
                        longitude: '{{ $longitude }}'
                    };
                    $wire.on('company-edit', (event) => {
                        const data = event.detail;
                        form.company_name = data.company_name || '';
                        form.name = data.name || '';
                        form.email = data.email || '';
                        form.phone = data.phone || '';
                        form.address = data.address || '';
                        form.website = data.website || '';
                        form.latitude = data.latitude || '';
                        form.longitude = data.longitude || '';
                        $wire.set('company_name', form.company_name);
                        $wire.set('name', form.name);
                        $wire.set('email', form.email);
                        $wire.set('phone', form.phone);
                        $wire.set('address', form.address);
                        $wire.set('website', form.website);
                        $wire.set('latitude', form.latitude);
                        $wire.set('longitude', form.longitude);
                        clearErrors();
                    });">
                    <form wire:submit.prevent="{{ $modalAction }}">
                        <!-- Modal header -->
                        <div class="bg-gray-900 px-4 py-3 sm:px-6">
                            <div class="flex items-center justify-center relative">
                                <h3 class="text-lg font-medium text-white text-center">
                                    {{ $modalTitle }}
                                </h3>
                                <button type="button" wire:click="closeModal"
                                    class="absolute right-0 text-white hover:text-gray-200 focus:outline-none">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal body -->
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label for="company_name" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Company Name:</label>
                                    <input type="text" x-model="form.company_name"
                                        @input="
                                            let words = $event.target.value.toLowerCase().split(' ');
                                            words = words.map(word => word.charAt(0).toUpperCase() + word.slice(1));
                                            $event.target.value = words.join(' ');
                                            $wire.set('company_name', $event.target.value);
                                            validateField('company_name');
                                        "
                                        id="company_name"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        :class="{ 'border-red-500': errors.company_name }" placeholder="Enter company name">
                                    <div class="text-red-500 text-xs mt-1" x-show="errors.company_name" x-text="errors.company_name"></div>
                                   
                                </div>

                                <div class="mb-4">
                                    <label for="name" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">CEO Name:</label>
                                    <input type="text" x-model="form.name"
                                        @input="
                                            let words = $event.target.value.toLowerCase().split(' ');
                                            words = words.map(word => word.charAt(0).toUpperCase() + word.slice(1));
                                            $event.target.value = words.join(' ');
                                            $wire.set('name', $event.target.value);
                                            validateField('name');
                                        "
                                        id="name"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        :class="{ 'border-red-500': errors.name }" placeholder="Enter CEO name">
                                    <div class="text-red-500 text-xs mt-1" x-show="errors.name" x-text="errors.name"></div>
                                    
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Email:</label>
                                    <input type="email" x-model="form.email"
                                        @input="$wire.set('email', $event.target.value); validateEmail($event.target.value);"
                                        @blur="checkEmailAvailability($event.target.value)" id="email"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        :class="{ 'border-red-500': errors.email }" placeholder="Enter email">
                                    <div class="text-red-500 text-xs mt-1" x-show="errors.email" x-text="errors.email"></div>
                                    
                                </div>

                                <div class="mb-4">
                                    <label for="phone" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Phone:</label>
                                    <input type="tel" x-model="form.phone" @input="formatPhone($event);"
                                        @blur="validatePhone($event.target.value); checkPhoneAvailability($event.target.value);"
                                        id="phone"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        :class="{ 'border-red-500': errors.phone }" placeholder="Enter phone (XXX) XXX-XXXX">
                                    <div class="text-red-500 text-xs mt-1" x-show="errors.phone" x-text="errors.phone"></div>
                                    
                                </div>

                                <div class="mb-4">
                                    <label for="address" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Address:</label>
                                    <input type="text" x-model="form.address"
                                        @input="$wire.set('address', $event.target.value); $event.target.value = $event.target.value.toUpperCase(); validateField('address');"
                                        id="address"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase"
                                        :class="{ 'border-red-500': errors.address }" placeholder="Enter address">
                                    <div class="text-red-500 text-xs mt-1" x-show="errors.address" x-text="errors.address"></div>
                                   
                                </div>

                                <div class="mb-4">
                                    <label for="website" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Website:</label>
                                    <input type="url" x-model="form.website"
                                        @input="$wire.set('website', $event.target.value); validateField('website');"
                                        id="website"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        :class="{ 'border-red-500': errors.website }" placeholder="https://www.example.com">
                                    <div class="text-red-500 text-xs mt-1" x-show="errors.website" x-text="errors.website"></div>
                                    <span class="text-xs text-gray-500 mt-1">Enter URL starting with https:// or www.</span>
                                    
                                </div>

                                <input type="hidden" x-model="form.latitude" wire:model="latitude" id="latitude">
                                <input type="hidden" x-model="form.longitude" wire:model="longitude" id="longitude">
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
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
                                class="sm:w-auto w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-900 dark:bg-gray-800 text-base font-medium text-white hover:bg-gray-700 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-gray-600">
                                <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-show="!isSubmitting">{{ $modalAction === 'store' ? 'Create' : 'Update' }}</span>
                                <span x-show="isSubmitting">Saving...</span>
                            </button>
                            <button type="button" wire:click="closeModal"
                                class="mr-3 hidden lg:inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div x-data="{
        showDeleteModal: false,
        companyToDelete: null,
        isDeleting: false,
        init() {
            window.addEventListener('delete-confirmation', (event) => {
                this.companyToDelete = event.detail;
                this.showDeleteModal = true;
            });
            window.addEventListener('companyDeleted', () => {
                this.showDeleteModal = false;
                this.isDeleting = false;
                this.companyToDelete = null;
            });
        }
    }" x-show="showDeleteModal" x-cloak class="fixed inset-0 overflow-y-auto z-50">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-show="showDeleteModal"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full md:w-3/4 sm:w-full"
                x-show="showDeleteModal">
                <div class="bg-red-600 px-4 py-3">
                    <div class="flex items-center justify-center">
                        <h3 class="text-lg font-medium text-white text-center" id="modal-headline">
                            Confirm Deletion
                        </h3>
                        <button @click="showDeleteModal = false; companyToDelete = null;"
                            class="absolute right-0 mr-4 text-white hover:text-gray-200 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-headline">
                                Delete Company
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" x-show="companyToDelete">
                                    Are you sure you want to delete the company <span class="font-bold" x-text="companyToDelete?.company_name"></span>? This action will soft delete the company.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                        x-on:click="
                            isDeleting = true;
                            @this.deleteCompany(companyToDelete.id).then(() => {
                                showDeleteModal = false;
                                isDeleting = false;
                            }).catch(error => {
                                console.error('Error deleting company:', error);
                                isDeleting = false;
                            });
                        "
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        :disabled="isDeleting" :class="{ 'opacity-75 cursor-not-allowed': isDeleting }">
                        <span x-show="!isDeleting">Delete</span>
                        <span x-show="isDeleting" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Deleting...
                        </span>
                    </button>
                    <button type="button" @click="showDeleteModal = false; companyToDelete = null;"
                        class="hidden lg:inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        :disabled="isDeleting" :class="{ 'opacity-75 cursor-not-allowed': isDeleting }">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Restore Confirmation Modal -->
    <div x-data="{
        showRestoreModal: false,
        companyToRestore: null,
        isRestoring: false,
        init() {
            window.addEventListener('restore-confirmation', (event) => {
                this.companyToRestore = event.detail;
                this.showRestoreModal = true;
            });
            window.addEventListener('companyRestored', () => {
                this.showRestoreModal = false;
                this.isRestoring = false;
                this.companyToRestore = null;
            });
        }
    }" x-show="showRestoreModal" x-cloak class="fixed inset-0 overflow-y-auto z-50">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-show="showRestoreModal"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full md:w-3/4 sm:w-full"
                x-show="showRestoreModal">
                <div class="bg-green-600 px-4 py-3">
                    <div class="flex items-center justify-center">
                        <h3 class="text-lg font-medium text-white text-center" id="modal-headline">
                            Confirm Restoration
                        </h3>
                        <button @click="showRestoreModal = false; companyToRestore = null;"
                            class="absolute right-0 mr-4 text-white hover:text-gray-200 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-headline">
                                Restore Company
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" x-show="companyToRestore">
                                    Are you sure you want to restore the company <span class="font-bold" x-text="companyToRestore?.company_name"></span>? This action will reactivate the company.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                        x-on:click="
                            isRestoring = true;
                            @this.restore(companyToRestore.id).then(() => {
                                showRestoreModal = false;
                                isRestoring = false;
                            }).catch(error => {
                                console.error('Error restoring company:', error);
                                isRestoring = false;
                            });
                        "
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                        :disabled="isRestoring" :class="{ 'opacity-75 cursor-not-allowed': isRestoring }">
                        <span x-show="!isRestoring">Restore</span>
                        <span x-show="isRestoring" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Restoring...
                        </span>
                    </button>
                    <button type="button" @click="showRestoreModal = false; companyToRestore = null;"
                        class="mr-3 hidden lg:inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        :disabled="isRestoring" :class="{ 'opacity-75 cursor-not-allowed': isRestoring }">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formValidation() {
            return {
                form: {
                    company_name: '',
                    name: '',
                    email: '',
                    phone: '',
                    address: '',
                    website: '',
                    latitude: '',
                    longitude: ''
                },
                errors: {},
                isSubmitting: false,
                modalAction: '',

                init() {
                    this.form.company_name = this.$wire.company_name || '';
                    this.form.name = this.$wire.name || '';
                    this.form.email = this.$wire.email || '';
                    this.form.phone = this.$wire.phone || '';
                    this.form.address = this.$wire.address || '';
                    this.form.website = this.$wire.website || '';
                    this.form.latitude = this.$wire.latitude || '';
                    this.form.longitude = this.$wire.longitude || '';
                },

                formatPhone(e) {
                    if (!e || !e.target) return;

                    // Handle backspace/delete
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
                            this.form.phone = `(${value.substring(0,3)}) ${value.substring(3,6)}-${value.substring(6)}`;
                        }
                        this.$wire.set('phone', this.form.phone);
                        this.validatePhone(this.form.phone);
                        return;
                    }

                    // Format as user types
                    let value = e.target.value.replace(/\D/g, '').substring(0, 10);
                    if (value.length >= 6) {
                        value = `(${value.substring(0,3)}) ${value.substring(3,6)}-${value.substring(6)}`;
                    } else if (value.length >= 3) {
                        value = `(${value.substring(0,3)}) ${value.substring(3)}`;
                    } else if (value.length > 0) {
                        value = `(${value}`;
                    }

                    e.target.value = value;
                    this.form.phone = value;
                    this.$wire.set('phone', value);
                    this.validatePhone(value);
                },

                validateEmail(email) {
                    this.errors.email = '';
                    if (!email) {
                        this.errors.email = 'Email is required';
                        return false;
                    }

                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        this.errors.email = 'Please enter a valid email address';
                        return false;
                    }
                    return true;
                },

                checkEmailAvailability(email) {
                    if (!this.validateEmail(email)) return;

                    this.$wire.checkEmailExists(email).then(exists => {
                        if (exists) {
                            this.errors.email = 'This email is already in use';
                        }
                    });
                },

                validatePhone(phone) {
                    this.errors.phone = '';
                    if (!phone) {
                        this.errors.phone = 'Phone is required';
                        return false;
                    }

                    if (!/^\(\d{3}\) \d{3}-\d{4}$/.test(phone)) {
                        this.errors.phone = 'Please enter a valid phone number format: (XXX) XXX-XXXX';
                        return false;
                    }
                    return true;
                },

                checkPhoneAvailability(phone) {
                    if (!this.validatePhone(phone)) return;

                    this.$wire.checkPhoneExists(phone).then(exists => {
                        if (exists) {
                            this.errors.phone = 'This phone number is already in use';
                        }
                    });
                },

                validateField(field) {
                    this.errors[field] = '';

                    switch (field) {
                        case 'company_name':
                            if (!this.form.company_name) {
                                this.errors.company_name = 'Company name is required';
                                return false;
                            }
                            return true;
                        case 'name':
                            if (!this.form.name) {
                                this.errors.name = 'CEO name is required';
                                return false;
                            }
                            return true;
                        case 'email':
                            return this.validateEmail(this.form.email);
                        case 'phone':
                            return this.validatePhone(this.form.phone);
                        case 'address':
                            if (!this.form.address) {
                                this.errors.address = 'Address is required';
                                return false;
                            }
                            return true;
                        case 'website':
                            if (!this.form.website) {
                                this.errors.website = 'Website is required';
                                return false;
                            }
                            const urlRegex = /^(https?:\/\/)?([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}(\/.*)?$/;
                            if (!urlRegex.test(this.form.website)) {
                                this.errors.website = 'Please enter a valid URL (e.g., https://www.example.com)';
                                return false;
                            }
                            return true;
                        case 'latitude':
                            if (this.form.latitude && !/^-?([1-8]?[0-9](\.[0-9]+)?|90(\.0+)?)$/.test(this.form.latitude)) {
                                this.errors.latitude = 'Please enter a valid latitude (-90 to 90)';
                                return false;
                            }
                            return true;
                        case 'longitude':
                            if (this.form.longitude && !/^-?((1[0-7][0-9])|([1-9]?[0-9]))(\.[0-9]+)?$/.test(this.form.longitude)) {
                                this.errors.longitude = 'Please enter a valid longitude (-180 to 180)';
                                return false;
                            }
                            return true;
                    }
                    return true;
                },

                validateForm() {
                    let isValid = true;

                    if (!this.validateField('company_name')) isValid = false;
                    if (!this.validateField('name')) isValid = false;
                    if (!this.validateField('email')) isValid = false;
                    if (!this.validateField('phone')) isValid = false;
                    if (!this.validateField('address')) isValid = false;
                    if (!this.validateField('website')) isValid = false;
                    this.validateField('latitude');
                    this.validateField('longitude');

                    if (isValid) {
                        this.syncToLivewire();
                    }

                    return isValid;
                },

                syncToLivewire() {
                    this.$wire.set('company_name', this.form.company_name);
                    this.$wire.set('name', this.form.name);
                    this.$wire.set('email', this.form.email);
                    this.$wire.set('phone', this.form.phone);
                    this.$wire.set('address', this.form.address);
                    this.$wire.set('website', this.form.website);
                    this.$wire.set('latitude', this.form.latitude);
                    this.$wire.set('longitude', this.form.longitude);
                },

                clearErrors() {
                    this.errors = {};
                }
            };
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div>