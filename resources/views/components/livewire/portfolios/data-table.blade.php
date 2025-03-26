<div class="overflow-x-auto mt-4">
    <table class="min-w-full bg-white dark:bg-gray-700 rounded-lg overflow-hidden">
        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
            <tr>
                <th class="w-20 py-3 px-4 text-left">
                    <a href="#" wire:click.prevent="sort('id')" class="flex items-center">
                        ID
                        @if ($sortField === 'id')
                            @if ($sortDirection === 'asc')
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </a>
                </th>
                <th class="w-32 py-3 px-4 text-left">
                    <span>Image</span>
                </th>
                <th class="py-3 px-4 text-left">
                    <a href="#" wire:click.prevent="sort('title')" class="flex items-center">
                        Title
                        @if ($sortField === 'title')
                            @if ($sortDirection === 'asc')
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </a>
                </th>
                <th class="py-3 px-4 text-left">
                    <a href="#" wire:click.prevent="sort('project_type_id')" class="flex items-center">
                        Project Type
                        @if ($sortField === 'project_type_id')
                            @if ($sortDirection === 'asc')
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </a>
                </th>
                <th class="py-3 px-4 text-left">
                    <a href="#" wire:click.prevent="sort('service_category_id')" class="flex items-center">
                        Service Category
                        @if ($sortField === 'service_category_id')
                            @if ($sortDirection === 'asc')
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </a>
                </th>

                <th class="py-3 px-4 text-left">
                    <a href="#" wire:click.prevent="sort('created_at')" class="flex items-center">
                        Created At
                        @if ($sortField === 'created_at')
                            @if ($sortDirection === 'asc')
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </a>
                </th>
                <th class="py-3 px-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @forelse($portfolios as $portfolio)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="py-3 px-4">{{ $portfolio->id }}</td>
                    <td class="py-3 px-4">
                        @if ($portfolio->image)
                            <img src="{{ asset($portfolio->image) }}" alt="{{ $portfolio->title }}"
                                class="w-20 h-20 object-cover rounded">
                        @else
                            <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                <span class="text-gray-500">No Image</span>
                            </div>
                        @endif
                    </td>
                    <td class="py-3 px-4">{{ $portfolio->title }}</td>
                    <td class="py-3 px-4">{{ $portfolio->projectType->name ?? 'N/A' }}</td>
                    <td class="py-3 px-4">{{ $portfolio->serviceCategory->category ?? 'N/A' }}</td>
                    <td class="py-3 px-4">
                        <span
                            class="px-2 py-1 rounded text-xs {{ $portfolio->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($portfolio->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4">{{ $portfolio->created_at->format('M d, Y') }}</td>
                    <td class="py-3 px-4 text-center">
                        @if (!$portfolio->deleted_at)
                            <div class="flex items-center justify-center space-x-2">
                                <button wire:click="edit({{ $portfolio->id }})"
                                    class="text-blue-600 hover:text-blue-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    wire:click="$dispatch('openDeleteConfirmation', {
                                        'id': {{ $portfolio->id }},
                                        'name': '{{ $portfolio->title }}',
                                        'message': 'Are you sure you want to delete this portfolio item?'
                                    })"
                                    class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        @else
                            <button
                                wire:click="$dispatch('openRestoreConfirmation', {
                                    'id': '{{ $portfolio->uuid }}',
                                    'name': '{{ $portfolio->title }}',
                                    'message': 'Are you sure you want to restore this portfolio item?'
                                })"
                                class="text-green-600 hover:text-green-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="py-6 px-4 text-center text-gray-500 dark:text-gray-400">
                        No portfolio items found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
