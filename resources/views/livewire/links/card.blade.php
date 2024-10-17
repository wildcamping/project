<article class="block">
    <div wire:ignore id="browseMap" class="w-full h-[40vh] drop-shadow-2xl"></div>
    <div class="relative">
        <div class="flex flex-col items-center w-full -mt-24 pt-20 sm:pt-0">
            <div class="flex w-full max-w-md flex-col gap-20 py-6">
                <div
                    class="block group p-4 rounded-2xl border dark:border-transparent border-slate-200 dark:bg-slate-900 bg-slate-50">
                    <div class="pb-3">
                        <div class="border-b dark:border-slate-900 border-slate-200 pb-3">

                            <div
                                class="flex items-center justify-between flex-wrap border-b dark:border-slate-900 border-slate-200 pb-3 gap-2">
                                <a href="{{ route('profile.show', ['username' => $link->user->username]) }}"
                                    class="group/profile flex items-center gap-3" data-navigate-ignore="true"
                                    wire:navigate>
                                    <figure
                                        class="{{ $link->user->is_company_verified ? 'rounded-md' : 'rounded-full' }} h-10 w-10 flex-shrink-0 dark:bg-slate-800 bg-slate-100 transition-opacity group-hover/profile:opacity-90">
                                        <img src="{{ $link->user->avatar_url }}" alt="{{ $link->user->username }}"
                                            class="{{ $link->user->is_company_verified ? 'rounded-md' : 'rounded-full' }} h-10 w-10" />
                                    </figure>
                                    <div class="overflow-hidden ">
                                        <div class="items flex">
                                            <p class="truncate font-medium dark:text-slate-50 text-slate-950">
                                                {{ $link->user->name }}
                                            </p>

                                            @if ($link->user->is_verified && $link->user->is_company_verified)
                                                <x-icons.verified-company :color="$question->user->right_color"
                                                    class="ml-1 mt-0.5 h-3.5 w-3.5" />
                                            @elseif ($link->user->is_verified)
                                                <x-icons.verified :color="$question->user->right_color" class="ml-1 mt-0.5 h-3.5 w-3.5" />
                                            @endif
                                        </div>

                                        <p
                                            class="truncate text-slate-500 transition-colors dark:group-hover/profile:text-slate-400 group-hover/profile:text-slate-600">
                                            {{ '@' . $link->user->username }}
                                        </p>
                                    </div>
                                </a>
                               <livewire:links.votes :link="$link"/>
                            </div>
                            <div class="flex flex-row justify-between flex-wrap gap-4 mt-4">
                                <div class="flex flex-row justify-end flex-wrap gap-4">
                                    @foreach ($property as $item)
                                        @if ($item['slug'] == 'altana')
                                            <x-popover
                                                text="{{ $item['name'] }}: {{ $item['value'] == 1 ? 'jest' : 'brak' }}"
                                                class="{{ $item['value'] == 1 ? 'text-teal-500' : 'dark:text-slate-800 text-slate-200' }}"><x-icons.bower
                                                    class="h-8 w-8" /></x-popover>
                                        @endif

                                        @if ($item['slug'] == 'dostep-do-jeziora-rzeki')
                                            <x-popover
                                                text="{{ $item['name'] }}: {{ $item['value'] == 1 ? 'jest' : 'brak' }}"
                                                class="{{ $item['value'] == 1 ? 'text-teal-500' : 'dark:text-slate-800 text-slate-200' }}"><x-icons.water
                                                    class="h-8 w-8" /></x-popover>
                                        @endif

                                        @if ($item['slug'] == 'plac-zabaw')
                                            <x-popover
                                                text="{{ $item['name'] }}: {{ $item['value'] == 1 ? 'jest' : 'brak' }}"
                                                class="{{ $item['value'] == 1 ? 'text-teal-500' : 'dark:text-slate-800 text-slate-200' }}"><x-icons.playground
                                                    class="h-8 w-8" /></x-popover>
                                        @endif

                                        @if ($item['slug'] == 'pomost')
                                            <x-popover
                                                text="{{ $item['name'] }}: {{ $item['value'] == 1 ? 'jest' : 'brak' }}"
                                                class="{{ $item['value'] == 1 ? 'text-teal-500' : 'dark:text-slate-800 text-slate-200' }}"><x-icons.peir
                                                    class="h-8 w-8" /></x-popover>
                                        @endif

                                        @if ($item['slug'] == 'punkt-czerpania-wody')
                                            <x-popover
                                                text="{{ $item['name'] }}: {{ $item['value'] == 1 ? 'jest' : 'brak' }}"
                                                class="{{ $item['value'] == 1 ? 'text-teal-500' : 'dark:text-slate-800 text-slate-200' }}"><x-icons.drawing-water
                                                    class="h-8 w-8" /></x-popover>
                                        @endif

                                        @if ($item['slug'] == 'wc')
                                            <x-popover
                                                text="{{ $item['name'] }}: {{ $item['value'] == 1 ? 'jest' : 'brak' }}"
                                                class="{{ $item['value'] == 1 ? 'text-teal-500' : 'dark:text-slate-800 text-slate-200' }}"><x-icons.wc
                                                    class="h-8 w-8" /></x-popover>
                                        @endif
                                    @endforeach
                                </div>
                                <x-primary-button class="gap-2"
                                    x-on:click="navigate('{{ $link->lat }}', '{{ $link->lng }}')">
                                    <x-heroicon-o-truck class="size-4" />{{ __('Nawiguj') }}
                                </x-primary-button>
                            </div>
                            <div class="prose prose-sm dark:prose-invert prose-h3:text-sm">
                                @if ($link->images)
                                    <div class="flex flex-row gap-4">
                                        @foreach ($link->images as $image)
                                            <div class="basis-auto" x-data="hasLightBoxImages">
                                                <img src="{{ $image->url }}" alt=""
                                                    class="relative inline-block object-cover object-center w-18 h-18 rounded-lg" />
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <h6 class=" dark:text-tail-400 text-tail-800 font-semibold mt-6 ">
                                {{ $link->title }}
                            </h6>
                            <p class="mt-3 dark:text-slate-400 text-slate-800">
                                {!! $link->description !!}
                            </p>


                        </div>

                        <div
                            class="mt-3 flex items-center justify-between text-sm text-slate-500  border-b dark:border-slate-900 border-slate-200 pb-3">
                            @php
                                $comments_count = $link->commentsCount();
                            @endphp
                            <div class="flex items-center gap-1">
                                <a x-ref="parentLink"
                                    href="{{ Route('links.show', [
                                        'link' => $link->id,
                                        'username' => $link->user->username,
                                    ]) }}#comment"
                                    wire:navigate
                                    title="{{ Number::format($comments_count) }} {{ str(__('Komentarze'))->plural($comments_count) }}"
                                    @class([
                                        'flex items-center transition-colors group-hover:text-cyan-500 dark:hover:text-slate-400 hover:text-slate-600 focus:outline-none',
                                        'cursor-pointer' => !$commenting,
                                    ])>
                                    <x-heroicon-o-chat-bubble-left-right class="size-4" />
                                    @if ($comments_count > 0)
                                        <span class="ml-1">
                                            {{ Number::abbreviate($comments_count) }}
                                        </span>
                                    @endif
                                </a>

                                <span>•</span>

                                @php
                                    $likeExists = $link->is_liked;
                                    $likesCount = $link->likes_count;
                                @endphp

                                <button x-data="likeLinkButton('{{ $link->id }}', @js($likeExists), {{ $likesCount }}, @js(auth()->check()))" x-cloak data-navigate-ignore="true"
                                    x-on:click="toggleLike" :title="likeButtonTitle"
                                    class="flex items-center transition-colors dark:hover:text-slate-400 hover:text-slate-600 focus:outline-none">
                                    <x-heroicon-s-heart class="h-4 w-4" x-show="isLiked" />
                                    <x-heroicon-o-heart class="h-4 w-4" x-show="!isLiked" />
                                    <span class="ml-1" x-show="count" x-text="likeButtonText"></span>
                                </button>
                                <span>•</span>
                                <p class="inline-flex cursor-help items-center"
                                    title="{{ Number::format($link->views) }} {{ str(__('View'))->plural($link->views) }}">
                                    <x-icons.chart class="h-4 w-4" />
                                    @if ($link->views > 0)
                                        <span class="mx-1">
                                            {{ Number::abbreviate($link->views) }}
                                        </span>
                                    @endif
                                </p>
                            </div>

                            <div class="flex items-center text-slate-500 ">
                                @php
                                    $timestamp = $link->updated_at ?: $link->created_at;
                                @endphp

                                <time class="cursor-help"
                                    title="{{ $timestamp->timezone(session()->get('timezone', 'UTC'))->isoFormat('ddd, D MMMM YYYY HH:mm') }}"
                                    datetime="{{ $timestamp->timezone(session()->get('timezone', 'UTC'))->toIso8601String() }}">
                                    {{ $link->updated_at ? '' : null }}
                                    {{ $timestamp->timezone(session()->get('timezone', 'UTC'))->diffForHumans() }}
                                </time>

                                <span class="mx-1">•</span>

                                <button data-navigate-ignore="true" x-data="bookmarkLinkButton('{{ $link->id }}', @js($link->is_bookmarked), {{ $link->bookmarks_count }}, @js(auth()->check()))" x-cloak
                                    x-on:click="toggleBookmark" :title="bookmarkButtonTitle"
                                    class="mr-1 flex items-center transition-colors dark:hover:text-slate-400 hover:text-slate-600 focus:outline-none">
                                    <x-heroicon-s-bookmark class="h-4 w-4" x-show="isBookmarked" />
                                    <x-heroicon-o-bookmark class="h-4 w-4" x-show="!isBookmarked" />
                                    <span class="ml-1" x-show="count" x-text="bookmarkButtonText"></span>
                                </button>
                                <x-dropdown align="left" width=""
                                    dropdown-classes="top-[-3.4rem] shadow-none"
                                    content-classes="flex flex-col space-y-1">
                                    <x-slot name="trigger">
                                        <button data-navigate-ignore="true"
                                            x-bind:class="{
                                                'text-pink-500 hover:text-pink-600': open,
                                                'text-slate-500 dark:hover:text-slate-400 hover:text-slate-600': !open
                                            }"
                                            title="Share"
                                            class="flex items-center transition-colors duration-150 ease-in-out focus:outline-none">
                                            <x-heroicon-o-paper-airplane class="h-4 w-4" />
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <button data-navigate-ignore="true" x-cloak x-data="copyUrl"
                                            x-show="isVisible"
                                            x-on:click="
                                    copyToClipboard(
                                        '{{ route('links.show', [
                                            'username' => $link->user->username,
                                            'link' => $link,
                                        ]) }}',
                                    )
                                "
                                            type="button"
                                            class="text-slate-500 transition-colors dark:hover:text-slate-400 hover:text-slate-600 focus:outline-none">
                                            <x-heroicon-o-link class="size-4" />
                                        </button>
                                        <button data-navigate-ignore="true" x-cloak x-data="shareProfile"
                                            x-show="isVisible"
                                            x-on:click="
                                    share({
                                        url: '{{ route('links.show', [
                                            'username' => $link->user->username,
                                            'link' => $link,
                                        ]) }}',
                                    })
                                "
                                            class="text-slate-500 transition-colors dark:hover:text-slate-400 hover:text-slate-600 focus:outline-none">
                                            <x-heroicon-o-link class="size-4" />
                                        </button>
                                        <button data-navigate-ignore="true" x-cloak x-data="shareProfile"
                                            x-on:click="
                                    twitter({
                                        url: '{{ route('links.show', ['username' => $link->user->username, 'link' => $link]) }}',
                                        link: '{{ str_replace("'", "\'", $link->description) }}',
                                        message: '{{ __('See it on Kemping') }}',
                                    })
                                "
                                            type="button"
                                            class="text-slate-500 transition-colors dark:hover:text-slate-400 hover:text-slate-600 focus:outline-none">
                                            <x-icons.twitter-x class="size-4" />
                                        </button>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                        <div class="mt-6 text-sm text-slate-500 gap-4" id="comment">
                            <livewire:comments :model="$link" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
@push('styles')
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.6.0/mapbox-gl.css" rel="stylesheet" />
@endpush
@script
    <script>
        window.addEventListener('browseMap', e => {
            mapboxgl.accessToken = '{{ config('app.map_api_key') }}';
            const map = new mapboxgl.Map({
                container: 'browseMap',
                style: 'mapbox://styles/mapbox/standard',
                center: [{{ $link->lng }}, {{ $link->lat }}],
                zoom: 14,
                attributionControl: false

            });


            map.on('load', function() {
                map.resize();

                map.addControl(new mapboxgl.NavigationControl(), 'bottom-right');
                map.addControl(new mapboxgl.FullscreenControl(), 'bottom-right');
                map.doubleClickZoom.disable();

                const el = document.createElement('div');
                el.className = 'marker';
                el.style.backgroundImage = `url(/img/camping-location.png?t=666i66)`;
                el.style.width = `27px`;
                el.style.height = `40px`;
                el.style.backgroundSize = '100%';

                var marker = new mapboxgl.Marker({
                        element: el,
                        draggable: false,
                        anchor: 'bottom',
                    }).setLngLat([{{ $link->lng }}, {{ $link->lat }}])
                    .addTo(map);



            });
        });
    </script>
@endscript
@push('scripts')
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.6.0/mapbox-gl.js"></script>
@endpush
