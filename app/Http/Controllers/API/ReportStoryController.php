<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ReportStory;
use Illuminate\Http\Request;

class ReportStoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reportedUsers = ReportStory::with('story','user')->where('reported_by_user_id',auth()->user()->id)->get();
        return success_response($reportedUsers,'Reported stories.');
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
        if($er = __validation($request->all(),['story_id' => ['required','exists:stories,id']])) return $er;
        $report = ReportStory::create([
            'story_id' => $request->story_id,
            'reported_by_user_id' => auth()->user()->id,
            'reason' => $request->reason ?? '',
        ]);
        $reportedUsers = ReportStory::with('story','user')->find($report->id);
        return success_response($reportedUsers,'Report has been submitted.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReportStory  $reportStory
     * @return \Illuminate\Http\Response
     */
    public function show(ReportStory $reportStory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReportStory  $reportStory
     * @return \Illuminate\Http\Response
     */
    public function edit(ReportStory $reportStory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReportStory  $reportStory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReportStory $reportStory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReportStory  $reportStory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReportStory $reportStory)
    {
        //
    }
}
