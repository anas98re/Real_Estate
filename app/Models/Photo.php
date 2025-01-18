<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'model_id',
        'model',
        'model_type'
    ];

    public function imageable()
    {
        return $this->morphTo();
    }
}
