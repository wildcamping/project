@php
    $classes = "text-base font-medium dark:text-slate-400 text-slate-600  mt-3";
@endphp

<h3
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</h3>
