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

            $table->string('growth')->nullable()->after('specialization');
            $table->string('demand')->nullable()->after('growth');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_nodes', function (Blueprint $table) {

            $table->dropColumn(['growth', 'demand']);

        });
    }
};
