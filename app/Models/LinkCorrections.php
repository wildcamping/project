<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $link_id
 * @property int $user_id
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class LinkCorrections extends Model
{
    use SoftDeletes;

    protected $table = 'links_corrections';

    public $timestamps = true;


}
