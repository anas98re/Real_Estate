<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Activity extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['activity_name'];

    protected $fillable = [
        'type_id',
        'activity_name',
    ];

    public function type()
    {
        return $this->belongsTo(Activity_type::class, 'type_id');
    }

    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'activity_users');
    // }

    public function User()
    {
        return $this->hasManyThrough(
            User::class,
            Activity_user::class,
            'activity_id',
            'id',
            'id',
            'user_id'
        );
    }
}
