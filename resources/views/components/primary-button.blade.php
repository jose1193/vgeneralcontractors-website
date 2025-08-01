<button
    {{ $attributes->merge(['class' => 'group relative overflow-hidden bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 inline-flex items-center px-7 py-2.5 rounded-lg text-white justify-center']) }}>
    <span class="z-40">{{ $slot }}</span>
    <svg class="z-40 ml-2 -mr-1 w-3 h-3 transition-all duration-300 group-hover:translate-x-1" fill="currentColor"
        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd"
            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
            clip-rule="evenodd"></path>
    </svg>
    <div
        class="absolute inset-0 h-[200%] w-[200%] rotate-45 translate-x-[-70%] transition-all group-hover:scale-100 bg-white/30 group-hover:translate-x-[50%] z-20 duration-1000">
    </div>
</button>
