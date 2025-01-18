<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'realty_id',
        'rating',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function realty()
    {
        return $this->belongsTo(Realty::class,'realty_id');
    }
}
