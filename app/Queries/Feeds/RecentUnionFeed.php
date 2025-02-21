<?php

declare(strict_types=1);

namespace App\Queries\Feeds;

use App\Models\Question;
use App\Models\Link;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final readonly class RecentUnionFeed
{
    /**
     * Create a new instance of the RecentQuestionsFeed.
     */
    public function __construct(
        private ?string $hashtag = null,
    ) {}

    /**
     * Get the query builder for the feed.
     *
     * @return Builder<Question>
     */
    public function builder(): Builder
    {
        $questions = Question::query()
            ->where('answer', '!=', null)
            ->where('is_ignored', false)
            ->where('is_reported', false)
            ->when($this->hashtag, function (Builder $query): void {
                $query->select('id')->whereHas('hashtags', function (Builder $query): void {
                    $query
                    // using 'like' for this query (with no wildcards) will
                    // result in a case-insensitive lookup from sqlite,
                    // which is what we want.
                        ->where('name', 'like', $this->hashtag);
                });
            }, function (Builder $query): void {
                $query->select(DB::Raw('IFNULL(root_id, id) as newest_id'), DB::Raw('IFNULL(root_id, id) as id'))
                    ->groupBy('newest_id')
                    ->orderByDesc(DB::raw('MAX(`updated_at`)'));
            });

            return Link::query()
            ->where('is_visible', true)
            ->when($this->hashtag, function (Builder $query): void {
                $query->select('id')->whereHas('hashtags', function (Builder $query): void {
                    $query->where('name', 'like', $this->hashtag);
                });
            })
            // ->union($questions)
            ->orderByDesc('updated_at'); 
    }
}
