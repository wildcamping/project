<?php

declare(strict_types=1);

namespace App\Livewire\Links;

use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Url;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

final class Show extends Component
{
    /**
     * The component's link ID.
     */
    #[Locked]
    public string $linkId;

    /**
     * Determine if this is currently being viewed in the index (list) view.
     */
    #[Locked]
    public bool $inIndex = false;

    /**
     * Determine if the parent link should be shown.
     */
    #[Locked]
    public bool $showParents = false;

    /**
     * Determine if this is currently being viewed in thread view.
     */
    #[Locked]
    public bool $inThread = false;

    /**
     * Whether the pinned label should be displayed or not.
     */
    #[Locked]
    public bool $pinnable = false;

    /**
     * Enable the comment box.
     */
    #[Locked]
    public bool $commenting = false;

    /**
     * view.
     */
    #[Locked]
    public string $view = 'livewire.links.show';

    /**
     * The previous link ID, where the user came from.
     */
    #[Url]
    public ?string $previousLinkId = null;

    /**
     * Refresh the component.
     */
    #[On('link.updated')]
    #[On('link.created')]
    #[On('link.refresh.20')] 
    public function refresh(): void
    {
        //
    }


    public function mount()
    {
        $this->dispatch('browseMap');

    }

    /**
     * Get the listeners for the component.
     *
     * @return array<string, string>
     */
    public function getListeners(): array
    {
        return $this->inIndex ? [] : [
            'link.ignore' => 'ignore',
            'link.reported' => 'redirectToProfile',
        ];
    }

    /**
     * Redirect to the profile.
     */
    public function redirectToProfile(): void
    {
        $link = Link::findOrFail($this->linkId);

        $this->redirectRoute('profile.show', ['username' => $link->to->username], navigate: true);
    }

    /**
     * Ignores the link.
     */
    public function ignore(): void
    {
        if (! auth()->check()) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        if ($this->inIndex) {
            $this->dispatch('notification.created', message: 'Link ignored.');

            $this->dispatch('link.ignore', linkId: $this->linkId);

            return;
        }

        $link = Link::findOrFail($this->linkId);

        $this->authorize('ignore', $link);

        $link->update(['is_ignored' => true]);

        $this->redirectRoute('profile.show', ['username' => $link->to->username], navigate: true);
    }

    /**
     * Bookmark the link.
     */
    #[Renderless]
    public function bookmark(): void
    {
        if (! auth()->check()) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        $link = Link::findOrFail($this->linkId);

        $bookmark = $link->bookmarks()->firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        if ($bookmark->wasRecentlyCreated) {
            $this->dispatch('notification.created', message: 'Bookmark added.');
        }
    }

    /**
     * Like the link.
     */
    #[Renderless]
    public function like(): void
    {
        if (! auth()->check()) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        $link = Link::findOrFail($this->linkId);

        $link->likes()->firstOrCreate([
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Pin a link.
     */
    public function pin(): void
    {
        if (! auth()->check()) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        $user = type(auth()->user())->as(User::class);

        $link = Link::findOrFail($this->linkId);

        $this->authorize('pin', $link);

        Link::withoutTimestamps(fn () => $user->pinnedLink()->update(['pinned' => false]));
        Link::withoutTimestamps(fn () => $link->update(['pinned' => true]));

        $this->dispatch('link.updated');
    }

    /**
     * Unpin a pinned link.
     */
    public function unpin(): void
    {
        if (! auth()->check()) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        $link = Link::findOrFail($this->linkId);

        $this->authorize('update', $link);

        Link::withoutTimestamps(fn () => $link->update(['pinned' => false]));

        $this->dispatch('link.updated');
    }

    /**
     * Unbookmark the link.
     */
    #[Renderless]
    public function unbookmark(): void
    {
        if (! auth()->check()) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        $link = Link::findOrFail($this->linkId);

        if ($bookmark = $link->bookmarks()->where('user_id', auth()->id())->first()) {
            $this->authorize('delete', $bookmark);

            if ($bookmark->delete()) {
                $this->dispatch('notification.created', message: 'Bookmark removed.');
            }
        }

        $this->dispatch('link.unbookmarked');
    }

    /**
     * Unlike the link.
     */
    #[Renderless]
    public function unlike(): void
    {
        if (! auth()->check()) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        $link = Link::findOrFail($this->linkId);

        if ($like = $link->likes()->where('user_id', auth()->id())->first()) {
            $this->authorize('delete', $like);

            $like->delete();
        }
    }

    /**
     * Get the placeholder for the component.
     */
    public function placeholder(): View
    {
        return view('livewire.links.placeholder'); // @codeCoverageIgnore
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        $link = Link::where('id', $this->linkId)
            ->withExists(['bookmarks as is_bookmarked' => function (Builder $query): void {
                $query->where('user_id', auth()->id());
            }, 'likes as is_liked' => function (Builder $query): void {
                $query->where('user_id', auth()->id());
            }])
            ->when($this->inThread && ! $this->commenting, function (Builder $query): void {
                $query->with(['descendants' => function (Relation $relation): void {
                    $relation->getQuery()
                        ->with('parent')
                        ->limit(1)
                        ->orderByDesc('updated_at');
                }]);
            })
            ->withCount(['likes', 'bookmarks'])
            ->firstOrFail();

        $property = \App\Models\LinkProperty::select(['links_property.id', 'links_property.name', 'links_property.slug', 'links_property_values.value'])->where('links_property.category', 1)
            ->leftJoin('links_property_values', function ($join) {
                $join->on('links_property_values.link_property_id', '=', 'links_property.id');
                $join->on('links_property_values.link_id', '=', DB::raw(($this->linkId > 0 ? $this->linkId : 0)));
            })
            ->orderBy('links_property.name', 'ASC')
            ->get()->toArray();

        return view($this->view, [
            'user' => $link->user,
            'link' => $link,
            'property' => $property
        ]);
    }
}
