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
        'create-btn inline-flex items-center justify-center border border-white/5 rounded-md font-semibold uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition-all duration-300 backdrop-blur-md shadow-lg relative overflow-hidden';

    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-xs',
        'lg' => 'px-6 py-3 text-sm',
    ];

    $variantClasses = [
        'primary' =>
            'bg-gradient-to-r from-purple-600/80 to-purple-800/80 text-white hover:from-purple-600/90 hover:to-purple-800/90 active:from-purple-700 active:to-purple-900 focus:border-purple-600 focus:ring-purple-300/20 shadow-purple-500/20 after:bg-purple-400/10',
        'secondary' =>
            'bg-gradient-to-r from-blue-600/80 to-blue-800/80 text-white hover:from-blue-600/90 hover:to-blue-800/90 active:from-blue-700 active:to-blue-900 focus:border-blue-600 focus:ring-blue-300/20 shadow-blue-500/20 after:bg-blue-400/10',
        'success' =>
            'bg-gradient-to-br from-emerald-400/80 via-green-500/80 to-teal-600/80 text-white hover:from-emerald-400/90 hover:via-green-500/90 hover:to-teal-600/90 active:from-emerald-500 active:via-green-600 active:to-teal-700 focus:border-green-500 focus:ring-green-300/20 shadow-green-500/30 after:bg-green-300/10',
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

<button id="{{ $id }}" type="button" class="{{ $classes }} w-full sm:w-auto group"
    title="Create {{ $entityName ? ucfirst($entityName) : 'New Item' }}">
    <span class="absolute inset-0 w-full h-full bg-white/5 backdrop-filter backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
    <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent animate-shimmer-slow"></span>
    <span class="relative z-10 flex items-center">
        <span class="mr-2 transform group-hover:rotate-90 transition-transform duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="{{ $iconPaths[$icon] ?? $iconPaths['plus'] }}" />
            </svg>
        </span>
        <span class="relative">{{ $label }}</span>
    </span>
</button>

<style>
    @keyframes shimmer-slow {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    .animate-shimmer-slow {
        animation: shimmer-slow 3s infinite;
    }
    .create-btn {
        box-shadow: 0 0 15px rgba(0, 200, 100, 0.2), inset 0 0 10px rgba(255, 255, 255, 0.1);
    }
    .create-btn:hover {
        box-shadow: 0 0 20px rgba(0, 200, 100, 0.3), inset 0 0 15px rgba(255, 255, 255, 0.15);
    }
</style>
