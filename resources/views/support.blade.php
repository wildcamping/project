<x-app-layout>
    <div class="mx-auto my-16 max-w-7xl px-6 lg:px-8">
        <a
            href="{{ route('about') }}"
            class="-mt-10 mb-12 flex items-center dark:text-slate-400 text-slate-600 hover:underline"
            wire:navigate
        >
            <x-icons.chevron-left class="size-4" />
            <span>{{ __('Back') }}</span>
        </a>

        <div class="mt-6">
            <div class="prose prose-slate dark:prose-invert mx-auto max-w-4xl">
                <h1>Wsparcie</h1>
                <p><strong>Aktualizacja: 09-10-2024</strong></p>

                <p>
                    Jeżeli masz pytania lub optrzebujesz pomocy skontaktuj się z nami <a href="mailto:team@wildcamping.pl">team@wildcamping.pl</a> .
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
