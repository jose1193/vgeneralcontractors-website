<div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
    <div class="bg-white dark:bg-gray-800">
        <div class="flex items-center">
            <span class="text-gray-600 font-semibold">{{ __('construction') }}</span>
        </div>
        <h1 class="mt-4 text-3xl font-bold text-gray-800 dark:text-white">
            {{ __('welcome_to_company') }}
        </h1>
        <p class="mt-6 text-gray-600 dark:text-gray-400 leading-relaxed text-lg">
            {{ __('company_description') }}
        </p>
        <div class="mt-8">
            <x-button class="px-6 py-3 text-sm bg-gray-600 text-white">
                {{ __('create_post') }}
            </x-button>

        </div>
    </div>
    <div class="relative">
        <div class="relative">
            <video src="{{ asset('assets/video/video.mp4') }}" alt="Construction Video"
                class="w-full h-64 md:h-80 lg:h-96 object-cover rounded-lg shadow-lg relative z-10" controls autoplay
                muted loop>
            </video>
            <div class="absolute inset-0 bg-black opacity-30 rounded-lg pointer-events-none"></div>
        </div>

    </div>
</div>

<!-- Marketing Channels Dashboard -->
<div class="p-6 lg:p-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Marketing Channels</h1>
                <p class="text-slate-400">Monitor your lead generation and conversion metrics</p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-sm text-slate-400">Live Data</span>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <!-- Card 1: Your Sales Target -->
        <div class="dashboard-card bg-slate-800/60 rounded-2xl p-6 glow-green">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-slate-400">Your Sales Target</h3>
                        <p class="text-xs text-slate-500">Advertising Campaign</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs text-green-400 bg-green-400/20 px-2 py-1 rounded-lg">+12%</span>
                </div>
            </div>

            <div class="mb-4">
                <div class="text-4xl font-bold text-white mb-1">$700.00</div>
                <div class="flex items-center text-sm">
                    <span class="text-green-400 mr-2">▲ 8.5%</span>
                    <span class="text-slate-400">vs last month</span>
                </div>
            </div>

            <!-- Mini Chart Bars -->
            <div class="flex items-end space-x-1 h-12">
                <div class="bg-green-400 rounded-sm" style="width: 4px; height: 60%;"></div>
                <div class="bg-green-400 rounded-sm" style="width: 4px; height: 80%;"></div>
                <div class="bg-green-400 rounded-sm" style="width: 4px; height: 40%;"></div>
                <div class="bg-green-400 rounded-sm" style="width: 4px; height: 100%;"></div>
                <div class="bg-green-400 rounded-sm" style="width: 4px; height: 70%;"></div>
                <div class="bg-green-400 rounded-sm" style="width: 4px; height: 90%;"></div>
                <div class="bg-green-400 rounded-sm" style="width: 4px; height: 50%;"></div>
                <div class="bg-green-400 rounded-sm" style="width: 4px; height: 75%;"></div>
            </div>
        </div>

        <!-- Card 2: Email & Commerce -->
        <div class="dashboard-card bg-slate-800/60 rounded-2xl p-6 glow-purple">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-slate-400">Email & Commerce</h3>
                        <p class="text-xs text-slate-500">Email & E-Commerce</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs text-purple-400 bg-purple-400/20 px-2 py-1 rounded-lg">+5%</span>
                </div>
            </div>

            <div class="mb-4">
                <div class="text-4xl font-bold text-white mb-1">$600.00</div>
                <div class="flex items-center text-sm">
                    <span class="text-purple-400 mr-2">▲ 4.2%</span>
                    <span class="text-slate-400">vs last month</span>
                </div>
            </div>

            <!-- Pie Chart Representation -->
            <div class="flex items-center justify-center">
                <div class="relative w-16 h-16">
                    <svg class="w-16 h-16 transform -rotate-90">
                        <circle cx="32" cy="32" r="28" stroke="#374151" stroke-width="4" fill="none" />
                        <circle cx="32" cy="32" r="28" stroke="#8b5cf6" stroke-width="4" fill="none"
                            stroke-dasharray="110" stroke-dashoffset="44" stroke-linecap="round" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xs font-bold text-purple-400">65%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Referrals and Partners -->
        <div class="dashboard-card bg-slate-800/60 rounded-2xl p-6 glow-orange">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-slate-400">Referrals and Partners</h3>
                        <p class="text-xs text-slate-500">Referrals and Partner</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs text-orange-400 bg-orange-400/20 px-2 py-1 rounded-lg">+18%</span>
                </div>
            </div>

            <div class="mb-4">
                <div class="text-4xl font-bold text-white mb-1">$500.00</div>
                <div class="flex items-center text-sm">
                    <span class="text-orange-400 mr-2">▲ 15.3%</span>
                    <span class="text-slate-400">vs last month</span>
                </div>
            </div>

            <!-- Area Chart Representation -->
            <div class="relative h-12">
                <svg class="w-full h-full" viewBox="0 0 100 40">
                    <defs>
                        <linearGradient id="orangeGradient" x1="0%" y1="0%" x2="0%"
                            y2="100%">
                            <stop offset="0%" style="stop-color:#f97316;stop-opacity:0.8" />
                            <stop offset="100%" style="stop-color:#f97316;stop-opacity:0.1" />
                        </linearGradient>
                    </defs>
                    <path d="M0,35 Q25,25 50,15 T100,10 L100,40 L0,40 Z" fill="url(#orangeGradient)" />
                    <path d="M0,35 Q25,25 50,15 T100,10" stroke="#f97316" stroke-width="2" fill="none" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Additional Info Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- New Patient Leads Chart Area -->
        <div class="dashboard-card bg-slate-800/60 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">New Patient Leads</h3>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                    <span class="text-xs text-slate-400">Facebook</span>
                    <div class="w-3 h-3 bg-purple-400 rounded-full ml-4"></div>
                    <span class="text-xs text-slate-400">Instagram</span>
                </div>
            </div>
            <div class="text-2xl font-bold text-white mb-2">2,847</div>
            <div class="text-sm text-slate-400 mb-4">Total leads this month</div>

            <!-- Bar Chart Placeholder -->
            <div class="flex items-end justify-between space-x-2 h-24">
                <div class="bg-green-400 rounded-t" style="width: 12px; height: 60%;"></div>
                <div class="bg-green-400 rounded-t" style="width: 12px; height: 80%;"></div>
                <div class="bg-purple-400 rounded-t" style="width: 12px; height: 40%;"></div>
                <div class="bg-purple-400 rounded-t" style="width: 12px; height: 90%;"></div>
                <div class="bg-green-400 rounded-t" style="width: 12px; height: 70%;"></div>
                <div class="bg-purple-400 rounded-t" style="width: 12px; height: 50%;"></div>
                <div class="bg-green-400 rounded-t" style="width: 12px; height: 85%;"></div>
                <div class="bg-purple-400 rounded-t" style="width: 12px; height: 65%;"></div>
            </div>
        </div>

        <!-- Marketing Channel Performance -->
        <div class="dashboard-card bg-slate-800/60 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-6">Marketing Channel</h3>

            <!-- Performance Stats -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                        <span class="text-slate-300">Facebook</span>
                    </div>
                    <div class="text-right">
                        <div class="text-white font-semibold">35.1%</div>
                        <div class="text-xs text-green-400">+2.3%</div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-purple-400 rounded-full"></div>
                        <span class="text-slate-300">Instagram</span>
                    </div>
                    <div class="text-right">
                        <div class="text-white font-semibold">28.7%</div>
                        <div class="text-xs text-purple-400">+1.8%</div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-orange-400 rounded-full"></div>
                        <span class="text-slate-300">Google Ads</span>
                    </div>
                    <div class="text-right">
                        <div class="text-white font-semibold">18.9%</div>
                        <div class="text-xs text-orange-400">+0.9%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
