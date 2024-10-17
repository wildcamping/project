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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('links_corrections');
        Schema::create('links_corrections', function (Blueprint $table): void {
            $table->id();
            $table->text('description')->nullable();
            $table->boolean('is_confirm')->default(true);
            $table->foreignIdFor(Link::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });


    }
};
