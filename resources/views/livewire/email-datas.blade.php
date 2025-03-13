<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <!-- Success and error messages -->
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)"
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

        <!-- Main container -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <!-- Controls and search -->
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 space-y-4 md:space-y-0">
                    <div class="w-full md:w-1/3">
                        <label for="search" class="sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input id="search" wire:model.live.debounce.300ms="search"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-800 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-500 focus:border-blue-300 dark:focus:border-blue-600 focus:ring focus:ring-blue-200 dark:focus:ring-blue-700 focus:ring-opacity-50 sm:text-sm"
                                placeholder="Search" type="search">
                        </div>
                    </div>
                    <div
                        class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                        <!-- Toggle to show deleted emails -->
                        <div class="flex items-center w-full sm:w-auto justify-between sm:justify-start">
                            <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">Show Inactive Emails</span>
                            <button type="button" wire:click="toggleShowDeleted"
                                class="{{ $showDeleted ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700' }} relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span
                                    class="{{ $showDeleted ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
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
                            <button wire:click="openModal"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 dark:bg-indigo-700 hover:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
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
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                                    wire:click="sort('email')">
                                    <div class="flex items-center justify-center">
                                        EMAIL
                                        @if ($sortField === 'email')
                                            @if ($sortDirection === 'asc')
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                                    wire:click="sort('type')">
                                    <div class="flex items-center justify-center">
                                        TYPE
                                        @if ($sortField === 'type')
                                            @if ($sortDirection === 'asc')
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                                    wire:click="sort('phone')">
                                    <div class="flex items-center justify-center">
                                        PHONE
                                        @if ($sortField === 'phone')
                                            @if ($sortDirection === 'asc')
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                                    wire:click="sort('created_at')">
                                    <div class="flex items-center justify-center">
                                        CREATED AT
                                        @if ($sortField === 'created_at')
                                            @if ($sortDirection === 'asc')
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center justify-center">
                                        ACTIONS
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                            @forelse($emailDatas as $emailData)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $emailData->email }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center capitalize">
                                        <div class="text-sm">
                                            @if ($emailData->type === 'collections' || $emailData->type === 'Collections')
                                                <span
                                                    class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-purple-500 text-white">
                                                    <svg class="h-3 w-3 mr-1" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $emailData->type }}
                                                </span>
                                            @elseif($emailData->type === 'info' || $emailData->type === 'Info')
                                                <span
                                                    class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-blue-500 text-white">
                                                    <svg class="h-3 w-3 mr-1" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $emailData->type }}
                                                </span>
                                            @elseif($emailData->type === 'appointment' || $emailData->type === 'Appointment')
                                                <span
                                                    class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-green-500 text-white">
                                                    <svg class="h-3 w-3 mr-1" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $emailData->type }}
                                                </span>
                                            @elseif($emailData->type === 'personal' || $emailData->type === 'Personal')
                                                <span
                                                    class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-orange-500 text-white">
                                                    <svg class="h-3 w-3 mr-1" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $emailData->type }}
                                                </span>
                                            @elseif($emailData->type === 'work' || $emailData->type === 'Work')
                                                <span
                                                    class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-red-500 text-white">
                                                    <svg class="h-3 w-3 mr-1" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                                                            clip-rule="evenodd"></path>
                                                        <path
                                                            d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z">
                                                        </path>
                                                    </svg>
                                                    {{ $emailData->type }}
                                                </span>
                                            @elseif($emailData->type === 'business' || $emailData->type === 'Business')
                                                <span
                                                    class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-indigo-500 text-white">
                                                    <svg class="h-3 w-3 mr-1" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 002 2h-2a2 2 0 01-2 2H4a2 2 0 01-2-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $emailData->type }}
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-gray-500 text-white">
                                                    <svg class="h-3 w-3 mr-1" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $emailData->type }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $this->formatPhoneForDisplay($emailData->phone) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $emailData->created_at ? $emailData->created_at->format('m/d/Y h:i A') : '' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm font-medium text-center">
                                        <div class="inline-flex items-center justify-center space-x-4">
                                            <!-- Edit button -->
                                            <button wire:click="edit('{{ $emailData->uuid }}')"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 focus:outline-none">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </button>

                                            @if ($emailData->deleted_at)
                                                <!-- Restore button -->
                                                <button
                                                    @click="$dispatch('confirmRestore', { uuid: '{{ $emailData->uuid }}', email: '{{ addslashes($emailData->email) }}' })"
                                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 focus:outline-none">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                    </svg>
                                                </button>
                                            @else
                                                <!-- Delete button -->
                                                <button
                                                    @click="$dispatch('confirmDelete', { uuid: '{{ $emailData->uuid }}', email: '{{ addslashes($emailData->email) }}' })"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 focus:outline-none">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-4 text-center" colspan="5">No emails available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                    <div class="text-sm text-gray-700 dark:text-gray-300 w-full sm:w-auto text-center sm:text-left">
                        Showing {{ $emailDatas->firstItem() ?? 0 }} to {{ $emailDatas->lastItem() ?? 0 }} of
                        {{ $emailDatas->total() }} results
                    </div>
                    <div class="w-full sm:w-auto">
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

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full md:w-3/4 sm:w-full"
                    x-data="formValidation()" x-init="modalAction = '{{ $modalAction }}';
                    // Initialize form values
                    form = {
                        email: '{{ $email }}',
                        phone: '{{ $phone }}',
                        type: '{{ $type }}',
                        user_id: '{{ $user_id }}',
                        description: '{{ $description }}'
                    };
                    
                    // Listen for update events
                    $wire.on('email-edit', (event) => {
                        const data = event.detail;
                        console.log('Received email data:', data);
                    
                        // Update form with new data
                        if (data) {
                            form.email = data.email || '';
                            form.phone = data.phone || '';
                            form.type = data.type || '';
                            form.user_id = data.user_id || '';
                            form.description = data.description || '';
                        }
                    
                        // Sync with Livewire
                        $wire.set('email', form.email);
                        $wire.set('phone', form.phone);
                        $wire.set('type', form.type);
                        $wire.set('user_id', form.user_id);
                        $wire.set('description', form.description);
                    
                        clearErrors();
                    });">
                    <form wire:submit.prevent="{{ $modalAction === 'update' ? 'update' : 'store' }}">
                        <!-- Modal header -->
                        <div class="bg-gray-900 px-4 py-3 sm:px-6">
                            <div class="flex items-center justify-center relative">
                                <h3 class="text-lg font-medium text-white text-center" id="modal-title">
                                    {{ $modalAction === 'update' ? 'Edit Email' : 'Add New Email' }}
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
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Email Field -->
                                <div class="col-span-1 md:col-span-2">
                                    <label for="email"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="email" id="email" x-model="form.email"
                                            @input="$wire.set('email', $event.target.value); validateEmail($event.target.value);"
                                            @blur="checkEmailAvailability($event.target.value)"
                                            class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            :class="{
                                                'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500': errors
                                                    .email
                                            }"
                                            placeholder="Email address">
                                        <div class="text-red-500 text-xs mt-1" x-show="errors.email"
                                            x-text="errors.email"></div>
                                        @error('email')
                                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Type Field -->
                                <div>
                                    <label for="type"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <select id="type" x-model="form.type"
                                            @change="$wire.set('type', $event.target.value); validateType($event.target.value);"
                                            class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            :class="{
                                                'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500': errors
                                                    .type
                                            }">
                                            <option value="">Select Type</option>
                                            <option value="Personal">Personal</option>
                                            <option value="Work">Work</option>
                                            <option value="Business">Business</option>
                                            <option value="Collections">Collections</option>
                                            <option value="Info">Info</option>
                                            <option value="Appointment">Appointment</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <div class="text-red-500 text-xs mt-1" x-show="errors.type"
                                            x-text="errors.type"></div>
                                        @error('type')
                                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Phone Field -->
                                <div>
                                    <label for="phone"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="text" id="phone" x-model="form.phone"
                                            @input="formatPhone($event); validatePhone($event.target.value);"
                                            @blur="checkPhoneAvailability($event.target.value)"
                                            class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            :class="{
                                                'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500': errors
                                                    .phone
                                            }"
                                            placeholder="Phone number (XXX) XXX-XXXX">
                                        <div class="text-red-500 text-xs mt-1" x-show="errors.phone"
                                            x-text="errors.phone"></div>
                                        @error('phone')
                                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- User Field -->
                                <div class="col-span-1 md:col-span-2">
                                    <label for="user_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">User</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <select id="user_id" x-model="form.user_id"
                                            @change="$wire.set('user_id', $event.target.value); validateUserId($event.target.value);"
                                            class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            :class="{
                                                'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500': errors
                                                    .user_id
                                            }">
                                            <option value="">Select User</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}
                                                    {{ $user->last_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-red-500 text-xs mt-1" x-show="errors.user_id"
                                            x-text="errors.user_id"></div>
                                        @error('user_id')
                                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Description Field -->
                                <div class="col-span-1 md:col-span-2">
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                    <div class="mt-1">
                                        <textarea id="description" x-model="form.description" @input="$wire.set('description', $event.target.value);"
                                            rows="3"
                                            class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            :class="{
                                                'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500': errors
                                                    .description
                                            }"
                                            placeholder="Description"></textarea>
                                        <div class="text-red-500 text-xs mt-1" x-show="errors.description"
                                            x-text="errors.description"></div>
                                        @error('description')
                                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                x-on:click.prevent="
                                if (!isSubmitting && validateForm()) {
                                    isSubmitting = true;
                                }"
                                @validation-failed.window="isSubmitting = false" :disabled="isSubmitting"
                                :class="{ 'opacity-75 cursor-not-allowed': isSubmitting }"
                                class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-900 dark:bg-gray-800 text-base font-medium text-white hover:bg-gray-700 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-gray-600">
                                <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span x-show="!isSubmitting">Save</span>
                                <span x-show="isSubmitting">Saving...</span>
                            </button>
                            <button type="button" wire:click="closeModal"
                                class="hidden sm:inline-flex mt-3 w-full sm:w-auto justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-10 sm:text-sm mr-3">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete and Restore Confirmation Modals remain mostly unchanged -->
    <!-- ... -->

    <script>
        function formValidation() {
            return {
                form: {
                    email: @json($email ?? ''),
                    phone: @json($phone ?? ''),
                    type: @json($type ?? ''),
                    user_id: @json($user_id ?? ''),
                    description: @json($description ?? '')
                },
                errors: {},
                isSubmitting: false,
                modalAction: '',

                init() {
                    this.initFormValues();

                    // Listen for validation errors from Livewire
                    document.addEventListener('livewire:initialized', () => {
                        Livewire.on('validationErrors', (errors) => {
                            this.errors = errors;
                        });
                    });

                    // Listen for form data from Livewire
                    window.addEventListener('form-data', (event) => {
                        const data = event.detail;
                        for (const key in data) {
                            if (this.form.hasOwnProperty(key)) {
                                this.form[key] = data[key];
                            }
                        }
                    });

                    // Listen for validation errors from custom event
                    window.addEventListener('validation-errors', (event) => {
                        this.errors = event.detail;
                    });
                },

                initFormValues() {
                    this.form = {
                        email: this.$wire.email || '',
                        phone: this.$wire.phone || '',
                        type: this.$wire.type || '',
                        user_id: this.$wire.user_id || '',
                        description: this.$wire.description || ''
                    };
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

                        // Validate the resulting format
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

                    // Always validate complete format after any changes
                    if (value && value.length > 0 && !/^\(\d{3}\) \d{3}-\d{4}$/.test(value)) {
                        this.errors.phone = 'Please enter a valid phone number format: (XXX) XXX-XXXX';
                    } else {
                        this.errors.phone = '';
                    }
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

                validatePhone(phone) {
                    this.errors.phone = '';
                    if (!phone) return true; // Phone is optional

                    // Check for complete phone number format
                    if (!/^\(\d{3}\) \d{3}-\d{4}$/.test(phone)) {
                        this.errors.phone = 'Please enter a valid phone number format: (XXX) XXX-XXXX';
                        return false;
                    }
                    return true;
                },

                validateType(type) {
                    this.errors.type = '';
                    if (!type) {
                        this.errors.type = 'Type is required';
                        return false;
                    }
                    return true;
                },

                validateUserId(userId) {
                    this.errors.user_id = '';
                    if (!userId) {
                        this.errors.user_id = 'User is required';
                        return false;
                    }
                    return true;
                },

                checkEmailAvailability(email) {
                    if (!this.validateEmail(email)) return;

                    // Only check availability if email is valid
                    this.$wire.checkEmailExists(email).then(exists => {
                        if (exists) {
                            this.errors.email = 'This email is already in use';
                        }
                    });
                },

                checkPhoneAvailability(phone) {
                    if (!this.validatePhone(phone)) return;
                    if (!phone) return; // Phone is optional

                    // Only check availability if phone is valid
                    this.$wire.checkPhoneExists(phone).then(exists => {
                        if (exists) {
                            this.errors.phone = 'This phone number is already in use';
                        }
                    });
                },

                clearErrors() {
                    this.errors = {};
                },

                validateForm() {
                    let isValid = true;

                    // Validate required fields
                    if (!this.validateEmail(this.form.email)) isValid = false;
                    if (!this.validateType(this.form.type)) isValid = false;
                    if (!this.validateUserId(this.form.user_id)) isValid = false;
                    if (this.form.phone && !this.validatePhone(this.form.phone)) isValid = false;

                    // Sync with Livewire if valid
                    if (isValid) {
                        this.syncToLivewire();
                    }

                    return isValid;
                },

                syncToLivewire() {
                    if (this.modalAction === 'update') {
                        this.$wire.update();
                    } else {
                        this.$wire.store();
                    }
                }
            };
        }
    </script>
</div>
