@props(['name', 'label', 'error' => null, 'mode' => 'update', 'required' => false])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
        {{ $label }}@if ($required)
            <span class="text-red-500">*</span>
        @endif:
    </label>

    @if ($mode === 'store')
        <input type="text" id="{{ $name }}" disabled
            placeholder="Will be automatically generated from name and lastname"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-100">
        <div class="mt-1 text-xs text-gray-500 italic">
            Example: If name is "John Doe", username will be something like "johnd123"
        </div>
    @else
        <input type="text" id="{{ $name }}" name="{{ $name }}" wire:model.live="{{ $name }}"
            placeholder="{{ $label }}"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-800 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-300 dark:focus:border-blue-600 @error($name) border-red-500 @enderror"
            {{ $required ? 'required' : '' }}>
        <div class="mt-1 text-xs text-gray-500 italic">
            Username must be at least 7 characters and contain at least 2 numbers
        </div>
    @endif

    @error($name)
        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
    @enderror
    @if ($error)
        <div class="text-red-500 text-xs mt-1">{{ $error }}</div>
    @endif
</div>
