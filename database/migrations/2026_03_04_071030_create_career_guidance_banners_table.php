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
        Schema::create('career_guidance_banners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('instructor_name');
            $table->text('description');

            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->string('google_meet_link');
            $table->string('image');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_guidance_banners');
    }
};
