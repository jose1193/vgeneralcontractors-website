<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div
                class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-white leading-tight">
                    {{ __('dashboard') }}
                </h2>
                <p class="text-slate-400 text-sm mt-1">Welcome back! Here's what's happening with your business.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="dashboard-card bg-slate-800/60 backdrop-blur-lg border border-slate-700/50 overflow-hidden shadow-2xl rounded-2xl">
                <x-welcome />
            </div>
        </div>
    </div>
</x-app-layout>
