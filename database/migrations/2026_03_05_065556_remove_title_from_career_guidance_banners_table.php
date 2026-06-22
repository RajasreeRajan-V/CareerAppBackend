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
        Schema::table('career_guidance_banners', function (Blueprint $table) {
            $table->string('profession')->nullable()->after('name');

            // Drop title column
            $table->dropColumn('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_guidance_banners', function (Blueprint $table) {

            // Add title column back
            $table->string('title')->nullable();

            // Remove profession column
            $table->dropColumn('profession');
        });
    }
};