@props([
    'as' => 'button',
])

<{{ $as }} {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border text-red-500 border-red-500 rounded-lg font-semibold text-xs tracking-widest dark:hover:bg-red-900/20 hover:bg-red-400/20 focus:outline-none focus:ring-0 focus:ring-offset-0 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</{{ $as }}>
