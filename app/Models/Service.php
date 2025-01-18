<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['service_name'];


    protected $fillable = [
        'type_id',
        'service_name',
    ];

    public function type()
    {
        return $this->belongsTo(Service_type::class, 'type_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'service_users');
    }

    public function User()
    {
        return $this->hasManyThrough(
            User::class,
            Service_user::class,
            'service_id',
            'id',
            'id',
            'user_id'
        );
    }
}
