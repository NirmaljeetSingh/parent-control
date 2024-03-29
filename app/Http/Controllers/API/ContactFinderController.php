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
        $data = json_decode($request->data,true);
        $app_exists = [];
        $app_not_exists = [];
        $phone_numbers = [];

        foreach($data as $val){
            $hiphen_replace = str_replace("-","",$val['number']);
            $white_space = str_replace(" ","",$hiphen_replace);
            $braces = str_replace(")","",str_replace("(","",$white_space));
            $phone = str_replace('+','',$braces);
            if(!in_array($phone,$phone_numbers)){
                $phone_numbers[] = $phone;
                $temp_obj = ['firstName' => $val['firstName'] ?? '','number' => $val['number'] ?? '','lastName' => $val['lastName'] ?? '','user' => []];
                try {
                    //code...
                    if($user = User::whereRaw("CONCAT(code,'',phone_no) LIKE '%{$phone}'")->first())
                    {
                        $temp_obj['user'] = $user;
                        $app_exists[] = $temp_obj;
                    }
                    else
                    {
                        $app_not_exists[] = $temp_obj;
                    }
                } catch (\Throwable $th) {
                    // return  $th->getMessage();
                    // return User::whereRaw("CONCAT(code,'',phone_no) LIKE %{$phone}")->toSql();
                }
            }
        }
        return success_response(['has_app' => $app_exists,'not_has_app' => $app_not_exists],'List fetch successfully.');
    }
    // public function jsonUnique($arr){
    //     $temp = [];
    //     $rs = [];
    //     foreach($arr as $val){
    //         if(!in_array($val->))
    //     }
    // }
    public function contactJson(Request $request)
    {
        $rules = [
            'data' => ['required'],
        ];
        if($er = __validation($request->all(),$rules)) return $er;
        $app_exists = [];
        $app_not_exists = [];
        $phone_numbers = [];

        foreach($request->data as $val){
            $hiphen_replace = str_replace("-","",$val['number']);
            $white_space = str_replace(" ","",$hiphen_replace);
            $braces = str_replace(")","",str_replace("(","",$white_space));
            $phone = str_replace('+','',$braces);
            if(!in_array($phone,$phone_numbers)){
                $phone_numbers[] = $phone;
                $temp_obj = ['firstName' => $val['firstName'] ?? '','number' => $val['number'] ?? '','lastName' => $val['lastName'] ?? '','user' => []];
                try {
                    //code...
                    if($user = User::whereRaw("CONCAT(code,'',phone_no) LIKE '%{$phone}'")->first())
                    {
                        $temp_obj['user'] = $user;
                        $app_exists[] = $temp_obj;
                    }
                    else
                    {
                        $app_not_exists[] = $temp_obj;
                    }
                } catch (\Throwable $th) {
                    // return  $th->getMessage();
                    // return User::whereRaw("CONCAT(code,'',phone_no) LIKE %{$phone}")->toSql();
                }
            }
        }
        return success_response(['has_app' => $app_exists,'not_has_app' => $app_not_exists],'List fetch successfully.');
    }
}
