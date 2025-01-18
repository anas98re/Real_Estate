<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;


    protected $fillable = [
        'name',
        'username',
        'first_name',
        'second_name',
        'password',
        'gender',
        'email',
        'email_verified_at',
        'verification_expired_at',
        'OTP_verified_at',
        'OTP_expired_at',
        'phone',
        'facebook_account',
        'instagram_account',
        'tiktok_account',
        'twitter_account',
        'birthday',
        'country_id',
        'city',
        'last_login',
        'active',
        'verified',
        'verification_code',
        'created_by',
        'updated_by',
        'deleted_by',
        'referred_by',
        'fcm_token',
        'phone_code',
        'cover',
        'badge_id',
    ];

    protected $dates = ['deleted_at'];

    // Get the chat rooms created by the user.
    public function createdChatRooms()
    {
        return $this->hasMany(ChatRoom::class, 'created_by');
    }

    // Get the chat rooms the user is a member of.
    public function chatRooms()
    {
        return $this->belongsToMany(ChatRoom::class, 'chat_room_users')
            ->withPivot('is_have_permission')
            ->withTimestamps();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function verified()
    {
        $this->email_verified_at = now(); // Assuming you are using Laravel's built-in email verification feature

        // You might want to save the user after marking them as verified
        $this->save();
    }

    public function verified_otp()
    {
        // Perform actions to mark the OTP as verified for the user
        // For example, you can update a column in the database to indicate that the OTP has been verified
        $this->verification_code = true;
        $this->save();
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'model');
    }
}
