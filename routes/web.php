<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\SidaktphController;
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

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/', [loginController::class, 'authenticate'])->name('login');
Route::post('logout', [loginController::class, 'logout'])->name('logout');

// Route::middleware(['auth'])->group(function () {
Route::get('/index', [unitController::class, 'index']);
Route::get('/dashboard_gudang', [unitController::class, 'dashboard_gudang'])->name('dashboard_gudang');
Route::get('/dashboardtph', [SidaktphController::class, 'index'])->name('dashboardtph');
Route::post('/getData', [SidaktphController::class, 'getData'])->name('getData');
Route::post('/dashboardtph', [SidaktphController::class, 'chart'])->name('chart');

Route::post('/getBtTph', [SidaktphController::class, 'getBtTph'])->name('getBtTph');
Route::post('/getKrTph', [SidaktphController::class, 'getKrTph'])->name('getKrTph');
Route::post('/getBHtgl', [SidaktphController::class, 'getBHtgl'])->name('getBHtgl');
Route::post('/exportPDF', [SidaktphController::class, 'exportPDF'])->name('exportPDF');
Route::post('/getDataByYear', [unitController::class, 'getDataByYear'])->name('getDataByYear');

Route::get('/tambah', [unitController::class, 'tambah']);
Route::post('/store', [unitController::class, 'store']);
Route::get('/edit/{id}', [unitController::class, 'edit']);
Route::post('/update', [unitController::class, 'update']);
Route::get('/hapus/{id}', [unitController::class, 'hapus']);
Route::get('detailInspeksi/{id}', [unitController::class, 'detailInspeksi'])->name('detailInspeksi');
Route::get('/qc', [unitController::class, 'load_qc_gudang'])->name('qc');
Route::get('/hapusRecord/{id}', [unitController::class, 'hapusRecord'])->name('hapusRecord');
Route::get('/cetakpdf/{id}', [unitController::class, 'cetakpdf']);
// });
