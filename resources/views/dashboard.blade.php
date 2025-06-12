<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="flex items-center space-x-4">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    + Create Appointment
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Contenido básico del dashboard -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Welcome to Dashboard') }}
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('This is your main dashboard. Use the sidebar to navigate through different sections.') }}
            </p>
        </div>
    </div>
</x-app-layout>
