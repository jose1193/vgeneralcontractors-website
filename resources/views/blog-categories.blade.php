<x-app-layout>
    <div class="bg-gray-800" style="background-color: #141414;">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold leading-tight text-white">
                {{ __('blog_categories_title') }}
            </h2>
            <p class="mt-2 text-sm text-gray-300">
                {{ __('blog_categories_subtitle') }}
            </p>
        </div>
    </div>

    @livewire('blog-categories')
</x-app-layout>
