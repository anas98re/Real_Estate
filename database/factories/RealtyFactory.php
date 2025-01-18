<?php

namespace Database\Factories;

use App\Models\Realty;
use App\Models\RealtyFeatures;
use App\Models\RealtyImportantPoints;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Realty>
 */
class RealtyFactory extends Factory
{
    protected $model = Realty::class;

    public function definition()
    {
        return [
            'name' => [
                'en' => $this->faker->words(2, true),
                'fr' => $this->faker->words(2, true),
            ],
            'description' => [
                'en' => $this->faker->paragraph,
                'fr' => $this->faker->paragraph,
            ],
            'price' => $this->faker->numberBetween(100000, 1000000),
            'location' => [
                'en' => $this->faker->address,
                'fr' => $this->faker->address,
            ],
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'num_rooms' => $this->faker->numberBetween(1, 5),
            'num_bathrooms' => $this->faker->numberBetween(1, 3),
            'space_sqft' => $this->faker->numberBetween(500, 3000),
            'year_built' => $this->faker->numberBetween(1970, 2020),
            'parking_spaces' => $this->faker->numberBetween(0, 2),
            'lot_size' => $this->faker->numberBetween(1000, 5000),
            'status' => $this->faker->randomElement(['Available', 'Sold', 'Under Offer']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Realty $realty) {
            // Explicitly ensure the closure is correctly scoped
            RealtyFeatures::factory()->count(3)->create([
                'realty_id' => $realty->id,
            ]);

            RealtyImportantPoints::factory()->count(3)->create([
                'realty_id' => $realty->id,
            ]);
        });
    }
}
