<x-app-layout>
    {{-- Dark background container with consistent styling --}}
    <div class="min-h-screen bg-gray-900" style="background-color: #141414;">
        {{-- Header section with title and subtitle --}}
        <div class="p-4 sm:p-6">
            <div class="mb-4 sm:mb-8 text-center sm:text-center md:text-left lg:text-left">
                <h2 class="text-base sm:text-base md:text-2xl lg:text-2xl font-bold text-white mb-2">
                    {{ __('Posts Management') }}
                </h2>
                <p class="text-base sm:text-base md:text-base lg:text-base text-gray-400">
                    {{ __('Manage your blog posts with traditional CRUD operations') }}
                </p>
            </div>
        </div>

        {{-- Main content area --}}
        <div class="max-w-7xl mx-auto py-2 sm:py-4 md:py-2 lg:py-2 px-4 sm:px-6 lg:px-8 pb-12">
            {{-- Success and error messages --}}
            @if (session()->has('message'))
                <div class="mb-6 bg-green-500/20 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="mb-6 bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Main container --}}
            <div class="dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg">
                <div class="p-6">
                    {{-- Header with search and controls --}}
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
                        {{-- Search bar --}}
                        <div class="relative flex-1 max-w-md">
                            <form method="GET" action="{{ route('posts-crud.index') }}" class="flex">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="{{ __('Search posts...') }}"
                                    class="flex-1 text-gray-300 placeholder-gray-500 rounded-l-lg px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-yellow-500 border-0"
                                    style="background-color: #2C2E36;">
                                <button type="submit"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 px-4 py-2 rounded-r-lg font-medium transition-colors">
                                    {{ __('Search') }}
                                </button>
                                <svg class="w-5 h-5 text-gray-500 absolute left-3 top-2.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </form>
                        </div>

                        {{-- Controls --}}
                        <div
                            class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                            {{-- Show deleted toggle --}}
                            <label class="flex items-center text-gray-300">
                                <input type="checkbox" {{ request('show_deleted') === 'true' ? 'checked' : '' }}
                                    onchange="toggleShowDeleted(this)"
                                    class="mr-2 rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500 focus:ring-offset-gray-800">
                                {{ __('Show Deleted') }}
                            </label>

                            {{-- Per page dropdown --}}
                            <select name="per_page" onchange="changePerPage(this.value)"
                                class="text-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 border-0"
                                style="background-color: #2C2E36;">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10
                                    {{ __('per page') }}</option>
                                <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25
                                    {{ __('per page') }}</option>
                                <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50
                                    {{ __('per page') }}</option>
                                <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100
                                    {{ __('per page') }}</option>
                            </select>

                            {{-- Create button --}}
                            <a href="{{ route('posts-crud.create') }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 px-4 py-2 rounded-lg font-medium transition-colors inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('Create Post') }}
                            </a>
                        </div>
                    </div>

                    {{-- Posts table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_field' => 'post_title', 'sort_direction' => request('sort_field') === 'post_title' && request('sort_direction') === 'asc' ? 'desc' : 'asc']) }}"
                                            class="hover:text-yellow-400">
                                            {{ __('Title') }}
                                            @if (request('sort_field') === 'post_title')
                                                @if (request('sort_direction') === 'asc')
                                                    ↑
                                                @else
                                                    ↓
                                                @endif
                                            @endif
                                        </a>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        {{ __('Category') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        {{ __('Status') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_field' => 'created_at', 'sort_direction' => request('sort_field') === 'created_at' && request('sort_direction') === 'asc' ? 'desc' : 'asc']) }}"
                                            class="hover:text-yellow-400">
                                            {{ __('Created') }}
                                            @if (request('sort_field') === 'created_at')
                                                @if (request('sort_direction') === 'asc')
                                                    ↑
                                                @else
                                                    ↓
                                                @endif
                                            @endif
                                        </a>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        {{ __('Author') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700">
                                @forelse($posts as $post)
                                    <tr class="hover:bg-gray-750 {{ $post->deleted_at ? 'opacity-60' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-200">
                                                {{ Str::limit($post->post_title, 50) }}
                                            </div>
                                            <div class="text-sm text-gray-400">
                                                {{ Str::limit(strip_tags($post->post_content), 80) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-500/20 text-blue-400">
                                                {{ $post->category->blog_category_name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($post->post_status === 'published')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-500/20 text-green-400">
                                                    {{ __('Published') }}
                                                </span>
                                            @elseif($post->post_status === 'scheduled')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-500/20 text-orange-400">
                                                    {{ __('Scheduled') }}
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-500/20 text-gray-400">
                                                    {{ $post->post_status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                            {{ $post->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                            {{ $post->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                @if (!$post->deleted_at)
                                                    {{-- Edit button --}}
                                                    <a href="{{ route('posts-crud.edit', $post->uuid) }}"
                                                        class="text-yellow-400 hover:text-yellow-300 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>

                                                    {{-- Delete button --}}
                                                    <button
                                                        onclick="deletePost('{{ $post->uuid }}', '{{ addslashes($post->post_title) }}')"
                                                        class="text-red-400 hover:text-red-300 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                @else
                                                    {{-- Restore button --}}
                                                    <button
                                                        onclick="restorePost('{{ $post->uuid }}', '{{ addslashes($post->post_title) }}')"
                                                        class="text-green-400 hover:text-green-300 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                                            {{ __('No posts found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($posts->hasPages())
                        <div class="mt-6">
                            {{ $posts->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for CRUD operations --}}
    <script>
        function toggleShowDeleted(checkbox) {
            const url = new URL(window.location);
            if (checkbox.checked) {
                url.searchParams.set('show_deleted', 'true');
            } else {
                url.searchParams.delete('show_deleted');
            }
            window.location.href = url.toString();
        }

        function changePerPage(value) {
            const url = new URL(window.location);
            url.searchParams.set('per_page', value);
            url.searchParams.delete('page'); // Reset to first page
            window.location.href = url.toString();
        }

        function deletePost(uuid, title) {
            if (confirm(`Are you sure you want to delete the post "${title}"?`)) {
                fetch(`{{ route('posts-crud.index') }}/${uuid}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Error: ' + error.message);
                    });
            }
        }

        function restorePost(uuid, title) {
            if (confirm(`Are you sure you want to restore the post "${title}"?`)) {
                fetch(`{{ route('posts-crud.index') }}/${uuid}/restore`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Error: ' + error.message);
                    });
            }
        }
    </script>
</x-app-layout>
