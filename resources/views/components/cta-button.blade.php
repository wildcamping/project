@props([
    'as' => 'button',
])
<{{ $as }} {{ $attributes->merge(['type' => 'submit', 'class' => 'mt-5 rounded-full bg-pink-500 bg-opacity-90 px-3 py-1.5 font-mona text-sm font-medium uppercase text-slate-900 dark:hover:text-white hover:text-white focus:outline-none focus:ring-0 focus:ring-offset-0 transition ease-in-out duration-150 inline-flex items-center']) }}>
    {{ $slot }}
</{{ $as }}>
