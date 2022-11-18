<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Story,FriendRequest};
use Auth;

class StoriesController extends Controller
{
    public function index(Request $request)
    {
        $user_id = auth()->user()->id;
        $friends =  FriendRequest::where(function($rr) use($user_id){
            $rr->where('user_id',$user_id)->orWhere('friend_user_id',$user_id);
        })->where('request','accepted')->get()->each(function($qr) use($user_id){
            $qr->_id = ($qr->user_id == $user_id) ? $qr->friend_user_id : $qr->user_id;
        })->pluck('_id');
        return success_response(Story::whereIn('user_id',$friends)->get(),'Data fetch successfully');
    }
    public function myStories(Request $request)
    {
        return success_response(Story::where('user_id',Auth::user()->id)->get(),'Data fetch successfully');
    }
    public function store(Request $request)
    {
        $rules = [
            'image' => 'required|mimes:jpg,jpeg,png',
        ];
        if($er = __validation($request->all(),$rules)) return $er;
        $story = Story::create([
            'image' => __upload($request->image,'stories'),
            'description' => $request->description ?? '',
            'user_id' => Auth::user()->id
        ]);
        return success_response(Story::find($story->id),'Data saved successfully');
    }
}
