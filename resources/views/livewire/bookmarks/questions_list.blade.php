<?php

use Livewire\Volt\Component;
use App\Livewire\Concerns\HasLoadMore;
use Illuminate\Http\Request;
use App\Models\User;
use Livewire\Attributes\On;

new class extends Component {
    use HasLoadMore;


    /**
     * Refresh the component.
     */
    #[On('question.unbookmarked')]
    public function refresh(): void {}

    public function with(Request $request): array
    {
        $user = type($request->user())->as(User::class);
        return [
            'user' => $user,
            'bookmarks' => $user->bookmarks()
                ->with('question')
                ->orderBy('created_at', 'desc')
                ->simplePaginate($this->perPage)
        ];
    }


}; ?>
<div class="mb-20 space-y-10">
    <div class="w-full">
        <div class="flex gap-2 overflow-x-auto border-b border-slate-200 dark:border-slate-900">
            <button class="font-bold text-black border-b-2 border-black dark:border-white dark:text-white h-min px-4 py-2 text-sm" type="button">{{ __('Questions') }}</button>
            <a href="{{  route('bookmarks.links') }}"
                class="h-min px-4 py-2 text-sm text-neutral-600 font-medium dark:text-slate-200 dark:hover:border-b-slate-200 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900">{{ __('Places') }}</a>
        </div>
        <div class="px-2 py-4 text-neutral-600 dark:text-slate-200">
            <div>
                @forelse ($bookmarks as $bookmark)
                    <livewire:questions.show :questionId="$bookmark->question->id" :key="'question-' . $bookmark->question->id" :inIndex="true" />
                @empty
                    <div class="rounded-lg">
                        <p class="text-slate-400">{{ __('No bookmarks.') }}</p>
                    </div>
                @endforelse

                <x-load-more-button :perPage="$perPage" :paginator="$bookmarks"
                    message="{{ __('There are no more bookmarks to load, or you have scrolled too far.') }}" />
            </div>
        </div>
    </div>


</div>
