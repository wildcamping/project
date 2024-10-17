<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.components.head')
    @stack('styles')
</head>

<body class="dark:bg-slate-950 bg-slate-100 bg-center bg-repeat font-sans dark:text-slate-50 text-slate-900 antialiased"
    style="background-image: url({{ asset('/img/dots.svg') }})">
    @persist('flash-messages')
        <livewire:flash-messages.show />
    @endpersist

    <div class="flex min-h-screen flex-col">
        <main class="flex-grow">
            <div
                class="fixed right-0 z-10 fixed top-0 z-20 h-16 flex w-full justify-end gap-2 border-b dark:border-slate-200/10 border-slate-900/10 dark:bg-slate-950/20 bg-slate-100/20 p-4 shadow-2xl backdrop-blur-md">
                @include('layouts.navigation')
            </div>

            <div class="flex min-h-screen flex-col justify-top">
                {{ $slot }}

                <x-image-lightbox />
            </div>
        </main>
    </div>
    @livewireScriptConfig
    @stack('scripts')
</body>

</html>
