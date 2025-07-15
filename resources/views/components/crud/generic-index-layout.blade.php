@props([
    'title',
    'subtitle',
    'entityName',
    'entityNamePlural',
    'searchPlaceholder' => 'Search...',
    'showDeletedLabel' => 'Show Inactive',
    'addNewLabel' => 'Add New',
    'managerName',
    'tableColumns' => [],
    'tableId' => 'generic-table',
    'createButtonId' => 'createBtn',
    'searchId' => 'searchInput',
    'showDeletedId' => 'showDeleted',
    'perPageId' => 'perPage',
    'paginationId' => 'pagination',
    'alertId' => 'alertContainer',
    'theme' => 'black-crystal',
])

<x-app-layout>
    {{-- Modern container with consistent theme --}}
    <div class="min-h-screen {{ $theme === 'black-crystal' ? 'bg-black' : 'bg-gray-50' }}"
        style="{{ $theme === 'black-crystal' ? 'background: linear-gradient(to bottom, #000000, #1a1a1a);' : 'background: linear-gradient(to bottom, #f8fafc, #f1f5f9);' }}">

        {{-- Header section with modern design --}}
        <div class="p-4 sm:p-6 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto">
                <div class="mb-4 sm:mb-6">
                    <h1
                        class="text-2xl sm:text-3xl font-bold {{ $theme === 'black-crystal' ? 'text-white' : 'text-gray-900' }} mb-2">
                        {{ $title }}
                    </h1>
                    <p class="text-base sm:text-lg {{ $theme === 'black-crystal' ? 'text-gray-400' : 'text-gray-600' }}">
                        {{ $subtitle }}
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            {{-- Alert Messages --}}
            <div id="{{ $alertId }}" class="mb-4">
                @if (session()->has('message'))
                    <x-crud.alert type="success" :message="session('message')" />
                @endif
                @if (session()->has('error'))
                    <x-crud.alert type="error" :message="session('error')" />
                @endif
            </div>

            {{-- Action Bar with Modern Design --}}
            <div class="mb-6">
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4 rounded-xl {{ $theme === 'black-crystal' ? 'bg-gray-900/50' : 'bg-white/50' }} backdrop-blur-sm border {{ $theme === 'black-crystal' ? 'border-purple-500/20' : 'border-blue-300/20' }} shadow-lg">
                    {{-- Search and Filters --}}
                    <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                        {{-- Search Input --}}
                        <div class="relative flex-grow">
                            <input type="text" id="{{ $searchId }}"
                                class="w-full px-4 py-2 rounded-lg {{ $theme === 'black-crystal' ? 'bg-black/50 text-white border-purple-500/30' : 'bg-white/80 text-gray-900 border-blue-300/30' }} border focus:outline-none focus:ring-2 {{ $theme === 'black-crystal' ? 'focus:ring-purple-500/50' : 'focus:ring-blue-500/50' }} transition-all duration-300"
                                placeholder="{{ $searchPlaceholder }}">
                        </div>

                        {{-- Show Deleted Toggle --}}
                        <div class="flex items-center space-x-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="{{ $showDeletedId }}" class="sr-only peer">
                                <div
                                    class="w-11 h-6 {{ $theme === 'black-crystal' ? 'bg-gray-700' : 'bg-gray-200' }} peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all {{ $theme === 'black-crystal' ? 'peer-checked:bg-purple-600' : 'peer-checked:bg-blue-600' }}">
                                </div>
                                <span
                                    class="ml-3 text-sm {{ $theme === 'black-crystal' ? 'text-gray-300' : 'text-gray-600' }}">{{ $showDeletedLabel }}</span>
                            </label>
                        </div>

                        {{-- Per Page Select --}}
                        <select id="{{ $perPageId }}"
                            class="rounded-lg {{ $theme === 'black-crystal' ? 'bg-black/50 text-white border-purple-500/30' : 'bg-white/80 text-gray-900 border-blue-300/30' }} border focus:outline-none focus:ring-2 {{ $theme === 'black-crystal' ? 'focus:ring-purple-500/50' : 'focus:ring-blue-500/50' }} px-3 py-2">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>

                    {{-- Create Button --}}
                    <button id="{{ $createButtonId }}"
                        class="px-6 py-2 rounded-lg font-medium transition-all duration-300 {{ $theme === 'black-crystal' ? 'bg-purple-600 hover:bg-purple-700 text-white' : 'bg-blue-600 hover:bg-blue-700 text-white' }} shadow-lg hover:shadow-xl transform hover:scale-105">
                        {{ $addNewLabel }}
                    </button>
                </div>
            </div>

            {{-- Table Component --}}
            <div class="mb-6">
                <x-crud.generic-glassmorphic-table :id="$tableId" :columns="$tableColumns" :manager-name="$managerName"
                    :theme="$theme" />
            </div>

            {{-- Pagination --}}
            <div id="{{ $paginationId }}"
                class="mt-6 flex justify-between items-center {{ $theme === 'black-crystal' ? 'text-white/80' : 'text-gray-600' }}">
            </div>
        </div>

        {{-- Slot for additional content --}}
        {{ $slot }}
    </div>
</x-app-layout>
