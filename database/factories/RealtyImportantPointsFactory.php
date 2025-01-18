<?php

namespace Database\Factories;

use App\Models\Realty;
use App\Models\RealtyImportantPoints;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RealtyImportantPoints>
 */
class RealtyImportantPointsFactory extends Factory
{
    protected $model = RealtyImportantPoints::class;

    public function definition()
    {
        return [
            'realty_id' => \App\Models\Realty::factory(),
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
