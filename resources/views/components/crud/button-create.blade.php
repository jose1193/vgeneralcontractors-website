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
        'create-btn inline-flex items-center justify-center border border-white/10 rounded-md font-semibold uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition-all duration-200 backdrop-blur-sm shadow-lg';

    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-xs',
        'lg' => 'px-6 py-3 text-sm',
    ];

    $variantClasses = [
        'primary' =>
            'bg-gradient-to-r from-purple-600 to-purple-800 text-white hover:from-purple-700 hover:to-purple-900 active:from-purple-800 active:to-purple-950 focus:border-purple-800 focus:ring-purple-300/30 shadow-purple-500/30',
        'secondary' =>
            'bg-gradient-to-r from-blue-600 to-blue-800 text-white hover:from-blue-700 hover:to-blue-900 active:from-blue-800 active:to-blue-950 focus:border-blue-800 focus:ring-blue-300/30 shadow-blue-500/30',
        'success' =>
            'bg-gradient-to-r from-emerald-600 to-emerald-800 text-white hover:from-emerald-700 hover:to-emerald-900 active:from-emerald-800 active:to-emerald-950 focus:border-emerald-800 focus:ring-emerald-300/30 shadow-emerald-500/30',
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
    <span class="mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="{{ $iconPaths[$icon] ?? $iconPaths['plus'] }}" />
        </svg>
    </span>
    {{ $label }}
</button>
