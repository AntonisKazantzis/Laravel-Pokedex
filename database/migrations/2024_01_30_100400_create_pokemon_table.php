<?php

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
        Schema::create('pokemon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pokemon_id');
            $table->string('name')->index();
            $table->string('sprite_1_path')->index();
            $table->string('sprite_2_path')->index();
            $table->float('height');
            $table->float('weight');
            $table->json('types');
            $table->json('stats');
            $table->json('abilities');
            $table->json('egg_groups');
            $table->json('evolution_chain');
            $table->string('genera')->nullable()->index();
            $table->string('growth_rate')->index();
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pokemon');
    }
};
