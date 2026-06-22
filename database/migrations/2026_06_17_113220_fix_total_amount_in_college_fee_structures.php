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
            $table->decimal('total_amount', 15, 2)->default(0)->change();
        });
    }
    
    public function down(): void
    {
        Schema::table('college_fee_structures', function (Blueprint $table) {
            $table->unsignedInteger('total_amount')->default(0)->change();
        });
    }
};
