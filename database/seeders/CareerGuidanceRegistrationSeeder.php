<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CareerGuidanceRegistration;

class CareerGuidanceRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        CareerGuidanceRegistration::factory()->count(20)->create();
    }
}
