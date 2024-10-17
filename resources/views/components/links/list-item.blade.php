@props([
    'user' => null,
    'link' => null,
])

@php
    $isUserProfileOwner = auth()->user()?->is($user);
@endphp

<a
    href="{{ route('links.show', ['link' => $link, 'username' => $user->username]) }}"
    target="_blank"
    rel="me noopener"
    class="items-center justify-center px-4 font-bold text-white transition duration-300 ease-in-out"
>
    <div class="flex h-full items-center justify-center">
        <p class="truncate">
            {{ $link->title }}
        </p>
    </div>
</a>
