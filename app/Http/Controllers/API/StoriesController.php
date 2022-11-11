<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Story;
use Auth;

class StoriesController extends Controller
{
    public function index(Request $request)
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
