<?php

namespace App\Actions\PermissionActions;

use App\ApiHelper\PermissionsHelper;
use App\Repository\PermissionRepository;
use Illuminate\Http\Request;
use Symfony\Component\Routing\RequestContext;

class GetPermissionsByRoleAction
{
    protected $PermissionRepository;

    public function __construct(PermissionRepository $PermissionRepository)
    {
        return $this->PermissionRepository = $PermissionRepository;
    }

    public function __invoke(Request $request)
    {
        $role = $request->route('role');
        if (!PermissionsHelper::hasPermission('permission.show')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->PermissionRepository->getPermissionsByRole($role);
    }
}
