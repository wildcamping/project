<x-card-layout>
    <div class="w-full h-full">
        <livewire:links.show :linkId="$link->id" :in-thread="true" :commenting="true" :showParents="true" view="livewire.links.card" />
    </div>
</x-card-layout>
