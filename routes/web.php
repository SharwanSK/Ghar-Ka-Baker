<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;


Route::get('/', function () {
    return view('welcome');
});

/* ============For Register================= */
Route::get('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/register',[RegisterController::class,'store']);
/* ============For Register================= */


/* ============For login================= */
Route::get('/login',[LoginController::class,'login'])->name('login');
Route::post('/login',[LoginController::class,'authenticate']);
/* ============For login================= */

/* ============For dashboard================= */
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('checkbakery');

/* ============For dashboard================= */


/* ============For logout================= */
Route::post('/logout',[LoginController::class,'logout']);
/* ============For logout================= */

/* ============For products================= */
 Route::get('/products',[ProductController::class,'index'])->middleware('checkbakery');
 Route::post('/products',[ProductController::class,'store'])->middleware('checkbakery');
  // for edit product
   
 Route::get('/products/{id}/edit',[ProductController::class,'edit'])->name('product.edit')->middleware('checkbakery');
 Route::put('/products/{id}',[ProductController::class,'update'])->name('product.update')->middleware('checkbakery');
  


  // for Delete
Route::delete('/products/{id}',[ProductController::class,'destroy'])->name('product.destroy')->middleware('checkbakery');
/* ============For products================= */

/* ============For customers================= */
 Route::get('/customers',[CustomerController::class,'index'])->middleware('checkbakery');
 Route::post('/customers',[CustomerController::class,'store'])->middleware('checkbakery');
 Route::put('/customers/{id}',[CustomerController::class,'update'])->name('customer.update')->middleware('checkbakery');
 Route::delete('/customers/{id}',[CustomerController::class,'destroy'])->name('customer.destroy')->middleware('checkbakery');
/* ============For customers================= */

/* ============For orders================= */
 Route::get('/orders',[OrderController::class,'index'])->middleware('checkbakery');
 Route::post('/orders',[OrderController::class,'store'])->middleware('checkbakery');
 Route::delete('/orders/{id}',[OrderController::class,'destroy'])->name('order.destroy')->middleware('checkbakery');

 // for edit
 Route::get('/orders/{id}/edit',[OrderController::class,'edit'])->name('order.edit')->middleware('checkbakery');
Route::put('/orders/{id}',[OrderController::class,'update'])->name('order.update')->middleware('checkbakery');
Route::get('/calendar', [OrderController::class, 'calendar'])->middleware('checkbakery');
Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])->name('order.invoice')->middleware('checkbakery');
/* ============For orders================= */

/* ============For payments================= */
Route::get('/payments', [PaymentController::class, 'index'])->middleware('checkbakery');
Route::post('/payments', [PaymentController::class, 'store'])->middleware('checkbakery');
/* ============For payments================= */

/* ============For settings================= */
Route::get('/settings', [SettingsController::class, 'index'])->middleware('checkbakery');
Route::put('/settings', [SettingsController::class, 'update'])->middleware('checkbakery');
/* ============For settings================= */




