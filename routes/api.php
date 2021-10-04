<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/', [AuthController::class, "index"]);
// main authentication
Route::post('/login', [AuthController::class, "loginAuth"]);
Route::post('/register', [AuthController::class, "registerAuth"]);
Route::post('/update-password/{user}', [AuthController::class, "passwordUpdate"]);
// third party authentication
Route::post('/login/google', [AuthController::class, "registerGoogle"]);
Route::post('/login/facebook', [AuthController::class, "registerFacebook"]);
Route::post('/login/apple', [AuthController::class, "registerApple"]);

// get info via post
Route::post('/user/info', [UserController::class, "getLoggedIn_Info"]);
Route::post('/user/all', [UserController::class, "getAllUser"]);
