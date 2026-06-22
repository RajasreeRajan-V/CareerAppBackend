<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('device_tokens', function (Blueprint $table) {
        $table->timestamp('failed_at')->nullable()->after('updated_at');
        $table->text('fail_reason')->nullable()->after('failed_at');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('device_tokens', function (Blueprint $table) {
        $table->dropColumn(['failed_at', 'fail_reason']);
    });
}
};
