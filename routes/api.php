<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\StatusController;
use App\Http\Controllers\Admin\RemotiveFilterController;
use App\Http\Controllers\User\SendEmailController;
use App\Http\Controllers\User\RequestLeaveController; 
use App\Http\Controllers\Admin\LeavesController;
use App\Http\Requests\Admin\RejectLeaveRequestRequest;
use App\Http\Requests\Admin\ApproveLeaveRequestRequest;
use App\Http\Controllers\Admin\MeLejeController;
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
Route::get('/approved-leaves', [StatusController::class, 'approvedLeaves']);
Route::get('/statusmeleje', [MeLejeController::class, 'getStatusMeLeje']);
Route::get('/statusmeleje/filter', [MeLejeController::class, 'getStatusMeLejeFiltered']);

//Route::middleware('auth:sanctum')->get('/pending-leaves', [StatusController::class, 'countPending']); 

//Route::middleware('auth')->get('/pending-leaves', [StatusController::class, 'countPending']);




Route::get('/admin/leaves', [LeavesController::class, 'getLeaves']);

  

