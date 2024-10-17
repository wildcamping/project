<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\LikeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $link_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property boolean $vote
 * @property-read User $user
 * @property-read Link $link
 */
final class ConfirmLink extends Model
{
    /** @use HasFactory<LikeFactory> */
    use HasFactory;

    protected $table = 'confirms_link';
    /**
     * The link that the like belongs to.
     *
     * @return BelongsTo<link, Like>
     */
    public function link(): BelongsTo
    {
        return $this->belongsTo(link::class);
    }

    /**
     * The user that the like belongs to.
     *
     * @return BelongsTo<User, Like>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
