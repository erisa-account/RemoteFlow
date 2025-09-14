<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProductController; 
use App\Http\Controllers\UserController;
use App\Http\Controllers\CheckinController;
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
    return view('forms');
})->middleware(['auth', 'verified'])->name('forms'); 

Route::get('/user', function () {
    return view('user');
})->middleware(['auth', 'verified'])->name('user'); 



Route::post('/checkin', [CheckinController::class, 'store'])->name('checkin.store');


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

 Route::middleware(['auth'])->group(function () {
    Route::resource('/product', ProductController::class);

    Route::middleware(['is_admin'])->name('admin.')->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::resource('/products', AdminProductController::class);
    });

       
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
    });

});


    
require __DIR__.'/auth.php';
