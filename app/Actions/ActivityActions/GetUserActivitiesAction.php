<?php

namespace App\Actions\ActivityActions;

use App\Repository\ActivityRepository;
use Illuminate\Http\Request;

class GetUserActivitiesAction
{
    protected $ActivityRepository;

    public function __construct(ActivityRepository $ActivityRepository) {
        $this->ActivityRepository = $ActivityRepository;
    }

    public function __invoke(Request $request)
    {
        $perPage = $request->route('perPage');
        return $this->ActivityRepository->getUserActivities($perPage);
    }
}
