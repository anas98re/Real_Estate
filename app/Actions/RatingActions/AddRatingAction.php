<?php

namespace App\Actions\RatingActions;

use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\StoreRatingRequest;
use App\Repository\RatingRepository;
use Illuminate\Http\Request;

class AddRatingAction
{
    protected $RatingRepositry;

    public function __construct(RatingRepository $RatingRepositry)
    {
        return $this->RatingRepositry = $RatingRepositry;
    }

    public function __invoke(StoreRatingRequest $request)
    {
        $id = $request->route('id');
        if (!PermissionsHelper::hasPermission('rating.add')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->RatingRepositry->addRating($request, $id);
    }
}
