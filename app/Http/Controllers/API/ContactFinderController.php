<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;

class ContactFinderController extends Controller
{
    public function contact(Request $request)
    {
        $rules = [
            'data' => ['required'],
        ];
        if($er = __validation($request->all(),$rules)) return $er;
        $app_exists = [];
        $app_not_exists = [];
        foreach($request->data as $val){
            $hiphen_replace = str_replace("-","",$val['number']);
            $white_space = str_replace(" ","",$hiphen_replace);
            $braces = str_replace(")","",str_replace("(","",$white_space));
            $phone = str_replace('+','',$braces);
            $phone_numbers[] = $phone;
            $temp_obj = ['firstName' => $val['firstName'] ?? '','number' => $val['number'] ?? '','lastName' => $val['lastName'] ?? ''];
            if(User::where('phone_no', 'LIKE',"%{$phone}%")->exists())
            {
                $app_exists[] = $temp_obj;
            }
            else
            {
                $app_not_exists[] = $temp_obj;
            }
        }
        return success_response(['has_app' => $app_exists,'not_has_app' => $app_not_exists],'List fetch successfully.');
    }
}
