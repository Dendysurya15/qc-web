<?php

use App\Http\Controllers\unitController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;

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



Route::get('/index', [unitController::class, 'index']);
Route::get('/dashboard', [unitController::class, 'dashboard'])->name('dashboard');

Route::get('/tambah', [unitController::class, 'tambah']);
Route::post('/store', [unitController::class, 'store']);
Route::get('/edit/{id}', [unitController::class, 'edit']);
Route::post('/update', [unitController::class, 'update']);
Route::get('/hapus/{id}', [unitController::class, 'hapus']);
Route::get('detailInspeksi/{wil}/{est}/{bulan}', [unitController::class, 'detailInspeksi'])->name('detailInspeksi');
Route::get('/qc', [unitController::class, 'load_qc_gudang'])->name('qc');
Route::get('/cetakpdf/{id}', [unitController::class, 'cetakpdf']);
