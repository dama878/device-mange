<?php

use App\Http\Controllers\Admin\DeviceController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\DevicesController;
use App\Http\Controllers\Api\TypesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [UserController::class, 'index'])->name('login');
Route::get('/app/login', [UserController::class, 'index']);
Route::get('/logins', [UserController::class, 'index']);
Route::get('/user/login', [UserController::class, 'index']);
Route::post('/user/do-login', [UserController::class, 'doLogin']);

// Admin site routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/app/dashboard', [DashboardController::class, 'index']);

    Route::get('/app/type', [TypeController::class, 'index']);
    Route::get('/internal/types/get/{id?}', [TypesController::class, 'get']);
    Route::get('/internal/types/get-parent-list', [TypesController::class, 'getParentList']);
    Route::post('/internal/types/add', [TypesController::class, 'add']);
    Route::post('/internal/types/update', [TypesController::class, 'update']);
    Route::post('/internal/types/delete/{id}', [TypesController::class, 'delete']);

    Route::get('/app/device', [DeviceController::class, 'index']);
    Route::get('/internal/devices/get/{id?}', [DevicesController::class, 'get']);
    Route::post('/internal/devices/add', [DevicesController::class, 'add']);
    Route::post('/internal/devices/update', [DevicesController::class, 'update']);
    Route::post('/internal/devices/delete/{id}', [DevicesController::class, 'delete']);
});