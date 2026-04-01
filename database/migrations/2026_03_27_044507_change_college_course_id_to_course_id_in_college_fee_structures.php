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
            // Drop old FK constraint
            $table->dropForeign(['college_course_id']);
            $table->dropColumn('college_course_id');

            // Add correct FK pointing to courses
            $table->foreignId('course_id')
                ->after('id')
                ->constrained('courses')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('college_fee_structures', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');

            $table->foreignId('college_course_id')
                ->constrained('college_courses')
                ->cascadeOnDelete();
        });
    }
};
