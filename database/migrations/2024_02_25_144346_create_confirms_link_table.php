<?php

declare(strict_types=1);

use App\Models\Link;
use App\Models\User;
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
        Schema::create('confirms_link', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Link::class)->constrained()->cascadeOnDelete();

            $table->unique(['user_id', 'link_id']);
            $table->boolean('vote')->nullable()->default(null);

            $table->timestamps();
        });
    }
};
