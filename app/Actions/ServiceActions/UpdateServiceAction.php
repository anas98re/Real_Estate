<?php

namespace App\Actions\ServiceActions;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\UpdateactivityRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Repository\ActivityRepository;
use App\Repository\ServiceRepository;

class UpdateServiceAction
{
    protected $ServiceRepository;

    public function __construct(ServiceRepository $ServiceRepository)
    {
        $this->ServiceRepository = $ServiceRepository;
    }

    public function __invoke(UpdateServiceRequest $request)
    {
        $id = $request->route('id');
        if (
            !PermissionsHelper::hasPermission('service.update')
            || !PermissionsHelper::hasPermissionUpdateOrRemoveService($id)
        ) {
            $service = Service::find($id);

            if (!$service) {
                return ApiResponseHelper::sendMessageResponse('Service not found and Forbidden', 404, false);
            }
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $this->ServiceRepository->updateService($request, $id);
    }
}
