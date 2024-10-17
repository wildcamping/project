<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Map;

Route::middleware('guest')->group(function () {
    Route::get('/getLinks', [Map::class, 'getLinks'])->name('api-getlinks');
    Route::get('/getLink,{link_id}', [Map::class, 'getLink'])->name('api-getlink');
    
});

Route::middleware('auth')->group(function () {
    
});
