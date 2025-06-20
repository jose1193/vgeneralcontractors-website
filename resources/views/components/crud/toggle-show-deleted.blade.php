@props([
    'id' => 'showDeleted',
    'label' => 'Show Inactive Items',
    'managerName' => 'crudManager',
    'class' => '',
])

<div class="flex items-center justify-end sm:justify-start w-full sm:w-auto {{ $class }}">
    <label for="{{ $id }}" class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300 capitalize">
        {{ $label }}
    </label>
    <label for="{{ $id }}" class="flex items-center cursor-pointer">
        <div class="relative">
            <input type="checkbox" id="{{ $id }}" class="sr-only">
            <div class="block bg-gray-600 w-10 h-6 rounded-full transition-colors duration-200"></div>
            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200">
            </div>
        </div>
    </label>
</div>

@push('styles')
    <style>
        /* Toggle CSS for {{ $id }} */
        #{{ $id }}:checked~.dot {
            transform: translateX(100%);
            background-color: #48bb78;
            /* Tailwind green-500 */
        }

        #{{ $id }}:checked~.block {
            background-color: #a0aec0;
            /* Tailwind gray-500 */
        }

        /* Hover effects */
        label[for="{{ $id }}"] .block {
            transition: background-color 0.2s ease;
        }

        label[for="{{ $id }}"]:hover .block {
            background-color: #4a5568;
            /* Slightly lighter gray on hover */
        }

        #{{ $id }}:checked+label[for="{{ $id }}"]:hover .block {
            background-color: #cbd5e0;
            /* Lighter gray when checked and hovered */
        }
    </style>
@endpush
