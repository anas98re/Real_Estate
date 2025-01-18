<?php

namespace App\Services;

use App\ApiHelper\ApiResponseHelper;
use App\Http\Resources\RealtyResource;
use App\Models\Rating;
use App\Models\Realty;
use App\Models\Realty_user;

class RealtyService
{
    public function extractFilters($request)
    {
        return $request->only([
            'min_price',
            'max_price',
            'location',
            'country',
            'cities',
            'num_rooms',
            'num_bathrooms',
            'min_space_sqft',
            'max_space_sqft',
            'year_built',
            'parking_spaces',
            'min_lot_size',
            'max_lot_size',
            'keyword',
        ]);
    }

    /**
     * Generic method to fetch realties with filtering, sorting and pagination.
     */
    public function fetchRealties($request,  $userId = null)
    {
        // Default values for pagination and sorting
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'asc');

        // Validate sortBy against allowed columns
        $validSortColumns = [
            'price',
            'space_sqft',
            'year_built',
            'num_rooms',
            'num_bathrooms',
            'parking_spaces',
            'lot_size',
            'id',
            'created_at',
            'average_rating'
        ];

        if (!in_array($sortBy, $validSortColumns)) {
            return ApiResponseHelper::sendMessageResponse('Invalid sort column', 400, false);
        }

        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            return ApiResponseHelper::sendMessageResponse('Invalid sort order', 400, false);
        }

        $filters = $this->extractFilters($request);

        $query = Realty::query();

        if ($userId) {
            $realtyIds = Realty_user::where('user_id', $userId)->pluck('realty_id');
            $query->whereIn('id', $realtyIds);
        }

        $realties = $query->filter($filters)
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);

        $realtyResources = RealtyResource::collection($realties);

        return ApiResponseHelper::sendResponsePaginate($realtyResources, $perPage);
    }
}
