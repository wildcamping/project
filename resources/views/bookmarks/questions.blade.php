<x-app-layout>
    <x-slot name="title">{{ __('Bookmarks') }}</x-slot>

    <div class="flex flex-col items-center justify-center">
        <div class="min-h-screen w-full max-w-md overflow-hidden px-2 sm:px-0">
            <livewire:bookmarks.questions_list />
        </div>
    </div>
</x-app-layout>
