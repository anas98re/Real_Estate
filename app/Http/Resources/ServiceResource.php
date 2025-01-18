<?php

namespace App\Http\Resources;

use App\Models\Realty_user;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'service_name' => $this->service_name,
            'type_service_name' => $this->type?->type_service_name,
            'userName' => $this->User->first()?->name,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s')
        ];
    }
}
