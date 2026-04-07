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
          Schema::table('career_nodes', function (Blueprint $table) {
            $table->boolean('newgen_course')->default(false)->after('career_options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('career_nodes', function (Blueprint $table) {
            $table->dropColumn('newgen_course');
        });
    }
};
