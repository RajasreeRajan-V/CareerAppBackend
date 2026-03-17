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
        Schema::table('college_registrations', function (Blueprint $table) {

            if (!Schema::hasColumn('college_registrations', 'password')) {
                $table->string('password')->after('pincode');
            }

            if (!Schema::hasColumn('college_registrations', 'college_id')) {
                $table->foreignId('college_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('colleges')
                      ->onDelete('cascade');
            }

        });
    }

    public function down(): void
    {
        Schema::table('college_registrations', function (Blueprint $table) {

            if (Schema::hasColumn('college_registrations', 'college_id')) {
                $table->dropForeign(['college_id']);
                $table->dropColumn('college_id');
            }

            if (Schema::hasColumn('college_registrations', 'password')) {
                $table->dropColumn('password');
            }

        });
    }
};
