<?php

declare(strict_types=1);

use App\Models\Link;
use App\Models\LinkProperty;
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
        Schema::dropIfExists('links_property');
        Schema::create('links_property', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 40);
            $table->string('slug', 40);
            $table->integer('category');
        });

        
        Schema::dropIfExists('links_property_values');
        Schema::create('links_property_values', function (Blueprint $table): void {
            $table->id();
            $table->string('value', 160);
            $table->foreignIdFor(Link::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(LinkProperty::class)->constrained()->cascadeOnDelete();
        });
    }
};
