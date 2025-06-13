<x-app-layout>
    {{-- Commenting out Jetstream header to avoid duplicate titles --}}
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Company Information') }}
        </h2>
    </x-slot> --}}

    <x-welcome title="{{ __('company_data_title') }}" subtitle="{{ __('company_data_subtitle') }}">
        <div class="py-6">
            <livewire:company-data />
        </div>
    </x-welcome>
</x-app-layout>
