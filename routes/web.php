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

Route::post('/asset', [App\Http\Controllers\AssetController::class, 'store'])->name('asset.create');
Route::get('/asset', [App\Http\Controllers\AssetController::class, 'create'])->name('asset.create.form');
Route::post('/delete-asset/{id}', [App\Http\Controllers\AssetController::class, 'delete'])->name('asset.delete');

Route::get('/asset/{id}/transaction', [App\Http\Controllers\TransactionController::class, 'create'])->name('transaction.create.form');
Route::post('/asset/{id}/transaction', [App\Http\Controllers\TransactionController::class, 'store'])->name('transaction.create');
Route::post('/asset/{asset_id}/transaction/{id}/delete', [App\Http\Controllers\TransactionController::class, 'delete'])->name('transaction.delete');

Route::get('/fiat', [App\Http\Controllers\FiatController::class, 'index'])->name('fiat');
Route::post('/fiat', [App\Http\Controllers\FiatController::class, 'store'])->name('fiat.create');
Route::get('/fiat/create', [App\Http\Controllers\FiatController::class, 'create'])->name('fiat.create.form');
Route::post('/delete-fiat/{id}', [App\Http\Controllers\FiatController::class, 'delete'])->name('fiat.delete');
