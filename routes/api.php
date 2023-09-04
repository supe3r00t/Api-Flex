<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('register', [AuthController::class, 'register']);

Route::post('login',[AuthController::class,'login']);

//
//Route::middleware('auth:api')->get('test_token',function (){
//
//    return "You have a token";

    // رجوع بينات يوزر بعد token
//    return auth()->user();
//});


Route::middleware('auth:api')->prefix('user')->group(function (){

   Route::post('update/password',[UserController::class,'updatePassword']);

   Route::post('update/profile',[UserController::class,'updateProfile']);


});

Route::resource('categories',CategoryController::class);
Route::put('categories/{categoryId}/restore',[CategoryController::class,'restore']);
Route::delete('categories/{categoryId}/force-delete',[CategoryController::class,'forceDelete']);

//=============================================================================================


Route::resource('tasks',TaskController::class);
Route::put('tasks/{taskId}/restore',[TaskController::class,'restore']);
Route::delete('tasks/{taskId}/force-delete',[TaskController::class,'forceDelete']);
