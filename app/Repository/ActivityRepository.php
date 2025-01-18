<?php

namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use App\ApiHelper\ApiResponseHelper;
use App\Http\Resources\ActivityResource;
use App\Interfaces\ActivityInterface;
use App\Interfaces\AuthInterface;
use App\Models\Activity;
use App\Models\Activity_user;

class ActivityRepository extends BaseRepositoryImplementation implements ActivityInterface
{
    public function model()
    {
        return Activity::class;
    }

    public function getAllActivities($perPage = 10)
    {
        try {
            $perPage = request('per_page', $perPage);
            $activities = Activity::paginate($perPage);
            $ActivityResources = ActivityResource::collection($activities);
            return ApiResponseHelper::sendResponseCollection($ActivityResources, "All activities retrieved successfully");
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function addActivity($request)
    {
        try {
            $data = $request->all();
            $activity = $this->create($data);

            Activity_user::create([
                'user_id' => auth('sanctum')->user()->id,
                'activity_id' => $activity->id
            ]);

            $activityResources = new ActivityResource($activity);
            return ApiResponseHelper::sendResponseNew($activityResources, "Activity added successfully");
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function updateActivity($request, $id)
    {
        try {
            $activity = Activity::find($id);

            if (!$activity) {
                return ApiResponseHelper::sendMessageResponse('Activity not found', 404, false);
            }

            $data = $request->all();

            // Handling translatable fields explicitly if they are present
            foreach (['activity_name'] as $field) {
                if (isset($data[$field]) && is_array($data[$field])) {
                    $activity->setTranslations($field, $data[$field]);
                } else {
                    // Update non-translatable fields directly or handle errors
                    $activity->fill($data);
                }
            }

            $activity->save();

            // Update or create the relation to Activity_user
            Activity_user::updateOrCreate([
                'user_id' => auth('sanctum')->user()->id,
                'activity_id' => $activity->id
            ]);

            $activityResources = new ActivityResource($activity);
            return ApiResponseHelper::sendResponseNew($activityResources, "Activity updated successfully");
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }


    public function removeActivity($id)
    {
        try {
            $activity = Activity::find($id);

            if (!$activity) {
                return ApiResponseHelper::sendMessageResponse('Activity not found', 404, false);
            }

            $activity->delete();

            return ApiResponseHelper::sendMessageResponse('Activity deleted successfully', 200, true);
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function getUserActivities($perPage = 10)
    {
        $userId = auth('sanctum')->user()->id;

        $perPage = request('per_page', $perPage);
        $userActivities = Activity_user::where('user_id', $userId)
            ->with('activity')->paginate($perPage);

        if ($userActivities->isEmpty()) {
            return ApiResponseHelper::sendMessageResponse('No activities found for the user', 404, false);
        }

        $activities = $userActivities->pluck('activity');
        $ActivityResources = ActivityResource::collection($activities);
        return ApiResponseHelper::sendResponseCollection($ActivityResources, "User activities retrieved successfully");
    }
}