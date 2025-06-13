<div class="overflow-x-auto -mx-4 sm:mx-0 sm:rounded-lg">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                @include('components.sort-position', [
                    'field' => 'blog_category_name',
                    'label' => strtoupper(__('blog_category_name')),
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                ])
                @include('components.sort-position', [
                    'field' => 'blog_category_description',
                    'label' => strtoupper(__('description')),
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                ])
                @include('components.sort-position', [
                    'field' => 'created_at',
                    'label' => strtoupper(__('created_at')),
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                ])
                <th scope="col"
                    class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ strtoupper(__('actions')) }}
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
            @forelse ($categories as $category)
                <tr class="{{ $category->trashed() ? 'bg-red-100 dark:bg-red-900/30' : '' }}">
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $category->blog_category_name }}
                        </div>
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ Str::limit($category->blog_category_description, 50) }}
                        </div>
                    </td>
                    <td
                        class="px-3 sm:px-6 py-4 whitespace-no-wrap text-sm text-gray-500 dark:text-gray-400 text-center">
                        {{ $category->created_at->format('F d, Y h:i A') }}
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-no-wrap text-sm font-medium text-center">
                        <div class="inline-flex items-center justify-center space-x-2 sm:space-x-4">
                            @if ($category->trashed())
                                <!-- Restore button -->
                                <button
                                    @click="window.dispatchEvent(new CustomEvent('restore-confirmation', {detail: {uuid: '{{ $category->uuid }}', name: '{{ $category->blog_category_name }}'}}))"
                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 focus:outline-none"
                                    title="{{ __('restore_blog_category') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                </button>
                            @else
                                <!-- Edit button -->
                                <button wire:click="edit('{{ $category->uuid }}')"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 focus:outline-none"
                                    title="{{ __('edit_blog_category') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>

                                <!-- Delete button -->
                                <button
                                    @click="window.dispatchEvent(new CustomEvent('delete-confirmation', {detail: {uuid: '{{ $category->uuid }}', name: '{{ $category->blog_category_name }}'}}))"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 focus:outline-none"
                                    title="{{ __('delete_blog_category') }}">
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
                    <td class="px-3 sm:px-6 py-4 text-center" colspan="4">{{ __('no_blog_categories_found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
