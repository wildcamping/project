@php
    $classes = "mt-1 text-sm text-slate-500";
@endphp

<p
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</p>
