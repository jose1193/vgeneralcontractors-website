<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <x-alerts.success :message="session('message')" />
        @endif
        @if (session()->has('error'))
            <x-alerts.error :message="session('error')" />
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 space-y-4 md:space-y-0">
                    <x-input-search />

                    <div
                        class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                        <x-select-input-per-pages name="perPage" wireModel="perPage" class="sm:w-32">
                            <option value="10">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </x-select-input-per-pages>

                        <button wire:click="refreshCallList"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Sync Calls
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th wire:click="sort('start_timestamp')"
                                    class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer">
                                    Date/Time
                                    @if ($sortField === 'start_timestamp')
                                        @if ($sortDirection === 'asc')
                                            ↑
                                        @else
                                            ↓
                                        @endif
                                    @endif
                                </th>
                                <th
                                    class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    From</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    To</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Duration</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Sentiment</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($calls as $call)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($call['start_timestamp'] ?? '')->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4">{{ $call['from_number'] ?? '' }}</td>
                                    <td class="px-6 py-4">{{ $call['to_number'] ?? '' }}</td>
                                    <td class="px-6 py-4">
                                        {{ isset($call['duration_ms']) ? round($call['duration_ms'] / 1000) . 's' : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ isset($call['call_analysis']) && ($call['call_analysis']['call_successful'] ?? false) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $call['call_status'] ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $sentiment = isset($call['call_analysis'])
                                                ? $call['call_analysis']['user_sentiment'] ?? ''
                                                : '';
                                        @endphp
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if ($sentiment === 'Positive') bg-emerald-100 text-emerald-800
                                            @elseif($sentiment === 'Negative')
                                                bg-rose-100 text-rose-800
                                            @else
                                                bg-slate-100 text-slate-800 @endif">
                                            {{ $sentiment ?: 'Unknown' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <button wire:click="showCallDetails('{{ $call['call_id'] ?? '' }}')"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            View Details
                                        </button>
                                        @if (isset($call['recording_url']) && $call['recording_url'])
                                            <a href="{{ $call['recording_url'] }}" target="_blank"
                                                class="ml-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                                    </path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No call records found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $calls->links() }}
                </div>
            </div>
        </div>
    </div>

    @if ($showTranscript && $selectedCall)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <div class="pointer-events-auto w-screen max-w-2xl">
                            <div class="flex h-full flex-col overflow-y-scroll bg-white dark:bg-gray-900 shadow-xl">
                                <div class="px-4 py-6 sm:px-6">
                                    <div class="flex items-start justify-between">
                                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Call Details
                                        </h2>
                                        <button wire:click="closeTranscript"
                                            class="rounded-md bg-white dark:bg-gray-900 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            <span class="sr-only">Close panel</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="relative mt-6 flex-1 px-4 sm:px-6">
                                    <div class="space-y-6">
                                        @if (isset($selectedCall['call_analysis']) && isset($selectedCall['call_analysis']['call_summary']))
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Summary
                                                </h3>
                                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $selectedCall['call_analysis']['call_summary'] }}
                                                </p>
                                            </div>
                                        @endif

                                        @if (isset($selectedCall['transcript']))
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                    Transcript</h3>
                                                <div class="mt-2 space-y-4">
                                                    @foreach (explode("\n", $selectedCall['transcript']) as $line)
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $line }}</p>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($selectedCall['metadata']) && is_array($selectedCall['metadata']) && !empty($selectedCall['metadata']))
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                    Additional Information</h3>
                                                <dl class="mt-2 space-y-2">
                                                    @foreach ($selectedCall['metadata'] as $key => $value)
                                                        <div>
                                                            <dt
                                                                class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                                {{ ucfirst(str_replace('_', ' ', $key)) }}
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                                {{ is_array($value) ? json_encode($value) : $value }}
                                                            </dd>
                                                        </div>
                                                    @endforeach
                                                </dl>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
