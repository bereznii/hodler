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

\Illuminate\Support\Facades\Auth::routes();

Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');
Route::post('/profile', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('profile.update');
Route::get('/', [App\Http\Controllers\AssetController::class, 'index'])->name('home');
Route::get('/advanced', [App\Http\Controllers\AssetController::class, 'advanced'])->name('advanced');

Route::post('/asset', [App\Http\Controllers\AssetController::class, 'create'])->name('asset.create');
Route::post('/delete-asset/{id}', [App\Http\Controllers\AssetController::class, 'delete'])->name('asset.delete');

Route::post('/transaction', [App\Http\Controllers\TransactionController::class, 'create'])->name('transaction.create');
