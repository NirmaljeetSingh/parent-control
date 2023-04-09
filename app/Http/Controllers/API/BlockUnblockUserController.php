<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlockedUnblockUser;
use Illuminate\Http\Request;

class BlockUnblockUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blocked_users = BlockedUnblockUser::with('blockedUser')->where('blocked_by_user_id',auth()->user()->id)->get();
        return success_response($blocked_users,'Blocked users.');
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
        $login_user_id = auth()->user()->id;
        $user_id = $request->user_id;
        $old_get = BlockedUnblockUser::where([
                ['blocked_user_id',$user_id],['blocked_by_user_id',$login_user_id]
            ])->first();
            // return empty($old_get);
        if(!$old_get){
            $user = BlockedUnblockUser::create([
                'blocked_by_user_id' => $login_user_id,
                'blocked_user_id' => $user_id,
                'reason' => $request->reason ?? ''
            ]);
            return success_response([],'User has been blocked.');
        }
        $old_get->delete();
        return success_response([],'User has been unblocked.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BlockedUnblockUser  $blockedUnblockUser
     * @return \Illuminate\Http\Response
     */
    public function show(BlockedUnblockUser $blockedUnblockUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BlockedUnblockUser  $blockedUnblockUser
     * @return \Illuminate\Http\Response
     */
    public function edit(BlockedUnblockUser $blockedUnblockUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BlockedUnblockUser  $blockedUnblockUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlockedUnblockUser $blockedUnblockUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BlockedUnblockUser  $blockedUnblockUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlockedUnblockUser $blockedUnblockUser)
    {
        //
    }
}
