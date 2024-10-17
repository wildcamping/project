<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $link_id
 * @property int $link_value_id
 * @property string $value
 */
final class LinkPropertyValues extends Model
{

    protected $table = 'links_property_values';

    public $timestamps = false;

    /**
     * Get the user that owns the link.
     *
     * @return BelongsTo<Property, Property>
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

}
