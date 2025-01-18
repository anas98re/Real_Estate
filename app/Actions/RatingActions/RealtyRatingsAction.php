<?php

namespace App\Actions\RatingActions;

use App\ApiHelper\PermissionsHelper;
use App\Repository\RatingRepository;
use Illuminate\Http\Request;

class RealtyRatingsAction
{
    protected $RatingRepositry;

    public function __construct(RatingRepository $RatingRepositry)
    {
        return $this->RatingRepositry = $RatingRepositry;
    }

    public function __invoke(Request $request)
    {
        $id = $request->route('id');
        if (!PermissionsHelper::hasPermission('rating.show')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->RatingRepositry->RealtyRatings($id);
    }
}
