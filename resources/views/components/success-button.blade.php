@props([
    'as' => 'button',
])

<{{ $as }} {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border text-green-500 border-green-500 rounded-lg font-semibold text-xs tracking-widest dark:hover:bg-green-900/20 hover:bg-green-400/20 focus:outline-none focus:ring-0 focus:ring-offset-0 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</{{ $as }}>
