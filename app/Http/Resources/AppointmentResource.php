<?php

namespace App\Http\Resources;

use App\Models\Realty_user;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'appointment_date' => $this->appointment_date,
            'user_name' => $this->user?->name,
            'appointment_type' => $this->appointmentType?->name,
            'realty_name' => $this->realty?->name,
            'user_name' => $this->user?->name,
            'agent_name' => $this->agent?->name,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s')
        ];
    }
}
