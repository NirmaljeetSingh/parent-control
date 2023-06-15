<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{
            AuthenticationController,
            SettingController,
            StoriesController,
            FriendRequestController,
            HelperController,
            ContactFinderController,
            ReportUserController,
            ReportStoryController,
            BlockUnblockUserController
        };

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login',[AuthenticationController::class,'login']);
Route::middleware('auth:sanctum')->group(function(){
    Route::post('verify_no',[AuthenticationController::class,'verify_no']);
    Route::post('profile-image',[AuthenticationController::class,'profileImageUpload']);
    Route::post('profile',[AuthenticationController::class,'profileUpdate']);
    Route::get('profile',[AuthenticationController::class,'profile']);

    Route::delete('profile',[AuthenticationController::class,'profileDelete']);

    Route::resource('setting',SettingController::class);
    
    Route::resource('story',StoriesController::class);
    Route::get('my-stories',[StoriesController::class,'myStories']);
    Route::post('story-seen',[StoriesController::class,'storySeen']);

    Route::resource('friend-request',FriendRequestController::class);
    Route::get('pending-friend-request',[FriendRequestController::class,'friendRequests']);

    Route::post('friend-request-accept',[FriendRequestController::class,'approveRequest']);
    Route::post('friend-request-reject',[FriendRequestController::class,'rejectRequest']);
    Route::post('friend-request-block',[FriendRequestController::class,'blockFriend']);
    
    Route::get('find-friend',[FriendRequestController::class,'findFriend']);
    Route::get('find-parent',[FriendRequestController::class,'searchParent']);

    Route::get('children-get',[FriendRequestController::class,'childrenGet']);

    Route::post('help',[HelperController::class,'create']);

    Route::resource('report-user',ReportUserController::class,[
        "only" => ['index','store']
    ]);
    Route::resource('report-story',ReportStoryController::class,[
        "only" => ['index','store']
    ]);
    Route::resource('block-user',BlockUnblockUserController::class,[
        "only" => ['index','store']
    ]);
    
});
Route::post('find-contacts',[ContactFinderController::class,'contact']);
Route::post('find-contacts-json',[ContactFinderController::class,'contactJson']);