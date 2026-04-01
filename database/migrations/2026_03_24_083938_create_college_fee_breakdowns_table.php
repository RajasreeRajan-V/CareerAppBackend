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
        Schema::create('college_fee_breakdowns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_structure_id')->constrained('college_fee_structures')->cascadeOnDelete();
            $table->string('label'); 
            $table->decimal('amount', 10, 2);
            $table->integer('sequence')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('college_fee_breakdowns');
    }
};
