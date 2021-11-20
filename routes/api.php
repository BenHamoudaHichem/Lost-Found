<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Middleware;
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
Route::post('login', [AuthController::class,'authenticate']);

Route::post('register',[UserController::class,'register']);
Route::post('register/admin',[AdminController::class,'register']);

Route::group(['middleware' => ['jwt.verify']], function() {


        Route::get('demo',[UserController::class,'demo']);
        Route::get('logout',[AuthController::class,'logout']);
        /**------------Admin-------------- */
        Route::get('demo1',[AdminController::class,'demo'])->middleware('admin');
        Route::post('logout',[AuthController::class,'logout'])->middleware('admin');



});