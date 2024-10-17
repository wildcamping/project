<div class="mb-12 w-full dark:text-slate-200 text-slate-800">
    @if ($forYouQuestions->isEmpty())
        <section class="rounded-lg">
            <p class="my-8 text-center text-lg text-slate-500">
                {{ __("We haven't found any questions that may interest you based on the activity you've done on Pinkary.") }}"
            </p>
        </section>
    @else
        <section class="mb-12 min-h-screen space-y-10">
            @foreach ($forYouQuestions as $question)
                <livewire:questions.show
                    :questionId="$question->id"
                    :key="'question-' . $question->id"
                    :inIndex="true"
                    :pinnable="false"
                />
            @endforeach

            <x-load-more-button
                :perPage="$perPage"
                :paginator="$forYouQuestions"
                message="{{ __('There are no more questions to load, or you have scrolled too far.') }}"
            />
        </section>
    @endif
</div>
