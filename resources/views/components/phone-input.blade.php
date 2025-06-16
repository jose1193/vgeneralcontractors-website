@props(['name', 'label', 'model' => null, 'error' => null, 'wireModel' => null])

<div class="mb-4">
    <label for="{{ $name }}"
        class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">{{ $label }}:</label>
    <input type="tel"
        @if ($wireModel) wire:model.blur="{{ $wireModel }}" @else wire:model.blur="{{ $name }}" @endif
        id="{{ $name }}"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline @error($name) border-red-500 @enderror"
        placeholder="(XXX) XXX-XXXX">
    @if ($error)
        <div class="text-red-500 text-xs mt-1">{{ $error }}</div>
    @endif
</div>
