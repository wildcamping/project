<?php
 
use Livewire\Volt\Component;
 
new class extends Component {

} 
?>
<div class="mb-20 space-y-10">
    <div class="w-full">
        <div class="flex gap-2 overflow-x-auto border-b border-slate-200 dark:border-slate-900" >
            <a href="{{ route('bookmarks.questions') }}"
                class="h-min px-4 py-2 text-sm text-neutral-600 font-medium dark:text-slate-200 dark:hover:border-b-slate-200 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900">{{ __('Questions') }}</a>
            <button class="font-bold text-black border-b-2 border-black dark:border-white dark:text-white h-min px-4 py-2 text-sm" type="button">{{ __('Places') }}</button>
        </div>
        <div class="px-2 py-4 text-neutral-600 dark:text-slate-200">
            <div>
                @forelse ($bookmarks_link as $bookmark)
                    <livewire:links.show :linkId="$bookmark->link->id" :key="'link-' . $bookmark->link->id" :inIndex="true" />
                @empty
                    <div class="rounded-lg">
                        <p class="text-slate-400">{{ __('No bookmarks.') }}</p>
                    </div>
                @endforelse

                <x-load-more-button :perPage="$perPage" :paginator="$bookmarks_link"
                    message="{{ __('There are no more bookmarks to load, or you have scrolled too far.') }}" />
            </div>
        </div>
    </div>
</div>
