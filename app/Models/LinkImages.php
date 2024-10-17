<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $link_id
 * @property string $url
 * @property string $name
 */
final class LinkImages extends Model
{

    protected $table = 'links_images';

    public $timestamps = false;


}
