@props(['name', 'label', 'options' => [], 'error' => null, 'required' => false, 'placeholder' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
        {{ $label }}@if ($required)
            <span class="text-red-500">*</span>
        @endif:
    </label>
    <select id="{{ $name }}" name="{{ $name }}" wire:model.live="{{ $name }}"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-800 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-300 dark:focus:border-blue-600 @error($name) border-red-500 @enderror"
        {{ $required ? 'required' : '' }}>
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}">
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    @error($name)
        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
    @enderror
    @if ($error)
        <div class="text-red-500 text-xs mt-1">{{ $error }}</div>
    @endif
</div>
