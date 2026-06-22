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
        Schema::table('college_fee_structures', function (Blueprint $table) {
            $table->unique(['course_id', 'fee_type', 'fee_mode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('college_fee_structures', function (Blueprint $table) {
            $table->dropUnique(['course_id', 'fee_type', 'fee_mode']);
        });
    }
};
