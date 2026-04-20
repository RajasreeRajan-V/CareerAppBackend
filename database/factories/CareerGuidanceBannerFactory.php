<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CareerGuidanceBanner>
 */
class CareerGuidanceBannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
{
    return [
        'name' => $this->faker->name(),
        'profession' => $this->faker->jobTitle(),
        'instructor_name' => $this->faker->name(),
        'description' => $this->faker->paragraph(),
        'event_date' => now(),
        'start_time' => now(),
        'end_time' => now()->addHour(),
        'google_meet_link' => $this->faker->url(),
        'image' => 'default.jpg',
    ];
}

}
