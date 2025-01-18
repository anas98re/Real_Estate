<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realty_user extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_user',
        'user_id',
        'realty_id',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function realty()
    {
        return $this->belongsTo(Realty::class);
    }
}
