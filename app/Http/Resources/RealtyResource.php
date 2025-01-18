<?php

namespace App\Http\Resources;

use App\Models\Realty_user;
use App\Models\RealtyFeatures;
use App\Models\RealtyImportantPoints;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RealtyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'average_rating' => $this->average_rating,
            'description' => $this->description,
            'price' => $this->price,
            'photos' => PhotoResource::collection($this->photos),
            'location' => $this->location,
            'country' => $this->country,
            'city' => $this->city,
            'num_rooms' => $this->num_rooms,
            'num_bathrooms' => $this->num_bathrooms,
            'space_sqft' => $this->space_sqft,
            'year_built' => $this->year_built,
            'parking_spaces' => $this->parking_spaces,
            'lot_size' => $this->lot_size,
            'year_built' => $this->year_built,
            'features' => $this->features() ? FeaturesRealtyResource::collection($this->features()) : [],
            'imortantPoints' => $this->imortantPoints() ? ImortantPointsRealtyResource::collection($this->imortantPoints()) : [],
            // 'features' => $this->featuresResponse(),

            'status' => $this->status,
            'owner' => $this->owner?->first()?->name,
            'userId' => $this->owner?->first()?->id,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s')
        ];
    }

    private function features()
    {
        return RealtyFeatures::where('realty_id', $this->id)->get();
    }

    private function imortantPoints()
    {
        return RealtyImportantPoints::where('realty_id', $this->id)->get();
    }

    private function featuresResponse()
    {
        $data = [];

        $features = $this->features();

        if ($features) {
            foreach ($features as $index => $feature) {
                $data[] = $feature->name;
            }
        }

        return $data;
    }
}
