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
       Schema::table('device_tokens', function (Blueprint $table) {
            $table->string('device_id')->nullable()->after('fail_reason');
            $table->string('platform')->nullable()->after('device_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_tokens', function (Blueprint $table) {
            $table->dropColumn(['device_id', 'platform']);
        });
    }
};
