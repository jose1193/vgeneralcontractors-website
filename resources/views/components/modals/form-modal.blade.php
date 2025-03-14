@props(['title', 'isOpen', 'maxWidth' => '2xl', 'submitAction'])

@if ($isOpen)
    <div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-{{ $maxWidth }} w-full md:w-3/4 sm:w-full">
                <form wire:submit.prevent="{{ $submitAction }}">
                    <!-- Modal header -->
                    <div class="bg-gray-900 px-4 py-3 sm:px-6">
                        <div class="flex items-center justify-center relative">
                            <h3 class="text-lg font-medium text-white text-center">
                                {{ $title }}
                            </h3>
                            <button type="button" wire:click="closeModal"
                                class="absolute right-0 text-white hover:text-gray-200 focus:outline-none">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal body -->
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        {{ $slot }}
                    </div>

                    <!-- Modal footer -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        {{ $footer }}
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
