@props(['name', 'label', 'error' => null, 'placeholder' => '', 'type' => 'text', 'required' => false])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
        {{ $label }}@if ($required)
            <span class="text-red-500">*</span>
        @endif:
    </label>
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}"
        wire:model.live="{{ $name }}" placeholder="{{ $placeholder ?: $label }}"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-800 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-300 dark:focus:border-blue-600 @error($name) border-red-500 @enderror"
        {{ $required ? 'required' : '' }}>
    @error($name)
        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
    @enderror
    @if ($error)
        <div class="text-red-500 text-xs mt-1">{{ $error }}</div>
    @endif
</div>
