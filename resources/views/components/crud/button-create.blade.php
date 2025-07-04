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
        'create-btn inline-flex items-center justify-center border border-transparent rounded-md font-semibold uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition-all duration-200';

    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-xs',
        'lg' => 'px-6 py-3 text-sm',
    ];

    $variantClasses = [
        'primary' =>
            'bg-green-600 text-white hover:bg-green-700 active:bg-green-800 focus:border-green-800 focus:ring-green-200',
        'secondary' =>
            'bg-blue-600 text-white hover:bg-blue-700 active:bg-blue-800 focus:border-blue-800 focus:ring-blue-200',
        'success' =>
            'bg-emerald-600 text-white hover:bg-emerald-700 active:bg-emerald-800 focus:border-emerald-800 focus:ring-emerald-200',
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
