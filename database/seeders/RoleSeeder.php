<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Owner']);
        Role::create(['name' => 'Agent']);
        Role::create(['name' => 'Client']);
        Role::create(['name' => 'Real-estate-Agent']);
        Role::create(['name' => 'Financial-Agent']);
        Role::create(['name' => 'Photographer']);
        Role::create(['name' => 'Inspector']);
    }
}
