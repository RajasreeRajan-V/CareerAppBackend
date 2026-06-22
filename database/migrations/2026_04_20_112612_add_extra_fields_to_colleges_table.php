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
        Schema::table('colleges', function (Blueprint $table) {
             $table->string('principal_name')->nullable()->after('about');
             $table->string('password')->nullable()->after('principal_name');
             $table->foreignId('state_id')
                ->nullable()
                ->after('password')
                ->constrained('states')
                ->nullOnDelete();

             $table->foreignId('district_id')
                ->nullable()
                ->after('state_id')
                ->constrained('districts')
                ->nullOnDelete();

            $table->string('pincode', 6)->nullable()->after('district_id');
            $table->boolean('is_verified')->default(false)->after('pincode');
            $table->boolean('password_changed')->default(false)->after('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
     public function down(): void
    {
        Schema::table('colleges', function (Blueprint $table) {

            $table->dropForeign(['state_id']);
            $table->dropForeign(['district_id']);

            $table->dropColumn([
                'principal_name',
                'password',
                'state_id',
                'district_id',
                'pincode',
                'is_verified',
                'password_changed'
            ]);
        });
    }
};
