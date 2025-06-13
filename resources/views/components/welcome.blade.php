@props(['title' => 'Your Sales Insights', 'subtitle' => 'Track your performance and grow your business'])

<div class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white min-h-screen transition-colors duration-300">
    <!-- Dashboard Content -->
    <div class="p-6">
        <!-- Dashboard Header -->
        <div class="mb-8 text-center sm:text-center md:text-left lg:text-left">
            <h2 class="text-xs sm:text-xs md:text-2xl lg:text-2xl font-bold text-gray-900 dark:text-white mb-2">
                {{ $title }}</h2>
            <p class="text-xs sm:text-xs md:text-base lg:text-base text-gray-600 dark:text-gray-400">{{ $subtitle }}
            </p>
        </div>

        <!-- Quick Action Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Create Appointment Card -->
            <a href="{{ route('appointments.create') }}"
                class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg shadow-gray-900/50 dark:shadow-gray-900/50 border border-gray-200 dark:border-gray-700 hover:scale-105 hover:shadow-2xl hover:shadow-green-500/20 dark:hover:shadow-green-500/20 transition-all duration-300">
                <!-- Gradient Overlay -->
                <div
                    class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div class="relative z-10">
                    <div
                        class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-4 group-hover:bg-green-200 dark:group-hover:bg-green-800/50 transition-colors duration-300">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400 group-hover:text-green-700 dark:group-hover:text-green-300 transition-colors duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-green-700 dark:group-hover:text-green-300 transition-colors duration-300">
                        Create Appointment</h3>
                    <p
                        class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-colors duration-300">
                        Schedule new client meetings</p>
                </div>
            </a>

            <!-- Calendar View Card -->
            <a href="{{ route('appointment-calendar') }}"
                class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg shadow-gray-900/50 dark:shadow-gray-900/50 border border-gray-200 dark:border-gray-700 hover:scale-105 hover:shadow-2xl hover:shadow-blue-500/20 dark:hover:shadow-blue-500/20 transition-all duration-300">
                <!-- Gradient Overlay -->
                <div
                    class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div class="relative z-10">
                    <div
                        class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-4 group-hover:bg-blue-200 dark:group-hover:bg-blue-800/50 transition-colors duration-300">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors duration-300">
                        Calendar View</h3>
                    <p
                        class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-colors duration-300">
                        View
                        all appointments</p>
                </div>
            </a>

            <!-- Manage Posts Card -->
            <a href="{{ route('admin.posts') }}"
                class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg shadow-gray-900/50 dark:shadow-gray-900/50 border border-gray-200 dark:border-gray-700 hover:scale-105 hover:shadow-2xl hover:shadow-yellow-500/20 dark:hover:shadow-yellow-500/20 transition-all duration-300">
                <!-- Gradient Overlay -->
                <div
                    class="absolute inset-0 bg-gradient-to-br from-yellow-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div class="relative z-10">
                    <div
                        class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center mb-4 group-hover:bg-yellow-200 dark:group-hover:bg-yellow-800/50 transition-colors duration-300">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 group-hover:text-yellow-700 dark:group-hover:text-yellow-300 transition-colors duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-yellow-700 dark:group-hover:text-yellow-300 transition-colors duration-300">
                        Manage Posts</h3>
                    <p
                        class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-colors duration-300">
                        Create
                        and edit content</p>
                </div>
            </a>

            <!-- Portfolio Card -->
            <a href="{{ route('portfolios') }}"
                class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg shadow-gray-900/50 dark:shadow-gray-900/50 border border-gray-200 dark:border-gray-700 hover:scale-105 hover:shadow-2xl hover:shadow-purple-500/20 dark:hover:shadow-purple-500/20 transition-all duration-300">
                <!-- Gradient Overlay -->
                <div
                    class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div class="relative z-10">
                    <div
                        class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-4 group-hover:bg-purple-200 dark:group-hover:bg-purple-800/50 transition-colors duration-300">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 group-hover:text-purple-700 dark:group-hover:text-purple-300 transition-colors duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-purple-700 dark:group-hover:text-purple-300 transition-colors duration-300">
                        Portfolio</h3>
                    <p
                        class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-colors duration-300">
                        Manage
                        project showcase</p>
                </div>
            </a>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Pending Appointments -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg shadow-gray-900/50 dark:shadow-gray-900/50 border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-600 dark:text-gray-300 text-sm font-medium">Pending Appointments</h3>
                        <div class="mt-2">
                            <div class="flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ \App\Models\Appointment::where('inspection_status', 'Pending')->count() }}
                                </p>
                            </div>
                            <div class="mt-2 flex items-center text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Awaiting confirmation</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Leads -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg shadow-gray-900/50 dark:shadow-gray-900/50 border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-600 dark:text-gray-300 text-sm font-medium">New Leads</h3>
                        <div class="mt-2">
                            <div class="flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ \App\Models\Appointment::where('status_lead', 'New')->count() }}
                                </p>
                            </div>
                            <div class="mt-2 flex items-center text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Require follow-up</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Published Posts -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg shadow-gray-900/50 dark:shadow-gray-900/50 border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-600 dark:text-gray-300 text-sm font-medium">Published Posts</h3>
                        <div class="mt-2">
                            <div class="flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ \App\Models\Post::where('post_status', 'published')->count() }}
                                </p>
                            </div>
                            <div class="mt-2 flex items-center text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Blog articles live</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Activity Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Lead Sources Chart -->
            <div
                class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg shadow-gray-900/50 dark:shadow-gray-900/50 border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Lead Sources</h3>
                    <div class="text-xs text-gray-600 dark:text-gray-400">{{ date('Y') }}</div>
                </div>

                @php
                    // Get lead sources data for current year
                    $leadSources = \App\Models\Appointment::whereYear('created_at', date('Y'))
                        ->selectRaw('lead_source, COUNT(*) as count')
                        ->groupBy('lead_source')
                        ->get();

                    $total = $leadSources->sum('count');
                    $colors = [
                        'Website' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-600 dark:text-blue-400'],
                        'Facebook Ads' => ['bg' => 'bg-green-500', 'text' => 'text-green-600 dark:text-green-400'],
                        'Retell AI' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-600 dark:text-purple-400'],
                        'Reference' => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-600 dark:text-yellow-400'],
                        'default' => ['bg' => 'bg-gray-500', 'text' => 'text-gray-600 dark:text-gray-400'],
                    ];
                @endphp

                @if ($leadSources->count() > 0)
                    <div class="flex items-center justify-center mb-6">
                        <div class="relative">
                            <svg class="w-56 h-56 transform -rotate-90" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor"
                                    stroke-width="8" class="text-gray-200 dark:text-gray-700" />
                                @php
                                    $offset = 0;
                                @endphp
                                @foreach ($leadSources as $source)
                                    @php
                                        $percentage = ($source->count / $total) * 100;
                                        $strokeDasharray = ($percentage / 100) * 251.2; // 2 * π * 40
                                        $strokeDashoffset = 251.2 - $strokeDasharray;
                                        $color = $colors[$source->lead_source] ?? $colors['default'];
                                    @endphp
                                    <circle cx="50" cy="50" r="40" fill="none"
                                        stroke="currentColor" stroke-width="8"
                                        stroke-dasharray="{{ $strokeDasharray }} 251.2"
                                        stroke-dashoffset="{{ -$offset }}"
                                        class="{{ str_replace('bg-', 'text-', $color['bg']) }}"
                                        style="transition: stroke-dasharray 0.5s ease-in-out;" />
                                    @php
                                        $offset += $strokeDasharray;
                                    @endphp
                                @endforeach
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Total</div>
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $total }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @foreach ($leadSources as $source)
                            @php
                                $percentage = round(($source->count / $total) * 100, 1);
                                $color = $colors[$source->lead_source] ?? $colors['default'];
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 rounded-full {{ $color['bg'] }}"></div>
                                    <span
                                        class="text-sm text-gray-700 dark:text-gray-300">{{ $source->lead_source }}</span>
                                </div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $percentage }}% ({{ $source->count }})
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-600 dark:text-gray-400 text-sm">No lead source data available
                    </div>
                @endif
            </div>

            <!-- Monthly Leads Chart -->
            <div
                class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg shadow-gray-900/50 dark:shadow-gray-900/50 border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Monthly Leads</h3>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Current Year</div>
                </div>

                @php
                    // Get monthly data for current year
                    $monthlyData = [];
                    for ($i = 1; $i <= 12; $i++) {
                        $count = \App\Models\Appointment::whereYear('created_at', date('Y'))
                            ->whereMonth('created_at', $i)
                            ->count();
                        $monthlyData[] = [
                            'month' => date('M', mktime(0, 0, 0, $i, 1)),
                            'count' => $count,
                        ];
                    }
                    $maxCount = collect($monthlyData)->max('count') ?: 1;
                @endphp

                <div
                    class="h-64 bg-gray-50 dark:bg-gray-900 rounded-lg flex items-end justify-between p-4 relative transition-colors duration-300">
                    <!-- Y-axis labels -->
                    <div
                        class="absolute left-2 top-4 bottom-4 flex flex-col justify-between text-xs text-gray-600 dark:text-gray-500">
                        @for ($i = $maxCount; $i >= 0; $i -= max(1, floor($maxCount / 4)))
                            <span>{{ $i }}</span>
                        @endfor
                    </div>

                    <!-- Bars -->
                    @foreach ($monthlyData as $data)
                        <div class="flex flex-col items-center group relative">
                            <div class="w-6 bg-blue-500 rounded-t hover:bg-blue-600 transition-colors duration-200"
                                style="height: {{ $maxCount > 0 ? ($data['count'] / $maxCount) * 200 : 0 }}px;">
                            </div>
                            <!-- Tooltip -->
                            <div
                                class="absolute bottom-full mb-2 hidden group-hover:block bg-gray-800 dark:bg-gray-700 text-white text-xs rounded py-1 px-2 whitespace-nowrap z-10">
                                {{ $data['month'] }}: {{ $data['count'] }} leads
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- X-axis labels -->
                <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mt-4 ml-8">
                    @foreach ($monthlyData as $data)
                        <span>{{ $data['month'] }}</span>
                    @endforeach
                </div>

                <!-- Summary -->
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-gray-600 dark:text-gray-400">
                        Total: {{ collect($monthlyData)->sum('count') }} leads
                    </div>
                    <div class="text-gray-600 dark:text-gray-400">
                        Peak: {{ collect($monthlyData)->max('count') }} leads
                    </div>
                </div>
            </div>

            <!-- Recent Activity - Live Leads Marquesina -->
            <div
                class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg shadow-gray-900/50 dark:shadow-gray-900/50 border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Live Leads</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Live updates from your latest appointments</p>
                </div>

                @php
                    $recentLeads = \App\Models\Appointment::orderBy('created_at', 'desc')->take(20)->get();
                @endphp

                <div class="h-80 overflow-hidden relative">
                    <!-- Gradient fade effects -->
                    <div
                        class="absolute top-0 left-0 right-0 h-8 bg-gradient-to-b from-white dark:from-gray-800 to-transparent z-10 pointer-events-none">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 right-0 h-8 bg-gradient-to-t from-white dark:from-gray-800 to-transparent z-10 pointer-events-none">
                    </div>

                    <!-- Scrolling content -->
                    <div class="animate-marquee-vertical hover:pause-animation">
                        @foreach ($recentLeads as $lead)
                            <div
                                class="flex items-center space-x-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700/50 mb-3 transition-colors duration-300">
                                <div
                                    class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                                    @if ($lead->status_lead === 'New') bg-green-100 dark:bg-green-900/30
                                    @elseif($lead->status_lead === 'Called') bg-blue-100 dark:bg-blue-900/30
                                    @elseif($lead->status_lead === 'Pending') bg-yellow-100 dark:bg-yellow-900/30
                                    @elseif($lead->status_lead === 'Declined') bg-red-100 dark:bg-red-900/30
                                    @else bg-gray-100 dark:bg-gray-500 @endif">

                                    @if ($lead->status_lead === 'New')
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    @elseif($lead->status_lead === 'Called')
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    @elseif($lead->status_lead === 'Pending')
                                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($lead->status_lead === 'Declined')
                                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $lead->full_name }}
                                        </h4>
                                        <span
                                            class="text-xs px-2 py-0.5 rounded
                                            @if ($lead->status_lead === 'New') bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-400
                                            @elseif($lead->status_lead === 'Called') bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-400
                                            @elseif($lead->status_lead === 'Pending') bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-400
                                            @elseif($lead->status_lead === 'Declined') bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-400
                                            @else bg-gray-100 dark:bg-gray-500/20 text-gray-800 dark:text-gray-400 @endif">
                                            {{ $lead->status_lead }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                        {{ $lead->location }} • {{ $lead->phone }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 flex-shrink-0 ml-2">
                                        {{ $lead->created_at->diffForHumans() }}
                                    </p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span
                                            class="text-xs px-2 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                                            {{ $lead->lead_source }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Duplicate content for seamless loop -->
                        @foreach ($recentLeads as $lead)
                            <div
                                class="flex items-center space-x-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700/50 mb-3 transition-colors duration-300">
                                <div
                                    class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                                    @if ($lead->status_lead === 'New') bg-green-100 dark:bg-green-900/30
                                    @elseif($lead->status_lead === 'Called') bg-blue-100 dark:bg-blue-900/30
                                    @elseif($lead->status_lead === 'Pending') bg-yellow-100 dark:bg-yellow-900/30
                                    @elseif($lead->status_lead === 'Declined') bg-red-100 dark:bg-red-900/30
                                    @else bg-gray-100 dark:bg-gray-500 @endif">

                                    @if ($lead->status_lead === 'New')
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    @elseif($lead->status_lead === 'Called')
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    @elseif($lead->status_lead === 'Pending')
                                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($lead->status_lead === 'Declined')
                                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $lead->full_name }}
                                        </h4>
                                        <span
                                            class="text-xs px-2 py-0.5 rounded
                                            @if ($lead->status_lead === 'New') bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-400
                                            @elseif($lead->status_lead === 'Called') bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-400
                                            @elseif($lead->status_lead === 'Pending') bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-400
                                            @elseif($lead->status_lead === 'Declined') bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-400
                                            @else bg-gray-100 dark:bg-gray-500/20 text-gray-800 dark:text-gray-400 @endif">
                                            {{ $lead->status_lead }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                        {{ $lead->location }} • {{ $lead->phone }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 flex-shrink-0 ml-2">
                                        {{ $lead->created_at->diffForHumans() }}
                                    </p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span
                                            class="text-xs px-2 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                                            {{ $lead->lead_source }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes marquee-vertical {
        0% {
            transform: translateY(100%);
        }

        100% {
            transform: translateY(-100%);
        }
    }

    .animate-marquee-vertical {
        animation: marquee-vertical 60s linear infinite;
    }

    .hover\:pause-animation:hover {
        animation-play-state: paused;
    }
</style>
