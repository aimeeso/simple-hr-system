<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
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



Route::middleware(['guest'])->post('/auth/login', AuthController::class . '@login');

Route::group(['prefix' => 'auth', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/profile', AuthController::class . '@profile');
    Route::post('/logout', AuthController::class . '@logout');
});

Route::group(['prefix' => '', 'middleware' => 'auth:sanctum'], function () {

});

Route::group(['prefix' => 'admin', 'middleware' => 'auth:sanctum'], function () {
    Route::resource('users', UserController::class)->only(['index', 'show', 'store', 'update']);
    Route::post('users/{user}/yearly-annual-leaves/bulk-upsert', UserController::class . '@bulkUpsertUserYearlyAnnualLeave');
});
