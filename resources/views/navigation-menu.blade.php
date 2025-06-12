<nav x-data="{ open: false, adminDropdown: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <div class="flex items-center">
                    <!-- V General Logo -->
                    <div class="w-8 h-8 bg-yellow-400 rounded flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">V General</span>
                </div>
            </div>

            <!-- Navigation Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                    class="text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-blue-600 font-semibold' : '' }}">
                    Dashboard
                </a>

                <!-- Administration -->
                @if (auth()->check() && (auth()->user()->can('READ_COMPANY_DATA') || auth()->user()->can('READ_USER')))
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium {{ request()->routeIs('company-data') || request()->routeIs('users') ? 'text-blue-600 font-semibold' : '' }}">
                            Administration
                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute z-50 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200"
                            style="display: none;">
                            <div class="py-1">
                                @can('READ_COMPANY_DATA')
                                    <a href="{{ route('company-data') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ __('company_data') }}
                                    </a>
                                @endcan
                                @can('READ_USER')
                                    <a href="{{ route('users') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ __('users') }}
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Services -->
                @if (auth()->check() && (auth()->user()->can('READ_EMAIL_DATA') || auth()->user()->can('READ_SERVICE_CATEGORY')))
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium {{ request()->routeIs('email-datas') || request()->routeIs('service-categories') ? 'text-blue-600 font-semibold' : '' }}">
                            Services
                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute z-50 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200"
                            style="display: none;">
                            <div class="py-1">
                                @can('READ_EMAIL_DATA')
                                    <a href="{{ route('email-datas') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ __('emails') }}
                                    </a>
                                @endcan
                                @can('READ_SERVICE_CATEGORY')
                                    <a href="{{ route('service-categories') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ __('service_categories') }}
                                    </a>
                                @endcan
                                @can('READ_USER')
                                    <a href="{{ route('call-records') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ __('Call Records') }}
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Retell AI -->
                @if (auth()->check() && auth()->user()->can('READ_USER'))
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium {{ request()->routeIs('call-records') ? 'text-blue-600 font-semibold' : '' }}">
                            Retell AI
                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute z-50 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200"
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('call-records') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('Call Records') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Appointments -->
                @if (auth()->check() && auth()->user()->can('READ_APPOINTMENT'))
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium {{ request()->routeIs('appointments.index') || request()->routeIs('appointment-calendar') ? 'text-blue-600 font-semibold' : '' }}">
                            Appointments
                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute z-50 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200"
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('appointments.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('manage_appointments') }}
                                </a>
                                <a href="{{ route('appointment-calendar') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('calendar_view') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endcan

                <!-- Blog Management -->
                @if (auth()->check() && (auth()->user()->can('READ_POST') || auth()->user()->can('READ_BLOG_CATEGORY')))
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.posts') || request()->routeIs('blog-categories') ? 'text-blue-600 font-semibold' : '' }}">
                            Blog Management
                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute z-50 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200"
                            style="display: none;">
                            <div class="py-1">
                                @can('READ_POST')
                                    <a href="{{ route('admin.posts') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ __('posts') }}
                                    </a>
                                @endcan
                                @can('READ_BLOG_CATEGORY')
                                    <a href="{{ route('blog-categories') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ __('blog_categories') }}
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Portfolio -->
                @if (auth()->check() && auth()->user()->can('READ_PORTFOLIO'))
                    <a href="{{ route('portfolios') }}"
                        class="text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium {{ request()->routeIs('portfolios') ? 'text-blue-600 font-semibold' : '' }}">
                        Portfolio
                    </a>
                @endif
        </div>

        <!-- Right Side -->
        <div class="hidden md:flex items-center space-x-4">
            <!-- Language Switcher -->
            <div class="flex items-center space-x-2">
                <img src="https://flagcdn.com/w20/us.png" alt="US Flag" class="w-5 h-3">
                <span class="text-sm font-medium text-gray-700">EN</span>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </div>

            <!-- Administrator Dropdown -->
            <div class="relative">
                <button @click="adminDropdown = !adminDropdown"
                    class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium">
                    <span>Administrator</span>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="adminDropdown" @click.away="adminDropdown = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50"
                    style="display: none;">
                    <div class="py-1">
                        <div class="px-4 py-2 border-b border-gray-200">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>

                        <a href="{{ route('profile.show') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            {{ __('profile') }}
                        </a>

                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                            <a href="{{ route('api-tokens.index') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ __('api_tokens') }}
                            </a>
                        @endif

                        <div class="border-t border-gray-200 mt-1"></div>

                        <form method="POST" action="{{ secure_url(route('logout', [], false)) }}" x-data>
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ __('log_out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu button -->
        <div class="md:hidden">
            <button @click="open = !open"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Mobile menu -->
<div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden bg-white border-t border-gray-200">
    <div class="px-2 pt-2 pb-3 space-y-1">
        <a href="{{ route('dashboard') }}"
            class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50' : '' }}">
            Dashboard
        </a>

        <!-- Mobile dropdowns would go here -->
        <!-- For simplicity, showing direct links -->
        @can('READ_COMPANY_DATA')
            <a href="{{ route('company-data') }}"
                class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                Company Data
            </a>
        @endcan

        @can('READ_USER')
            <a href="{{ route('users') }}"
                class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                Users
            </a>
        @endcan

        <!-- Add other mobile links as needed -->
    </div>
</div>
</nav>
