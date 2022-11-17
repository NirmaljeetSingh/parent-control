<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'to' => ['required','exists:users,id'],
            'message' => 'required',
        ];
        if($er = __validation($request->all(),$rules)) return $er;
    }
}
