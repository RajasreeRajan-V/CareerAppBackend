<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CareerGuidanceRegistration;
use App\Models\CareerGuidanceBanner;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CareerGuidanceRegistration>
 */
class CareerGuidanceRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CareerGuidanceRegistration::class;

    public function definition(): array
    {
       return [
            'career_guidance_banner_id' => CareerGuidanceBanner::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => '9' . $this->faker->numberBetween(100000000, 999999999), // Indian-style
        ];
    }
}
