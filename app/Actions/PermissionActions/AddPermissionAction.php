<?php

namespace App\Actions\PermissionActions;

use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\StoreappointmentRequest;
use App\Http\Requests\StorePermissionRequest;
use App\Repository\PermissionRepository;

class AddPermissionAction
{
    protected $PermissionRepository;

    public function __construct(PermissionRepository $PermissionRepository)
    {
        return $this->PermissionRepository = $PermissionRepository;
    }

    public function __invoke(StorePermissionRequest $request)
    {
        if (!PermissionsHelper::hasPermission('permission.add')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->PermissionRepository->addPermission($request);
    }
}
