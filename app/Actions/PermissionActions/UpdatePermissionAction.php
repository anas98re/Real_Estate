<?php

namespace App\Actions\PermissionActions;

use App\ApiHelper\PermissionsHelper;
use App\Repository\PermissionRepository;
use Illuminate\Http\Request;

class UpdatePermissionAction
{
    protected $PermissionRepository;

    public function __construct(PermissionRepository $PermissionRepository)
    {
        return $this->PermissionRepository = $PermissionRepository;
    }

    public function __invoke(Request $request)
    {
        $id = $request->route('id');
        if (!PermissionsHelper::hasPermission('permission.update')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->PermissionRepository->updatePermission($request, $id);
    }
}
