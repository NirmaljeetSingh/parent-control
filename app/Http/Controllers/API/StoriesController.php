<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Story,FriendRequest,StoryView,User};
use Auth;
use App\Events\ActionEvent;
use Carbon\Carbon;

class StoriesController extends Controller
{
    public function index(Request $request)
    {
        $user_id = auth()->user()->id;
        if($request->type && $request->type == 'parent')
        {
            // $stories = User::with('story')->whereHas('story')->where('id',$request->user_id)->get();
            // return success_response($stories,'Data fetch successfully');
            $user_id = $request->user_id;
        }
        
        $friends =  FriendRequest::where(function($rr) use($user_id){
            $rr->where('user_id',$user_id)->orWhere('friend_user_id',$user_id);
        })->where('request','accepted')->whereType('friend')->get()
        ->each(function($qr) use($user_id){
            $qr->_id = ($qr->user_id == $user_id) ? $qr->friend_user_id : $qr->user_id;
        })->pluck('_id');
        // return $friends;
        $friendsParent =  FriendRequest::whereTypeAndRequest('parent','accepted')->where('friend_user_id',auth()->user()->id)->pluck('id')->toArray();
        for ($i=0; $i < count($friendsParent); $i++) { 
            $friends[count($friends)] = $friendsParent[$i];
        }
        // return $friends;
        // $my_stories = Story::where('user_id',Auth::user()->id)->where('created_at','>',$date)->get();
        // $all_stories = Story::with('user')->whereIn('user_id',$friends)->where('created_at','>',$date)->get();
        // $friends[count($friends)] = $user_id;
        $myStories = User::with('story')->whereHas('story')->whereIn('id',$friends)->get();
        $allStories = User::with('story')->whereHas('story')->where('id',$user_id)->get();
        return success_response(['myStories' => $myStories,'allStories' => $allStories],'Data fetch successfully');
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
    public function storySeen(Request $request)
    {
        // $actionId = "score_update";
        // $actionData = array("team1_score" => 46);
        // event(new ActionEvent($actionId, $actionData));
        // return 'event';
        $rules = [
            'story_id' => 'required|exists:stories,id',
        ];
        if($er = __validation($request->all(),$rules)) return $er;

        $data = StoryView::updateOrCreate(['story_id' => $request->story_id,'user_id' => auth()->user()->id]);
        return success_response($data,'Data fetch successfully');
    }
}
