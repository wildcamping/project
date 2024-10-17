<x-app-layout>
    <div>
        <x-slot name="title">
            <div class="flex gap-2 justify-between">
                {{ __('Places') }}
            </div>
        </x-slot>

        <div class="flex flex-col items-center justify-center">
            <div class="min-h-screen w-full max-w-md overflow-hidden px-2 sm:px-0">
                <livewire:profile.links :user="$user" />
            </div>
        </div>
    </div>
</x-app-layout>
