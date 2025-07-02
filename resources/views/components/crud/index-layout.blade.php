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
        /* Modern Animated Gradient Header with Particles */
        .animated-gradient-header {
            background: linear-gradient(-45deg, #8b5cf6, #6366f1, #3b82f6, #1d4ed8, #7c3aed, #f59e0b, #eab308);
            background-size: 500% 500%;
            animation: gradientShift 10s ease-in-out infinite;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Floating White Particles */
        .animated-gradient-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: floatPattern 20s linear infinite;
            pointer-events: none;
        }

        .animated-gradient-header::after {
            content: '';
            position: absolute;
            top: 20%;
            right: 10%;
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            border-radius: 50%;
            animation: floatBubble 6s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes floatPattern {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }

            100% {
                transform: translate(-50px, -50px) rotate(360deg);
            }
        }

        @keyframes floatBubble {

            0%,
            100% {
                transform: translateY(0) scale(1);
                opacity: 0.7;
            }

            50% {
                transform: translateY(-20px) scale(1.1);
                opacity: 0.9;
            }
        }

        /* Modern Glass Container with Animated Border */
        .glass-container {
            position: relative;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 8px 32px rgba(139, 92, 246, 0.1), 0 4px 16px rgba(99, 102, 241, 0.05);
            overflow: hidden;
        }

        .glass-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 1rem;
            border: 1px solid transparent;
            background: linear-gradient(90deg, #f59e0b, #eab308, #8b5cf6, #d946ef, #f59e0b) border-box;
            background-size: 200% auto;
            -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: destination-out;
            mask-composite: exclude;
            z-index: 0;
            animation: rotateGradient 3s linear infinite;
        }

        @keyframes rotateGradient {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 200% 50%;
            }
        }

        /* Glass Filter Styles */
        .glass-input-filter {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
        }

        .glass-input-filter:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(139, 92, 246, 0.4);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }

        .glass-input-filter::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        /* Modern Button Styles */
        .modern-button {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(139, 92, 246, 0.2), 0 4px 16px rgba(99, 102, 241, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .modern-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.2), rgba(139, 92, 246, 0.1));
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.6s ease;
            z-index: 0;
        }

        .modern-button:hover::before {
            width: 300px;
            height: 300px;
        }

        .modern-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(139, 92, 246, 0.3), 0 6px 20px rgba(245, 158, 11, 0.2), 0 4px 12px rgba(99, 102, 241, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.98);
        }

        .modern-button>* {
            position: relative;
            z-index: 1;
        }
    </style>
@endpush

<x-app-layout>
    {{-- Modern Dark Background with Gradient --}}
    <div class="min-h-screen" style="background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 50%, #0f0f0f 100%);">
        {{-- Animated Gradient Header --}}
        <div class="animated-gradient-header">
            <div class="container mx-auto px-4 py-8 relative z-10">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 drop-shadow-lg">
                        {{ $title }}
                    </h1>
                    <p class="text-lg md:text-xl text-white/90 drop-shadow-md">
                        {{ $subtitle }}
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 -mt-16 relative z-20">
            <!-- Success and error messages -->
            <div id="{{ $alertId }}"></div>
            @if (session()->has('message'))
                <x-crud.alert type="success" :message="session('message')" />
            @endif
            @if (session()->has('error'))
                <x-crud.alert type="error" :message="session('error')" />
            @endif

            <!-- Main Glass Container -->
            <div class="glass-container">
                <div class="relative z-10">
                    <!-- Filter and action bar -->
                    <x-crud.filter-bar :search-id="$searchId" :search-placeholder="$searchPlaceholder" :show-deleted-id="$showDeletedId" :show-deleted-label="$showDeletedLabel"
                        :per-page-id="$perPageId" :create-button-id="$createButtonId" :add-new-label="$addNewLabel" :manager-name="$managerName" />

                    <!-- Table -->
                    <div class="mt-6">
                        <x-crud.advanced-table :id="$tableId" :columns="$tableColumns" :manager-name="$managerName" />
                    </div>

                    <!-- Pagination -->
                    <div id="{{ $paginationId }}" class="mt-6 flex justify-between items-center"></div>
                </div>
            </div>
        </div>

        {{ $slot }}
    </div>
</x-app-layout>
