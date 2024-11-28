<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KompetensiController;
use App\Http\Controllers\UserController;
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
Route::pattern('userType', 'admin|manajemen|dosen');

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

    Route::get('/{userType}/', [UserController::class, 'index']);
    Route::post('/{userType}/list', [UserController::class, 'list']);
    Route::get('/{userType}/{id}/detail', [UserController::class, 'detailUser']);

    Route::get('/{userType}/create_ajax', [UserController::class, 'create_ajax']);
    Route::post('/{userType}/store_ajax', [UserController::class, 'store_ajax']);
    Route::get('/{userType}/import', [UserController::class, 'import']);
    Route::post('/{userType}/import_ajax', [UserController::class, 'import_ajax']);
    Route::get('/{userType}/export_excel', [UserController::class, 'export_excel']);
    Route::get('/{userType}/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);
    Route::delete('/{userType}/{id}/delete_ajax', [UserController::class, 'delete_ajax']);
    Route::get('/{userType}/{id}/edit_ajax', [UserController::class, 'edit_ajax']);
    Route::post('/{userType}/{id}/update_ajax', [UserController::class, 'update_ajax']);

    Route::get('/{userType}/{id}/tambah_kompetensi_ajax', [UserController::class, 'tambah_kompetensi_ajax']);
    Route::post('/{userType}/{id}/store_kompetensi_ajax', [UserController::class, 'store_kompetensi_ajax']);
    Route::post('/{userType}/{id}/delete_kompetensi_user', [UserController::class, 'delete_kompetensi_ajax']);

    Route::group(['prefix' => 'kegiatan'], function () {
        Route::get('/', [KegiatanController::class, 'index']);
        Route::post('/list', [KegiatanController::class, 'list']);
        Route::get('/{id}/detail', [KegiatanController::class, 'detailKegiatan']);
        Route::get('/create_ajax', [KegiatanController::class, 'create_ajax']);
        Route::post('/store_ajax', [KegiatanController::class, 'store_ajax']);
        Route::get('/edit_ajax', [KegiatanController::class, 'edit_ajax']);
        Route::get('/delete_ajax', [KegiatanController::class, 'confirm_ajax']);
        Route::post('/{id}/update_ajax', [KegiatanController::class, 'update_ajax']);
        Route::delete('/{id}/delete_ajax', [KegiatanController::class, 'delete_ajax']);

        Route::get('/anggota_show_ajax', [KegiatanController::class, 'anggota_show_ajax']);
        Route::get('/{id}/anggota_create_ajax', [KegiatanController::class, 'anggota_create_ajax']);
        Route::post('/{id}/anggota_store_ajax', [KegiatanController::class, 'anggota_store_ajax']);
        Route::get('/{id}/anggota_edit_ajax', [KegiatanController::class, 'anggota_edit_ajax']);
        Route::post('/{id}/anggota_update_ajax', [KegiatanController::class, 'anggota_update_ajax']);
        Route::get('/{id}/anggota_delete_ajax', [KegiatanController::class, 'anggota_confirm_ajax']);
        Route::delete('/{id}/anggota_delete_ajax', [KegiatanController::class, 'anggota_delete_ajax']);

        Route::get('/{id}/tambah_kompetensi_ajax', [KegiatanController::class, 'tambah_kompetensi_ajax']);
        Route::post('/{id}/store_kompetensi_ajax', [KegiatanController::class, 'store_kompetensi_ajax']);
        Route::post('/{id}/delete_kompetensi_kegiatan', [KegiatanController::class, 'delete_kompetensi_ajax']);

        Route::get('/{id}/lampiran_create_ajax', [KegiatanController::class, 'lampiran_create_ajax']);
        Route::post('/{id}/lampiran_store_ajax', [KegiatanController::class, 'lampiran_store_ajax']);
        Route::delete('/{id}/lampiran_delete_ajax', [KegiatanController::class, 'lampiran_delete_ajax']);

        Route::get('/agenda/{id}', [KegiatanController::class, 'agenda_detail']);
        Route::get('/{id}/agenda_create_ajax', [KegiatanController::class, 'agenda_create_ajax']);
        Route::post('/{id}/agenda_store_ajax', [KegiatanController::class, 'agenda_store_ajax']);
        Route::get('/{id}/agenda_edit_ajax', [KegiatanController::class, 'agenda_edit_ajax']);
        Route::post('/{id}/agenda_update_ajax', [KegiatanController::class, 'agenda_update_ajax']);
        Route::get('/{id}/agenda_delete_ajax', [KegiatanController::class, 'agenda_confirm_ajax']);
        Route::delete('/{id}/agenda_delete_ajax', [KegiatanController::class, 'agenda_delete_ajax']);

        Route::get('/{id}/agenda_anggota_create_ajax', [KegiatanController::class, 'agenda_anggota_create_ajax']);
        Route::get('/{id}/agenda_anggota_delete_ajax', [KegiatanController::class, 'agenda_anggota_confirm_ajax']);
        Route::delete('/{id}/agenda_anggota_delete_ajax', [KegiatanController::class, 'agenda_anggota_delete_ajax']);

        Route::get('/agenda_progress_show_ajax', [KegiatanController::class, 'agenda_progress_show_ajax']);
        Route::get('/{id}/agenda_progress_create_ajax', [KegiatanController::class, 'agenda_progress_create_ajax']);
        Route::post('/{id}/progress_store_ajax', [KegiatanController::class, 'agenda_progress_store_ajax']);
        Route::get('/{id}/agenda_progress_edit_ajax', [KegiatanController::class, 'agenda_progress_edit_ajax']);
        Route::post('/{id}/agenda_progress_update_ajax', [KegiatanController::class, 'agenda_progress_update_ajax']);
        Route::delete('/{id}/agenda_progress_delete_ajax', [KegiatanController::class, 'agenda_progress_delete_ajax']);
        Route::delete('/{id}/agenda_progress_attachment_delete_ajax', [KegiatanController::class, 'agenda_progress_attachment_delete_ajax']);
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

    Route::group(['prefix' => 'jabatan'], function () {
        Route::get('/', [JabatanController::class, 'index']);
        Route::post('/list', [JabatanController::class, 'list']);

        Route::get('/create_ajax', [JabatanController::class, 'create_ajax']);
        Route::post('/store_ajax', [JabatanController::class, 'store_ajax']);
        Route::get('/{id}/delete_ajax', [JabatanController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [JabatanController::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [JabatanController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [JabatanController::class, 'edit_ajax']);
        Route::post('/{id}/update_ajax', [JabatanController::class, 'update_ajax']);
    });


    Route::get('logout', [AuthController::class, 'logout']);
});
