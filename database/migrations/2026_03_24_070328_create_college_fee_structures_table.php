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
         Schema::create('college_fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('college_course_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->enum('fee_type', ['government', 'management', 'nri']);
            $table->enum('fee_mode', ['total', 'yearly', 'semester']);
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->string('currency')->default('INR');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('college_fee_structures');
    }
};
