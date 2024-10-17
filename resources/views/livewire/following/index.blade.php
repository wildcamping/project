<x-modal
    name="following"
    maxWidth="2xl"
>
    <div class="p-10" x-on:open-modal.window="$event.detail == 'following' ? $wire.set('isOpened', true) : null">
        <div>
            @if ($following->isEmpty())
                <strong> <span>@</span>{{ $user->username }} {{ __('does not have any following') }} </strong>
            @else
                <strong> <span>@</span>{{ $user->username }} {{ __('following') }} </strong>
            @endif
        </div>

        @if ($following->isNotEmpty())
            <section class="mt-10 max-w-2xl max-h-96 overflow-y-auto">
                <ul class="flex flex-col gap-2">
                    @foreach ($following as $followingUser)
                        <li>
                            <a
                                href="{{ route('profile.show', ['username' => $followingUser->username]) }}"
                                class="group flex items-center gap-3 rounded-2xl border border-slate-900 bg-slate-950 bg-opacity-80 p-4 transition-colors hover:bg-slate-900"
                                wire:navigate
                            >
                                <figure class="{{ $followingUser->is_company_verified ? 'rounded-md' : 'rounded-full' }} h-12 w-12 flex-shrink-0 overflow-hidden bg-slate-800 transition-opacity group-hover:opacity-90">
                                    <img
                                        class="{{ $followingUser->is_company_verified ? 'rounded-md' : 'rounded-full' }} h-12 w-12"
                                        src="{{ $followingUser->avatar_url }}"
                                        alt="{{ $followingUser->username }}"
                                    />
                                </figure>
                                <div class="flex flex-col overflow-hidden text-sm">
                                    <div class="flex items-center space-x-2">
                                        <p class="truncate font-medium">
                                            {{ $followingUser->name }}
                                        </p>

                                        @if ($followingUser->is_verified && $followingUser->is_company_verified)
                                            <x-icons.verified-company
                                                :color="$followingUser->right_color"
                                                class="size-4"
                                            />
                                        @elseif ($followingUser->is_verified)
                                            <x-icons.verified
                                                :color="$followingUser->right_color"
                                                class="size-4"
                                            />
                                        @endif
                                    </div>
                                    <p class="truncate text-left text-slate-500 transition-colors group-hover:text-slate-400">
                                        {{ '@'.$followingUser->username }}
                                        @if ($followingUser->is_follower)
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
                {{ $following->links() }}
            </div>
        @endif
    </div>
</x-modal>
