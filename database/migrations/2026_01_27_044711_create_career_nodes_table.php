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
        Schema::create('career_nodes', function (Blueprint $table) {
        $table->id();
        $table->string('title')->unique();
        $table->text('description');

        // Multiple values stored as JSON
        $table->json('subjects')->nullable();
        $table->json('career_options');

        $table->string('video');
        $table->string('thumbnail');

        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_nodes');
    }
};
