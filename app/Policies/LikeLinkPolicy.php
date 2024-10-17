<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\LikeLink;
use App\Models\User;

final readonly class LikeLinkPolicy
{
    /**
     * Determine whether the user can delete the like.
     */
    public function delete(User $user, LikeLink $like): bool
    {
        return $user->id === $like->user_id;
    }
}
