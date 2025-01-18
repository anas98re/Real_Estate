<?php

namespace Database\Seeders;

use App\Models\Realty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RealtySeeder extends Seeder
{
    public function run()
    {
        Realty::factory()->count(2000)->create();
    }
}
