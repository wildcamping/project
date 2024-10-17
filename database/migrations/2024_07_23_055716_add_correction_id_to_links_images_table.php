<?php

declare(strict_types=1);

use App\Models\LinkCorrections;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('links_images', function (Blueprint $table): void {
            $table->foreignIdFor(\App\Models\LinkCorrections::class)->cascadeOnDelete()->nullable();
        });
    }
};
