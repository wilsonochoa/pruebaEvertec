<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\UsersController;

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

Route::get('orders', [OrdersController::class, 'index']);

Route::get('users', [UsersController::class, 'index']);

Route::get('preview', [OrdersController::class, 'previewOrder'])->name('preview');

Route::post('previewProcess', [OrdersController::class, 'previewProcess'])->name('order.previewProcess');

Route::get('viewStateOrden', [OrdersController::class, 'viewStateOrden'])->name('viewStateOrden');