<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    NRO
                </th>
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                    wire:click="sort('company_name')">
                    <div class="flex items-center justify-center">
                        COMPANY NAME
                        @if ($sortField === 'company_name')
                            @if ($sortDirection === 'asc')
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </div>
                </th>
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                    wire:click="sort('name')">
                    <div class="flex items-center justify-center">
                        CEO
                        @if ($sortField === 'name')
                            @if ($sortDirection === 'asc')
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </div>
                </th>
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                    wire:click="sort('email')">
                    <div class="flex items-center justify-center">
                        EMAIL
                        @if ($sortField === 'email')
                            @if ($sortDirection === 'asc')
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
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
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </div>
                </th>
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                    wire:click="sort('address')">
                    <div class="flex items-center justify-center">
                        ADDRESS
                        @if ($sortField === 'address')
                            @if ($sortDirection === 'asc')
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
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
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </div>
                </th>
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                    wire:click="sort('deleted_at')">
                    <div class="flex items-center justify-center">
                        STATUS
                        @if ($sortField === 'deleted_at')
                            @if ($sortDirection === 'asc')
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </div>
                </th>
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    ACTIONS
                </th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
            @forelse ($companies as $index => $company)
                <tr class="{{ $company->trashed() ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            {{ $index + 1 }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100 capitalize">
                            {{ $company->company_name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm text-gray-900 dark:text-gray-100 capitalize">
                            {{ $company->name }}</div>
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
                        <div class="text-sm text-gray-900 dark:text-gray-100 capitalize">
                            {{ $company->address }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                        {{ $company->created_at->format('F d, Y h:i A') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                        @if ($company->trashed())
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                Inactive
                            </span>
                        @else
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                Active
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                        <div class="inline-flex items-center justify-center space-x-4">
                            <!-- Edit button -->
                            <button wire:click="edit('{{ $company->uuid }}')"
                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </button>

                            @if ($company->trashed())
                                <!-- Restore button -->
                                <button
                                    @click="window.dispatchEvent(new CustomEvent('restore-confirmation', {
                                    detail: {
                                        id: '{{ $company->uuid }}',
                                        type: 'company',
                                        name: '{{ addslashes($company->company_name) }}'
                                    }
                                }))"
                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                </button>
                            @else
                                <!-- Delete button -->
                                <button
                                    @click="window.dispatchEvent(new CustomEvent('delete-confirmation', {
                                        detail: {
                                            id: '{{ $company->uuid }}',
                                            type: 'company',
                                            name: '{{ addslashes($company->company_name) }}'
                                        }
                                    }))"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                    <td class="px-6 py-4 text-center" colspan="9">No companies available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
