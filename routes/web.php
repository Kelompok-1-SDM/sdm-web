<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManajemenController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\PenugasanController;
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
Route::middleware(['check.jwt'])->group(function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'postlogin']);
});

Route::middleware(['jwt.required'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::group(['prefix' => 'dosen'], function () {
        Route::get('/', [DosenController::class, 'index'])->name('dosen.index');
        Route::post('/list', [DosenController::class, 'list']);
    });

    Route::get('/penugasan', [PenugasanController::class, 'index'])->name('penugasan.index');

    Route::get('logout', [AuthController::class, 'logout']);
});

Route::get('/manajemen', [ManajemenController::class, 'index']);