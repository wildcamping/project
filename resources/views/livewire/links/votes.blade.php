<?php

use Livewire\Volt\Component;

new class extends Component {
    /**
     * The component's link ID.
     */
    #[Locked]
    public $link;

    public $has_voted;
    public $positive_vote;
    public $negative_vote;

    public function mount()
    {
        $vote = $this->link->user->attachVoteStatus($this->link); // dd($vote);
        $this->has_voted = $vote->has_voted;
        $this->positive_vote = $vote->has_voted ? $vote->has_upvoted : false;
        $this->negative_vote = $vote->has_voted ? $vote->has_downvoted : false;
    }

    public function confirmVote()
    {
        if (!auth()->check()) {
            $this->redirectRoute('login', navigate: true);
            return;
        }

        if ($this->positive_vote && $this->has_voted) {
            $this->dispatch('notification.created', message: __('You have already added a positive rating.'));
        } else {
            if ($this->link->user->upvote($this->link)) {
                $this->positive_vote = true;
                $this->negative_vote = false;
                $this->dispatch('notification.created', message: __('Confirm added.'));
            }
        }
    }

    public function unconfirmVote()
    {
        if (!auth()->check()) {
            $this->redirectRoute('login', navigate: true);
            return;
        }

        if ($this->negative_vote && $this->has_voted) {
            $this->dispatch('notification.created', message: __('You have already added a negative rating.'));
        } else {
            if ($this->link->user->downvote($this->link)) {
                $this->negative_vote = true;
                $this->positive_vote = false;
                $this->dispatch('notification.created', message: __('Unconfirm added.'));
            }
        }
    }
}; ?>

<div class="">
    <div
        class="flex flex-wrap items-center justify-center border dark:border-slate-600 border-slate-200 rounded-full p-1 gap-2">
        <div x-data="" x-cloak data-navigate-ignore="true" wire:click="confirmVote"
            title="{{ __('Confirmed') }}">
            <x-success-button class="!rounded-full !px-2">
                <x-heroicon-s-hand-thumb-up class="h-4 w-4" x-show="$wire.positive_vote" />
                <x-heroicon-o-hand-thumb-up class="h-4 w-4" x-show="!$wire.positive_vote" />
            </x-success-button>
        </div>
        <x-h4 class="!mt-0">{{ $link->totalVotes() }}&deg;</x-h4>
        <div x-data="" x-cloak data-navigate-ignore="true" wire:click="unconfirmVote"
            title="{{ __('Unconfirmed') }}">
            <x-warning-button class="!rounded-full !px-2">
                <x-heroicon-s-hand-thumb-down class="h-4 w-4" x-show="$wire.negative_vote" />
                <x-heroicon-o-hand-thumb-down class="h-4 w-4" x-show="!$wire.negative_vote" />
            </x-warning-button>
        </div>
    </div>
</div>
