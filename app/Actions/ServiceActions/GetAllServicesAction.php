<?php

namespace App\Actions\ServiceActions;

use App\ApiHelper\PermissionsHelper;
use App\Repository\ActivityRepository;
use App\Repository\ServiceRepository;
use Illuminate\Http\Request;

class GetAllServicesAction
{
    protected $ServiceRepository;

    public function __construct(ServiceRepository $ServiceRepository) {
        $this->ServiceRepository = $ServiceRepository;
    }

    public function __invoke(Request $request)
    {
        $perPage = $request->route('perPage');
        if (!PermissionsHelper::hasPermission('service.show')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->ServiceRepository->getAllServices($perPage);
    }
}
