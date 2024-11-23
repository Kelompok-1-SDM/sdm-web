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

Route::pattern('id', '[a-zA-Z0-9]{24}');
Route::get('resetPassword', [AuthController::class, 'ForgotPassword'])->name('resetPassword');
Route::post('resetPassword', [AuthController::class, 'ResetPassword']);
Route::get('requestReset', [AuthController::class, 'requestReset'])->name('requestReset');
Route::post('requestReset', [AuthController::class, 'requestReset']);
Route::middleware(['check.jwt'])->group(function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'postlogin']);
});

Route::middleware(['jwt.required'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::group(['prefix' => 'dosen'], function () {
        Route::get('/', [DosenController::class, 'index']);
        Route::post('/list', [DosenController::class, 'list']);

        Route::get('/create_ajax', [DosenController::class, 'create_ajax']);
        Route::post('/store_ajax', [DosenController::class, 'store_ajax']);
        Route::get('/import', [DosenController::class, 'import']);
        Route::get('/export_excel', [DosenController::class, 'export_excel']);
        Route::get('/{id}/delete_ajax', [DosenController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [DosenController::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [DosenController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [DosenController::class, 'edit_ajax']);
        Route::post('/{id}/update_ajax', [DosenController::class, 'update_ajax']);
    });

    Route::group(['prefix' => 'manajemen'], function () {
        Route::get('/', [ManajemenController::class, 'index']);
        Route::post('/list', [ManajemenController::class, 'list']);

        Route::get('/create_ajax', [ManajemenController::class, 'create_ajax']);
        Route::post('/store_ajax', [ManajemenController::class, 'store_ajax']);
        Route::get('/import', [ManajemenController::class, 'import']);
        Route::get('/export_excel', [ManajemenController::class, 'export_excel']);
        Route::get('/{id}/delete_ajax', [ManajemenController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [ManajemenController::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [ManajemenController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [ManajemenController::class, 'edit_ajax']);
        Route::post('/{id}/update_ajax', [ManajemenController::class, 'update_ajax']);
    });

    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::post('/list', [AdminController::class, 'list']);

        Route::get('/create_ajax', [AdminController::class, 'create_ajax']);
        Route::post('/store_ajax', [AdminController::class, 'store_ajax']);
        Route::get('/import', [AdminController::class, 'import']);
        Route::post('/import_ajax', [AdminController::class, 'import_ajax']);
        Route::get('/export_excel', [AdminController::class, 'export_excel']);
        Route::get('/{id}/delete_ajax', [AdminController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [AdminController::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [AdminController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [AdminController::class, 'edit_ajax']);
        Route::post('/{id}/update_ajax', [AdminController::class, 'update_ajax']);
    });

    Route::group(['prefix' => 'kegiatan'], function () {
        Route::get('/', [KegiatanController::class, 'index']);
        Route::post('/list', [KegiatanController::class, 'list']);
        Route::get('/{id}/detail', [KegiatanController::class, 'detailKegiatan']);

        Route::get('/anggota_show_ajax', [KegiatanController::class, 'anggota_show_ajax']);
        Route::get('/{id}/anggota_create_ajax', [KegiatanController::class, 'anggota_create_ajax']);
        Route::post('/{id}/anggota_store_ajax', [KegiatanController::class, 'anggota_store_ajax']);
        Route::get('/{id}/anggota_edit_ajax', [KegiatanController::class, 'anggota_edit_ajax']);
        Route::post('/{id}/anggota_update_ajax', [KegiatanController::class, 'anggota_update_ajax']);
        Route::get('/{id}/anggota_delete_ajax', [KegiatanController::class, 'anggota_confirm_ajax']);
        Route::delete('/{id}/anggota_delete_ajax', [KegiatanController::class, 'anggota_delete_ajax']);
    });

    Route::group(['prefix' => 'kompetensi'], function () {
        Route::get('/', [KompetensiController::class, 'index']);
        Route::post('/list', [KompetensiController::class, 'list']);

        Route::get('/create_ajax', [KompetensiController::class, 'create_ajax']);
        Route::post('/store_ajax', [KompetensiController::class, 'store_ajax']);
        Route::get('/{id}/delete_ajax', [KompetensiController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [KompetensiController::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [KompetensiController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [KompetensiController::class, 'edit_ajax']);
        Route::post('/{id}/update_ajax', [KompetensiController::class, 'update_ajax']);
    });


    Route::get('logout', [AuthController::class, 'logout']);
});
