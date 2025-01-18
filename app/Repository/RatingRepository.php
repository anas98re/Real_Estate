<?php

namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use App\ApiHelper\ApiResponseHelper;
use App\Http\Resources\RatingResource;
use App\Interfaces\RatingInterface;
use App\Models\rating;
use App\Models\Realty;
use Illuminate\Support\Facades\DB;

class RatingRepository extends BaseRepositoryImplementation implements RatingInterface
{
    public function model()
    {
        return Rating::class;
    }

    public function addRating($request, $realtyId)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['realty_id'] = $realtyId;
            $data['user_id'] = auth('sanctum')->user()->id;

            $Realty = Realty::where('id', $realtyId)->first();

            if (!$Realty) {
                return ApiResponseHelper::sendMessageResponse('Realty not found', 404, false);
            }

            $rating = $this->create($data);

            $averageRating = Rating::where('realty_id', $realtyId)->avg('rating');

            $Realty->update(['average_rating' => $averageRating]);

            DB::commit();
            return ApiResponseHelper::sendResponseNew($rating, "Rating added successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function updateRating($request, $id)
    {
        try {
            $rating = Rating::find($id);

            if (!$rating) {
                return ApiResponseHelper::sendMessageResponse('Rating not found', 404, false);
            }

            $data = $request->all();
            $rating->update($data);

            return ApiResponseHelper::sendResponseNew($rating, "Rating updated successfully");
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function deleteRating($id)
    {
        try {
            $rating = Rating::find($id);

            if (!$rating) {
                return ApiResponseHelper::sendMessageResponse('Rating not found', 404, false);
            }

            $rating->delete();

            return ApiResponseHelper::sendMessageResponse('Rating deleted successfully', 200, true);
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function RealtyRatings($realtyId)
    {
        try {
            $ratings = Rating::where('realty_id', $realtyId)->get();

            if ($ratings->isEmpty()) {
                return ApiResponseHelper::sendMessageResponse('No ratings found for this realty', 404, false);
            }

            $realtyResources = RatingResource::collection($ratings);
            return ApiResponseHelper::sendResponseNew($realtyResources, "Ratings for realty retrieved successfully");
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function getPublicRealtyRating($id)
    {
        try {
            $averageRating = Rating::where('realty_id', $id)->avg('rating');

            return ApiResponseHelper::sendResponseNew(['average_rating' => $averageRating], "Average rating for the realty retrieved successfully");
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }
}
