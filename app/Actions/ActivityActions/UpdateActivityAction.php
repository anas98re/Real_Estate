<?php

namespace App\Actions\ActivityActions;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\UpdateactivityRequest;
use App\Models\Activity;
use App\Repository\ActivityRepository;

class UpdateActivityAction
{
    protected $ActivityRepository;

    public function __construct(ActivityRepository $ActivityRepository)
    {
        $this->ActivityRepository = $ActivityRepository;
    }

    public function __invoke(UpdateactivityRequest $request)
    {
        $id = $request->route('id');
        if (
            !PermissionsHelper::hasPermission('activity.update')
            || !PermissionsHelper::hasPermissionUpdateOrRemoveActivity($id)
        ) {
            $activity = Activity::find($id);

            if (!$activity) {
                return ApiResponseHelper::sendMessageResponse('Activity not found and Forbidden', 404, false);
            }
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->ActivityRepository->updateActivity($request, $id);
    }
}
