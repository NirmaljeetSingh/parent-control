<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone_no',
        'email',
        'password',
        'otp',
        'image',
        'phone_no_verified_at',
        'location',
        'fav_location',
        'bio',
        'parent_key',
        'account_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_no_verified_at' => 'datetime',
    ];

    // protected $appends = ['full_name'];
    // protected function fullName(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => $this->first_name.' '.$this->last_name,
    //     );
    // }
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value) ? asset($value) : $value,
        );
    }
    protected function location(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value) ? $value : "",
        );
    }
    protected function favLocation(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value) ? $value : "",
        );
    }
    protected function bio(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value) ? $value : "",
        );
    }
    public function setting()
    {
        return $this->hasOne(Setting::class);
    }
}
