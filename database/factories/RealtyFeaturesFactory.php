<?php

namespace Database\Factories;

use App\Models\Realty;
use App\Models\RealtyFeatures;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReaeltyFeatures>
 */
class RealtyFeaturesFactory extends Factory
{
    protected $model = RealtyFeatures::class;

    public function definition()
    {
        return [
            'realty_id' => \App\Models\Realty::factory(), // Generates a realty if not provided
            'name' => [
                'en' => $this->faker->words(2, true),
                'fr' => $this->faker->words(2, true),
            ],
            'description' => [
                'en' => $this->faker->sentence(),
                'fr' => $this->faker->sentence(),
            ],
        ];
    }
}
