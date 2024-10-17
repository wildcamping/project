<div>
    <header
        class="flex w-full flex-1 items-center justify-between border-b dark:border-slate-900 border-slate-200 py-3.5 dark:text-slate-200 text-slate-800">
        <div class="flex items-center gap-4">
            <div>
                <h6 class="text-slate-800 font-semibold">
                    {{ $link->title }}
                </h6>
            </div>
        </div>
        <time class="flex-none py-0.5 text-xs dark:font-semibold leading-5 dark:text-slate-500 text-slate-600">
            {{ $link->created_at->format('Y-m-d') }}
        </time>
    </header>
    <div class="prose prose-sm dark:prose-invert prose-h3:text-sm border-b dark:border-slate-900 border-slate-200">
        @if ($link->images)
            <div class="flex flex-row gap-4">
                @foreach ($link->images as $image)
                    <div class="basis-auto" x-data="hasLightBoxImages">
                        <img src="{{ $image->url }}" alt=""
                            class="relative inline-block object-cover object-center w-22 h-22 rounded-lg" />
                    </div>
                @endforeach
            </div>
        @endif
        <p class="m-0 ">{{ $link->description }}</p>
    </div>
    <div class="flex w-full flex-1 items-center justify-between py-3.5 dark:text-slate-200 text-slate-800">
        @php($comment_cnt = $link->commentsCount())
        <a x-ref="parentLink"
            href="https://mapa.pro-linuxpl.com/@golaszewski/links/9d0bf418-118e-4dfc-ad1c-390343150573"
            wire:navigate="" title="{{ $comment_cnt }} Comments"
            class="flex items-center transition-colors group-hover:text-cyan-500 dark:hover:text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" aria-hidden="true" data-slot="icon">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155">
                </path>
            </svg> <!--[if BLOCK]><![endif]--> <span class="ml-1">
                {{ $comment_cnt }}
            </span>
            <!--[if ENDBLOCK]><![endif]-->
        </a>
        <div>

        </div>
    </div>















    <div class="mt-3 flex items-center justify-between text-sm text-slate-500">
        <div class="flex items-center gap-1">
            <a href="{{ Route('links.show', [
                                'link' => $link->id,
                                'username' => $link->to->username,
                            ]) }}"
                            wire:navigate 
                title="{{ Number::format($link->comment_cnt) }} {{ str('Komentarz')->plural($link->comment_cnt) }}"
                @class([
                    'flex items-center transition-colors group-hover:text-pink-500 dark:hover:text-slate-400 hover:text-slate-600 focus:outline-none',
                    'cursor-pointer' => !$commenting,
                ])>
                <x-heroicon-o-chat-bubble-left-right class="size-4" />
                @if ($link->children_count > 0)
                    <span class="ml-1">
                        {{ Number::abbreviate($link->children_count) }}
                    </span>
                @endif
            </a>

            <span>•</span>

            @php
                $likeExists = $link->is_liked;
                $likesCount = $link->likes_count;
            @endphp

            <button x-data="likeButton('{{ $link->id }}', @js($likeExists), {{ $likesCount }}, @js(auth()->check()))" x-cloak data-navigate-ignore="true" x-on:click="toggleLike"
                :title="likeButtonTitle"
                class="flex items-center transition-colors dark:hover:text-slate-400 hover:text-slate-600 focus:outline-none">
                <x-heroicon-s-heart class="h-4 w-4" x-show="isLiked" />
                <x-heroicon-o-heart class="h-4 w-4" x-show="!isLiked" />
                <span class="ml-1" x-show="count" x-text="likeButtonText"></span>
            </button>
            <span>•</span>
            <p class="inline-flex cursor-help items-center"
                title="{{ Number::format($link->views) }} {{ str('View')->plural($link->views) }}">
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
                $timestamp = $link->answer_updated_at ?: $link->answer_created_at;
            @endphp

            <time class="cursor-help"
                title="{{ $timestamp->timezone(session()->get('timezone', 'UTC'))->isoFormat('ddd, D MMMM YYYY HH:mm') }}"
                datetime="{{ $timestamp->timezone(session()->get('timezone', 'UTC'))->toIso8601String() }}">
                {{ $link->answer_updated_at ? 'Edited:' : null }}
                {{ $timestamp->timezone(session()->get('timezone', 'UTC'))->diffForHumans(short: true) }}
            </time>

            <span class="mx-1">•</span>

            <button data-navigate-ignore="true" x-data="bookmarkButton('{{ $link->id }}', @js($link->is_bookmarked), {{ $link->bookmarks_count }}, @js(auth()->check()))" x-cloak x-on:click="toggleBookmark"
                :title="bookmarkButtonTitle"
                class="mr-1 flex items-center transition-colors dark:hover:text-slate-400 hover:text-slate-600 focus:outline-none">
                <x-heroicon-s-bookmark class="h-4 w-4" x-show="isBookmarked" />
                <x-heroicon-o-bookmark class="h-4 w-4" x-show="!isBookmarked" />
                <span class="ml-1" x-show="count" x-text="bookmarkButtonText"></span>
            </button>
            <x-dropdown align="left" width="" dropdown-classes="top-[-3.4rem] shadow-none"
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
                    <button data-navigate-ignore="true" x-cloak x-data="copyUrl" x-show="isVisible"
                        x-on:click="
                                    copyToClipboard(
                                        '{{ route('links.show', [
                                            'username' => $link->to->username,
                                            'link' => $link,
                                        ]) }}',
                                    )
                                "
                        type="button"
                        class="text-slate-500 transition-colors dark:hover:text-slate-400 hover:text-slate-600 focus:outline-none">
                        <x-heroicon-o-link class="size-4" />
                    </button>
                    <button data-navigate-ignore="true" x-cloak x-data="shareProfile" x-show="isVisible"
                        x-on:click="
                                    share({
                                        url: '{{ route('links.show', [
                                            'username' => $link->to->username,
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
                                        url: '{{ route('links.show', ['username' => $link->to->username, 'link' => $link]) }}',
                                        link: '{{ str_replace("'", "\'", $link->isSharedUpdate() ? $link->answer : $link->content) }}',
                                        message: '{{ $link->isSharedUpdate() ? 'See it on Pinkary' : 'See response on Pinkary' }}',
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













    <div class="absolute inset-x-0 -bottom-2 flex h-2 justify-center overflow-hidden">
        <div class="absolute right-5 -mt-px flex h-[2px] w-2/3">
            <div class="w-full flex-none bg-gradient-to-r from-slate-950 via-cyan-400 to-slate-950 blur-sm"></div>
        </div>
    </div>
</div>
