<div style="background-color: #141414;" class="text-white min-h-screen">
    <!-- Dashboard Content -->
    <div class="p-6">
        <!-- Dashboard Header -->
        <div class="mb-8 text-center sm:text-center md:text-left lg:text-left">
            <h2 class="text-xs sm:text-xs md:text-2xl lg:text-2xl font-bold text-white mb-2">Your Sales Insights</h2>
            <p class="text-xs sm:text-xs md:text-base lg:text-base text-gray-400">Track your performance and grow your
                business</p>
        </div>

        <!-- Quick Action Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Create Appointment Card -->
            <a href="{{ route('appointments.create') }}"
                class="group relative overflow-hidden rounded-xl p-6 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-green-500/25"
                style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(34, 197, 94, 0.05) 100%); border: 1px solid rgba(34, 197, 94, 0.2);">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center group-hover:bg-green-500/30 transition-colors duration-300">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3
                            class="text-sm font-semibold text-white group-hover:text-green-300 transition-colors duration-300">
                            Create Appointment</h3>
                        <p class="text-xs text-gray-400 group-hover:text-gray-300 transition-colors duration-300">
                            Schedule new client meeting</p>
                    </div>
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-r from-green-500/0 via-green-500/5 to-green-500/0 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
            </a>

            <!-- Calendar View Card -->
            <a href="{{ route('appointment-calendar') }}"
                class="group relative overflow-hidden rounded-xl p-6 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-blue-500/25"
                style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%); border: 1px solid rgba(59, 130, 246, 0.2);">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center group-hover:bg-blue-500/30 transition-colors duration-300">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3
                            class="text-sm font-semibold text-white group-hover:text-blue-300 transition-colors duration-300">
                            Calendar View</h3>
                        <p class="text-xs text-gray-400 group-hover:text-gray-300 transition-colors duration-300">View
                            all appointments</p>
                    </div>
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-r from-blue-500/0 via-blue-500/5 to-blue-500/0 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
            </a>

            <!-- Create Post Card -->
            <a href="{{ route('admin.posts') }}"
                class="group relative overflow-hidden rounded-xl p-6 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-yellow-500/25"
                style="background: linear-gradient(135deg, rgba(234, 179, 8, 0.1) 0%, rgba(234, 179, 8, 0.05) 100%); border: 1px solid rgba(234, 179, 8, 0.2);">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center group-hover:bg-yellow-500/30 transition-colors duration-300">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3
                            class="text-sm font-semibold text-white group-hover:text-yellow-300 transition-colors duration-300">
                            Manage Posts</h3>
                        <p class="text-xs text-gray-400 group-hover:text-gray-300 transition-colors duration-300">Create
                            & edit blog posts</p>
                    </div>
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-r from-yellow-500/0 via-yellow-500/5 to-yellow-500/0 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
            </a>

            <!-- View Portfolio Card -->
            <a href="{{ route('portfolios') }}"
                class="group relative overflow-hidden rounded-xl p-6 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-purple-500/25"
                style="background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(147, 51, 234, 0.05) 100%); border: 1px solid rgba(147, 51, 234, 0.2);">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center group-hover:bg-purple-500/30 transition-colors duration-300">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3
                            class="text-sm font-semibold text-white group-hover:text-purple-300 transition-colors duration-300">
                            Portfolio</h3>
                        <p class="text-xs text-gray-400 group-hover:text-gray-300 transition-colors duration-300">Manage
                            project gallery</p>
                    </div>
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-r from-purple-500/0 via-purple-500/5 to-purple-500/0 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
            </a>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- New Patient Leads Card -->
            <div style="background-color: #2C2E36;" class="rounded-lg p-6 border border-gray-900">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-300 text-sm font-medium">New Patient Leads</h3>
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="text-3xl font-bold text-white mb-1">1,234</div>
                    <div class="flex items-center text-sm">
                        <span class="text-green-400 mr-1">↗ +12%</span>
                        <span class="text-gray-400">vs last month</span>
                    </div>
                </div>
                <!-- Mini Chart -->
                <div class="h-16 bg-gray-900 rounded flex items-end justify-between px-2 py-2">
                    <div class="w-2 bg-green-500 rounded-t" style="height: 30%"></div>
                    <div class="w-2 bg-green-500 rounded-t" style="height: 45%"></div>
                    <div class="w-2 bg-green-500 rounded-t" style="height: 60%"></div>
                    <div class="w-2 bg-green-500 rounded-t" style="height: 40%"></div>
                    <div class="w-2 bg-green-500 rounded-t" style="height: 80%"></div>
                    <div class="w-2 bg-green-500 rounded-t" style="height: 70%"></div>
                    <div class="w-2 bg-green-500 rounded-t" style="height: 90%"></div>
                </div>
            </div>

            <!-- Revenue Card -->
            <div style="background-color: #2C2E36;" class="rounded-lg p-6 border border-gray-900">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-300 text-sm font-medium">Total Revenue</h3>
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="text-3xl font-bold text-white mb-1">$89,421</div>
                    <div class="flex items-center text-sm">
                        <span class="text-green-400 mr-1">↗ +8.2%</span>
                        <span class="text-gray-400">vs last month</span>
                    </div>
                </div>
                <!-- Mini Chart -->
                <div class="h-16 bg-gray-900 rounded flex items-end justify-between px-2 py-2">
                    <div class="w-2 bg-blue-500 rounded-t" style="height: 50%"></div>
                    <div class="w-2 bg-blue-500 rounded-t" style="height: 65%"></div>
                    <div class="w-2 bg-blue-500 rounded-t" style="height: 40%"></div>
                    <div class="w-2 bg-blue-500 rounded-t" style="height: 75%"></div>
                    <div class="w-2 bg-blue-500 rounded-t" style="height: 55%"></div>
                    <div class="w-2 bg-blue-500 rounded-t" style="height: 85%"></div>
                    <div class="w-2 bg-blue-500 rounded-t" style="height: 95%"></div>
                </div>
            </div>

            <!-- Active Projects Card -->
            <div style="background-color: #2C2E36;" class="rounded-lg p-6 border border-gray-900">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-300 text-sm font-medium">Active Projects</h3>
                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="text-3xl font-bold text-white mb-1">56</div>
                    <div class="flex items-center text-sm">
                        <span class="text-red-400 mr-1">↘ -2.1%</span>
                        <span class="text-gray-400">vs last month</span>
                    </div>
                </div>
                <!-- Progress Ring -->
                <div class="flex items-center justify-center h-16">
                    <div class="relative w-12 h-12">
                        <svg class="w-12 h-12 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-gray-900" stroke="currentColor" stroke-width="3" fill="none"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-yellow-500" stroke="currentColor" stroke-width="3" fill="none"
                                stroke-dasharray="75, 100"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-xs font-semibold text-white">75%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Sales Overview Chart -->
            <div style="background-color: #2C2E36;" class="rounded-lg p-6 border border-gray-900">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-white">Sales Overview</h3>
                    <div class="flex space-x-2">
                        <button
                            class="px-3 py-1 text-xs bg-yellow-500 text-gray-900 rounded-full font-medium">7D</button>
                        <button class="px-3 py-1 text-xs text-gray-400 hover:text-white rounded-full">30D</button>
                        <button class="px-3 py-1 text-xs text-gray-400 hover:text-white rounded-full">90D</button>
                    </div>
                </div>

                <!-- Chart Area -->
                <div class="h-64 bg-gray-900 rounded-lg flex items-end justify-between p-4">
                    <!-- Simulated Bar Chart -->
                    <div class="flex items-end space-x-2 w-full">
                        <div class="bg-yellow-500 rounded-t w-8" style="height: 60%"></div>
                        <div class="bg-yellow-500 rounded-t w-8" style="height: 80%"></div>
                        <div class="bg-yellow-500 rounded-t w-8" style="height: 45%"></div>
                        <div class="bg-yellow-500 rounded-t w-8" style="height: 90%"></div>
                        <div class="bg-yellow-500 rounded-t w-8" style="height: 70%"></div>
                        <div class="bg-yellow-500 rounded-t w-8" style="height: 85%"></div>
                        <div class="bg-yellow-500 rounded-t w-8" style="height: 95%"></div>
                        <div class="bg-yellow-500 rounded-t w-8" style="height: 75%"></div>
                        <div class="bg-yellow-500 rounded-t w-8" style="height: 65%"></div>
                        <div class="bg-yellow-500 rounded-t w-8" style="height: 88%"></div>
                    </div>
                </div>

                <div class="flex justify-between text-xs text-gray-400 mt-4">
                    <span>Mon</span>
                    <span>Tue</span>
                    <span>Wed</span>
                    <span>Thu</span>
                    <span>Fri</span>
                    <span>Sat</span>
                    <span>Sun</span>
                </div>
            </div>

            <!-- Customer Analytics -->
            <div style="background-color: #2C2E36;" class="rounded-lg p-6 border border-gray-900">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-white">Customer Analytics</h3>
                    <button class="text-gray-400 hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                        </svg>
                    </button>
                </div>

                <!-- Donut Chart Simulation -->
                <div class="flex items-center justify-center mb-6">
                    <div class="relative w-32 h-32">
                        <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 36 36">
                            <!-- Background circle -->
                            <path class="text-gray-900" stroke="currentColor" stroke-width="4" fill="none"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <!-- Progress circles -->
                            <path class="text-blue-500" stroke="currentColor" stroke-width="4" fill="none"
                                stroke-dasharray="40, 100"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-green-500" stroke="currentColor" stroke-width="4" fill="none"
                                stroke-dasharray="30, 100" stroke-dashoffset="-40"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-yellow-500" stroke="currentColor" stroke-width="4" fill="none"
                                stroke-dasharray="30, 100" stroke-dashoffset="-70"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-white">2.4K</div>
                                <div class="text-xs text-gray-400">Total</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Legend -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-300">New Customers</span>
                        </div>
                        <span class="text-sm font-semibold text-white">40%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-300">Returning</span>
                        </div>
                        <span class="text-sm font-semibold text-white">30%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-300">Referrals</span>
                        </div>
                        <span class="text-sm font-semibold text-white">30%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="w-full" style="background-color: #2C2E36;" class="rounded-lg border border-gray-900">
            <div class="px-6 py-4 border-b border-gray-900">
                <h3 class="text-lg font-semibold text-white">Recent Activity</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-white">New appointment scheduled</p>
                            <p class="text-xs text-gray-400">John Doe - Kitchen Renovation</p>
                            <p class="text-xs text-gray-500">2 minutes ago</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-white">Portfolio updated</p>
                            <p class="text-xs text-gray-400">Added 3 new project images</p>
                            <p class="text-xs text-gray-500">15 minutes ago</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z"
                                    clip-rule="evenodd" />
                                <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V9a1 1 0 00-1-1h-1v-1z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-white">New blog post published</p>
                            <p class="text-xs text-gray-400">"Top 10 Home Renovation Tips"</p>
                            <p class="text-xs text-gray-500">1 hour ago</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-white">Payment received</p>
                            <p class="text-xs text-gray-400">$2,500 from ABC Construction</p>
                            <p class="text-xs text-gray-500">3 hours ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
