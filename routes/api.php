<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PelangganDataController;
use App\Http\Controllers\PenyewaanController;
use App\Http\Controllers\PenyewaanDetailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return $request->user();
});



//Auth
Route::post('/admin/auth/login', [AuthController::class, 'login']);
Route::post('/admin/auth/register', [AuthController::class, 'register']);
Route::middleware('auth:api')->post('/refresh-password', [AuthController::class, 'refreshPassword']);

//Admin
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::get('/{id}', [AdminController::class, 'show']);
    Route::post('/', [AdminController::class, 'store']);
    Route::put('/{id}', [AdminController::class, 'update']);
    Route::delete('/{id}', [AdminController::class, 'destroy']);
    Route::put('/up/{id}', [AdminController::class, 'update_pass']);

});

//pelanggan
Route::get('/pelanggan', [PelangganController::class, 'index']);
Route::get('/pelanggan/{pelanggan_id}', [PelangganController::class, 'show']);
Route::post('/pelanggan', [PelangganController::class, 'store']);
Route::put('/pelanggan/{pelanggan_id}', [PelangganController::class, 'update']);
Route::delete('/pelanggan/{pelanggan_id}', [PelangganController::class, 'destroy']);

//pelangganData
Route::get('/pelanggan-data', [PelangganDataController::class, 'index']);
Route::get('/pelanggan-data/{id}', [PelangganDataController::class, 'show']);
Route::post('/pelanggan-data', [PelangganDataController::class, 'store']);
Route::put('/pelanggan-data/{id}', [PelangganDataController::class, 'update']);
Route::delete('/pelanggan-data/{id}', [PelangganDataController::class, 'destroy']);

//penyewaan
Route::get('/penyewaan', [PenyewaanController::class, 'index']);
Route::get('/penyewaan/{id}', [PenyewaanController::class, 'show']);
Route::post('/penyewaan', [PenyewaanController::class, 'store']);
Route::put('/penyewaan/{id}', [PenyewaanController::class, 'update']);
Route::delete('/penyewaan/{id}', [PenyewaanController::class, 'destroy']);

//Kategori
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/kategori/{id}', [KategoriController::class, 'show']);
Route::post('/kategori', [KategoriController::class, 'store']);
Route::put('/kategori/{id}', [KategoriController::class, 'update']);
Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);

//Alat
Route::get('/alat', [AlatController::class, 'index']);
Route::get('/alat/{id}', [AlatController::class, 'show']);
Route::post('/alat', [AlatController::class, 'store']);
Route::put('/alat/{id}', [AlatController::class, 'update']);
Route::delete('/alat/{id}', [AlatController::class, 'destroy']);

//PenyewaanDetail
Route::get('/penyewaan-detail', [PenyewaanDetailController::class, 'index']);
Route::get('/penyewaan-detail/{id}', [PenyewaanDetailController::class, 'show']);
Route::post('/penyewaan-detail', [PenyewaanDetailController::class, 'store']);
Route::put('/penyewaan-detail/{id}', [PenyewaanDetailController::class, 'update']);
Route::delete('/penyewaan-detail/{id}', [PenyewaanDetailController::class, 'destroy']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('admin', AdminController::class);
});
