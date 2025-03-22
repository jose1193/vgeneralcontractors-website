<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    NRO
                </th>
                @include('components.sort-position', [
                    'field' => 'post_title',
                    'label' => 'TITLE',
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                ])
                @include('components.sort-position', [
                    'field' => 'category_id',
                    'label' => 'CATEGORY',
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                ])
                @include('components.sort-position', [
                    'field' => 'created_at',
                    'label' => 'CREATED',
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                ])
                @include('components.sort-position', [
                    'field' => 'updated_at',
                    'label' => 'UPDATED',
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                ])
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    {{ __('Actions') }}
                </th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($posts as $index => $post)
                <tr class="{{ $post->deleted_at ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm text-gray-900 dark:text-gray-200">
                            {{ $index + 1 }}
                        </div>
                    </td>
                    <td
                        class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900 dark:text-gray-200">
                        <div class="flex items-center justify-center">
                            @if ($post->post_image)
                                <img src="{{ Storage::url($post->post_image) }}" alt="{{ $post->post_title }}"
                                    class="h-10 w-10 rounded-full object-cover mr-3">
                            @else
                                <div
                                    class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center mr-3">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="truncate max-w-xs" title="{{ $post->post_title }}">
                                {{ \Illuminate\Support\Str::limit($post->post_title, 30, '...') }}
                                @if ($post->deleted_at)
                                    <span class="text-xs text-red-600 dark:text-red-400 ml-2">
                                        ({{ __('Inactive') }})
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                        {{ $post->category ? $post->category->blog_category_name : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                        {{ $post->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                        {{ $post->updated_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex justify-center space-x-2">
                            <!-- View Button -->
                            <a href="{{ route('posts.show', $post->post_title_slug) }}" target="_blank"
                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </a>

                            @if (!$post->deleted_at)
                                <!-- Edit Button -->
                                <button wire:click="edit('{{ $post->uuid }}')"
                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <button
                                    @click="window.dispatchEvent(new CustomEvent('delete-confirmation', {detail: {uuid: '{{ $post->uuid }}', name: '{{ $post->post_title }}'}}))"
                                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            @else
                                <!-- Restore Button -->
                                <button
                                    @click="window.dispatchEvent(new CustomEvent('restore-confirmation', {detail: {uuid: '{{ $post->uuid }}', name: '{{ $post->post_title }}'}}))"
                                    class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6"
                        class="px-6 py-4 text-center text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('No posts found') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
