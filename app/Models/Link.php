<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\LinkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; 
use Usamamuneerchaudhary\Commentify\Traits\Commentable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelVote\Traits\Votable;

/**
 * @property int $id
 * @property int $user_id
 * @property int $click_count
 * @property string $description
 * @property string $title
 * @property string $lat
 * @property string $lng
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $views
 * @property-read User $user
 */
final class Link extends Model
{
    /** @use HasFactory<LinkFactory> */
    use HasFactory;
    use Commentable;
    use SoftDeletes;
    use Votable;

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'click_count',
        'description',
        'title',
        'lat',
        'lng',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the user that owns the link.
     *
     * @return BelongsTo<User, Link>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the link's images.
     *
     * @return HasMany<Link>
     */
    public function images(): HasMany
    {
        return $this->hasMany(\App\Models\LinkImages::class)
        ->where('links_images.is_confirm', '=', 1);
    }

    /**
     * Get the link's property.
     *
     * @return HasMany<Link>
     */
    public function property_values(): HasMany
    {
        return $this->hasMany(\App\Models\LinkPropertyValues::class);
    }

    /**
     * Get the bookmarks for the question.
     *
     * @return HasMany<BookmarkLink>
     */
    public function bookmarks(): HasMany
    {
        return $this->hasMany(BookmarkLink::class);
    }

    /**
     * Get the likes for the question.
     *
     * @return HasMany<LikeLink>
     */
    public function likes(): HasMany
    {
        return $this->hasMany(LikeLink::class);
    }

    /**
     * Get the likes for the question.
     *
     * @return HasMany<LinkCorrections>
     */
    public function active_correction(): HasMany
    {
        return $this->hasMany(LinkCorrections::class)
        ->where('links_corrections.user_id', '=', auth()->id())
        ->where('links_corrections.is_confirm', '=', 0);
    }

    /**
     * Get the likes for the question.
     *
     * @return HasMany<LikeLink>
     */
   // public function comments(): HasMany
   // {
       // return $this->hasMany(\Usamamuneerchaudhary\Commentify\Models\Comment::class);
  //  }


    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }


    public function updateModel($data): bool
    {
        $property = $data['property'];
        unset($data['property']);

        $this->update($data);

        foreach ($property as $key => $item) {
            if (!is_null($item['value']))
                \App\Models\LinkPropertyValues::updateOrCreate(
                    ['link_id' => $this->id, 'link_property_id' => $item['id']],
                    ['value' => $item['value']]
                );
        }

        return true;
    }

    public static function createModel($data, $user_id): bool
    {
        $property = $data['property'];
        unset($data['property']);

        $images_path = $data['images_preview'];
        unset($data['images_preview']);

        $data['user_id'] = (int)$user_id;

        DB::beginTransaction();

        try {

            $link = Link::create($data);
            
            foreach ($property as $key => $item) {
                if (!is_null($item['value']))
                    \App\Models\LinkPropertyValues::updateOrCreate(
                        ['link_id' => $link->id, 'link_property_id' => $item['id']],
                        ['value' => $item['value']]
                    );
            }

            foreach ($images_path as $key => $item) {
                \App\Models\LinkImages::Create(
                    ['link_id' => $link->id, 'name' => $item['name'], 'url' => $item['path']]
                );
            }
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return true;
        }
    }

    public function deleteLink(): bool
    {
        DB::beginTransaction();
        try { 
            foreach ($this->images()->get() as $key => $item) { 
                if (file_exists(public_path().$item->url))
                    unlink(public_path().$item->url);
                $item->delete();
            }
            $this->delete();
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return true;
        }
    }

    public function commentsCount(): int
    {
        return (int)\Usamamuneerchaudhary\Commentify\Models\Comment::where('commentable_type', 'App\Models\Link')->where('commentable_id', $this->id)->count();
    }
    
}
