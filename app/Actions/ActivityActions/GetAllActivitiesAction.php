<?php

namespace App\Actions\ActivityActions;

use App\ApiHelper\PermissionsHelper;
use App\Repository\ActivityRepository;
use Illuminate\Http\Request;

class GetAllActivitiesAction
{
    protected $ActivityRepository;

    public function __construct(ActivityRepository $ActivityRepository)
    {
        $this->ActivityRepository = $ActivityRepository;
    }

    public function __invoke(Request $request)
    {
        $perPage = $request->route('perPage');
        if (!PermissionsHelper::hasPermission('activity.show')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->ActivityRepository->getAllActivities($perPage);
    }
}
