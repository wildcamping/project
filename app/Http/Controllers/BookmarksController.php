<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

final readonly class BookmarksController
{
    /**
     * Display all bookmarks.
     */
    public function questions(): View
    {
        return view('bookmarks.questions');
    }

        /**
     * Display all bookmarks.
     */
    public function links(): View
    {
        return view('bookmarks.links');
    }
}
