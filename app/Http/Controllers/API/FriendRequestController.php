<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FriendRequest;

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
}
