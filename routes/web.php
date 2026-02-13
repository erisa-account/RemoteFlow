<?php

use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminProductController; 
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\CheckinController;
// use App\Http\Controllers\TestController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\UsersNameController;
use App\Http\Controllers\Admin\RemotiveTableController;
use App\Http\Controllers\Admin\RemotiveFilterController;
use App\Http\Controllers\User\RequestLeaveController;
use App\Http\Controllers\Admin\LeavesController; 
use App\Http\Controllers\User\UserLeavesController;
use App\Http\Controllers\User\DayMarkerController;
use App\Http\Controllers\User\HolidaysController;
use App\Http\Controllers\User\StatusController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\EmailController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});






/*Route::get('/admin', function () {
    return view('admin.dashboard');
});*/


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware(['auth', 'is_user'])->name('user.')->prefix('user')->group(function () {

/*Route::get('/user', function () {
    return view('user');
})->name('user');*/


Route::get('/', [UserController::class, 'index'])->name('userdashboard');


    

Route::get('/forms', function () { 
    return view('user.forms.forms'); 
})->name('forms'); 

Route::get('/index', function () { 
    return view('user.tables.index'); 
})->name('index'); 

Route::get('/test', function () { 
    return view('test.test'); 
})->name('test');


Route::get('/vacanciesemployee', function () {
    return view('user.vacanciesemployee.index');
})->name('vacanciesemployee');


Route::get('/remotivecalendar', function () { 
    return view('user.schedule.index'); 
})->name('remotivecalendar'); 


    Route::post('/checkin/store', [CheckinController::class, 'store'])->name('checkin.store');
    Route::post('/checkin/update/{id}', [CheckinController::class, 'update'])->name('checkin.update');
    
   Route::post('/user/starting-date', [RequestLeaveController::class, 'storeStartingDate'])->name('starting-date');
   Route::get('/leave-data', [RequestLeaveController::class, 'getLeaveData']);
 
});
 

Route::post('/leave-request', [RequestLeaveController::class, 'storerequest'])->middleware('auth');


Route::get('/leave-summary', [RequestLeaveController::class, 'getLeaveSummary'])->middleware('auth');


Route::get('/leave-history', [UserLeavesController::class, 'getUserLeaves'])->middleware('auth');

Route::get('/day-marker/{user}', [DayMarkerController::class, 'index']);


Route::get('/holidays', [HolidaysController::class, 'index']);
Route::get('/holidays/weekend', [HolidaysController::class, 'weekendHolidays']);



Route::get('/pending-leaves', [StatusController::class, 'countPending'])->middleware('auth');






Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});






Route::get('/debug-leave', [RequestLeaveController::class, 'debugLeave'])->middleware('auth');
 
     
    
    
    Route::middleware(['auth'])->group(function () {
    Route::resource('/product', ProductController::class);
    });


    Route::middleware(['auth', 'is_admin'])->name('admin.')->prefix('admin')->group(function () {
       
        Route::get('/', [AdminController::class, 'index'])->name('index');
       
        Route::resource('/products', AdminProductController::class);
        
        Route::get('/form', function () {
            return view('admin.forms.index');
        })->name('form');
        
        Route::get('/vacanciesadmin', function () {
            return view('admin.cards.index');
        })->name('vacanciesadmin');
        
        Route::put('/admin/test/{id}', [TestController::class, 'update'])->name('admin.test.update');
        
        Route::get('/export-status-calendar', [RemotiveFilterController::class, 'exportStatusCalendar'])
        ->name('admin.remotive.exportStatusCalendar');
        
        Route::post('/leaves/{id}/approve', [LeavesController::class, 'approve']);
        
         Route::post('/leaves/{id}/reject', [LeavesController::class, 'reject']);
         
    });




/*Route::get('/test-mail', function () {
    Mail::raw('Hello, this is a test email from Laravel.', function ($message) {
        $message->to('erisaibro@c7.al')
                ->subject('Test Email from Laravel')
                ->from(config('mail.from.address'), config('mail.from.name'));
    });

    return 'Email sent successfully!';
});*/


Route::post('/send-email', [EmailController::class, 'send'])
    ->name('email.send');


    
       

   



    
require __DIR__.'/auth.php'; 
