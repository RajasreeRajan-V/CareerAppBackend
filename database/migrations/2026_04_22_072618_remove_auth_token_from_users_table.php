<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop columns
            $table->dropColumn(['auth_token', 'role', 'password', 'current_education']);
            
            // Modify columns
            $table->string('email')->nullable()->change();
            $table->string('phone')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restore dropped columns
            $table->string('auth_token')->after('current_education');
            $table->string('role');
            $table->string('password');
            $table->string('current_education')->nullable();
            
            // Revert column changes
            $table->string('email')->nullable(false)->change();
            $table->string('phone')->nullable()->change();
        });
    }
};