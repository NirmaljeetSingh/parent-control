<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Twilio\Rest\Client;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        if($er = __validation($request->all(),['phone_no' => 'required'])) return $er;
        $user = User::where('phone_no',$request->phone_no)->first();
        if(!$user)
        {
            $user = User::create($request->all());
            
            $user->setting()->create([
                'parent_key' => md5($user->id)
            ]);
        }
        $otp = rand(1000,9999);
        $user->otp = $otp;
        $user->save();
        try {
            self::sms($request->phone_no,$otp);
        } catch (\Throwable $th) {
            return error_response([],'SMS not send due to '.$th->getMessage());
        }
        $token = $user->createToken($user->phone_no)->plainTextToken;
        return success_response(['user' => User::with('setting')->find($user->id),'token' => $token],'User registered.');
    }
    public function verify_no(Request $request)
    {
        if($er = __validation($request->all(),['otp' => 'required'])) return $er;
        if(auth()->user()->otp != $request->otp) return error_response([],'Invalid OTP!',400);
        auth()->user()->update(['phone_no_verified_at' => now()]);
        return success_response(auth()->user(),'OTP verified.');
    }
    
    public function profileImageUpload(Request $request)
    {
        if($er = __validation($request->all(),['image' => 'required'])) return $er;
        if(!$request->file('image')) return error_response([],'Image not found',404);
        auth()->user()->update(['image' => __upload($request->image)]);
        return success_response(User::find(auth()->user()->id),'Profile image uploaded.');
    }

    private static function sms($to,$otp)
    {
        $sid = env("TWILIO_ACCOUNT_SID");
        $token = env("TWILIO_AUTH_TOKEN");
        $from = env("TWILIO_PHONE_NO");
        $client = new Client($sid, $token);
        return $client->messages->create(
            // the number you'd like to send the message to
            $to,
            [
                // A Twilio phone number you purchased at twilio.com/console
                'from' => $from,
                // the body of the text message you'd like to send
                'body' => 'OTP for login into parent-control '.$otp
            ]
        );
    }

    public function profileUpdate(Request $request)
    {
        $params = [];
        if($request->name) $params['name'] = $request->name;
        if($request->location) $params['location'] = $request->location;
        if($request->fav_location) $params['fav_location'] = $request->fav_location;
        if($request->bio) $params['bio'] = $request->bio;
        // if($request->email) $params['email'] = $request->email;
        auth()->user()->update($params);
        return success_response(User::find(auth()->user()->id),'Profile updated.');
    }
    public function profile()
    {
        return success_response(User::with('setting')->find(auth()->user()->id),'Profile data.');
    }

}
