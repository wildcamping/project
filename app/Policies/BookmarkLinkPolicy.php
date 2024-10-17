<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\BookmarkLink;
use App\Models\User;

final readonly class BookmarkLinkPolicy
{
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BookmarkLink $bookmark): bool
    {
        return $user->id === $bookmark->user_id;
    }
}
