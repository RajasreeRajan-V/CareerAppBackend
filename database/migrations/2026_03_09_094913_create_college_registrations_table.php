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
        Schema::create('college_registrations', function (Blueprint $table) {
            $table->id();

            $table->string('college_name');
            $table->string('email')->unique();
            $table->string('contact_no', 12)->unique();
            $table->string('website')->nullable();
            $table->string('principal_name');

            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('pincode', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('college_registrations');
    }
};
