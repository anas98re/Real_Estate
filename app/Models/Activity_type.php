<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity_type extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_activity_name',
        'description',
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class, 'type_id');
    }
}
