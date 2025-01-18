<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class RealtyFeatures extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'realty_id',
        'name',
        'description'
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];

    public function realty()
    {
        return $this->belongsTo(Realty::class);
    }
}
