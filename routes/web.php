<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Route::get('logout', [AuthController::class, 'logout']);

// Route::middleware('auth')->group(function () {

//     Route::controller(AuthController::class)->group(function () {
//         Route::get('user', 'user');
//         // Route::get('logout', 'logout');
//         Route::post('profile', 'profile');
//     });

//     Route::resource('users', UserController::class);

//     Route::resource('categories', CategoryController::class);
// });

Route::middleware('auth:web')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::get('user', 'user');
        Route::get('logout', 'logout');
        Route::post('profile', 'profile');
    });
    // Route::get('user', [AuthController::class, 'user']);

    Route::resource('tags', TagController::class)->only([
        'store', 'show', 'update', 'destroy', 'edit'
    ]);

    Route::resource('categories', CategoryController::class)->only([
        'store', 'show', 'update', 'destroy', 'edit'
    ]);
});;

Route::any('{slug}', function () {
    return view('welcome');
});