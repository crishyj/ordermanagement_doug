<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('home');
   });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/partner', [App\Http\Controllers\PartnerController::class, 'index'])->name('partner.index');
Route::get('/changepassword', [App\Http\Controllers\PartnerController::class, 'changepassword'])->name('partner.changepassword');
Route::post('/changepassword', [App\Http\Controllers\PartnerController::class, 'resetpassword'])->name('partner.resetpassword');

Route::get('/partner/create', [App\Http\Controllers\PartnerController::class, 'create'])->name('partner.register');
Route::post('/partner/create', [App\Http\Controllers\PartnerController::class, 'store'])->name('partner.store');
Route::post('/partner', [App\Http\Controllers\PartnerController::class, 'update'])->name('partner.update');
Route::get('/partner/{id}', [App\Http\Controllers\PartnerController::class, 'delete'])->name('partner.delete');



Route::get('/order',  [App\Http\Controllers\OrderController::class, 'index'])->name('order.index');
Route::get('/orderstat', [App\Http\Controllers\OrderController::class, 'partner'])->name('order.partner');
Route::get('/archive', [App\Http\Controllers\OrderController::class, 'archive'])->name('order.archive');
Route::get('/order/create', [App\Http\Controllers\OrderController::class, 'create'])->name('order.register');
Route::post('/order/create', [App\Http\Controllers\OrderController::class, 'store'])->name('order.store');
Route::post('/order', [App\Http\Controllers\OrderController::class, 'update'])->name('order.update');
Route::get('/order/{id}', [App\Http\Controllers\OrderController::class, 'delete'])->name('order.delete');


