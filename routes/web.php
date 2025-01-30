<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\MatpelPengampuController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RombelController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\WalikelasController;
use App\Http\Middleware\CekStatusLogin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.auth.login');
});
//login
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware('cek-status-login')->group(function () {
    Route::prefix('/pages')->group(function () {
        //dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        //tahun-ajaran
        Route::resource('/tahun-ajaran', TahunAjaranController::class);
        Route::post('/tahun-ajaran/{id}/updateStatus', [TahunAjaranController::class, 'updateStatus'])->name('tahun-ajaran.updateStatus');
        //data-kelas
        Route::resource('/data-kelas', KelasController::class);
        //data-jurusan
        Route::resource('/data-jurusan', JurusanController::class);
        //data-siswa
        Route::resource('/data-siswa', SiswaController::class);
        Route::post('/data-siswa/{id}/updateStatus', [SiswaController::class, 'updateStatus'])->name('data-siswa.updateStatus');
        Route::get('/data-siswa/export/data', [SiswaController::class, 'export'])->name('data-siswa.export');
        //data-guru
        Route::resource('/data-guru', GuruController::class);
        Route::post('/data-guru/{id}/updateStatus', [GuruController::class, 'updateStatus'])->name('data-guru.updateStatus');
        //data-rombel
        Route::resource('/data-rombel', RombelController::class);
        Route::post('/data-rombel/siswarombel', [RombelController::class, 'SiswaRombel'])->name('data-rombel.siswaRombel');
        Route::post('/data-rombel/deletesiswa', [RombelController::class, 'deleteSiswa'])->name('data-rombel.deleteSiswa');
        Route::post('/data-rombel/pindahtingkat/{idkelas}/{idtahunajaran}', [RombelController::class, 'pindahTingkat'])->name('data-rombel.pindahTingkat');
        Route::post('/data-rombel/updaterombel/{idkelas}/{idtahunajaran}', [RombelController::class, 'updateRombel'])->name('data-rombel.updateRombel');
        Route::get('/data-rombel/{idkelas}/{idtahunajaran}', [RombelController::class, 'showStudents'])->name('data-rombel.showStudents');
        //data-mata-pelajaran
        Route::resource('/data-mata-pelajaran', MataPelajaranController::class);
        //data-matpel-pengampu
        Route::resource('/matpel-pengampu', MatpelPengampuController::class);
        //data-walikelas
        Route::resource('/data-walikelas', WalikelasController::class);
        //role
        Route::resource('/role', RoleController::class);
        //pengaturan
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::get('/pengaturan/menu', [PengaturanController::class, 'menuForm'])->name('pengaturan.menuForm');
        Route::post('/pengaturan/menu', [PengaturanController::class, 'menuFormStore'])->name('pengaturan.menuForm.store');
    });
});
