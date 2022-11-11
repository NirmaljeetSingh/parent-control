<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{AuthenticationController,SettingController,StoriesController};

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

    Route::resource('setting',SettingController::class);
    
    Route::resource('story',StoriesController::class);
    
});