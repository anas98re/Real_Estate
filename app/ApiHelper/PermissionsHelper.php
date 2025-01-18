<?php

namespace App\ApiHelper;

use App\Models\Activity;
use App\Models\Activity_user;
use App\Models\Appointment;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Rating;
use App\Models\Realty_user;
use App\Models\Service;
use App\Models\Service_user;
use App\Models\User_role;

class PermissionsHelper
{
    public static function hasPermission1($name)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return false;
        }
        $permissionId = Permission::where('name', $name)->first()->id;

        $roleIds = User_role::where('user_id', $user->id)->get();

        $permissionRole = 0;
        foreach ($roleIds as $roleId) {
            $permissionRoleCheck = PermissionRole::where('permission_id', $permissionId)
                ->where('role_id', $roleId)->first();
            if ($permissionRoleCheck) $permissionRole = 0;
            break;
        }

        return $permissionRole ? 1 : 0;
    }

    public static function hasPermission($name)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return false;
        }

        // Find the permission based on the name
        $permission = Permission::where('name', $name)->first();

        if (!$permission) {
            return false;
        }

        // Get the user's roles
        $userRoles = $user->roles;

        // Check if any of the user's roles have the required permission
        foreach ($userRoles as $role) {
            $hasPermission = $role->permissions()->where('permissions.id', $permission->id)->exists();

            if ($hasPermission) {
                return true; // User has the permission
            }
        }

        return false; // User does not have the permission
    }

    public static function hasPermissionUpdateOrRemoveRealty($id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return false;
        }

        $realtyUser = Realty_user::where('user_id', $user->id)->where('realty_id', $id)->first();
        return $realtyUser ? 1 : 0;
    }

    public static function hasPermissionShowProfile($id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return false;
        }

        return $user->id == $id || $user->role_id == 1 ? true : false;
    }

    public static function hasPermissionUpdateOrRemoveActivity($id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return false;
        }

        $activityUser = Activity_user::where('user_id', $user->id)->where('activity_id', $id)->first();
        return $activityUser ? 1 : 0;
    }

    public static function hasPermissionUpdateOrRemoveAppointment($id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return false;
        }

        $Appointment = Appointment::where('user_id', $user->id)->where('id', $id)->first();
        return $Appointment ? 1 : 0;
    }

    public static function hasPermissionUpdateOrRemoveService($id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return false;
        }

        $serviceUser = Service_user::where('user_id', $user->id)
            ->where('service_id', $id)->first();
        return $serviceUser ? 1 : 0;
    }

    public static function hasPermissionUpdateOrRemoveRealtyRating()
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return false;
        }
        if ($user->role_id == 1) return true;

        $RatingUser = Rating::where('user_id', $user->id)->first();
        return $RatingUser ? 1 : 0;
    }
}
