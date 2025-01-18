<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agent_id',
        'realty_id',
        'appointment_type_id',
        'appointment_date',
        'status'
    ];

    public function appointmentType()
    {
        return $this->belongsTo(Appointment_type::class, 'appointment_type_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function realty()
    {
        return $this->belongsTo(Realty::class, 'realty_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
