@props(['id', 'label', 'placeholder' => '', 'error' => null])

<div class="mb-4">
    @if (isset($label))
        <label for="{{ $id }}" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
            {{ $label }}
        </label>
    @endif

    <input id="{{ $id }}"
        {{ $attributes->merge(['class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline' . ($error ? ' border-red-500' : '')]) }}
        placeholder="{{ $placeholder }}">

    @if ($error)
        <div class="text-red-500 text-xs mt-1">{{ $error }}</div>
    @endif
</div>
