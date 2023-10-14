<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','friend_user_id','request','type','parent_id'];

    /**
     * *******************************
     * @ request
     *  pending
     *  accepted
     *  reject
     *  blocked 
     * *******************************
     * @ type
     * parent
     * friend
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function friend_user()
    {
        return $this->belongsTo(User::class,'friend_user_id');
    }
    public function scopeFriendsOnly($query,$user_id)
    {
        return $query->selectRaw("friend_requests.*")->leftJoin("friend_requests as fq","fq.id","friend_requests.parent_id")
        ->where(function($qry) use($user_id){
            $qry->where(function($sub_qry) use($user_id){
                $sub_qry->where('friend_requests.user_id',$user_id)->orWhere('friend_requests.friend_user_id',$user_id);
            })
            ->whereRaw("(fq.request IS NULL OR fq.request = 'accepted')");
        });
    }
    public function scopeParentGet($query,$user_id){
        // return $query->where(function($qry) use($user_id){
        //     $qry->where('friend_requests.user_id',$user_id)->orWhere('friend_requests.friend_user_id',$user_id);
        // })
        // ->whereType('parent');
        return $query->where('user_id',$user_id)->whereType('parent')->whereRequest('accepted');
    }
}
