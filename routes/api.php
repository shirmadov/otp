<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Enums\TokenAbility;
use App\Http\Controllers\Api\JWTController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::post('/register',[AuthController::class,'sendOTP']);

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'loginUser']);
Route::post('/refresh',[AuthController::class,'refresh'])
    ->middleware([
    'auth:sanctum',
    'ability:'.TokenAbility::ISSUE_ACCESS_TOKEN->value,
]);





Route::prefix('jwt')->group(function () {
    Route::post('login', [JWTController::class,'login']);
    Route::post('logout', [JWTController::class,'logout']);
    Route::post('refresh', [JWTController::class,'refresh']);
    Route::post('me', [JWTController::class,'me']);
});
