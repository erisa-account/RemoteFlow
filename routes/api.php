<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\StatusController;
use App\Http\Controllers\Admin\RemotiveFilterController;
use App\Http\Controllers\User\SendEmailController;

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
 

Route::get('/statuses', [StatusController::class, 'index']);
Route::get('/users', [RemotiveFilterController::class, 'getUserName']);
Route::get('/remotive-table', [RemotiveFilterController::class, 'getRemotiveTable']);
Route::get('/remotive-table/filter', [RemotiveFilterController::class, 'getRemotiveFilteredTable']);
Route::post('/send-email', [SendEmailController::class, 'sendEmail']);
Route::get('/remotive-events', [RemotiveFilterController::class, 'events']); 
Route::get('/statusesnotonsite', [StatusController::class, 'getStatusesNotOnSite']); 
 