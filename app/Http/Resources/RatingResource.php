<?php

namespace App\Http\Resources;

use App\Models\Realty_user;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'description' => $this->description,
            'rating_user_name' => $this->user?->name,
            'realty_name' => $this->realty?->name,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s')
        ];
    }
}
