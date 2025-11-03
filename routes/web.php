<?php

use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminProductController; 
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\User\CheckinController;
// use App\Http\Controllers\TestController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\UsersNameController;
use App\Http\Controllers\Admin\RemotiveTableController;
use App\Http\Controllers\Admin\RemotiveFilterController;





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

Route::get('/forms', function () { 
    return view('user.forms.forms'); 
})->middleware(['auth', 'verified'])->name('forms'); 

Route::get('/index', function () { 
    return view('user.tables.index'); 
})->middleware(['auth', 'verified'])->name('index'); 

Route::get('/test', function () { 
    return view('test.test'); 
})->middleware(['auth', 'verified'])->name('test');


Route::get('/vacanciesemployee', function () {
    return view('user.vacanciesemployee.index');
})->middleware(['auth', 'verified'])->name('vacanciesemployee');


Route::get('/remotivecalendar', function () { 
    return view('user.schedule.index'); 
})->middleware(['auth', 'verified'])->name('remotivecalendar'); 

 

Route::get('/user', function () {
    return view('user');
})->middleware(['auth', 'verified'])->name('user'); 




//Route::post('/admin/users/store', [TestController::class, 'store'])->name('admin.users.store');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// Route::middleware(['auth'])->group(function () {
//     Route::resource('/product', ProductController::class);

//     Route:: middleware(['is_admin'])->name('admin.')->prefix('admin')->group(function () {
//         Route::get('/', [AdminController::class, 'index'])->name('index');
//         Route::resource('/products', AdminProductController::class);
//     });
// });

 
    
    
    //Route::post('/user/test/store', [TestController::class, 'store'])->name('user.test.store');
    //Route::get('/test/get', [TestController::class, 'getData'])->name('test.get');
    
    
    
    Route::middleware(['auth'])->group(function () {
    Route::resource('/product', ProductController::class);

    Route::middleware(['is_admin'])->name('admin.')->prefix('admin')->group(function () {
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


         
    });




       
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
    });
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
       
    });

     //Route::put('/admin/test/{id}', [TestController::class, 'update'])->name('admin.test.update');
        //Route::post('admin/test/store', [TestController::class, 'store'])->name('admin.test.store'); 
    Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::post('/checkin/store', [CheckinController::class, 'store'])->name('checkin.store');
    Route::post('/checkin/update/{id}', [CheckinController::class, 'update'])->name('checkin.update');
});
    
    Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
        Route::get('/test/get', [TestController::class, 'getData'])->name('test.get');
        Route::post('/test/store', [TestController::class, 'store'])->name('test.store');

    
});
});


    
require __DIR__.'/auth.php'; 
