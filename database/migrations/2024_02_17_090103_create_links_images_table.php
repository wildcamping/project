<?php

declare(strict_types=1);

use App\Models\Link;
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('links_images');
        Schema::create('links_images', function (Blueprint $table): void {
            $table->id();
            $table->string('url', 255);
            $table->string('name', 40);
            $table->integer('order', 0);
            
            $table->boolean('is_confirm')->default(true);
            $table->foreignIdFor(Link::class)->constrained()->cascadeOnDelete();
        });


    }
};
