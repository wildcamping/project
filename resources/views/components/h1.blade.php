@php
    $classes = "text-xl font-medium dark:text-slate-400 text-slate-600 mt-3";
@endphp

<h1
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</h1>
