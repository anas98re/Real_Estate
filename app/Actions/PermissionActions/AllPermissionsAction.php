<?php

namespace App\Actions\PermissionActions;

use App\ApiHelper\PermissionsHelper;
use App\Repository\PermissionRepository;
use Illuminate\Http\Request;

class AllPermissionsAction
{
    protected $PermissionRepository;

    public function __construct(PermissionRepository $PermissionRepository)
    {
        return $this->PermissionRepository = $PermissionRepository;
    }

    public function __invoke(Request $request)
    {
        $perPage = $request->route('perPage');
        if (!PermissionsHelper::hasPermission('permission.show')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->PermissionRepository->getAllPermission($perPage);
    }
}
