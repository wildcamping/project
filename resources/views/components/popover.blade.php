@props([
    'text' => ''
])

@php
    $classes = '';
@endphp

<div x-data="{ isOpen: false }" {{ $attributes->merge(['class' => $classes]) }}>
    <div @mouseover="isOpen = true" @mouseleave="isOpen = false"
        class="duration-300">
        {{ $slot }}
    </div>
    <div x-show="isOpen"
    style="display: none"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="popover z-10 absolute border dark:border-slate-800 border-slate-200 dark:bg-slate-900 bg-slate-50 mt-2 px-4 py-2 rounded-lg">
            
            <p class="dark:text-slate-400 text-slate-800 text-sm">{{ $text }}</p>
        </div>
</div>
