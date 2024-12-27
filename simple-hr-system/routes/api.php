<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserLeaveRequestController as AdminUserLeaveRequestController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserLeaveRequestController;
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
    Route::resource('user-leave-requests', UserLeaveRequestController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

});

Route::group(['prefix' => 'admin', 'middleware' => 'auth:sanctum'], function () {
    Route::resource('users', UserController::class)->only(['index', 'show', 'store', 'update']);
    Route::post('users/{user}/yearly-annual-leaves/bulk-upsert', UserController::class . '@bulkUpsertUserYearlyAnnualLeave');
    // user-leave-requests
    Route::get('user-leave-requests', AdminUserLeaveRequestController::class . '@indexAny');
    Route::get('users/{user}/leave-requests', AdminUserLeaveRequestController::class . '@indexOnly');
    Route::get('user-leave-requests/{userLeaveRequest}', AdminUserLeaveRequestController::class . '@show');
    Route::post('user-leave-requests', AdminUserLeaveRequestController::class . '@adminStore');
    Route::put('user-leave-requests/{userLeaveRequest}', AdminUserLeaveRequestController::class . '@adminUpdate');
    Route::delete('user-leave-requests/{userLeaveRequest}', AdminUserLeaveRequestController::class . '@destroy');
});
