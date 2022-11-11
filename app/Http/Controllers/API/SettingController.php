<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Auth;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        return success_response(Setting::where('user_id',Auth::user()->id)->first(),'Data fetched.');
    }
    public function store(Request $request)
    {
        $rules = [
            'story_presentation' => 'required',
            'chat_notification' => 'required',
            'post_notification' => 'required',
        ];
        if($er = __validation($request->all(),$rules)) return $er;

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        // return $data;
        $updated_setting = Setting::where('user_id',Auth::user()->id)->update($data);
        return success_response(Setting::where('user_id',Auth::user()->id)->first(),'Data updated.');
    }
}
