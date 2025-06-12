@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-gray-800 dark:border-gray-800 text-start text-base font-medium text-gray-800 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/50 focus:outline-none focus:text-gray-800 dark:focus:text-gray-300 focus:bg-gray-100 dark:focus:bg-gray-900 focus:border-gray-800 dark:focus:border-gray-800 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-800 dark:hover:border-gray-800 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-700 focus:border-gray-800 dark:focus:border-gray-800 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
