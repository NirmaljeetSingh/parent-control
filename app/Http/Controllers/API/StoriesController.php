<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Story,FriendRequest,StoryView,User,BlockedUnblockUser,Notification};
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
        $blocked_users = BlockedUnblockUser::where('blocked_by_user_id',$user_id)->pluck('blocked_user_id')->toArray();
        
        $friends =  FriendRequest::friendsOnly($user_id)
        ->where('friend_requests.request','accepted')->where('friend_requests.type','friend')->get()
        ->each(function($qr) use($user_id,$blocked_users){
            $tmp_user_id = ($qr->user_id == $user_id) ? $qr->friend_user_id : $qr->user_id;
            $qr->_id = (in_array($tmp_user_id,$blocked_users)) ? 0 : $tmp_user_id;
        })->pluck('_id');
        
        $allStories = User::with('story')->whereHas('story')->whereIn('id',$friends)->get();
        $myStories = User::with('story')->whereHas('story')->where('id',$user_id)->get();
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
        Notification::create([
            'reference_id' => $story->id,
            'user_id' => Auth::user()->id,
            'notification_type' => 'story'
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
    public function destroy($storyId){
        Story::find($storyId)?->delete();
        return success_response([],'Data delete successfully');
    }
}
