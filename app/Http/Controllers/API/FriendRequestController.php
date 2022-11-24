<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{FriendRequest,User};

class FriendRequestController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'friend_user_id' => ['required','exists:users,id'],
        ];
        if($er = __validation($request->all(),$rules)) return $er;

        $getFriend = FriendRequest::where([['user_id',auth()->user()->id],['friend_user_id',$request->friend_user_id]])->orWhere([['friend_user_id',auth()->user()->id],['user_id',$request->friend_user_id]])->first();
        if(auth()->user()->id == $request->friend_user_id) return error_response([],'Cannot make your self as a friend.',400);
        if(!$getFriend) $getFriend = FriendRequest::create(['user_id' => auth()->user()->id,'friend_user_id' => $request->friend_user_id]);
        return success_response($getFriend,'Request sent successfully.');
    }
    public function index(Request $request)
    {
        $getFriend = FriendRequest::where('user_id',auth()->user()->id)->orWhere('friend_user_id',auth()->user()->id)->get();
        return success_response($getFriend,'Friend list fetch successfully.');
    }
    public function friendRequests(Request $request)
    {
        $getFriend = FriendRequest::where('friend_user_id',auth()->user()->id)->get();
        return success_response($getFriend,'Pending request list fetch successfully.');
    }
    public function approveRequest(Request $request)
    {
        $rules = [
            'friend_request_id' => ['required','exists:friend_requests,id'],
        ];
        if($er = __validation($request->all(),$rules)) return $er;
        $getFriend = FriendRequest::find($request->friend_request_id);
        $getFriend->update(['request' => 'accepted']);
        return success_response($getFriend,'Friend request accepted.');
    }
    public function rejectRequest(Request $request)
    {
        $rules = [
            'friend_request_id' => ['required','exists:friend_requests,id'],
        ];
        if($er = __validation($request->all(),$rules)) return $er;
        $getFriend = FriendRequest::find($request->friend_request_id);
        $getFriend->update(['request' => 'reject']);
        return success_response($getFriend,'Friend request rejected.');
    }
    public function blockFriend(Request $request)
    {
        $rules = [
            'friend_request_id' => ['required','exists:friend_requests,id'],
        ];
        if($er = __validation($request->all(),$rules)) return $er;
        $getFriend = FriendRequest::find($request->friend_request_id);
        $getFriend->update(['request' => 'blocked']);
        return success_response($getFriend,'Friend request blocked.');
    }
    public function findFriend(Request $request)
    {
        // $rules = [
        //     'search' => ['required'],
        // ];
        // if($er = __validation($request->all(),$rules)) return $er;
        $search = $request->search ?? '';
        $user_id = auth()->user()->id;
        $friends =  FriendRequest::where(function($rr) use($user_id){
            $rr->where('user_id',$user_id)->orWhere('friend_user_id',$user_id);
        })->where('request','accepted')->get()->each(function($qr) use($user_id){
            $qr->_id = ($qr->user_id == $user_id) ? $qr->friend_user_id : $qr->user_id;
        })->pluck('_id')->toArray();
        $findFriend = User::when($search,function($qr) use($search){
                $qr->where('phone_no','LIKE',"%{$search}%");
            })
            ->whereNot('id',auth()->user()->id)->get()->each(function($collection) use($friends){
            $collection->is_friend = in_array($collection->id,$friends);
        });
        return success_response($findFriend,'Friend request blocked.');
    }
}
