<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\indexController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MetricsController;
//use App\Http\Middleware\LogResponseTimeAndErrorRate;

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

Route::get('/', [indexController::class, 'index'])->name('home');
Route::post('/store', [FileController::class, 'store'])->name('store.file');
Route::delete('/delete/{id}', [FileController::class, 'destroy'])->name('delete.file');
Route::get('/metrics', [MetricsController::class, 'metrics']);
Route::get('/test-error', function(){
	abort(500, 'Testing Error');
});
//Route::get('/metrics', [MetricsController::class, 'metrics'])->middleware(LogResponseTimeAndErrorRate::class);

