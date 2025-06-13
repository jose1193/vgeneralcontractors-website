{{-- resources/views/components/livewire/portfolios/data-table.blade.php --}}

@props([
    'portfolios', // The paginated collection of portfolio items
    'sortField',
    'sortDirection',
    'search', // Added prop for the search term
])

{{-- Added overflow-x-auto to the container div like the example --}}
<div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-md rounded-lg">
    {{-- Adjusted table classes to match example --}}
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        {{-- Adjusted thead background to match example --}}
        <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                {{-- START: Added Sequence Number Column Header --}}
                <th scope="col"
                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('nro') }}
                </th>
                {{-- END: Added Sequence Number Column Header --}}

                {{-- Imagen --}}
                <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('image') }}
                </th>
                {{-- Project Name --}}
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('project_name') }}
                </th>
                {{-- Service Category --}}
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('service_category') }}
                </th>

                {{-- Created At --}}
                <x-sort-position :field="'created_at'" :label="strtoupper(__('created_at'))" :sortField="$sortField" :sortDirection="$sortDirection" />

                {{-- Actions --}}
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('actions') }}
                </th>
            </tr>
        </thead>
        {{-- Adjusted tbody classes to match example --}}
        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
            @forelse ($portfolios as $portfolio)
                <tr wire:key="portfolio-{{ $portfolio->uuid ?? $portfolio->id }}"
                    class="{{ $portfolio->trashed() ? 'bg-red-100 dark:bg-red-900/30' : '' }}">

                    {{-- START: Added Sequence Number Cell --}}
                    <td
                        class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-500 dark:text-gray-300">
                        {{-- Calculate number based on pagination --}}
                        {{ $portfolios->firstItem() + $loop->index }}
                        {{-- Or simply use loop iteration if pagination context isn't needed: --}}
                        {{-- {{ $loop->iteration }} --}}
                    </td>
                    {{-- END: Added Sequence Number Cell --}}

                    {{-- Imagen --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($portfolio->images->isNotEmpty() && $portfolio->images->first()->path)
                            <img src="{{ $portfolio->images->first()->path }}"
                                alt="{{ $portfolio->projectType?->title ?? 'Portfolio Image' }}"
                                class="h-12 w-12 rounded-md object-cover border dark:border-gray-600 shadow-sm mx-auto sm:mx-0">
                        @else
                            <div
                                class="h-12 w-12 rounded-md bg-gray-100 dark:bg-gray-700 flex items-center justify-center border dark:border-gray-600 mx-auto sm:mx-0">
                                <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        @endif
                    </td>

                    {{-- Project Name --}}
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $portfolio->projectType?->title ?? 'N/A' }}
                        </div>
                    </td>

                    {{-- Service Category --}}
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                            {{ $portfolio->projectType?->serviceCategory?->category ?? 'N/A' }}
                        </span>
                    </td>

                    {{-- Created At --}}
                    <td class="px-6 py-4 whitespace-no-wrap text-sm text-gray-500 dark:text-gray-400 text-center">
                        {{ $portfolio->created_at->format('F d, Y h:i A') }}
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4 whitespace-no-wrap text-sm font-medium text-center">
                        <div class="inline-flex items-center justify-center space-x-4">
                            @if ($portfolio->trashed())
                                {{-- Restore Button --}}
                                @can('RESTORE_PORTFOLIO')
                                    <button
                                        @click.prevent="window.dispatchEvent(new CustomEvent('restore-confirmation', { detail: { uuid: '{{ $portfolio->uuid }}', name: @js($portfolio->projectType?->title ?? 'this item') }}))"
                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 focus:outline-none"
                                        title="Restore Portfolio">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                    </button>
                                @endcan
                            @else
                                {{-- Edit Button --}}
                                @can('UPDATE_PORTFOLIO')
                                    <button wire:click="edit('{{ $portfolio->uuid }}')"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 focus:outline-none"
                                        title="Edit Portfolio">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                @endcan

                                {{-- Delete/Trash Button --}}
                                @can('DELETE_PORTFOLIO')
                                    <button
                                        @click.prevent="window.dispatchEvent(new CustomEvent('delete-confirmation', { detail: { uuid: '{{ $portfolio->uuid }}', name: @js($portfolio->projectType?->title ?? 'this item') }}))"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 focus:outline-none"
                                        title="Delete Portfolio">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                @endcan
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    {{-- Adjusted colspan to 6 (Added 1 for the new '#' column) --}}
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                        @if (trim($search ?? '') !== '')
                            {{ __('no_portfolios_matching') }} "{{ $search }}".
                        @else
                            {{ __('no_portfolios_found') }}.
                            @can('CREATE_PORTFOLIO')
                                <button wire:click="$parent.create()"
                                    class="text-blue-500 hover:underline ml-1">{{ __('add_one_now') }}</button>
                            @endcan
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
