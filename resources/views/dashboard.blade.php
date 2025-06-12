<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Analytics & Insights') }}
            </h2>
            <div class="flex items-center space-x-4">
                <select
                    class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm">
                    <option>Monthly</option>
                    <option>Weekly</option>
                    <option>Daily</option>
                </select>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    + New Project
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Top Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Projects -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Projects</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">215.6k</p>
                        <div class="flex items-center mt-2">
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                +14.2%
                            </span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Revenue</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">$584.2k</p>
                        <div class="flex items-center mt-2">
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                +8.5%
                            </span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Projects -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Projects</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">42</p>
                        <div class="flex items-center mt-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                +3.2%
                            </span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Team Members -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Team Members</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">24</p>
                        <div class="flex items-center mt-2">
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                -1.2%
                            </span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Chart -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Project Analytics</h3>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">Sort by:</span>
                        <select class="bg-transparent border-none text-sm text-gray-600 dark:text-gray-400">
                            <option>Engagement</option>
                            <option>Revenue</option>
                            <option>Projects</option>
                        </select>
                    </div>
                </div>

                <!-- Chart Container -->
                <div
                    class="h-80 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 rounded-lg flex items-center justify-center relative overflow-hidden">
                    <!-- Simulated Chart Background -->
                    <div class="absolute inset-0 opacity-20">
                        <svg class="w-full h-full" viewBox="0 0 400 200">
                            <defs>
                                <linearGradient id="chartGradient" x1="0%" y1="0%" x2="0%"
                                    y2="100%">
                                    <stop offset="0%" style="stop-color:#FFD700;stop-opacity:0.8" />
                                    <stop offset="100%" style="stop-color:#FFD700;stop-opacity:0.1" />
                                </linearGradient>
                            </defs>
                            <path d="M0,150 Q100,120 200,100 T400,80" stroke="#FFD700" stroke-width="3"
                                fill="none" />
                            <path d="M0,150 Q100,120 200,100 T400,80 L400,200 L0,200 Z" fill="url(#chartGradient)" />
                        </svg>
                    </div>
                    <div class="text-center z-10">
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">215.6k</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Projects</p>
                    </div>
                </div>

                <!-- Chart Legend -->
                <div class="flex items-center justify-center space-x-6 mt-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Projects</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-400 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Revenue</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-purple-400 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Growth</span>
                    </div>
                </div>
            </div>

            <!-- Audience Insights -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Audience Insights</h3>
                    <span class="text-sm text-gray-500">All</span>
                </div>

                <!-- Donut Chart -->
                <div class="flex items-center justify-center mb-6">
                    <div class="relative w-32 h-32">
                        <svg class="transform -rotate-90 w-full h-full" viewBox="0 0 36 36">
                            <path class="text-gray-200 dark:text-gray-700" stroke="currentColor" stroke-width="3"
                                fill="none"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-yellow-400" stroke="currentColor" stroke-width="3" fill="none"
                                stroke-dasharray="45, 100" stroke-linecap="round"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-purple-400" stroke="currentColor" stroke-width="3" fill="none"
                                stroke-dasharray="17, 100" stroke-dashoffset="-45" stroke-linecap="round"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-blue-400" stroke="currentColor" stroke-width="3" fill="none"
                                stroke-dasharray="38, 100" stroke-dashoffset="-62" stroke-linecap="round"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-xl font-bold text-gray-900 dark:text-white">256.k</p>
                                <p class="text-xs text-gray-500">Total</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Active</span>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">45%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-blue-400 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Completed</span>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">38%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-purple-400 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Pending</span>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">17%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Platform Engagement -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Platform Engagement</h3>
                    <span class="text-sm text-gray-500">Sort by: Engagement</span>
                </div>

                <div class="space-y-4">
                    <div
                        class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-r from-pink-500 to-rose-500 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">IG</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Instagram</p>
                                <p class="text-sm text-gray-500">5.2k interactions</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900 dark:text-white">5.2k</p>
                            <p class="text-sm text-green-600">+8% ↑</p>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">TK</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">TikTok</p>
                                <p class="text-sm text-gray-500">1.2k interactions</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900 dark:text-white">1.2k</p>
                            <p class="text-sm text-red-600">-3% ↓</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Status & Timeline -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Current Project Status</h3>
                    <span class="text-sm text-gray-500">Weekly</span>
                </div>

                <div class="space-y-6">
                    <!-- Timeline Item -->
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-3 h-3 bg-green-400 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900 dark:text-white">In Progress</p>
                                <span class="text-sm text-gray-500">Today 09:30</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Schedule and manage your posts
                                effectively.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-3 h-3 bg-yellow-400 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900 dark:text-white">On Hold</p>
                                <span class="text-sm text-gray-500">Yesterday 14:20</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Review and approve pending
                                proposals.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-3 h-3 bg-red-400 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900 dark:text-white">Pending</p>
                                <span class="text-sm text-gray-500">Dec 18, 16:45</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update project timeline and
                                deliverables.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
