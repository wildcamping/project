@php
    $classes = "text-lg font-medium dark:text-slate-400 text-slate-600 mt-3";
@endphp

<h2
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</h2>
