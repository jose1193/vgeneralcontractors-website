<nav x-data="{ open: false, dark: $persist(false) }" :class="{ 'dark': dark }"
    class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <x-application-mark class="h-8 w-8" />
                    <span class="font-bold text-lg text-yellow-600">V General</span>
                </a>
            </div>
            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-6 items-center">
                <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                <a href="{{ route('company-data') }}" class="nav-link">Administración</a>
                <a href="{{ route('service-categories') }}" class="nav-link">Servicios</a>
                <a href="{{ route('appointments.index') }}" class="nav-link">Citas</a>
                <a href="{{ route('admin.posts') }}" class="nav-link">Blog</a>
                <a href="{{ route('portfolios') }}" class="nav-link">Portafolio</a>
                <x-language-switcher />
                <a href="{{ route('profile.show') }}" class="nav-link">Perfil</a>
                <form method="POST" action="{{ secure_url(route('logout', [], false)) }}" class="inline">
                    @csrf
                    <button type="submit" class="nav-link">Salir</button>
                </form>
                <button @click="dark = !dark"
                    class="ml-2 p-2 rounded hover:bg-yellow-100 dark:hover:bg-yellow-900 transition">
                    <svg x-show="!dark" class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.66 4.95l-.71-.71M4.05 4.93l-.71-.71" />
                    </svg>
                    <svg x-show="dark" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
                    </svg>
                </button>
            </div>
            <!-- Mobile Hamburger -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open"
                    class="p-2 rounded hover:bg-yellow-100 dark:hover:bg-yellow-900 transition">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Mobile Menu -->
    <div x-show="open"
        class="md:hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 px-4 pt-2 pb-4 space-y-2">
        <a href="{{ route('dashboard') }}" class="block nav-link">Dashboard</a>
        <a href="{{ route('company-data') }}" class="block nav-link">Administración</a>
        <a href="{{ route('service-categories') }}" class="block nav-link">Servicios</a>
        <a href="{{ route('appointments.index') }}" class="block nav-link">Citas</a>
        <a href="{{ route('admin.posts') }}" class="block nav-link">Blog</a>
        <a href="{{ route('portfolios') }}" class="block nav-link">Portafolio</a>
        <div class="py-2"><x-language-switcher /></div>
        <a href="{{ route('profile.show') }}" class="block nav-link">Perfil</a>
        <form method="POST" action="{{ secure_url(route('logout', [], false)) }}">
            @csrf
            <button type="submit" class="block nav-link w-full text-left">Salir</button>
        </form>
        <button @click="dark = !dark"
            class="mt-2 p-2 rounded hover:bg-yellow-100 dark:hover:bg-yellow-900 transition w-full flex items-center justify-center">
            <svg x-show="!dark" class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor"
                stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.66 4.95l-.71-.71M4.05 4.93l-.71-.71" />
            </svg>
            <svg x-show="dark" class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor"
                stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
            </svg>
            <span x-text="dark ? 'Light' : 'Dark'"></span>
        </button>
    </div>
    <style>
        .nav-link {
            @apply relative text-gray-700 dark:text-gray-200 hover:text-yellow-600 dark:hover:text-yellow-400 transition font-medium px-2 py-1 rounded;
        }

        .nav-link::after {
            content: '';
            display: block;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #eab308 0%, #facc15 100%);
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: absolute;
            left: 0;
            bottom: -2px;
            border-radius: 2px;
        }

        .nav-link:hover::after {
            width: 100%;
        }
    </style>
</nav>
