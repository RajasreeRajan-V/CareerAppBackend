<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('college_facilities', function (Blueprint $table) {
            $table->dropUnique('facility');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropUnique('name');
        });
    }

    public function down(): void
    {
        Schema::table('college_facilities', function (Blueprint $table) {
            $table->unique('facility');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->unique('name');
        });
    }
};