<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use App\Models\Link;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Livewire\Concerns\HasLoadMore;
use Illuminate\Http\Request;

final class Links extends Component
{
    use HasLoadMore;

    
    /**
     * The component's link ID.
     */
    #[Locked]
    public ?int $linkId = null;

    #[On('link.confirmDelete')]
    public function confirmDelete(int $linkId): void
    {
        $this->linkId = $linkId;
        $this->dispatch('open-modal', 'link-delete-modal');
    }

    #[On('link.deleteLink')]
    public function deleteLink(int $linkId): void
    {
        $user = type(auth()->user())->as(User::class);

        $link = Link::findOrFail($linkId);

        $this->authorize('delete', $link);

        $link->deleteLink();

        $this->dispatch('close-modal', 'link-delete-modal');
        $this->dispatch('notification.created', message: 'Link deleted.');
    }
        /**
     * Refresh the component.
     */
    #[On('link.created')]
    #[On('link.updated')]
    #[On('link-settings.updated')]
    public function refresh(): void
    {
        //
    }

/**
     * Render the component.
     */
    public function render(Request $request): View
    {
        $user = type($request->user())->as(User::class);
        return view('livewire.profile.links.list', [
            'links' => $user->links()
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage),//auth()->user()->links()->get(),
            'user' => type(auth()->user())->as(User::class),
        ]);
        
    }
}
