<?php

namespace App\Actions\ActivityActions;

use App\ApiHelper\PermissionsHelper;
use App\Repository\ActivityRepository;
use Illuminate\Http\Request;

class RemoveActivityAction
{
    protected $ActivityRepository;

    public function __construct(ActivityRepository $ActivityRepository) {
        $this->ActivityRepository = $ActivityRepository;
    }

    public function __invoke(Request $request)
    {
        $id = $request->route('id');
        if (
            !PermissionsHelper::hasPermission('activity.remove')
            || !PermissionsHelper::hasPermissionUpdateOrRemoveActivity($id)
        ) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->ActivityRepository->removeActivity($id);
    }
}
