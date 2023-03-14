<?php

use App\Http\Controllers\unitController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SidaktphController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TesExportController;
use App\Http\Controllers\inspectController;

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
Route::get('/listAsisten', [SidaktphController::class, 'listAsisten'])->name('listAsisten');
Route::post('/tambahAsisten', [SidaktphController::class, 'tambahAsisten'])->name('tambahAsisten');
Route::post('/perbaruiAsisten', [SidaktphController::class, 'perbaruiAsisten'])->name('perbaruiAsisten');
Route::post('/hapusAsisten', [SidaktphController::class, 'hapusAsisten'])->name('hapusAsisten');
Route::post('/getData', [SidaktphController::class, 'getData'])->name('getData');
Route::post('/dashboardtph', [SidaktphController::class, 'chart'])->name('chart');
Route::post('/downloadPDF', [SidaktphController::class, 'downloadPDF'])->name('downloadPDF');

Route::post('/getBtTph', [SidaktphController::class, 'getBtTph'])->name('getBtTph');
Route::post('/getKrTph', [SidaktphController::class, 'getKrTph'])->name('getKrTph');
Route::post('/getBHtgl', [SidaktphController::class, 'getBHtgl'])->name('getBHtgl');
Route::get('/exportPDF', [SidaktphController::class, 'exportPDF'])->name('exportPDF');
// Route::get('/404', [SidaktphController::class, 'notfound'])->name('404');
Route::post('/getDataByYear', [unitController::class, 'getDataByYear'])->name('getDataByYear');

Route::get('/tambah', [unitController::class, 'tambah']);
Route::post('/store', [unitController::class, 'store']);
Route::get('/edit/{id}', [unitController::class, 'edit']);
Route::post('/update', [unitController::class, 'update']);
Route::get('/hapus/{id}', [unitController::class, 'hapus']);
Route::get('detailInspeksi/{id}', [unitController::class, 'detailInspeksi'])->name('detailInspeksi');
Route::get('detailSidakTph/{est}/{afd}/{start}/{last}', [SidaktphController::class, 'detailSidakTph'])->name('detailSidakTph');
Route::post('getDetailTPH', [SidaktphController::class, 'getDetailTPH'])->name('getDetailTPH');
Route::post('getPlotLine', [SidaktphController::class, 'getPlotLine'])->name('getPlotLine');
Route::get('/qc', [unitController::class, 'load_qc_gudang'])->name('qc');
Route::get('/hapusRecord/{id}', [unitController::class, 'hapusRecord'])->name('hapusRecord');
Route::get('/cetakpdf/{id}', [unitController::class, 'cetakpdf']);
// });

Route::get('/dashboard_inspeksi', [inspectController::class, 'dashboard_inspeksi'])->name('dashboard_inspeksi');
Route::get('/cetakPDFFI/{id}/{est}/{tgl}', [inspectController::class, 'cetakPDFFI'])->name('cetakPDFFI');
Route::post('/getFindData', [inspectController::class, 'getFindData'])->name('getFindData');
Route::post('/changeDataInspeksi', [inspectController::class, 'changeDataInspeksi'])->name('changeDataInspeksi');
Route::post('/plotEstate', [inspectController::class, 'plotEstate'])->name('plotEstate');
Route::post('/plotBlok', [inspectController::class, 'plotBlok'])->name('plotBlok');
// Route::post('/filter', [inspectController::class, 'filter']);


Route::get('/filter', [inspectController::class, 'filter'])->name('filter');
Route::get('/graphfilter', [inspectController::class, 'graphfilter'])->name('graphfilter');
Route::get('/filterTahun', [inspectController::class, 'filterTahun'])->name('filterTahun');
Route::get('/scorebymap', [inspectController::class, 'scorebymap'])->name('scorebymap');
