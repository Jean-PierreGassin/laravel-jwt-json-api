<?php

use Illuminate\Support\Facades\Route;
use App\Api\Controllers\Auth\LoginController;
use App\Api\Controllers\Auth\RegisterController;
use App\Api\Controllers\User\UserDetailsController;
use App\Api\Controllers\ApiController;

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

Route::prefix('user')->group(function () {
    Route::post('register', [RegisterController::class, 'store']);
    Route::post('login', [LoginController::class, 'store']);
});

Route::middleware([\App\Api\Middleware\VerifyJWTToken::class])->group(function () {
    Route::get('me', [UserDetailsController::class, 'index']);
});

Route::any('{all}', [ApiController::class, 'fourOhFour'])->where('all', '.*');
