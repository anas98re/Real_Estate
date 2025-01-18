<?php

namespace App\Actions\ServiceActions;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\PermissionsHelper;
use App\Models\Service;
use App\Repository\ActivityRepository;
use App\Repository\ServiceRepository;
use Illuminate\Http\Request;

class RemoveServiceAction
{
    protected $ServiceRepository;

    public function __construct(ServiceRepository $ServiceRepository) {
        $this->ServiceRepository = $ServiceRepository;
    }

    public function __invoke(Request $request)
    {
        $id = $request->route('id');
        if (
            !PermissionsHelper::hasPermission('service.remove')
            || !PermissionsHelper::hasPermissionUpdateOrRemoveService($id)
        ) {
            $service = Service::find($id);

            if (!$service) {
                return ApiResponseHelper::sendMessageResponse('Service not found and Forbidden', 404, false);
            }
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $this->ServiceRepository->removeService($id);
    }
}
