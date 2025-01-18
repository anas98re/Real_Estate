<?php

namespace App\Actions\RatingActions;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\PermissionsHelper;
use App\Models\Rating;
use App\Repository\RatingRepository;
use Illuminate\Http\Request;

class UpdateRatingAction
{
    protected $RatingRepositry;

    public function __construct(RatingRepository $RatingRepositry)
    {
        return $this->RatingRepositry = $RatingRepositry;
    }

    public function __invoke(Request $request)
    {
        $id = $request->route('id');
        if (
            !PermissionsHelper::hasPermission('rating.update')
            || !PermissionsHelper::hasPermissionUpdateOrRemoveRealtyRating($id)
        ) {
            $service = Rating::find($id);

            if (!$service) {
                return ApiResponseHelper::sendMessageResponse('Rating not found and Forbidden', 404, false);
            }
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $this->RatingRepositry->updateRating($request, $id);
    }
}
