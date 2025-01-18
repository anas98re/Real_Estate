<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_type extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'type_service_name',
        'description',
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'type_id');
    }
}
