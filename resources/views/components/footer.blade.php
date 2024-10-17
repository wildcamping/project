<footer class="border-t dark:border-gray-800 border-gray-300">
    <div class="mx-auto max-w-7xl overflow-hidden px-6 py-16 sm:py-24 lg:px-8">
        <nav class="-mb-6 columns-2 sm:flex sm:justify-center sm:space-x-12" aria-label="Footer">

            {{-- <div class="pb-6">
                <a
                    href="{{ route('changelog') }}"
                    class="text-sm leading-6 dark:text-slate-400 text-slate-500 dark:hover:text-slate-200 hover:text-slate-950"
                    >Changelog</a
                >
            </div> --}}
            <div class="pb-6">
                <a href="{{ route('terms') }}"
                    class="text-sm leading-6 dark:text-slate-400 text-slate-500 dark:hover:text-slate-200 hover:text-slate-950">{{ __('Terms') }}</a>
            </div>
            <div class="pb-6">
                <a href="{{ route('privacy') }}"
                    class="text-sm leading-6 dark:text-slate-400 text-slate-500 dark:hover:text-slate-200 hover:text-slate-950">{{ __('Privacy Policy') }}</a>
            </div>
            <div class="pb-6">
                <a href="{{ route('support') }}"
                    class="text-sm leading-6 dark:text-slate-400 text-slate-500 dark:hover:text-slate-200 hover:text-slate-950">{{ __('Support') }}</a>
            </div>
            {{--
            <div class="pb-6">
                <a href="{{ route('brand.resources') }}"
                    class="text-sm leading-6 dark:text-slate-400 text-slate-500 dark:hover:text-slate-200 hover:text-slate-950">{{ __('Brand') }}</a>
            </div>--}}
        </nav>

        <div class="mt-10 flex space-x-10 sm:justify-center items-center">
        
            {{--<a href="https://twitter.com/PinkaryProject" target="_blank"
                class="dark:text-slate-400 text-slate-500 dark:hover:text-slate-200 hover:text-slate-950">
                <span class="sr-only">X</span>

                <x-icons.twitter-x class="h-6 w-6" />
            </a>

            <a href="https://github.com/pinkary-project" target="_blank"
                class="dark:text-slate-400 text-slate-500 dark:hover:text-slate-200 hover:text-slate-950">
                <span class="sr-only">Facebook</span>

                <x-icons.fb class="h-6 w-6" />
            </a>--}}

            <a href="https://buycoffee.to/wildcamping.pl" target="_blank"><img
                    src="https://buycoffee.to/img/share-button-primary.png" style="width: 136px; height: 35px"
                    alt="Postaw mi kawę na buycoffee.to"></a>
        </div>

        <p class="mt-10 text-xs leading-5 dark:text-slate-400 text-slate-500 sm:text-center">&copy; {{ date('Y') }}
            {{ config('app.name') }}.</p>
    </div>

    <livewire:views.create />
</footer>
