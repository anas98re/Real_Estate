<?php

namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use App\ApiHelper\ApiResponseHelper;
use App\Http\Resources\ServiceResource;
use App\Interfaces\ServiceInterface;
use App\Models\Service;
use App\Models\Service_user;

class ServiceRepository extends BaseRepositoryImplementation implements ServiceInterface
{
    public function model()
    {
        return Service::class;
    }

    public function getAllServices($perPage = 10)
    {
        try {
            $perPage = request('per_page', $perPage);
            $services = Service::paginate($perPage);
            $serviceResources = ServiceResource::collection($services);
            return ApiResponseHelper::sendResponseCollection($serviceResources, "All services retrieved successfully");
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function addService($request)
    {
        try {
            $data = $request->all();
            $service = $this->create($data);

            Service_user::create([
                'user_id' => auth('sanctum')->user()->id,
                'service_id' => $service->id
            ]);

            $serviceResources = new ServiceResource($service);
            return ApiResponseHelper::sendResponseNew($serviceResources, "Service added successfully");
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function updateService($request, $id)
    {
        try {
            $service = Service::find($id);

            if (!$service) {
                return ApiResponseHelper::sendMessageResponse('Service not found', 404, false);
            }

            $data = $request->all();
            $service->update($data);

            Service_user::updateOrCreate([
                'user_id' => auth('sanctum')->user()->id,
                'service_id' => $service->id
            ]);

            $serviceResources = new ServiceResource($service);
            return ApiResponseHelper::sendResponseNew($serviceResources, "Service updated successfully");
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function removeService($id)
    {
        try {
            $service = Service::find($id);

            if (!$service) {
                return ApiResponseHelper::sendMessageResponse('Service not found', 404, false);
            }

            $service->delete();

            return ApiResponseHelper::sendMessageResponse('Service deleted successfully', 200, true);
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function getUserServices($perPage = 10)
    {
        $userId = auth('sanctum')->user()->id;

        $perPage = request('per_page', $perPage);
        $userServices = Service_user::where('user_id', $userId)
            ->with('service')->paginate($perPage);

        if ($userServices->isEmpty()) {
            return ApiResponseHelper::sendMessageResponse('No services found for the user', 404, false);
        }

        $services = $userServices->pluck('service');
        $serviceResources = ServiceResource::collection($services);
        return ApiResponseHelper::sendResponseCollection($serviceResources, "User services retrieved successfully");
    }
}
