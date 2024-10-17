@php
    $classes = "text-sm font-medium dark:text-slate-400 text-slate-600 mt-3";
@endphp

<h4
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</h4>
