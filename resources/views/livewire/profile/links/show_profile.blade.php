<article class="block" id="q-{{ $linkId }}" x-data="copyCode">
    <div data-parent=true x-intersect.once.full="$dispatch('link-viewed', { linkId: '{{ $linkId }}' })"
        x-data="clickHandler" x-on:click="handleNavigation($event)"
        class="group p-4 mt-3 rounded-2xl border dark:border-transparent border-slate-200 dark:bg-slate-900 bg-slate-50 
            cursor-pointer transition-colors duration-100 ease-in-out dark:hover:bg-cyan-700/60 hover:bg-cyan-100/60">
        <div class="flex items-center gap-3">
            <div class="prose prose-sm dark:prose-invert prose-h3:text-sm">
                @if ($link->images->first())
                    <figure
                        class="rounded-full h-24 w-24 flex-shrink-0 dark:bg-slate-800 bg-slate-100 transition-opacity group-hover/profile:opacity-90"
                        x-data="hasLightBoxImages">
                        <img src="{{ $link->images()->first()->url }}" alt="{{ $link->title }}"
                            class="relative inline-block object-cover object-center w-24 h-24 rounded-lg" />
                    </figure>
                @endif
            </div>
            <div>
                <h6 class=" dark:text-slate-200 text-slate-800 font-semibold ">
                    {{ $link->title }}
                </h6>
                <p class="mt-3 dark:text-slate-200 text-slate-800">
                    {!! Str::words($link->description, 12, ' ...') !!}
                </p>
            </div>
        </div>

        <div class="mt-3 flex items-center justify-between text-sm text-slate-500">
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
                        'flex items-center transition-colors group-hover:text-pink-500 dark:hover:text-slate-400 hover:text-slate-600 focus:outline-none',
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

                <button x-data="likeLinkButton('{{ $link->id }}', @js($likeExists), {{ $likesCount }}, @js(auth()->check()))" x-cloak data-navigate-ignore="true" x-on:click="toggleLike"
                    :title="likeButtonTitle"
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

                <button data-navigate-ignore="true" x-data="bookmarkLinkButton('{{ $link->id }}', @js($link->is_bookmarked), {{ $link->bookmarks_count }}, @js(auth()->check()))" x-cloak x-on:click="toggleBookmark"
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
                                            'username' => $link->user->username,
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
        <div class="mt-6 flex items-center justify-end flex-wrap gap-4 border-t dark:border-slate-900 border-slate-200 pt-3"
            x-data="clickHandler">
            {{-- @if (!$link->is_visible)
                <x-warning-button class="gap-2"
                    wire:click="$dispatchTo('profile.links', 'link.confirmDelete', { linkId: {{ $link->id }} })"
                    data-navigate-ignore="true">
                    <x-heroicon-o-trash class="size-4" />
                </x-warning-button>
                <x-primary-button class="gap-2"
                    wire:click="$dispatchTo('links.edit', 'link.edit', { link: {{ $link->id }} })"
                    data-navigate-ignore="true">
                    <x-heroicon-o-pencil class="size-4" />{{ __('Edit') }}
                </x-primary-button>
            @else
                @if(empty($link->active_correction->first()))
                <x-popover text="{{ __('Place approved by the moderator.') }}">
                    <x-primary-button class="gap-2" wire:click="$dispatchTo('links.corrections', 'link.corrections_form', { link: {{ $link->id }} })" data-navigate-ignore="true">
                        <x-heroicon-o-pencil class="size-4" />{{ __('Report corrections') }}
                    </x-primary-button>
                </x-popover>
                @else
                    <x-paragraf>{{ __('Reported to moderation.') }}</x-paragraf>
                @endif
            @endif--}}
                <x-warning-button class="gap-2"
                    wire:click="$dispatchTo('profile.links', 'link.confirmDelete', { linkId: {{ $link->id }} })"
                    data-navigate-ignore="true">
                    <x-heroicon-o-trash class="size-4" />
                </x-warning-button>
                <x-primary-button class="gap-2"
                    wire:click="$dispatchTo('links.edit', 'link.edit', { link: {{ $link->id }} })"
                    data-navigate-ignore="true">
                    <x-heroicon-o-pencil class="size-4" />{{ __('Edit') }}
                </x-primary-button>
        </div>
    </div>
</article>
