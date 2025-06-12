@props(['open' => false])

<div x-data="{
    sidebarOpen: $persist(false),
    darkMode: $persist(false),
    init() {
        this.darkMode = localStorage.getItem('darkMode') === 'true' || false;
        this.updateTheme();
        this.$watch('sidebarOpen', () => {
            this.$dispatch('sidebar-changed', this.sidebarOpen);
        });
    },
    updateTheme() {
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        localStorage.setItem('darkMode', this.darkMode);
    },
    toggleTheme() {
        this.darkMode = !this.darkMode;
        this.updateTheme();
    }
}" @toggle-sidebar.window="sidebarOpen = !sidebarOpen">
    <!-- Mobile backdrop -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
        @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">

        <!-- Logo/Brand -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ config('app.name', 'VGeneralContractors') }}
                </span>
            </div>
            <button @click="sidebarOpen = false"
                class="lg:hidden p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                </svg>
                {{ __('dashboard') }}
            </a>

            <!-- Administration -->
            @if (auth()->check() && (auth()->user()->can('READ_COMPANY_DATA') || auth()->user()->can('READ_USER')))
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ __('administration') }}
                        </div>
                        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-8 mt-2 space-y-1">
                        @can('READ_COMPANY_DATA')
                            <a href="{{ route('company-data') }}"
                                class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('company-data') ? 'text-blue-600 dark:text-blue-400' : '' }}">
                                {{ __('company_data') }}
                            </a>
                        @endcan
                        @can('READ_USER')
                            <a href="{{ route('users') }}"
                                class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('users') ? 'text-blue-600 dark:text-blue-400' : '' }}">
                                {{ __('users') }}
                            </a>
                        @endcan
                    </div>
                </div>
            @endif

            <!-- Services -->
            @if (auth()->check() && (auth()->user()->can('READ_EMAIL_DATA') || auth()->user()->can('READ_SERVICE_CATEGORY')))
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ __('services') }}
                        </div>
                        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-8 mt-2 space-y-1">
                        @can('READ_EMAIL_DATA')
                            <a href="{{ route('email-datas') }}"
                                class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('email-datas') ? 'text-blue-600 dark:text-blue-400' : '' }}">
                                {{ __('emails') }}
                            </a>
                        @endcan
                        @can('READ_SERVICE_CATEGORY')
                            <a href="{{ route('service-categories') }}"
                                class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('service-categories') ? 'text-blue-600 dark:text-blue-400' : '' }}">
                                {{ __('service_categories') }}
                            </a>
                        @endcan
                        @can('READ_USER')
                            <a href="{{ route('call-records') }}"
                                class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('call-records') ? 'text-blue-600 dark:text-blue-400' : '' }}">
                                {{ __('Call Records') }}
                            </a>
                        @endcan
                    </div>
                </div>
            @endif

            <!-- Appointments -->
            @if (auth()->check() && auth()->user()->can('READ_APPOINTMENT'))
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ __('appointments') }}
                        </div>
                        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-8 mt-2 space-y-1">
                        <a href="{{ route('appointments.index') }}"
                            class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('appointments.index') ? 'text-blue-600 dark:text-blue-400' : '' }}">
                            {{ __('manage_appointments') }}
                        </a>
                        <a href="{{ route('appointment-calendar') }}"
                            class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('appointment-calendar') ? 'text-blue-600 dark:text-blue-400' : '' }}">
                            {{ __('calendar_view') }}
                        </a>
                    </div>
                </div>
            @endif

            <!-- Blog Management -->
            @if (auth()->check() && (auth()->user()->can('READ_POST') || auth()->user()->can('READ_BLOG_CATEGORY')))
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            {{ __('blog_management') }}
                        </div>
                        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-8 mt-2 space-y-1">
                        @can('READ_POST')
                            <a href="{{ route('admin.posts') }}"
                                class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('admin.posts') ? 'text-blue-600 dark:text-blue-400' : '' }}">
                                {{ __('posts') }}
                            </a>
                        @endcan
                        @can('READ_BLOG_CATEGORY')
                            <a href="{{ route('blog-categories') }}"
                                class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('blog-categories') ? 'text-blue-600 dark:text-blue-400' : '' }}">
                                {{ __('blog_categories') }}
                            </a>
                        @endcan
                    </div>
                </div>
            @endif

            <!-- Portfolio -->
            @if (auth()->check() && auth()->user()->can('READ_PORTFOLIO'))
                <a href="{{ route('portfolios') }}"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('portfolios') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    {{ __('portfolio') }}
                </a>
            @endif

            <!-- Language Demo -->
            @auth
                <a href="{{ route('translation-demo') }}"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('translation-demo') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                    </svg>
                    {{ __('language') }} {{ __('demo') }}
                </a>
            @endauth
        </nav>

        <!-- Theme Toggle & User -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('theme') }}</span>
                <button @click="toggleTheme()"
                    class="flex items-center justify-center w-12 h-6 bg-gray-200 dark:bg-gray-700 rounded-full transition-colors">
                    <div :class="darkMode ? 'translate-x-6' : 'translate-x-1'"
                        class="w-4 h-4 bg-white dark:bg-gray-300 rounded-full shadow-md transform transition-transform">
                    </div>
                </button>
            </div>

            <!-- User Info -->
            <div class="flex items-center space-x-3">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <img class="w-8 h-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                        alt="{{ Auth::user()->name }}" />
                @else
                    <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </span>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                        {{ Auth::user()->email }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
