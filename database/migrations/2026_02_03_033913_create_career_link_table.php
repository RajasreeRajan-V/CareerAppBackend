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
        Schema::create('career_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_career_id')
                  ->constrained('career_nodes')
                  ->cascadeOnDelete();

            $table->foreignId('child_career_id')
                  ->constrained('career_nodes')
                  ->cascadeOnDelete();

            $table->unique(['parent_career_id', 'child_career_id']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_link');
    }
};
