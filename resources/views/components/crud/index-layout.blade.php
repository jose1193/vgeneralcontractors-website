@props([
    'title',
    'subtitle',
    'entityName',
    'entityNamePlural',
    'searchPlaceholder',
    'showDeletedLabel',
    'addNewLabel',
    'managerName',
    'tableColumns' => [],
    'tableId' => 'crud-table',
    'createButtonId' => 'createBtn',
    'searchId' => 'searchInput',
    'showDeletedId' => 'showDeleted',
    'perPageId' => 'perPage',
    'paginationId' => 'pagination',
    'alertId' => 'alertContainer',
])

@push('styles')
    <style>
        /* Glassmorphic Container */
        .glassmorphic-container {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border-radius: 16px;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated Border */
        .animated-border {
            position: relative;
            overflow: hidden;
        }
        
        .animated-border::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4, #ffeaa7, #dda0dd, #ff6b6b);
            background-size: 400% 400%;
            animation: gradientBorder 4s ease infinite;
            border-radius: 18px;
            z-index: -1;
        }
        
        @keyframes gradientBorder {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        /* Border Glow Effect */
        .border-glow {
            box-shadow: 
                0 0 20px rgba(255, 107, 107, 0.3),
                0 0 40px rgba(78, 205, 196, 0.2),
                0 0 60px rgba(69, 183, 209, 0.1);
            animation: borderGlow 3s ease-in-out infinite alternate;
        }
        
        @keyframes borderGlow {
            from { box-shadow: 0 0 20px rgba(255, 107, 107, 0.3), 0 0 40px rgba(78, 205, 196, 0.2), 0 0 60px rgba(69, 183, 209, 0.1); }
            to { box-shadow: 0 0 30px rgba(255, 107, 107, 0.5), 0 0 60px rgba(78, 205, 196, 0.3), 0 0 90px rgba(69, 183, 209, 0.2); }
        }
        
        /* Table Shadow Animation */
        .table-shadow {
            animation: tableShadow 6s ease-in-out infinite;
        }
        
        @keyframes tableShadow {
            0%, 100% { box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37); }
            50% { box-shadow: 0 12px 48px 0 rgba(31, 38, 135, 0.5); }
        }
        
        /* Shimmer Effect */
        .shimmer {
            position: relative;
            overflow: hidden;
        }
        
        .shimmer::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        /* Filter Bar Glassmorphism */
        .filter-bar {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.08));
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 4px 16px 0 rgba(31, 38, 135, 0.2);
            border-radius: 12px;
        }
        
        /* Enhanced Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.1));
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.2));
        }
        
        /* Hover Effects */
        .glassmorphic-hover {
            transition: all 0.3s ease;
        }
        
        .glassmorphic-hover:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
            transform: translateY(-2px);
            box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.5);
        }
    </style>
@endpush

<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white shimmer">{{ $title }}</h1>
                @if($subtitle)
                    <p class="mt-2 text-gray-300">{{ $subtitle }}</p>
                @endif
            </div>

            <!-- Success/Error Messages -->
            <div id="{{ $alertId }}" class="mb-6"></div>

            <!-- Main Container with Glassmorphic Design -->
            <div class="glassmorphic-container animated-border border-glow table-shadow glassmorphic-hover custom-scrollbar">
                <!-- Filter Bar -->
                <div class="filter-bar p-6 border-b border-white/20">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <!-- Search -->
                        <div class="flex-1 max-w-md">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" id="{{ $searchId }}" class="block w-full pl-10 pr-3 py-2 border border-white/30 rounded-md leading-5 bg-white/10 backdrop-blur-sm text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400/50 transition-all duration-200" placeholder="{{ $searchPlaceholder }}">
                            </div>
                        </div>

                        <!-- Controls -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <!-- Show Deleted Toggle -->
                            <div class="flex items-center">
                                <input type="checkbox" id="{{ $showDeletedId }}" class="h-4 w-4 text-blue-400 focus:ring-blue-400/50 border-white/30 rounded bg-white/10 backdrop-blur-sm">
                                <label for="{{ $showDeletedId }}" class="ml-2 text-sm text-white/80">{{ $showDeletedLabel }}</label>
                            </div>

                            <!-- Per Page -->
                            <div class="flex items-center gap-2">
                                <label for="{{ $perPageId }}" class="text-sm text-white/80">{{ __('per_page') }}:</label>
                                <select id="{{ $perPageId }}" class="border border-white/30 rounded-md bg-white/10 backdrop-blur-sm text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400/50 transition-all duration-200">
                                    <option value="10" class="bg-gray-800">10</option>
                                    <option value="25" class="bg-gray-800">25</option>
                                    <option value="50" class="bg-gray-800">50</option>
                                    <option value="100" class="bg-gray-800">100</option>
                                </select>
                            </div>

                            <!-- Add New Button -->
                            <button id="{{ $createButtonId }}" class="inline-flex items-center px-4 py-2 border border-white/30 text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-500/20 to-purple-500/20 backdrop-blur-sm hover:from-blue-500/30 hover:to-purple-500/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400/50 transition-all duration-200 transform hover:scale-105">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ $addNewLabel }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Container -->
                <x-crud.advanced-table 
                    :id="$tableId" 
                    :columns="$tableColumns" 
                    :manager-name="$managerName" 
                    loading-text="{{ __('loading') }}" 
                    no-data-text="{{ __('no_data_available') }}" 
                    :responsive="true" 
                    :sortable="true" 
                    :dark-mode="true" 
                />

                <!-- Pagination -->
                <div id="{{ $paginationId }}" class="px-6 py-4 border-t border-white/20 bg-white/5 backdrop-blur-sm"></div>
            </div>
        </div>

        {{ $slot }}
    </div>
</x-app-layout>
