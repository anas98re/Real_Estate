<?php

namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use App\ApiHelper\ApiResponseCodes;
use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\FileHelper;
use App\ApiHelper\PermissionsHelper;
use App\Http\Resources\RealtyResource;
use App\Interfaces\RealtyInterface;
use App\Models\Permission;
use App\Models\Photo;
use App\Models\realty;
use App\Models\Realty_user;
use App\Models\RealtyFeatures;
use App\Models\RealtyImportantPoints;
use App\Services\RealtyService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\returnSelf;

class RealtyRepository extends BaseRepositoryImplementation implements RealtyInterface
{

    protected $myService;

    public function __construct(RealtyService $myService)
    {
        return $this->myService = $myService;
    }

    public function model()
    {
        return Realty::class;
    }

    public function getAllRealties($request)
    {
        try {
            $perPage = request('per_page', 10);

            $sortBy = $request->input('sort_by', 'price');
            $sortOrder = $request->input('sort_order', 'asc');

            $Data = Realty::orderBy($sortBy, $sortOrder)->paginate($perPage);
            $realtyResources = RealtyResource::collection($Data); //ActivityResource

            return $result = ApiResponseHelper::sendResponsePaginate($realtyResources, $perPage);

            // return ApiResponseHelper::sendResponseNew($result, "Done");
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function addRealty($data)
    {
        try {
            DB::beginTransaction();

            $realty = new Realty();
            $realty->fill($data);

            // Ensure data for translatable fields is in the correct format
            foreach (['name', 'description', 'location'] as $field) {
                if (isset($data[$field]) && is_array($data[$field])) {
                    $realty->setTranslations($field, $data[$field]);
                } else {
                    // Log or handle cases where data is not provided in the correct format
                    Log::warning("Expected array for translations, received: " . gettype($data[$field] ?? 'null') . " for field: $field");
                }
            }

            $realty->save();

            if (isset($data['photos'])) {
                foreach ($data['photos'] as $photoData) {
                    $file = FileHelper::handlingImageName($photoData);
                    $photo = new Photo(['path' => $file, 'model' => 'Realty']);
                    $realty->photos()->save($photo);
                }
            }

            // Add features to the RealtyFeatures table
            if (isset($data['features'])) {
                foreach ($data['features'] as $feature) {
                    $RealtyFeatures = new RealtyFeatures();
                    if (is_array($feature)) {
                        $RealtyFeatures->setTranslations('name', $feature);
                    } else {
                        $RealtyFeatures->name = $feature;  // Assuming default locale or single string
                    }
                    $RealtyFeatures->realty_id = $realty->id;
                    $RealtyFeatures->save();
                }
            }

            // Add Important points to the RealtyImportant table
            if (isset($data['important_points'])) {
                foreach ($data['important_points'] as $important_point) {
                    $RealtyImportantPoints = new RealtyImportantPoints();
                    if (is_array($important_point)) {
                        $RealtyImportantPoints->setTranslations('name', $important_point);
                    } else {
                        $RealtyImportantPoints->name = $important_point;  // Assuming default locale or single string
                    }
                    $RealtyImportantPoints->realty_id = $realty->id;
                    $RealtyImportantPoints->save();
                }
            }

            Realty_user::create([
                'user_id' => auth('sanctum')->user()->id,
                'realty_id' => $realty->id,
                'type_user' => 'owner'
            ]);
            $realtyResources = new RealtyResource($realty);

            DB::commit();
            return ApiResponseHelper::sendResponseNew($realtyResources, "Done");
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => [$e->getMessage()],
            ], ApiResponseCodes::BAD_REQUEST);
        }
    }

    public function getRealtyById($id)
    {
        $realty = Realty::find($id);

        if (!$realty) {
            return ApiResponseHelper::sendMessageResponse('Realty not found', 404, false);
        }
        $realtyResource = new RealtyResource($realty);
        DB::commit();

        return ApiResponseHelper::sendResponseNew($realtyResource, "realty Info");
    }

    public function updateRealty($request, $id)
    {
        try {
            DB::beginTransaction();
            $realty = Realty::find($id);

            if (!$realty) {
                return ApiResponseHelper::sendMessageResponse('Realty not found', 404, false);
            }

            $data = $request->all();

            // Update basic and translatable fields
            $realty->fill($data);
            foreach (['name', 'description', 'location'] as $field) {
                if (isset($data[$field]) && is_array($data[$field])) {
                    $realty->setTranslations($field, $data[$field]);
                }
            }

            $realty->save();

            // Handling photos, optionally delete old ones if needed
            if ($request->has('photos')) {
                // Optionally delete old photos if necessary
                // $realty->photos()->delete();

                foreach ($request->photos as $file) {
                    $photoPath = FileHelper::handlingImageName($file);
                    $photo = new Photo(['path' => $photoPath, 'model' => 'Realty']);
                    $realty->photos()->save($photo);
                }
            }

            // Update or attach features with translations
            if (isset($data['features'])) {
                $realty->features()->delete(); // Clear existing features
                foreach ($data['features'] as $feature) {
                    $RealtyFeatures = new RealtyFeatures();
                    if (is_array($feature)) {
                        $RealtyFeatures->setTranslations('name', $feature);
                    } else {
                        $RealtyFeatures->name = $feature; // Handle non-translatable case or default locale
                    }
                    $RealtyFeatures->realty_id = $realty->id;
                    $RealtyFeatures->save();
                }
            }

            // Update or attach Important points with translations
            if (isset($data['important_points'])) {
                $realty->important_points()->delete();
                foreach ($data['important_points'] as $important_point) {
                    $RealtyImportantPoints = new RealtyImportantPoints();
                    if (is_array($important_point)) {
                        $RealtyImportantPoints->setTranslations('name', $important_point);
                    } else {
                        $RealtyImportantPoints->name = $important_point; // Handle non-translatable case or default locale
                    }
                    $RealtyImportantPoints->realty_id = $realty->id;
                    $RealtyImportantPoints->save();
                }
            }

            $realty->load('owner', 'photos', 'features', 'important_points');
            $realtyResource = new RealtyResource($realty);
            DB::commit();

            return ApiResponseHelper::sendResponseNew($realtyResource, "Realty updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => [$e->getMessage()],
            ], ApiResponseCodes::BAD_REQUEST);
        }
    }


    public function removeRealty($id)
    {
        try {
            $realty = Realty::find($id);

            if (!$realty) {
                return ApiResponseHelper::sendMessageResponse('Realty not found', 404, false);
            }

            $photos = $realty->photos;

            foreach ($photos as $photo) {
                Storage::delete($photo->path);
            }

            // Delete associated photos
            $realty->photos()->delete();

            // Delete associated features
            $realty->features()->delete();

            // Delete associated important_points
            $realty->important_points()->delete();

            $realty->delete();

            return ApiResponseHelper::sendMessageResponse('Realty and associated elements deleted successfully', 200, true);
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }


    /**************************** Filters ****************************

    /**
     * Fetch realties associated with a user uthuntecated.
     */
    public function getUserRealties($request)
    {
        $userId = auth('sanctum')->user()->id;
        return $this->myService->fetchRealties($request, $userId);
    }

    /**
     * Fetch realties associated with a specific user by ID.
     */
    public function getRealtiesByUser($request, $id)
    {
        return $this->myService->fetchRealties($request, $id);
    }

    /**
     * Filter realties by agent ID.
     */
    public function filterRealtiesByAgentId($request, $id)
    {
        return $this->myService->fetchRealties($request, $id);
    }

    /**
     * Filter in general.
     */
    public function Filter($request)
    {
        return $this->myService->fetchRealties($request);
    }
}
