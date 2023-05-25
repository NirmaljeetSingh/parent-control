<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;


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
        'account_type',
        'code'
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

    protected $appends = ['is_request','is_blocked'];
    protected function isRequest(): Attribute
    {
        $requested = 0;
        try {
            if($this->id != auth()->user()->id)
            {
                $get = FriendRequest::where([['friend_user_id',auth()->user()->id],['user_id',$this->id]])
                            ->orWhere([['friend_user_id',$this->id],['user_id',auth()->user()->id]])->first();
                if($get)
                {
                    if($get->request == 'pending') $requested = 1;
                    elseif($get->request == 'accepted') $requested = 2;
                    elseif($get->request == 'reject') $requested = 3;
                    elseif($get->request == 'blocked') $requested = 4;
                }
            }
        } catch (\Throwable $th) {
            // throw $th;
            // $requested = 9;
        }
        return Attribute::make(
            get: fn ($value) => $requested,
        );
    }
    protected function isBlocked(): Attribute
    {
        $isBlocked = 0;
        try {
            $isBlocked = BlockedUnblockUser::where([
                ['blocked_user_id',$this->id],['blocked_by_user_id',auth()->user()->id]
            ])->count();
        } catch (\Throwable $th) {
        }
        return Attribute::make(
            get: fn ($value) => $isBlocked ?? 0,
        );
    }
    // protected function phoneNo(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => ($value) ? '+'.$value : $value,
    //     );
    // }
    protected function code(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value) ? '+'.$value : $value,
        );
    }
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
    public function story()
    {
        $date = Carbon::now()->subHours(24)->toDateTimeString();
        return $this->hasMany(Story::class)->where('created_at','>',$date);
    }
}
