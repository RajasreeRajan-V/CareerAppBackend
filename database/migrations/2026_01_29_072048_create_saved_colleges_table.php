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
    Schema::create('saved_colleges', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
              ->constrained('users')
              ->cascadeOnDelete();

        $table->foreignId('college_id')
              ->constrained('colleges')
              ->cascadeOnDelete();

        $table->timestamps();

        // Unique constraint
        $table->unique(['user_id', 'college_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_colleges');
    }
};
