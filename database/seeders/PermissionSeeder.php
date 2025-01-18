<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissionNames = [
            'realty.add',
            'realty.update',
            'realty.remove',
            'realty.show',
            'user.add',
            'user.update',
            'user.remove',
            'user.show',
            'activity.add',
            'activity.update',
            'activity.remove',
            'activity.show',
            'service.add',
            'service.update',
            'service.remove',
            'service.show',
            'rating.add',
            'rating.update',
            'rating.remove',
            'rating.show',
            'permission.add',
            'permission.update',
            'permission.remove',
            'permission.show',
            'appointment.add',
            'appointment.update',
            'appointment.remove',
            'appointment.show',
            'publicChat.add',
            'rating.remove'
        ];

        foreach ($permissionNames as $permissionName) {
            Permission::create(['name' => $permissionName]);
        }
    }
}
