<?php

namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use App\ApiHelper\ApiResponseHelper;
use App\http\Constants;
use App\Interfaces\PermissionInterface;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\DB;

class PermissionRepository extends BaseRepositoryImplementation implements PermissionInterface
{
    public function model()
    {
        return Permission::class;
    }
    public function getAllPermission($perPage)
    {
        try {
            $perPage = request('per_page', $perPage);
            $Data = Permission::paginate($perPage);
            return ApiResponseHelper::sendResponseNew($Data, "These are permissions");
        } catch (Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function addPermission($request)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();
            $permission = Permission::create($data);

            // Adding permission to admin denamically
            $permissionRole = new PermissionRole();
            $permissionRole->permission_id = $permission->id;
            $permissionRole->role_id = Constants::ADMIN_ROLE;
            $permissionRole->save();

            // Adding permission to another
            if (isset($request['roles'])) {
                foreach ($request['roles'] as $role) {
                    $permissionRole = new PermissionRole();
                    $permissionRole->permission_id = $permission->id;
                    $permissionRole->role_id = $role;
                    $permissionRole->save();
                }
            }

            DB::commit();
            return ApiResponseHelper::sendResponseNew($permission, "Added successfully");
        } catch (Exception $e) {
            DB::rollBack();
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function updatePermission($request, $id)
    {
        try {
            DB::beginTransaction();

            $permission = Permission::find($id);

            if (!$permission) {
                return ApiResponseHelper::sendMessageResponse('Permission not found', 404, false);
            }

            $data = $request->all();
            $permission->update($data);

            // Update permission roles
            PermissionRole::where('permission_id', $permission->id)
                ->where('role_id', '!=', Constants::ADMIN_ROLE)
                ->delete(); // Remove existing roles


            if (isset($data['roles'])) {
                foreach ($data['roles'] as $role) {
                    $newPermissionRole = new PermissionRole();
                    $newPermissionRole->permission_id = $permission->id;
                    $newPermissionRole->role_id = $role;
                    $newPermissionRole->save();
                }
            }

            DB::commit();
            return ApiResponseHelper::sendResponseNew($permission, "Updated successfully");
        } catch (Exception $e) {
            DB::rollBack();
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }
    public function removePermission($id)
    {
        try {
            DB::beginTransaction();

            $permission = Permission::find($id);

            if (!$permission) {
                return ApiResponseHelper::sendMessageResponse('Permission not found', 404, false);
            }

            $permission->delete();

            PermissionRole::where('permission_id', $permission->id)
                ->delete();

            DB::commit();
            return ApiResponseHelper::sendResponseNew("Done", "Deleted successfully");
        } catch (Exception $e) {
            DB::rollBack();
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function getPermissionsByRole($role)
    {
        try {
            DB::beginTransaction();

            $role = Role::where('name', $role)->first();

            if (! $role) {
                return ApiResponseHelper::sendMessageResponse('Role not found', 404, false);
            }

            $permissions = $role->permissions;

            if ($permissions->isEmpty()) {
                return ApiResponseHelper::sendMessageResponse('Permissions not found for this role', 404, false);
            }

            DB::commit();
            return ApiResponseHelper::sendResponseNew($permissions, "These are permissions");
        } catch (Exception $e) {
            DB::rollBack();
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }
}
