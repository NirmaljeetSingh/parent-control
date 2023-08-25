<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Notification,BlockedUnblockUser,FriendRequest};

class NotificationController extends Controller
{
    public function index(Request $request){
        $user_id = auth()->user()->id;
        if($request->type && $request->type == 'parent')
        {
            // $stories = User::with('story')->whereHas('story')->where('id',$request->user_id)->get();
            // return success_response($stories,'Data fetch successfully');
            $user_id = $request->user_id;
        }
        $blocked_users = BlockedUnblockUser::where('blocked_by_user_id',$user_id)->pluck('blocked_user_id')->toArray();
        
        $friends =  FriendRequest::where(function($rr) use($user_id){
            $rr->where('user_id',$user_id)->orWhere('friend_user_id',$user_id);
        })->where('request','accepted')->whereType('friend')->get()
        ->each(function($qr) use($user_id,$blocked_users){
            $tmp_user_id = ($qr->user_id == $user_id) ? $qr->friend_user_id : $qr->user_id;
            $qr->_id = (in_array($tmp_user_id,$blocked_users)) ? 0 : $tmp_user_id;
        })->pluck('_id');
        $notification = Notification::whereIn('id',$friends)->get();

        return success_response($notification,'Notification fetch.');
    }
    public function seen(Request $request){
        if($er = __validation($request->all(),['ids' => 'required'])) return $er;
        $ids = $request->ids;
        $notification = Notification::whereIn('id',$ids)->update(['status' => 'seen']);
        return success_response([],'Notification seen.');
    }
}
