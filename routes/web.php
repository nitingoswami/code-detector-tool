<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilePathController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\RegisteredUserController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/app', function () {
    return view('app');
})->middleware(['auth', 'verified'])->name('app');

Route::get('/report_view/{id}', function () {
    return view('viewReport');
})->middleware(['auth', 'verified'])->name('report_view');

Route::post('/app', [FilePathController::class, 'store'])->middleware(['auth', 'verified'])->name('store.file.path');
Route::get('/all-data', [FilePathController::class, 'get']);

Route::get('/user-data', [UserController::class, 'index']);
Route::put('/user-update/{id}', [UserController::class, 'update'])->name('user.update');



Route::post('/update/{id}', [FilePathController::class, 'update']);

Route::delete('/delete/{id}', [FilePathController::class, 'destroy'])->name('delete.file');

Route::get('/view/{id}', [FilePathController::class, 'viewDetails']);

// Route::get('/all-getReports', [FilePathController::class, 'getReports']);
Route::delete('/user-delete/{id}', [UserController::class, 'destroy'])->name('user.destroy');



Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', function () {
            return view('app');
        })->name('admin.dashboard');
        Route::get('/app', function () {
            return view('app');
        })->name('admin.app');
        Route::get('/user', function () {
            return view('user');
        })->name('user');
        Route::post('/create-user', [RegisteredUserController::class, "store"])->name("admin.user.store");
        Route::post('/comment', [FilePathController::class, 'storeComment'])->name('admin.store.comment');
        Route::get('/get/comment', [FilePathController::class, 'getComment'])->name('admin.get.comment');
        Route::post('/status', [FilePathController::class, 'updateStatus'])->name('admin.update.status');
        

    
    });
});





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
