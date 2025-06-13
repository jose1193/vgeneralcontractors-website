@php
    $customStyle = 'background-color: #2C2E36;';
    $customHoverStyle = 'background-color: rgba(44, 46, 54, 0.5);';
@endphp

<div>
    <!-- Top Header -->
    <nav class="fixed top-0 left-0 right-0 z-50 border-b" style="background-color: #1A1C22; border-color: #2C2E36;">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Left side - Logo and search -->
                <div class="flex items-center space-x-4">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-400 rounded flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="relative">
                        <input type="text" placeholder="Start Search Here..."
                            class="text-gray-300 placeholder-gray-500 rounded-lg px-4 py-2 pl-10 w-64 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                            style="background-color: #2C2E36; border: 1px solid #2C2E36;">
                        <svg class="w-5 h-5 text-gray-500 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Right side - User menu -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <button class="text-gray-400 hover:text-white p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-5 5v-5zM10.5 3.5a6 6 0 0 1 6 6v2l1.5 3h-15l1.5-3v-2a6 6 0 0 1 6-6z" />
                        </svg>
                    </button>

                    <!-- Messages -->
                    <button class="text-gray-400 hover:text-white p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </button>

                    <!-- User Profile -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center space-x-2 text-gray-300 hover:text-white">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <img class="w-8 h-8 rounded-full object-cover border-2 border-gray-700"
                                    src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            @else
                                <div class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center">
                                    <span
                                        class="text-sm font-medium text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg border z-50"
                            style="background-color: #2C2E36; border-color: #2C2E36; display: none;">
                            <div class="py-2">
                                <div class="px-4 py-2 border-b" style="border-color: #2C2E36;">
                                    <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                                </div>

                                <a href="{{ route('profile.show') }}"
                                    class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                    {{ __('profile') }}
                                </a>

                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <a href="{{ route('api-tokens.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                        {{ __('api_tokens') }}
                                    </a>
                                @endif

                                <div class="border-t mt-2" style="border-color: #2C2E36;"></div>

                                <form method="POST" action="{{ secure_url(route('logout', [], false)) }}" x-data>
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                        {{ __('log_out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="fixed left-0 top-16 bottom-0 w-16 z-40" style="background-color: #1A1C22;">
        <div class="flex flex-col items-center py-4 space-y-4">
            <!-- Dashboard -->
            <div class="relative group">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center justify-center w-10 h-10 transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-yellow-400 text-gray-900 rounded-full' : 'text-gray-400 hover:text-white border border-gray-600/30 hover:border-yellow-400/50 bg-transparent rounded-full' }}"
                    style="{{ !request()->routeIs('dashboard') ? 'transition: all 0.3s; &:hover { background-color: rgba(44, 46, 54, 0.5) !important; }' : '' }}"
                    onmouseover="{{ !request()->routeIs('dashboard') ? 'this.style.backgroundColor=\'rgba(44, 46, 54, 0.5)\'' : '' }}"
                    onmouseout="{{ !request()->routeIs('dashboard') ? 'this.style.backgroundColor=\'transparent\'' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v4" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v4" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 5v4" />
                    </svg>
                </a>
                @if (!request()->routeIs('dashboard'))
                    <div class="absolute left-12 top-0 text-white px-3 py-2 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 whitespace-nowrap z-50"
                        style="background-color: #2C2E36;">
                        Dashboard
                        <div class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-1 w-2 h-2 rotate-45"
                            style="background-color: #2C2E36;">
                        </div>
                    </div>
                @endif
            </div>

            <!-- Analytics -->
            <div class="relative group">
                <a href="#"
                    class="flex items-center justify-center w-10 h-10 transition-all duration-300 text-gray-400 hover:text-white border border-gray-600/30 hover:border-yellow-400/50 bg-transparent rounded-full"
                    onmouseover="this.style.backgroundColor='rgba(44, 46, 54, 0.5)'"
                    onmouseout="this.style.backgroundColor='transparent'">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </a>
                <div class="absolute left-12 top-0 text-white px-3 py-2 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 whitespace-nowrap z-50"
                    style="background-color: #2C2E36;">
                    Analytics
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-1 w-2 h-2 rotate-45"
                        style="background-color: #2C2E36;">
                    </div>
                </div>
            </div>

            <!-- Users -->
            @can('READ_USER')
                <div class="relative group">
                    <a href="{{ route('users') }}"
                        class="flex items-center justify-center w-10 h-10 transition-all duration-300 {{ request()->routeIs('users') ? 'bg-yellow-400 text-gray-900 rounded-full' : 'text-gray-400 hover:text-white border border-gray-600/30 hover:border-yellow-400/50 bg-transparent rounded-full' }}"
                        onmouseover="{{ !request()->routeIs('users') ? 'this.style.backgroundColor=\'rgba(44, 46, 54, 0.5)\'' : '' }}"
                        onmouseout="{{ !request()->routeIs('users') ? 'this.style.backgroundColor=\'transparent\'' : '' }}">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </a>
                    @if (!request()->routeIs('users'))
                        <div class="absolute left-12 top-0 text-white px-3 py-2 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 whitespace-nowrap z-50"
                            style="background-color: #2C2E36;">
                            Users
                            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-1 w-2 h-2 rotate-45"
                                style="background-color: #2C2E36;">
                            </div>
                        </div>
                    @endif
                </div>
            @endcan

            <!-- Services -->
            @can('READ_SERVICE_CATEGORY')
                <div class="relative group">
                    <a href="{{ route('service-categories') }}"
                        class="flex items-center justify-center w-10 h-10 transition-all duration-300 {{ request()->routeIs('service-categories') ? 'bg-yellow-400 text-gray-900 rounded-full' : 'text-gray-400 hover:text-white border border-gray-600/30 hover:border-yellow-400/50 bg-transparent rounded-full' }}"
                        onmouseover="{{ !request()->routeIs('service-categories') ? 'this.style.backgroundColor=\'rgba(44, 46, 54, 0.5)\'' : '' }}"
                        onmouseout="{{ !request()->routeIs('service-categories') ? 'this.style.backgroundColor=\'transparent\'' : '' }}">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </a>
                    @if (!request()->routeIs('service-categories'))
                        <div class="absolute left-12 top-0 text-white px-3 py-2 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 whitespace-nowrap z-50"
                            style="background-color: #2C2E36;">
                            Services
                            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-1 w-2 h-2 rotate-45"
                                style="background-color: #2C2E36;">
                            </div>
                        </div>
                    @endif
                </div>
            @endcan

            <!-- Appointments -->
            @can('READ_APPOINTMENT')
                <div class="relative group">
                    <a href="{{ route('appointments.index') }}"
                        class="flex items-center justify-center w-10 h-10 transition-all duration-300 {{ request()->routeIs('appointments.index') || request()->routeIs('appointment-calendar') ? 'bg-yellow-400 text-gray-900 rounded-full' : 'text-gray-400 hover:text-white border border-gray-600/30 hover:border-yellow-400/50 bg-transparent rounded-full' }}"
                        onmouseover="{{ !request()->routeIs('appointments.index') && !request()->routeIs('appointment-calendar') ? 'this.style.backgroundColor=\'rgba(44, 46, 54, 0.5)\'' : '' }}"
                        onmouseout="{{ !request()->routeIs('appointments.index') && !request()->routeIs('appointment-calendar') ? 'this.style.backgroundColor=\'transparent\'' : '' }}">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </a>
                    @if (!request()->routeIs('appointments.index') && !request()->routeIs('appointment-calendar'))
                        <div class="absolute left-12 top-0 text-white px-3 py-2 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 whitespace-nowrap z-50"
                            style="background-color: #2C2E36;">
                            Appointments
                            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-1 w-2 h-2 rotate-45"
                                style="background-color: #2C2E36;">
                            </div>
                        </div>
                    @endif
                </div>
            @endcan

            <!-- Portfolio -->
            @can('READ_PORTFOLIO')
                <div class="relative group">
                    <a href="{{ route('portfolios') }}"
                        class="flex items-center justify-center w-10 h-10 transition-all duration-300 {{ request()->routeIs('portfolios') ? 'bg-yellow-400 text-gray-900 rounded-full' : 'text-gray-400 hover:text-white border border-gray-600/30 hover:border-yellow-400/50 bg-transparent rounded-full' }}"
                        onmouseover="{{ !request()->routeIs('portfolios') ? 'this.style.backgroundColor=\'rgba(44, 46, 54, 0.5)\'' : '' }}"
                        onmouseout="{{ !request()->routeIs('portfolios') ? 'this.style.backgroundColor=\'transparent\'' : '' }}">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </a>
                    @if (!request()->routeIs('portfolios'))
                        <div class="absolute left-12 top-0 text-white px-3 py-2 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 whitespace-nowrap z-50"
                            style="background-color: #2C2E36;">
                            Portfolio
                            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-1 w-2 h-2 rotate-45"
                                style="background-color: #2C2E36;">
                            </div>
                        </div>
                    @endif
                </div>
            @endcan

            <!-- Blog -->
            @can('READ_POST')
                <div class="relative group" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center justify-center w-10 h-10 transition-all duration-300 {{ request()->routeIs('admin.posts') || request()->routeIs('blog-categories') ? 'bg-yellow-400 text-gray-900 rounded-full' : 'text-gray-400 hover:text-white border border-gray-600/30 hover:border-yellow-400/50 bg-transparent rounded-full' }}"
                        onmouseover="{{ !request()->routeIs('admin.posts') && !request()->routeIs('blog-categories') ? 'this.style.backgroundColor=\'rgba(44, 46, 54, 0.5)\'' : '' }}"
                        onmouseout="{{ !request()->routeIs('admin.posts') && !request()->routeIs('blog-categories') ? 'this.style.backgroundColor=\'transparent\'' : '' }}">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </button>

                    @if (!request()->routeIs('admin.posts') && !request()->routeIs('blog-categories'))
                        <div class="absolute left-12 top-0 text-white px-3 py-2 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 whitespace-nowrap z-50"
                            style="background-color: #2C2E36;">
                            Blog
                            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-1 w-2 h-2 rotate-45"
                                style="background-color: #2C2E36;">
                            </div>
                        </div>
                    @endif

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="absolute left-12 top-0 w-36 rounded-lg shadow-lg border z-50"
                        style="background-color: #2C2E36; border-color: #2C2E36; display: none;">
                        <div class="py-2">
                            <a href="{{ route('admin.posts') }}"
                                class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Posts</a>
                            <a href="{{ route('blog-categories') }}"
                                class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Categories</a>
                        </div>
                    </div>
                </div>
            @endcan

            <!-- Settings -->
            @can('READ_COMPANY_DATA')
                <div class="relative group">
                    <a href="{{ route('company-data') }}"
                        class="flex items-center justify-center w-10 h-10 transition-all duration-300 {{ request()->routeIs('company-data') ? 'bg-yellow-400 text-gray-900 rounded-full' : 'text-gray-400 hover:text-white border border-gray-600/30 hover:border-yellow-400/50 bg-transparent rounded-full' }}"
                        onmouseover="{{ !request()->routeIs('company-data') ? 'this.style.backgroundColor=\'rgba(44, 46, 54, 0.5)\'' : '' }}"
                        onmouseout="{{ !request()->routeIs('company-data') ? 'this.style.backgroundColor=\'transparent\'' : '' }}">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </a>
                    @if (!request()->routeIs('company-data'))
                        <div class="absolute left-12 top-0 text-white px-3 py-2 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-300 whitespace-nowrap z-50"
                            style="background-color: #2C2E36;">
                            Settings
                            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-1 w-2 h-2 rotate-45"
                                style="background-color: #2C2E36;">
                            </div>
                        </div>
                    @endif
                </div>
            @endcan
        </div>
    </div>
</div>
