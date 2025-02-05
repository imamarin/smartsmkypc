<?php

namespace Database\Seeders;

use App\Models\Fitur;
use App\Models\Guru;
use App\Models\HakAkses;
use App\Models\Jurusan;
use App\Models\Kategori;
use App\Models\Kelas;
use App\Models\Matpel;
use App\Models\Menu;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\UserRole;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'username' => 'adminTest',
            'password' => bcrypt('12341234'),
        ]);
        User::create([
            'username' => 'guru1',
            'password' => bcrypt('12341234'),
        ]);
        User::create([
            'username' => 'guru2',
            'password' => bcrypt('12341234'),
        ]);
        User::create([
            'username' => 'siswa1',
            'password' => bcrypt('12341234'),
        ]);
        User::create([
            'username' => 'siswa2',
            'password' => bcrypt('12341234'),
        ]);

        Role::create([
            'role' => 'Admin',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Role::create([
            'role' => 'Guru',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Role::create([
            'role' => 'Siswa',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        UserRole::create([
            'iduser' => 1,
            'idrole' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        UserRole::create([
            'iduser' => 2,
            'idrole' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        UserRole::create([
            'iduser' => 3,
            'idrole' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        UserRole::create([
            'iduser' => 4,
            'idrole' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        UserRole::create([
            'iduser' => 5,
            'idrole' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kategori::create([
            'kategori' => 'Dashboard',
            'posisi' => 1,
            'icon' => 'home',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kategori::create([
            'kategori' => 'Data Master',
            'posisi' => 2,
            'icon' => 'book',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kategori::create([
            'kategori' => 'Administrasi Guru',
            'posisi' => 3,
            'icon' => 'book',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kategori::create([
            'kategori' => 'Kurikulum',
            'posisi' => 4,
            'icon' => 'book',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kategori::create([
            'kategori' => 'Walikelas',
            'posisi' => 5,
            'icon' => 'book',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kategori::create([
            'kategori' => 'Pengaturan',
            'posisi' => 6,
            'icon' => 'settings',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Dashboard',
            'url' => '/pages/dashboard',
            'posisi' => 1,
            'idkategori' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Siswa',
            'url' => '/pages/data-siswa',
            'posisi' => 1,
            'idkategori' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Guru',
            'url' => '/pages/data-guru',
            'posisi' => 2,
            'idkategori' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Jurusan',
            'url' => '/pages/data-jurusan',
            'posisi' => 4,
            'idkategori' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Kelas',
            'url' => '/pages/data-kelas',
            'posisi' => 3,
            'idkategori' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Rombel',
            'url' => '/pages/data-rombel',
            'posisi' => 5,
            'idkategori' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Menu::create([
            'menu' => 'Masuk Mengajar',
            'url' => '/pages/masuk-mengajar',
            'posisi' => 1,
            'idkategori' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Rekap Presensi Mengajar',
            'url' => '/pages/rekap-presensi-mengajar',
            'posisi' => 2,
            'idkategori' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Rekap Presensi Siswa',
            'url' => '/pages/rekap-presensi-siswa',
            'posisi' => 3,
            'idkategori' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Rekap Presensi Siswa',
            'url' => '/pages/rekap-presensi-siswa',
            'posisi' => 4,
            'idkategori' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Matpel Pengampu',
            'url' => '/pages/matpel-pengampu',
            'posisi' => 5,
            'idkategori' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Jadwal Mengajar',
            'url' => '/pages/jadwal-mengajar',
            'posisi' => 6,
            'idkategori' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Mata Pelajaran',
            'url' => '/pages/data-mata-pelajaran',
            'posisi' => 1,
            'idkategori' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Walikelas',
            'url' => '/pages/data-walikelas',
            'posisi' => 2,
            'idkategori' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Rekap Presensi Guru',
            'url' => '/pages/data-rekap-presensi-guru',
            'posisi' => 3,
            'idkategori' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Rekap Presensi Siswa',
            'url' => '/pages/data-rekap-presensi-siswa',
            'posisi' => 4,
            'idkategori' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Jadwal Mengajar Guru',
            'url' => '/pages/data-jadwal-mengajar-guru',
            'posisi' => 6,
            'idkategori' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Jam Pelajaran',
            'url' => '/pages/data-jam-pelajaran',
            'posisi' => 7,
            'idkategori' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Siswa',
            'url' => '/pages/walikelas-siswa',
            'posisi' => 1,
            'idkategori' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Presensi Harian Siswa',
            'url' => '/pages/presensi-harian-siswa',
            'posisi' => 2,
            'idkategori' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Rekap Presensi Harian',
            'url' => '/pages/rekap-presensi-harian-siswa',
            'posisi' => 3,
            'idkategori' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Role & Hak Akses',
            'url' => '/pages/role',
            'posisi' => 1,
            'idkategori' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Sistem Blok',
            'url' => '/pages/sistem-blok',
            'posisi' => 2,
            'idkategori' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Tahun Ajaran',
            'url' => '/pages/tahun-ajaran',
            'posisi' => 3,
            'idkategori' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Fitur::create([
            'idmenu' => 2,
            'fitur' => 'Tambah',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Fitur::create([
            'idmenu' => 2,
            'fitur' => 'Ubah',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Fitur::create([
            'idmenu' => 2,
            'fitur' => 'Hapus',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Fitur::create([
            'idmenu' => 3,
            'fitur' => 'Tambah',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Fitur::create([
            'idmenu' => 3,
            'fitur' => 'Ubah',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Fitur::create([
            'idmenu' => 3,
            'fitur' => 'Hapus',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 1,
            'idfitur' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 1,
            'idfitur' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 1,
            'idfitur' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 1,
            'idfitur' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 1,
            'idfitur' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 1,
            'idfitur' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 2,
            'idfitur' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 2,
            'idfitur' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 2,
            'idfitur' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        TahunAjaran::create([
            'awal_tahun_ajaran' => '2023',
            'akhir_tahun_ajaran' => '2024',
            'status' => 1,
            'semester' => 'ganjil',
            'tgl_mulai' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Jurusan::create([
            'idtahunajaran' => 1,
            'jurusan' => 'RPL',
            'kompetensi' => 'Rekayan Perangkat Lunak',
            'program_keahlian' => 'Teknik Komputer dan Informatika',
            'bidang_keahlian' => 'Teknik Informasi dan Komunikasi',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kelas::create([
            'idtahunajaran' => 1,
            'kelas' => 'X RPL 1',
            'tingkat' => 'X',
            'idjurusan' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kelas::create([
            'idtahunajaran' => 1,
            'kelas' => 'X RPL 2',
            'tingkat' => 'X',
            'idjurusan' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kelas::create([
            'idtahunajaran' => 1,
            'kelas' => 'XI RPL 1',
            'tingkat' => 'XI',
            'idjurusan' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kelas::create([
            'idtahunajaran' => 1,
            'kelas' => 'XI RPL 2',
            'tingkat' => 'XI',
            'idjurusan' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Siswa::create([
            'nisn' => '12341234',
            'nis' => '12345',
            'nama' => 'Siswa Test 1',
            'iduser' => 4,
            'idtahunajaran' => 1,
            'nik' => '77777',
            'asal_sekolah' => 'smp',
            'alamat_siswa' => 'tasik',
            'tempat_lahir' => 'Tasikmalaya',
            'tanggal_lahir' => '1991-03-12',
            'jenis_kelamin' => 'L',
            'diterima_tanggal' => '2024-07-01',
            'kelas' => 'X RPL 1',
            'no_hp_siswa' => '67667',
            'status' => '1',
            'nama_ayah' => 'ayah',
            'nama_ibu' => 'ibu',
            'pekerjaan_ayah' => '-',
            'pekerjaan_ibu' => '-',
            'alamat_ortu' => '-',
            'no_hp_ortu' => '888'
        ]);

        Siswa::create([
            'nisn' => '12341235',
            'nis' => '12346',
            'nama' => 'Siswa Test 2',
            'iduser' => 4,
            'idtahunajaran' => 1,
            'nik' => '77778',
            'asal_sekolah' => 'smp',
            'alamat_siswa' => 'tasik',
            'tempat_lahir' => 'Tasikmalaya',
            'tanggal_lahir' => '1991-03-12',
            'jenis_kelamin' => 'L',
            'diterima_tanggal' => '2024-07-01',
            'kelas' => 'X RPL 1',
            'no_hp_siswa' => '67667',
            'status' => '1',
            'nama_ayah' => 'ayah',
            'nama_ibu' => 'ibu',
            'pekerjaan_ayah' => '-',
            'pekerjaan_ibu' => '-',
            'alamat_ortu' => '-',
            'no_hp_ortu' => '888'
        ]);

        Guru::create([
            'kode_guru' => '1122233',
            'nama' => 'Guru Satu',
            'nip' => '22333444',
            'alamat' => '-',
            'tempat_lahir' => '-',
            'tanggal_lahir' => '1991-03-12',
            'jenis_kelamin' => 'L',
            'no_hp' => '032434',
            'status' => '1',
            'nuptk' => '12323',
            'iduser' => 2,
        ]);

        Guru::create([
            'kode_guru' => '1122234',
            'nama' => 'Guru Dua',
            'nip' => '22333445',
            'alamat' => '-',
            'tempat_lahir' => '-',
            'tanggal_lahir' => '1991-03-12',
            'jenis_kelamin' => 'L',
            'no_hp' => '032434',
            'status' => '1',
            'nuptk' => '12324',
            'iduser' => 3,
        ]);

        Matpel::create([
            'kode_matpel' => 'MTK',
            'matpel' => 'Matematika',
            'kelompok' => 'adaptif',
            'matpels_kode' => null,
        ]);

        Matpel::create([
            'kode_matpel' => 'BIND',
            'matpel' => 'Bahasa Indonesia',
            'kelompok' => 'normatif',
            'matpels_kode' => null,
        ]);

        Matpel::create([
            'kode_matpel' => 'KKRPL',
            'matpel' => 'Konsentrasi Keahlian RPL',
            'kelompok' => 'kejuruan',
            'matpels_kode' => null,
        ]);

        Matpel::create([
            'kode_matpel' => 'KKTKJ',
            'matpel' => 'Konsentrasi Keahlian TKJ',
            'kelompok' => 'kejuruan',
            'matpels_kode' => null,
        ]);

        Matpel::create([
            'kode_matpel' => 'PWCS',
            'matpel' => 'Pemrograman Web Cliet Side',
            'kelompok' => 'kejuruan',
            'matpels_kode' => 'KKRPL',
        ]);

        Matpel::create([
            'kode_matpel' => 'PWSS',
            'matpel' => 'Pemrograman Web Server Side',
            'kelompok' => 'kejuruan',
            'matpels_kode' => 'KKRPL',
        ]);

        Matpel::create([
            'kode_matpel' => 'AS',
            'matpel' => 'Administrasi Server',
            'kelompok' => 'kejuruan',
            'matpels_kode' => 'KKTKJ',
        ]);

        Matpel::create([
            'kode_matpel' => 'KJ',
            'matpel' => 'Keamanan Jaringan',
            'kelompok' => 'kejuruan',
            'matpels_kode' => 'KKTKJ',
        ]);
    }
}
