<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\Realty;
use App\Models\User;
use App\Models\User_role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = User_role::where('role_id',1)->first()->user_id;
        $realties = Realty::all();
        foreach ($realties as $realty) {
            $checkExists = Rating::where('realty_id', $realty)->exists();
            if (!$checkExists) {
                Rating::create([
                    'rating' => 0,
                    'realty_id' => $realty->id,
                    'user_id' => $userId, // Replace with your user ID
                ]);
            }
        }
    }
}
