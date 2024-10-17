<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final readonly class LinkController
{
    /**
     * Display the link.
     */
    public function show(User $user, Link $link): View
    { 
        Gate::authorize('view', $link);

        abort_unless($link->user_id === $user->id, 404);

        return view('profile.links.show', [
            'link' => $link
        ]);
    }
}
