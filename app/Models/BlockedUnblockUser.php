<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedUnblockUser extends Model
{
    use HasFactory;
    protected $fillable = ['blocked_by_user_id','blocked_user_id','reason'];

    /**
     * *******************************
     * @ type
     *  0 => blocked by first_user_id
     *  1 => blocked by second_user_id
     *  2 => blocked by both
     * 
     */
    public function user()
    {
        return $this->belongsTo(User::class,'blocked_by_user_id','id');
    }
    public function blockedUser()
    {
        return $this->belongsTo(User::class,'blocked_user_id','id');
    }
}
