<?php

namespace App\Actions\ActivityActions;

use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\StoreactivityRequest;
use App\Http\Requests\StorerealtyRequest;
use App\Repository\ActivityRepository;

class AddActivityAction
{
    protected $ActivityRepository;

    public function __construct(ActivityRepository $ActivityRepository) {
        return $this->ActivityRepository = $ActivityRepository;
    }

    public function __invoke(StoreactivityRequest $request)
    {
        // return $request;
        if (!PermissionsHelper::hasPermission('activity.add')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->ActivityRepository->addActivity($request);
    }
}
