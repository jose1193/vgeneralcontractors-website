@props(['id', 'label', 'checked' => false, 'wire:click' => null])

<div class="flex items-center w-full md:w-auto justify-between md:justify-start">
    <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
    <button type="button" wire:click="{{ $attributes->get('wire:click') }}"
        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        :class="{
            'bg-blue-600': {{ $checked ? 'true' : 'false' }},
            'bg-gray-200 dark:bg-gray-700': !{{ $checked ? 'true' : 'false' }}
        }">
        <span
            class="pointer-events-none h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out flex items-center justify-center"
            :class="{
                'translate-x-5': {{ $checked ? 'true' : 'false' }},
                'translate-x-0': !{{ $checked ? 'true' : 'false' }}
            }">
            <svg x-show="{{ $checked ? 'true' : 'false' }}" class="h-4 w-4 text-blue-600" fill="none"
                stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg x-show="!{{ $checked ? 'true' : 'false' }}" class="h-4 w-4 text-gray-500" fill="none"
                stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </span>
    </button>
</div>
