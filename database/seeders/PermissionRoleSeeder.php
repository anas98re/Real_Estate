<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Admin' => Permission::all()->pluck('id'),
            'Owner' => Permission::whereIn('name', [
                'realty.add',
                'realty.update',
                'realty.remove',
                'realty.show',
                'user.update',
                'user.show',
                'appointment.add',
                'appointment.update',
                'appointment.remove',
                'appointment.show'
            ])->pluck('id'),
            'Agent' => Permission::whereIn('name', [
                'realty.add',
                'realty.update',
                'realty.remove',
                'realty.show',
                'user.show',
                'appointment.add',
                'appointment.update',
                'appointment.remove',
                'appointment.show',
                'activity.show',
                'service.show',
                'rating.add',
                'rating.update',
                'rating.show'
            ])->pluck('id'),
            'Client' => Permission::whereIn('name', [
                'realty.show',
                'user.update',
                'user.show',
                'appointment.add',
                'appointment.show',
                'rating.add',
                'rating.show'
            ])->pluck('id'),
            'Real-estate-Agent' => Permission::whereIn('name', [
                'realty.add',
                'realty.update',
                'realty.remove',
                'realty.show',
                'user.show',
                'appointment.add',
                'appointment.update',
                'appointment.remove',
                'appointment.show',
                'rating.update',
                'rating.show'
            ])->pluck('id'),
            'Financial-Agent' => Permission::whereIn('name', [
                'service.add',
                'service.update',
                'service.remove',
                'service.show',
                'user.show'
            ])->pluck('id'),
            'Photographer' => Permission::whereIn('name', [
                'activity.add',
                'activity.update'
            ])->pluck('id'),
            'Inspector' => Permission::whereIn('name', [
                'activity.add',
                'activity.update',
                'activity.show'
            ])->pluck('id'),
        ];

        foreach ($roles as $roleName => $permissionIds) {
            $role = Role::where('name', $roleName)->first();
            foreach ($permissionIds as $permissionId) {
                DB::table('permission_role')->insert([
                    'permission_id' => $permissionId,
                    'role_id' => $role->id,
                ]);
            }
        }
    }
}
