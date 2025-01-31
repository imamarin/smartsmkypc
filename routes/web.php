<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalMengajarController;
use App\Http\Controllers\JamPelajaranController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasukMengajarController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\MatpelPengampuController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RombelController;
use App\Http\Controllers\SistemBlokController;
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
        Route::get('/data-kelas/export/data', [KelasController::class, 'export'])->name('data-kelas.export');
        //data-jurusan
        Route::resource('/data-jurusan', JurusanController::class);
        Route::get('/data-jurusan/export/data', [JurusanController::class, 'export'])->name('data-jurusan.export');
        //data-siswa
        Route::resource('/data-siswa', SiswaController::class);
        Route::post('/data-siswa/{id}/updateStatus', [SiswaController::class, 'updateStatus'])->name('data-siswa.updateStatus');
        Route::get('/data-siswa/export/data', [SiswaController::class, 'export'])->name('data-siswa.export');
        //data-guru
        Route::resource('/data-guru', GuruController::class);
        Route::post('/data-guru/{id}/updateStatus', [GuruController::class, 'updateStatus'])->name('data-guru.updateStatus');
        Route::get('/data-guru/export/data', [GuruController::class, 'export'])->name('data-guru.export');
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
        //data-jam-pelajaran
        Route::resource('/data-jam-pelajaran', JamPelajaranController::class);
        Route::post('/data-jam-pelajaran/delete', [JamPelajaranController::class, 'delete'])->name('data-jam-pelajaran.delete');
        //role
        Route::resource('/role', RoleController::class);
        //sistem-blok
        Route::resource('/sistem-blok', SistemBlokController::class);
        //jadwal-Mengajar
        Route::resource('/jadwal-mengajar', JadwalMengajarController::class);
        //Masuk-Mengajar
        Route::get('/masuk-mengajar', [MasukMengajarController::class, 'index'])->name('masuk-mengajar.index');
        Route::get('/masuk-mengajar/{id}', [MasukMengajarController::class, 'show'])->name('masuk-mengajar.show');
        //jadwal-Mengajar
        Route::resource('/presensi', PresensiController::class);
        //pengaturan
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::get('/pengaturan/menu', [PengaturanController::class, 'menuForm'])->name('pengaturan.menuForm');
        Route::post('/pengaturan/menu', [PengaturanController::class, 'menuFormStore'])->name('pengaturan.menuForm.store');
    });
});
