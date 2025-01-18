<?php

namespace App\Actions\ServiceActions;

use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\StoreactivityRequest;
use App\Http\Requests\StorerealtyRequest;
use App\Http\Requests\StoreServiceRequest;
use App\Repository\ActivityRepository;
use App\Repository\ServiceRepository;

class AddServiceAction
{
    protected $ServiceRepository;

    public function __construct(ServiceRepository $ServiceRepository) {
        return $this->ServiceRepository = $ServiceRepository;
    }

    public function __invoke(StoreServiceRequest $request)
    {
        if (!PermissionsHelper::hasPermission('service.add')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->ServiceRepository->addService($request);
    }
}
