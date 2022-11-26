<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','friend_user_id','request'];

    /**
     * *******************************
     * @ request
     *  pending
     *  accepted
     *  reject
     *  blocked 
     * *******************************
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function friend_user()
    {
        return $this->belongsTo(User::class,'friend_user_id');
    }
}
