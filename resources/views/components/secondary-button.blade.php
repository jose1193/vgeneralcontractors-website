<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' =>
            'rounded-md bg-slate-800 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-slate-700 focus:shadow-none active:bg-slate-700 hover:bg-slate-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none',
    ]) }}>
    {{ $slot }}
</button>
