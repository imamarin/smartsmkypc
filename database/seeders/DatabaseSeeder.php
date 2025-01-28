<?php

namespace Database\Seeders;

use App\Models\Fitur;
use App\Models\HakAkses;
use App\Models\Jurusan;
use App\Models\Kategori;
use App\Models\Kelas;
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
            'username' => 'siswa',
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
            'kategori' => 'Pengaturan',
            'posisi' => 4,
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
            'menu' => 'Data Mata Pelajaran',
            'url' => '/pages/data-mata-pelajaran',
            'posisi' => 6,
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
            'menu' => 'Rekap Absensi Mengajar',
            'url' => '/pages/rekap-absensi-mengajar',
            'posisi' => 2,
            'idkategori' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Role & Hak Akses',
            'url' => '/pages/role',
            'posisi' => 1,
            'idkategori' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Tahun Ajaran',
            'url' => '/pages/tahun-ajaran',
            'posisi' => 2,
            'idkategori' => 4,
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
            'kdkelas' => 'X RPL 1',
            'tingkat' => 'X',
            'idjurusan' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Siswa::create([
            'nisn' => '12341234',
            'nis' => '12345',
            'nama' => 'Siswa Test',
            'iduser' => 2,
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
    }
}
