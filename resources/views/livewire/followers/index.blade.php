<x-modal
    name="followers"
    maxWidth="2xl"
>
    <div class="p-10" x-on:open-modal.window="$event.detail == 'followers' ? $wire.set('isOpened', true) : null">
        <div>
            @if ($followers->isEmpty())
                <strong> <span>@</span>{{ $user->username }} {{ __('does not have any followers') }} </strong>
            @else
                <strong> <span>@</span>{{ $user->username }} {{ __('followers') }} </strong>
            @endif
        </div>

        @if ($followers->isNotEmpty())
            <section class="mt-10 max-w-2xl max-h-96 overflow-y-auto">
                <ul class="flex flex-col gap-2">
                    @foreach ($followers as $follower)
                        <li>
                            <a
                                href="{{ route('profile.show', ['username' => $follower->username]) }}"
                                class="group flex items-center gap-3 rounded-2xl border border-slate-900 bg-slate-950 bg-opacity-80 p-4 transition-colors hover:bg-slate-900"
                                wire:navigate
                            >
                                <figure class="{{ $follower->is_company_verified ? 'rounded-md' : 'rounded-full' }} h-12 w-12 flex-shrink-0 overflow-hidden bg-slate-800 transition-opacity group-hover:opacity-90">
                                    <img
                                        class="{{ $follower->is_company_verified ? 'rounded-md' : 'rounded-full' }} h-12 w-12"
                                        src="{{ $follower->avatar_url }}"
                                        alt="{{ $follower->username }}"
                                    />
                                </figure>
                                <div class="flex flex-col overflow-hidden text-sm">
                                    <div class="flex items-center space-x-2">
                                        <p class="truncate font-medium">
                                            {{ $follower->name }}
                                        </p>

                                        @if ($follower->is_verified && $follower->is_company_verified)
                                            <x-icons.verified-company
                                                :color="$follower->right_color"
                                                class="size-4"
                                            />
                                        @elseif ($follower->is_verified)
                                            <x-icons.verified
                                                :color="$follower->right_color"
                                                class="size-4"
                                            />
                                        @endif
                                    </div>
                                    <p class="truncate text-left text-slate-500 transition-colors group-hover:text-slate-400">
                                        {{ '@'.$follower->username }}
                                        @if (auth()->user()?->isNot($user) && $follower->is_follower)
                                            <x-badge class="ml-1">
                                                {{ __('Follows you') }}
                                            </x-badge>
                                        @endif
                                    </p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>

            <div class="mt-5">
                {{ $followers->links() }}
            </div>
        @endif
    </div>
</x-modal>
