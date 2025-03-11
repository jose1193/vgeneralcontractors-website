<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Email Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <livewire:email-datas />
    </div>
</x-app-layout>
