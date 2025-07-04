@props([
    'id' => 'createBtn',
    'label' => 'Add New',
    'entityName' => '',
    'icon' => 'plus',
    'size' => 'md',
    'variant' => 'primary',
    'class' => '',
])

@php
    $baseClasses =
        'create-btn inline-flex items-center justify-center backdrop-blur-md border border-white/30 dark:border-gray-600/50 rounded-lg font-semibold uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition-all duration-300 shadow-lg animate-button-glow';

    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-xs',
        'lg' => 'px-6 py-3 text-sm',
    ];

    $variantClasses = [
        'primary' =>
            'bg-gradient-to-r from-green-500/80 to-emerald-600/80 text-white hover:from-green-600/90 hover:to-emerald-700/90 active:from-green-700/95 active:to-emerald-800/95 focus:ring-green-400/50 hover:shadow-green-500/25 hover:shadow-xl',
        'secondary' =>
            'bg-gradient-to-r from-blue-500/80 to-indigo-600/80 text-white hover:from-blue-600/90 hover:to-indigo-700/90 active:from-blue-700/95 active:to-indigo-800/95 focus:ring-blue-400/50 hover:shadow-blue-500/25 hover:shadow-xl',
        'success' =>
            'bg-gradient-to-r from-emerald-500/80 to-teal-600/80 text-white hover:from-emerald-600/90 hover:to-teal-700/90 active:from-emerald-700/95 active:to-teal-800/95 focus:ring-emerald-400/50 hover:shadow-emerald-500/25 hover:shadow-xl',
    ];

    $iconPaths = [
        'plus' => 'M12 6v6m0 0v6m0-6h6m-6 0H6',
        'add' => 'M12 6v6m0 0v6m0-6h6m-6 0H6',
        'create' => 'M12 6v6m0 0v6m0-6h6m-6 0H6',
        'new' => 'M12 6v6m0 0v6m0-6h6m-6 0H6',
    ];

    $classes =
        $baseClasses .
        ' ' .
        ($sizeClasses[$size] ?? $sizeClasses['md']) .
        ' ' .
        ($variantClasses[$variant] ?? $variantClasses['primary']) .
        ' ' .
        $class;
@endphp

<button id="{{ $id }}" type="button" class="{{ $classes }} w-full sm:w-auto"
    title="Create {{ $entityName ? ucfirst($entityName) : 'New Item' }}">
    <span class="mr-2 animate-icon-pulse">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="{{ $iconPaths[$icon] ?? $iconPaths['plus'] }}" />
        </svg>
    </span>
    {{ $label }}
</button>

@push('styles')
    <style>
        /* Glassmorphic Button Animations */
        @keyframes button-glow {
            0%, 100% {
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1),
                           0 0 0 1px rgba(255, 255, 255, 0.1),
                           inset 0 1px 0 rgba(255, 255, 255, 0.2);
            }
            50% {
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15),
                           0 0 0 1px rgba(255, 255, 255, 0.2),
                           inset 0 1px 0 rgba(255, 255, 255, 0.3);
            }
        }

        @keyframes icon-pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        /* Animation Classes */
        .animate-button-glow {
            animation: button-glow 3s ease-in-out infinite;
        }

        .animate-icon-pulse {
            animation: icon-pulse 2s ease-in-out infinite;
        }

        /* Enhanced Button Hover Effects */
        .create-btn:hover {
            transform: translateY(-2px);
            animation-duration: 1s;
        }

        .create-btn:active {
            transform: translateY(0);
            animation-duration: 0.5s;
        }

        /* Focus Ring Enhancement */
        .create-btn:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1),
                       0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }
    </style>
@endpush
