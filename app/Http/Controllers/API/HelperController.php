<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use App\Mail\HelpContentMail;


class HelperController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'text' => ['required','min:10'],
        ];
        if($er = __validation($request->all(),$rules)) return $er;
        try {
            // Mail::to('nirmaljeets20@gmail.com')->send(new HelpContentMail($request->text));
            Mail::to('sagar.verma33@gmail.com')->send(new HelpContentMail($request->text));
        } catch (\Throwable $th) {
            // return $th->getMessage();
            return error_response([],$th->getMessage());
        }
        return success_response([],'Help mail send successfully.');

    }
}
