<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Orders\OrdersController;



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

Route::get('index', [OrdersController::class, 'index'])->name('order.newOrder');

Route::get('lstorder', [OrdersController::class, 'lstOrder'])->name('order.lstorder');

Route::get('create/{id?}', [OrdersController::class, 'createOrder'])->name('order.create');

Route::post('createprocess', [OrdersController::class, 'createProcess'])->name('order.createProcess');

Route::get('preview/{order}', [OrdersController::class, 'previewOrder'])->name('order.preview');

Route::post('previewprocess', [OrdersController::class, 'previewProcess'])->name('order.previewProcess');

Route::get('viewstateorden/{id}', [OrdersController::class, 'viewStateOrden'])->name('viewStateOrden');

Route::get('retrypayorder/{id}', [OrdersController::class, 'retrypayorder'])->name('retryPayOrder');

