<?php

use App\Http\Controllers\BiodataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('registerBiodata', [BiodataController::class, 'biodataSignup']);
Route::post('signup', [BiodataController::class, 'Signup']);
Route::middleware('auth:api', 'verified', 'twofactorauth')->prefix('v1')->group(function(){
    Route::get('getuser', [BiodataController::class, 'getUser']);
    Route::post('logout', [BiodataController::class, 'Logout']);
});
Route::get('notloggedin', [BiodataController::class, 'notLoggedin'])->name('unauthorized');
Route::post('resendcode', [BiodataController::class, 'resendCode'])->middleware('auth:api');
Route::post('verifycode', [BiodataController::class, 'verifyCode'])->middleware('auth:api');
Route::get('getusers', [BiodataController::class, 'Getuserlist']);
Route::post('login', [BiodataController::class, 'Login']);
Route::post('sendtwofactorcode', [BiodataController::class, 'TwoFactorMail'])->middleware('auth:api');
Route::post('toggle', [BiodataController::class, 'ToggleTwoFactor'])->middleware('auth:api');
Route::post('verifyphone', [BiodataController::class, 'Verifynumber'])->middleware('auth:api');
Route::post('verifyphonecode', [BiodataController::class, 'Verifyphonecode'])->middleware('auth:api');
Route::post('confirmtwocode', [BiodataController::class, 'ConfirmTwoFactor'])->middleware('auth:api');
Route::post('createpost', [BiodataController::class, 'Createpost'])->middleware('auth:api');
Route::get('getpost', [BiodataController::class, 'getUserPosts'])->middleware('auth:api');
Route::post('comment', [BiodataController::class, 'Comment'])->middleware('auth:api');
Route::get('getcomment', [BiodataController::class, 'getPostcomment']);
