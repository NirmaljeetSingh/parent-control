<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ReportUser;
use Illuminate\Http\Request;

class ReportUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reportedUsers = ReportUser::with('reported','user')->where('reported_by_user_id',auth()->user()->id)->get();
        return success_response($reportedUsers,'Reported users.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($er = __validation($request->all(),['user_id' => ['required','exists:users,id']])) return $er;
        $report = ReportUser::create([
            'reported_user_id' => $request->user_id,
            'reported_by_user_id' => auth()->user()->id,
            'reason' => $request->reason ?? '',
        ]);
        $reportedUsers = ReportUser::with('reported','user')->find($report->id);
        return success_response($reportedUsers,'Report has been submitted.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReportUser  $reportUser
     * @return \Illuminate\Http\Response
     */
    public function show(ReportUser $reportUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReportUser  $reportUser
     * @return \Illuminate\Http\Response
     */
    public function edit(ReportUser $reportUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReportUser  $reportUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReportUser $reportUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReportUser  $reportUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReportUser $reportUser)
    {
        //
    }
}
