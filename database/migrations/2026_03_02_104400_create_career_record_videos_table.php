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
        Schema::create('career_record_videos', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('about');
            $table->string('url');
            $table->string('duration');
            $table->string('creator');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_record_videos');
    }
};
