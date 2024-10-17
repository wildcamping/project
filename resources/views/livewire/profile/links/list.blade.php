<div class="flex flex-col items-center justify-center" x-data="{
    showSettingsForm: {{ $errors->settings->isEmpty() ? 'false' : 'true' }},
    gradient: '{{ $user->gradient }}',
    link_shape: '{{ $user->link_shape }}',
}">
    <x-cta-button wire:click="$dispatchTo('links.create', 'link.create', {  })">
        <x-icons.plus class="mr-1.5 size-5" />
        {{ __('Add place') }}
    </x-cta-button>
    <div class="min-h-screen w-full max-w-md overflow-hidden rounded-lg px-4 dark:shadow-md md:px-0">
        @if ($links->isEmpty())
            <p class="mx-2 text-center text-slate-500">
                {{ __('No place yet. Add your first place!') }}
            </p>
        @else
            <ul class="space-y-3 w-full">
                @foreach ($links as $link)
                    <livewire:links.show :linkId="$link->id" :key="'link-' . $link->id" :inIndex="true"
                        view="livewire.profile.links.show_profile" />
                @endforeach
            </ul>
            <div class="mt-6">
                {{ $links->links() }}
            </div>
            <x-modal name="link-edit-modal" maxWidth="2xl">
                <div class="p-10">
                    <livewire:links.edit />
                </div>
            </x-modal>
            <x-modal name="link-create-modal" maxWidth="2xl">
                <div class="p-10">
                    <livewire:links.create :userId="$user->id" />
                </div>
            </x-modal>
            <x-modal name="link-corrections-modal" maxWidth="2xl">
                    <livewire:links.corrections />
            </x-modal>
            <x-modal name="link-delete-modal" maxWidth="xl">
                <div class="p-10">
                    <div class="flex justify-center items-center flex-col">
                        <x-heroicon-o-trash class="size-10" />
                        <h5 class="text-slate-800 font-semibold ">{{ __('Are You Sure?') }}</h5>
                        <p class="mt-3 dark:text-slate-200 text-slate-800">{{ __('Are you sure to delete this?') }}</p>
                        <div class="flex g-10 mt-3 justify-center gap-3">
                            <x-secondary-button x-on:click="$dispatch('close')">
                                <x-heroicon-o-x-mark class="size-4" />{{ __('Cancel') }}</x-secondary-button>
                            <x-danger-button
                                wire:click="$dispatchTo('profile.links', 'link.deleteLink', { linkId: {{ $linkId }} })"><x-heroicon-o-check
                                    class="size-4" />
                                {{ __('Confirm Delete') }}</x-danger-button>
                        </div>
                    </div>
                </div>
            </x-modal>
        @endif
    </div>
</div>
