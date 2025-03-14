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
                <!-- Controls: Search, Toggle Deleted, Per Page, Add Email -->
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
                                placeholder="Search emails..." type="search">
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                        <!-- Toggle to show deleted emails -->
                        <div class="flex items-center w-full sm:w-auto justify-between sm:justify-start">
                            <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">Show Inactive Emails</span>
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

                        <!-- Add email button -->
                        <div class="w-full sm:w-auto">
                            <button wire:click="create()"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 dark:bg-indigo-700 hover:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Email
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Emails table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sort('description')">
                                    <div class="flex items-center justify-center">
                                        DESCRIPTION
                                        @if ($sortField === 'description')
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
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sort('type')">
                                    <div class="flex items-center justify-center">
                                        TYPE
                                        @if ($sortField === 'type')
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
                            @forelse ($emailDatas as $emailData)
                                <tr class="{{ $emailData->trashed() ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900 dark:text-gray-100 capitalize">
                                            {{ $emailData->description ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $emailData->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            <a href="tel:{{ $emailData->phone }}">
                                                {{ \App\Helpers\PhoneHelper::format($emailData->phone) ?? 'N/A' }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900 dark:text-gray-100 capitalize">{{ $emailData->type }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                        {{ $emailData->created_at->format('F d, Y h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
    @if ($emailData->type == 'Collections' || $emailData->type == 'collections')
        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-purple-500 text-white">
            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
            </svg>
            {{ $emailData->type }}
        </span>
    @elseif ($emailData->type == 'Info' || $emailData->type == 'info')
        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-blue-500 text-white">
            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            {{ $emailData->type }}
        </span>
    @elseif ($emailData->type == 'Appointment' || $emailData->type == 'appointment')
        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-green-500 text-white">
            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
            </svg>
            {{ $emailData->type }}
        </span>
    @elseif ($emailData->type == 'Personal' || $emailData->type == 'personal')
        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-yellow-500 text-white">
            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
            {{ $emailData->type }}
        </span>
    @elseif ($emailData->type == 'Work' || $emailData->type == 'work')
        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-orange-500 text-white">
            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3 1h10v2H5V6zm0 4h10v2H5v-2zm0 4h10v2H5v-2z" clip-rule="evenodd"></path>
            </svg>
            {{ $emailData->type }}
        </span>
    @elseif ($emailData->type == 'Business' || $emailData->type == 'business')
        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-red-500 text-white">
            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 4h8a1 1 0 110 2H6a1 1 0 110-2zm0 4h8a1 1 0 110 2H6a1 1 0 110-2z" clip-rule="evenodd"></path>
            </svg>
            {{ $emailData->type }}
        </span>
    @elseif ($emailData->type == 'Other' || $emailData->type == 'other')
        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-gray-500 text-white">
            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
            </svg>
            {{ $emailData->type }}
        </span>
    @else
        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-gray-500 text-white">
            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
            </svg>
            {{ $emailData->type }}
        </span>
    @endif
</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                        @if ($emailData->trashed())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                Inactive
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
                                            <button wire:click="edit('{{ $emailData->uuid }}')" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 focus:outline-none">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>

                                            @if ($emailData->trashed())
                                                <!-- Restore button -->
                                                <button @click="window.dispatchEvent(new CustomEvent('restore-confirmation', {detail: {uuid: '{{ $emailData->uuid }}', email: '{{ addslashes($emailData->email) }}'}}))"
                                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 focus:outline-none">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                </button>
                                            @else
                                                <!-- Delete button -->
                                                <button @click="window.dispatchEvent(new CustomEvent('delete-confirmation', {detail: {uuid: '{{ $emailData->uuid }}', email: '{{ addslashes($emailData->email) }}'}}))"
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
                                    <td class="px-6 py-4 text-center" colspan="7">No emails available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                    <div class="text-sm text-gray-700 dark:text-gray-300 w-full sm:w-auto text-center sm:text-left">
                        Showing {{ $emailDatas->firstItem() ?? 0 }} to {{ $emailDatas->lastItem() ?? 0 }} of {{ $emailDatas->total() }} entries
                    </div>
                    <div class="w-full sm:w-auto flex justify-center sm:justify-end">
                        {{ $emailDatas->links() }}
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
                        description: '{{ $description }}',
                        email: '{{ $email }}',
                        phone: '{{ $phone }}',
                        type: '{{ $type }}',
                        user_id: '{{ $user_id }}'
                    };
                    $wire.on('email-edit', (event) => {
                        const data = event.detail;
                        form.description = data.description || '';
                        form.email = data.email || '';
                        form.phone = data.phone || '';
                        form.type = data.type || '';
                        form.user_id = data.user_id || '';
                        $wire.set('description', form.description);
                        $wire.set('email', form.email);
                        $wire.set('phone', form.phone);
                        $wire.set('type', form.type);
                        $wire.set('user_id', form.user_id);
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
                                    <label for="description" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Description:</label>
                                    <input type="text" x-model="form.description"
                                        @input="$wire.set('description', $event.target.value); validateField('description');"
                                        id="description"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-500 focus:border-blue-300 dark:focus:border-blue-600 focus:ring focus:ring-blue-200 dark:focus:ring-blue-700 focus:ring-opacity-50 sm:text-sm"
                                        :class="{ 'border-red-500': errors.description }" placeholder="Enter description">
                                    <div class="text-red-500 text-xs mt-1" x-show="errors.description" x-text="errors.description"></div>
                                   
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Email:</label>
                                    <input type="email" x-model="form.email"
                                        @input="$wire.set('email', $event.target.value); validateEmail($event.target.value);"
                                        @blur="checkEmailAvailability($event.target.value)" id="email"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-500 focus:border-blue-300 dark:focus:border-blue-600 focus:ring focus:ring-blue-200 dark:focus:ring-blue-700 focus:ring-opacity-50 sm:text-sm"
                                        :class="{ 'border-red-500': errors.email }" placeholder="Enter email">
                                    <div class="text-red-500 text-xs mt-1" x-show="errors.email" x-text="errors.email"></div>
                                   
                                </div>

                                <div class="mb-4">
                                    <label for="phone" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Phone:</label>
                                    <input type="tel" x-model="form.phone" @input="formatPhone($event);"
                                        @blur="validatePhone($event.target.value); checkPhoneAvailability($event.target.value);"
                                        id="phone"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-500 focus:border-blue-300 dark:focus:border-blue-600 focus:ring focus:ring-blue-200 dark:focus:ring-blue-700 focus:ring-opacity-50 sm:text-sm"
                                        :class="{ 'border-red-500': errors.phone }" placeholder="Enter phone (XXX) XXX-XXXX">
                                    <div class="text-red-500 text-xs mt-1" x-show="errors.phone" x-text="errors.phone"></div>
                                   
                                </div>

                                <div class="mb-4">
                                    <label for="type" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Type:</label>
                                    <select x-model="form.type" @change="$wire.set('type', $event.target.value); validateField('type');"
                                        id="type"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-500 focus:border-blue-300 dark:focus:border-blue-600 focus:ring focus:ring-blue-200 dark:focus:ring-blue-700 focus:ring-opacity-50 sm:text-sm"
                                        :class="{ 'border-red-500': errors.type }">
                                        <option value="">Select Type</option>
                                        <option value="Appointment">Appointment</option>
                                        <option value="Info">Info</option>
                                        <option value="Collections">Collections</option>
                                        <option value="Personal">Personal</option>
                                        <option value="Work">Work</option>
                                        <option value="Business">Business</option>
                                        <option value="Other">Other</option>
                                       
                                    </select>
                                    <div class="text-red-500 text-xs mt-1" x-show="errors.type" x-text="errors.type"></div>
                                   
                                </div>

                                <div class="mb-4 col-span-1 md:col-span-2">
                                    <label for="user_id" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">User:</label>
                                    <select x-model="form.user_id" @change="$wire.set('user_id', $event.target.value); validateField('user_id');"
                                        id="user_id"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-500 focus:border-blue-300 dark:focus:border-blue-600 focus:ring focus:ring-blue-200 dark:focus:ring-blue-700 focus:ring-opacity-50 sm:text-sm"
                                        :class="{ 'border-red-500': errors.user_id }">
                                        <option value="">Select User</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-red-500 text-xs mt-1" x-show="errors.user_id" x-text="errors.user_id"></div>
                                   
                                </div>
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
    :disabled="isSubmitting"
    class="sm:w-auto w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-900 dark:bg-gray-800 text-base font-medium text-white hover:bg-gray-700 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-gray-600"
    :class="{ 'opacity-75 cursor-not-allowed': isSubmitting }">
    <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    <span x-show="!isSubmitting">{{ $modalAction === 'store' ? 'Create' : 'Update' }}</span>
    <span x-show="isSubmitting">Saving...</span>
</button>
<button type="button" wire:click="closeModal"
    class="mr-3 hidden lg:inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-600"
    x-bind:disabled="isSubmitting"
    :class="{ 'opacity-75 cursor-not-allowed': isSubmitting }">
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
        emailToDelete: null,
        isDeleting: false,
        init() {
            window.addEventListener('delete-confirmation', (event) => {
                this.emailToDelete = event.detail;
                this.showDeleteModal = true;
            });
            window.addEventListener('emailDeleted', () => {
                this.showDeleteModal = false;
                this.isDeleting = false;
                this.emailToDelete = null;
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
                        <button @click="showDeleteModal = false; emailToDelete = null;"
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
                                Delete Email
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" x-show="emailToDelete">
                                    Are you sure you want to delete the email <span class="font-bold" x-text="emailToDelete?.email"></span>? This action will soft delete the email.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                        x-on:click="
                            isDeleting = true;
                            @this.deleteEmail(emailToDelete.uuid).then(() => {
                                showDeleteModal = false;
                                isDeleting = false;
                            }).catch(error => {
                                console.error('Error deleting email:', error);
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
                    <button type="button" @click="showDeleteModal = false; emailToDelete = null;"
                        class="hidden lg:inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-600"
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
        emailToRestore: null,
        isRestoring: false,
        init() {
            window.addEventListener('restore-confirmation', (event) => {
                this.emailToRestore = event.detail;
                this.showRestoreModal = true;
            });
            window.addEventListener('emailRestored', () => {
                this.showRestoreModal = false;
                this.isRestoring = false;
                this.emailToRestore = null;
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
                        <button @click="showRestoreModal = false; emailToRestore = null;"
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
                                Restore Email
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" x-show="emailToRestore">
                                    Are you sure you want to restore the email <span class="font-bold" x-text="emailToRestore?.email"></span>? This action will reactivate the email.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                        x-on:click="
                            isRestoring = true;
                            @this.restoreEmail(emailToRestore.uuid).then(() => {
                                showRestoreModal = false;
                                isRestoring = false;
                            }).catch(error => {
                                console.error('Error restoring email:', error);
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
                    <button type="button" @click="showRestoreModal = false; emailToRestore = null;"
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
                    description: '',
                    email: '',
                    phone: '',
                    type: '',
                    user_id: ''
                },
                errors: {},
                isSubmitting: false,
                modalAction: '',

                init() {
                    this.form.description = this.$wire.description || '';
                    this.form.email = this.$wire.email || '';
                    this.form.phone = this.$wire.phone || '';
                    this.form.type = this.$wire.type || '';
                    this.form.user_id = this.$wire.user_id || '';
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
                    if (phone && !/^\(\d{3}\) \d{3}-\d{4}$/.test(phone)) {
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
                        case 'description':
                            if (this.form.description && this.form.description.length > 255) {
                                this.errors.description = 'Description must not exceed 255 characters';
                                return false;
                            }
                            return true;
                        case 'email':
                            return this.validateEmail(this.form.email);
                        case 'phone':
                            return this.validatePhone(this.form.phone);
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
                    }
                    return true;
                },

                validateForm() {
                    let isValid = true;

                    if (!this.validateField('description')) isValid = false;
                    if (!this.validateField('email')) isValid = false;
                    if (!this.validateField('phone')) isValid = false;
                    if (!this.validateField('type')) isValid = false;
                    if (!this.validateField('user_id')) isValid = false;

                    if (isValid) {
                        this.syncToLivewire();
                    }

                    return isValid;
                },

                syncToLivewire() {
                    this.$wire.set('description', this.form.description);
                    this.$wire.set('email', this.form.email);
                    this.$wire.set('phone', this.form.phone);
                    this.$wire.set('type', this.form.type);
                    this.$wire.set('user_id', this.form.user_id);
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