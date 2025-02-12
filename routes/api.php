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
Route::post('/v1/auth/login', [AuthController::class, 'login']);
Route::post('/v1/auth/register', [AuthController::class, 'register']);
Route::middleware('auth:api')->post('/refresh-password', [AuthController::class, 'refreshPassword']);

//Admin
Route::prefix('v1')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::get('/{id}', [AdminController::class, 'show']);
        Route::post('/', [AdminController::class, 'store']);
        Route::put('/{id}', [AdminController::class, 'update']);
        Route::delete('/{id}', [AdminController::class, 'destroy']);
        Route::put('/up/{id}', [AdminController::class, 'update_pass']);
    });
});

//pelanggan
Route::get('/v1/pelanggan', [PelangganController::class, 'index']);
Route::get('/v1/pelanggan/{pelanggan_id}', [PelangganController::class, 'show']);
Route::post('/v1/pelanggan', [PelangganController::class, 'store']);
Route::put('/v1/pelanggan/{pelanggan_id}', [PelangganController::class, 'update']);
Route::delete('/v1/pelanggan/{pelanggan_id}', [PelangganController::class, 'destroy']);

//pelangganData
Route::get('/v1/data/pelanggan', [PelangganDataController::class, 'index']);
Route::get('/v1/data/pelanggan/{id}', [PelangganDataController::class, 'show']);
Route::post('/v1/data/pelanggan', [PelangganDataController::class, 'store']);
Route::put('/v1/data/pelanggan/{id}', [PelangganDataController::class, 'update']);
Route::delete('/v1/data/pelanggan/{id}', [PelangganDataController::class, 'destroy']);

//penyewaan
Route::get('/v1/penyewaan', [PenyewaanController::class, 'index']);
Route::get('/v1/penyewaan/{id}', [PenyewaanController::class, 'show']);
Route::post('/v1/penyewaan', [PenyewaanController::class, 'store']);
Route::put('/v1/penyewaan/{id}', [PenyewaanController::class, 'update']);
Route::delete('/v1/penyewaan/{id}', [PenyewaanController::class, 'destroy']);

//kategori
Route::get('/v1/kategori', [KategoriController::class, 'index']);
Route::get('/v1/kategori/{id}', [KategoriController::class, 'show']);
Route::post('/v1/kategori', [KategoriController::class, 'store']);
Route::put('/v1/kategori/{id}', [KategoriController::class, 'update']);
Route::delete('/v1/kategori/{id}', [KategoriController::class, 'destroy']);

//alat
Route::get('/v1/alat', [AlatController::class, 'index']);
Route::get('/v1/alat/{id}', [AlatController::class, 'show']);
Route::post('/v1/alat', [AlatController::class, 'store']);
Route::put('/v1/alat/{id}', [AlatController::class, 'update']);
Route::delete('/v1/alat/{id}', [AlatController::class, 'destroy']);

//penyewaanDetail
Route::get('/v1/detail/penyewaan', [PenyewaanDetailController::class, 'index']);
Route::get('/v1/detail/penyewaan/{id}', [PenyewaanDetailController::class, 'show']);
Route::post('/v1/detail/penyewaan', [PenyewaanDetailController::class, 'store']);
Route::put('/v1/detail/penyewaan/{id}', [PenyewaanDetailController::class, 'update']);
Route::delete('/v1/detail/penyewaan/{id}', [PenyewaanDetailController::class, 'destroy']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('admin', AdminController::class);
});
