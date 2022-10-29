<?php
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

function __validation($params,$rules,$messages = [])
{
    $validation = validator::make($params,$rules,$messages);
    if($validation->fails()) return error_response([],$validation->errors()->first());
    return [];
}

function success_response($data,$message = '')
{
    return response()->json(['status' => true,'message' => $message,'data' => $data],200);
}
function error_response($data,$message = '',$status = 500)
{
    return response()->json(['status' => false,'message' => $message,'data' => $data],$status);
}
function __upload($file,$folder = 'profile',$name = '')
{
    if($name) return Storage::putFileAs($folder,new File($file),$name);
    return Storage::putFile($folder,new File($file));
}

?>