@props(['name', 'label', 'checked' => false, 'error' => null])

<div class="mb-4">
    <div class="flex items-center">
        <input type="checkbox" id="{{ $name }}" name="{{ $name }}" wire:model.live="{{ $name }}"
            {{ $checked ? 'checked' : '' }}
            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error($name) border-red-500 @enderror">
        <label for="{{ $name }}" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
            {{ $label }}
        </label>
    </div>
    @error($name)
        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
    @enderror
    @if ($error)
        <div class="text-red-500 text-xs mt-1">{{ $error }}</div>
    @endif
</div>
