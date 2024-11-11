<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManajemenController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KompetensiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::pattern('id', '[0-9]+');
Route::get('resetPassword', [AuthController::class, 'ForgotPassword'])->name('resetPassword');
Route::post('resetPassword', [AuthController::class, 'ResetPassword']);
Route::middleware(['check.jwt'])->group(function () {

    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'postlogin']);
});

Route::middleware(['jwt.required'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::group(['prefix' => 'dosen'], function () {
        Route::get('/', [DosenController::class, 'index']);
        Route::post('/list', [DosenController::class, 'list']);
    });

    Route::group(['prefix' => 'manajemen'], function () {
        Route::get('/', [ManajemenController::class, 'index']);
        Route::post('/list', [ManajemenController::class, 'list']);
    });

    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::post('/list', [AdminController::class, 'list']);
    });

    Route::group(['prefix' => 'kegiatan'], function () {
        Route::get('/', [KegiatanController::class, 'index']);
        Route::post('/list', [KegiatanController::class, 'list']);
    });

    Route::group(['prefix' => 'kompetensi'], function () {
        Route::get('/', [KompetensiController::class, 'index']);
        Route::post('/list', [KompetensiController::class, 'list']);
    });


    Route::get('logout', [AuthController::class, 'logout']);
});
