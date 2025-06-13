@php
    $customStyle = 'background-color: #2C2E36;';
    $customHoverStyle = 'background-color: rgba(44, 46, 54, 0.5);';
@endphp

<div>
    <!-- Top Header -->
    <nav class="fixed top-0 left-0 right-0 z-50 border-b" style="background-color: #141414; border-color: #2C2E36;">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Left side - Logo and search -->
                <div class="flex items-center space-x-4">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center">
                            <img src="{{ asset('assets/logo/logo4-white.webp') }}" alt="V General Contractors Logo"
                                class="h-8 mr-3">
                        </a>
                    </div>

                    <!-- Search Bar - Desktop -->
                    <div class="relative hidden md:block">
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

                <!-- Right side - Language, Notifications, Messages, User menu -->
                <div class="flex items-center space-x-4">
                    <!-- Search Icon - Mobile -->
                    <div class="md:hidden relative" x-data="{ searchOpen: false }">
                        <button @click="searchOpen = !searchOpen" class="text-gray-400 hover:text-white p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>

                        <!-- Mobile Search Dropdown -->
                        <div x-show="searchOpen" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95" @click.away="searchOpen = false"
                            class="absolute right-0 top-12 w-80 rounded-lg shadow-lg border z-50 p-4"
                            style="background-color: #2C2E36; border-color: #2C2E36; display: none;">
                            <div class="relative">
                                <input type="text" placeholder="Start Search Here..."
                                    class="w-full text-gray-300 placeholder-gray-500 rounded-lg px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                                    style="background-color: #141414; border: 1px solid #2C2E36;">
                                <svg class="w-5 h-5 text-gray-500 absolute left-3 top-2.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Language Switcher -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button"
                            class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-400 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            @php
                                $currentLocale = app()->getLocale();
                                $languages = [
                                    'en' => [
                                        'name' => __('english'),
                                        'flag' => '<svg class="h-4 w-4" viewBox="0 0 20 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                       <rect width="20" height="15" fill="#B22234"/>
                                                       <rect width="20" height="1.15" y="1.15" fill="white"/>
                                                       <rect width="20" height="1.15" y="3.46" fill="white"/>
                                                       <rect width="20" height="1.15" y="5.77" fill="white"/>
                                                       <rect width="20" height="1.15" y="8.08" fill="white"/>
                                                       <rect width="20" height="1.15" y="10.39" fill="white"/>
                                                       <rect width="20" height="1.15" y="12.69" fill="white"/>
                                                       <rect width="8" height="8.08" fill="#3C3B6E"/>
                                                   </svg>',
                                        'code' => 'en',
                                    ],
                                    'es' => [
                                        'name' => __('spanish'),
                                        'flag' => '<svg class="h-4 w-4" viewBox="0 0 20 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                       <rect width="20" height="5" fill="#C60B1E"/>
                                                       <rect width="20" height="5" y="5" fill="#FFC400"/>
                                                       <rect width="20" height="5" y="10" fill="#C60B1E"/>
                                                   </svg>',
                                        'code' => 'es',
                                    ],
                                ];
                            @endphp
                            <span class="mr-2">{!! $languages[$currentLocale]['flag'] !!}</span>
                            <span>{{ $languages[$currentLocale]['name'] }}</span>
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
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
                            class="absolute z-50 mt-2 w-44 rounded-md shadow-lg origin-top-right right-0 ring-1 ring-black ring-opacity-5 focus:outline-none"
                            style="background-color: #2C2E36; display: none;">
                            <div class="py-1">
                                @foreach ($languages as $code => $language)
                                    <a href="{{ route('lang.switch', $code) }}"
                                        class="group flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 {{ $currentLocale === $code ? 'bg-gray-700' : '' }}">
                                        <span class="mr-3">{!! $language['flag'] !!}</span>
                                        <span>{{ $language['name'] }}</span>
                                        @if ($currentLocale === $code)
                                            <svg class="ml-auto h-4 w-4 text-green-500" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

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
    <div class="fixed left-2 sm:left-4 lg:left-6 top-16 bottom-0 w-16 z-40" style="background-color: #141414;">
        <div class="flex flex-col items-center py-4 space-y-4">
            <!-- Dashboard -->
            <div class="relative group">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center justify-center w-10 h-10 transition-all duration-300 cursor-pointer {{ request()->routeIs('dashboard') ? 'bg-yellow-400 text-gray-900 rounded-full' : 'text-gray-400 hover:text-white border border-gray-600/30 hover:border-yellow-400/50 bg-transparent rounded-full' }}"
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
                    <div class="absolute left-12 top-0 text-white px-3 py-2 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-150 whitespace-nowrap z-50"
                        style="background-color: #2C2E36;">
                        {{ __('dashboard') }}
                        <div class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-1 w-2 h-2 rotate-45"
                            style="background-color: #2C2E36;"></div>
                    </div>
                @endif
            </div>

            <!-- Administration Group -->
            @if (auth()->check() && (auth()->user()->can('READ_COMPANY_DATA') || auth()->user()->can('READ_USER')))
                <div class="relative group">
                    <div class="flex items-center justify-center w-10 h-10 transition-all duration-300 cursor-pointer {{ request()->routeIs('company-data') || request()->routeIs('users') ? 'bg-yellow-400 text-gray-900 rounded-full' : 'text-gray-400 hover:text-white border border-gray-600/30 hover:border-yellow-400/50 bg-transparent rounded-full' }}"
                        onmouseover="{{ !(request()->routeIs('company-data') || request()->routeIs('users')) ? 'this.style.backgroundColor=\'rgba(44, 46, 54, 0.5)\'' : '' }}"
                        onmouseout="{{ !(request()->routeIs('company-data') || request()->routeIs('users')) ? 'this.style.backgroundColor=\'transparent\'' : '' }}">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>

                    <!-- Submenu -->
                    <div class="absolute left-12 top-0 rounded-lg shadow-lg z-50 min-w-max opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-opacity duration-150"
                        style="background-color: #2C2E36;">
                        <div class="p-2 space-y-1">
                            <div class="text-xs text-yellow-400 px-3 py-1 font-medium uppercase tracking-wide">
                                {{ __('administration') }}</div>
                            @can('READ_COMPANY_DATA')
                                <a href="{{ route('company-data') }}"
                                    class="block px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded {{ request()->routeIs('company-data') ? 'bg-gray-700 text-white' : '' }}">
                                    {{ __('company_data') }}
                                </a>
                            @endcan
                            @can('READ_USER')
                                <a href="{{ route('users') }}"
                                    class="block px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded {{ request()->routeIs('users') ? 'bg-gray-700 text-white' : '' }}">
                                    {{ __('users') }}
                                </a>
                            @endcan
                        </div>
                        <div class="absolute left-0 top-4 transform -translate-x-1 w-2 h-2 rotate-45"
                            style="background-color: #2C2E36;"></div>
                    </div>
                </div>
            @endif

            <!-- Services Group -->
            @if (auth()->check() && (auth()->user()->can('READ_EMAIL_DATA') || auth()->user()->can('READ_SERVICE_CATEGORY')))
                <div class="relative group">
                    <div class="flex items-center justify-center w-10 h-10 transition-all duration-300 cursor-pointer {{ request()->routeIs('email-datas') || request()->routeIs('service-categories') || request()->routeIs('call-records') ? 'bg-yellow-400 text-gray-900 rounded-full' : 'text-gray-400 hover:text-white border border-gray-600/30 hover:border-yellow-400/50 bg-transparent rounded-full' }}"
                        onmouseover="{{ !(request()->routeIs('email-datas') || request()->routeIs('service-categories') || request()->routeIs('call-records')) ? 'this.style.backgroundColor=\'rgba(44, 46, 54, 0.5)\'' : '' }}"
                        onmouseout="{{ !(request()->routeIs('email-datas') || request()->routeIs('service-categories') || request()->routeIs('call-records')) ? 'this.style.backgroundColor=\'transparent\'' : '' }}">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <!-- Submenu -->
                    <div class="absolute left-12 top-0 rounded-lg shadow-lg z-50 min-w-max opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-opacity duration-150"
                        style="background-color: #2C2E36;">
                        <div class="p-2 space-y-1">
                            <div class="text-xs text-yellow-400 px-3 py-1 font-medium uppercase tracking-wide">
                                {{ __('services') }}</div>
                            @can('READ_EMAIL_DATA')
                                <a href="{{ route('email-datas') }}"
                                    class="block px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded {{ request()->routeIs('email-datas') ? 'bg-gray-700 text-white' : '' }}">
                                    {{ __('emails') }}
                                </a>
                            @endcan
                            @can('READ_SERVICE_CATEGORY')
                                <a href="{{ route('service-categories') }}"
                                    class="block px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded {{ request()->routeIs('service-categories') ? 'bg-gray-700 text-white' : '' }}">
                                    {{ __('service_categories') }}
                                </a>
                            @endcan
                            @can('READ_USER')
                                <a href="{{ route('call-records') }}"
                                    class="block px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded {{ request()->routeIs('call-records') ? 'bg-gray-700 text-white' : '' }}">
                                    {{ __('Call Records') }}
                                </a>
                            @endcan
                        </div>
                        <div class="absolute left-0 top-4 transform -translate-x-1 w-2 h-2 rotate-45"
                            style="background-color: #2C2E36;"></div>
                    </div>
                </div>
            @endif

            <!-- Appointments Group -->
            @if (auth()->check() && auth()->user()->can('READ_APPOINTMENT'))
                <div class="relative group">
                    <div class="flex items-center justify-center w-10 h-10 transition-all duration-300 cursor-pointer {{ request()->routeIs('appointments.index') || request()->routeIs('appointment-calendar') ? 'bg-yellow-400 text-gray-900 rounded-full' : 'text-gray-400 hover:text-white border border-gray-600/30 hover:border-yellow-400/50 bg-transparent rounded-full' }}"
                        onmouseover="{{ !(request()->routeIs('appointments.index') || request()->routeIs('appointment-calendar')) ? 'this.style.backgroundColor=\'rgba(44, 46, 54, 0.5)\'' : '' }}"
                        onmouseout="{{ !(request()->routeIs('appointments.index') || request()->routeIs('appointment-calendar')) ? 'this.style.backgroundColor=\'transparent\'' : '' }}">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>

                    <!-- Submenu -->
                    <div class="absolute left-12 top-0 rounded-lg shadow-lg z-50 min-w-max opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-opacity duration-150"
                        style="background-color: #2C2E36;">
                        <div class="p-2 space-y-1">
                            <div class="text-xs text-yellow-400 px-3 py-1 font-medium uppercase tracking-wide">
                                {{ __('appointments') }}</div>
                            <a href="{{ route('appointments.index') }}"
                                class="block px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded {{ request()->routeIs('appointments.index') ? 'bg-gray-700 text-white' : '' }}">
                                {{ __('manage_appointments') }}
                            </a>
                            <a href="{{ route('appointment-calendar') }}"
                                class="block px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded {{ request()->routeIs('appointment-calendar') ? 'bg-gray-700 text-white' : '' }}">
                                {{ __('calendar_view') }}
                            </a>
                        </div>
                        <div class="absolute left-0 top-4 transform -translate-x-1 w-2 h-2 rotate-45"
                            style="background-color: #2C2E36;"></div>
                    </div>
                </div>
            @endif

            <!-- Blog Management Group -->
            @if (auth()->check() && (auth()->user()->can('READ_POST') || auth()->user()->can('READ_BLOG_CATEGORY')))
                <div class="relative group">
                    <div class="flex items-center justify-center w-10 h-10 transition-all duration-300 cursor-pointer {{ request()->routeIs('admin.posts') || request()->routeIs('blog-categories') ? 'bg-yellow-400 text-gray-900 rounded-full' : 'text-gray-400 hover:text-white border border-gray-600/30 hover:border-yellow-400/50 bg-transparent rounded-full' }}"
                        onmouseover="{{ !(request()->routeIs('admin.posts') || request()->routeIs('blog-categories')) ? 'this.style.backgroundColor=\'rgba(44, 46, 54, 0.5)\'' : '' }}"
                        onmouseout="{{ !(request()->routeIs('admin.posts') || request()->routeIs('blog-categories')) ? 'this.style.backgroundColor=\'transparent\'' : '' }}">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>

                    <!-- Submenu -->
                    <div class="absolute left-12 top-0 rounded-lg shadow-lg z-50 min-w-max opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-opacity duration-150"
                        style="background-color: #2C2E36;">
                        <div class="p-2 space-y-1">
                            <div class="text-xs text-yellow-400 px-3 py-1 font-medium uppercase tracking-wide">
                                {{ __('blog_management') }}</div>
                            @can('READ_POST')
                                <a href="{{ route('admin.posts') }}"
                                    class="block px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded {{ request()->routeIs('admin.posts') ? 'bg-gray-700 text-white' : '' }}">
                                    {{ __('posts') }}
                                </a>
                            @endcan
                            @can('READ_BLOG_CATEGORY')
                                <a href="{{ route('blog-categories') }}"
                                    class="block px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded {{ request()->routeIs('blog-categories') ? 'bg-gray-700 text-white' : '' }}">
                                    {{ __('blog_categories') }}
                                </a>
                            @endcan
                        </div>
                        <div class="absolute left-0 top-4 transform -translate-x-1 w-2 h-2 rotate-45"
                            style="background-color: #2C2E36;"></div>
                    </div>
                </div>
            @endif

            <!-- Portfolio -->
            @if (auth()->check() && auth()->user()->can('READ_PORTFOLIO'))
                <div class="relative group">
                    <a href="{{ route('portfolios') }}"
                        class="flex items-center justify-center w-10 h-10 transition-all duration-300 cursor-pointer {{ request()->routeIs('portfolios') ? 'bg-yellow-400 text-gray-900 rounded-full' : 'text-gray-400 hover:text-white border border-gray-600/30 hover:border-yellow-400/50 bg-transparent rounded-full' }}"
                        onmouseover="{{ !request()->routeIs('portfolios') ? 'this.style.backgroundColor=\'rgba(44, 46, 54, 0.5)\'' : '' }}"
                        onmouseout="{{ !request()->routeIs('portfolios') ? 'this.style.backgroundColor=\'transparent\'' : '' }}">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </a>
                    @if (!request()->routeIs('portfolios'))
                        <div class="absolute left-12 top-0 text-white px-3 py-2 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-150 whitespace-nowrap z-50"
                            style="background-color: #2C2E36;">
                            {{ __('portfolio') }}
                            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-1 w-2 h-2 rotate-45"
                                style="background-color: #2C2E36;"></div>
                        </div>
                    @endif
                </div>
            @endif


        </div>
    </div>
</div>
