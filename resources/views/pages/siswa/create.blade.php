@extends('layouts.app')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Input Data Siswa</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('data-siswa.index') }}">Data Siswa</a></li>
                        <li class="breadcrumb-item active">Form Siswa</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <form action="{{ route('data-siswa.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <h4 class="card-title">Identitas Siswa</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">NISN<span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="nisn" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">NIS<span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="nis" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">NIK<span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="nik" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Nama Siswa<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="nama" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Tempat Lahir<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="tempat_lahir" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Tanggal Lahir<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="date" name="tanggal_lahir" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Jenis Kelamin<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="jenis_kelamin"
                                                value="P" required>
                                            <label class="form-check-label">
                                                Perempuan
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenis_kelamin"
                                                value="L" required>
                                            <label class="form-check-label">
                                                Laki-laki
                                            </label>
                                        </div>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 mb-lg-0 row">
                                    <label class="col-md-2 col-form-label">Asal Sekolah<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="asal_sekolah" required>
                                    </div>
                                </div><!-- end row -->
                            </div><!-- end col -->

                            <div class="col-xl-6">
                                <div class="row mb-3 mt-3 mt-xl-0">
                                    <label class="col-md-2 col-form-label">Alamat Siswa<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <textarea name="alamat_siswa" class="form-control" cols="30" rows="5" required></textarea>
                                    </div>
                                </div><!-- end row -->
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">No HP Siswa</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="no_hp_siswa">
                                    </div>
                                </div><!-- end row -->
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">Tangal Terima<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="date" name="diterima_tanggal" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">Diterima Dikelas<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="kelas" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">Masuk Tahun Ajaran<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select name="idtahunajaran" class="form-select select2" required>
                                            <option selected disabled>Pilih Tahun Ajaran</option>
                                            @foreach ($tahun_ajaran as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}
                                                    ({{ $item->semster == 'ganjil' ? 'Ganjil' : 'Genap' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div><!-- end row -->
                                <div class="row">
                                    <label class="col-md-2 col-form-label">Status<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select name="status" class="form-select select2" required>
                                            <option selected disabled>Pilih Status</option>
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div><!-- end row -->
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <h4 class="card-title">Data Orang Tua Siswa</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Nama Ayah<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="nama_ayah" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Nama Ibu<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="nama_ibu" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Pekerjaan Ayah<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="pekerjaan_ayah" required>
                                    </div>
                                </div><!-- end row -->
                            </div><!-- end col -->
                            <div class="col-xl-6">
                                <div class="row mb-3 mt-3 mt-xl-0">
                                    <label class="col-md-2 col-form-label">Pekerjaan Ibu<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="pekerjaan_ibu" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">Alamat Ortu<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <textarea name="alamat_ortu" class="form-control" cols="30" rows="4" required></textarea>
                                    </div>
                                </div><!-- end row -->
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">No HP Ortu</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="no_hp_ortu">
                                    </div>
                                </div><!-- end row -->
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <h4 class="card-title">Validasi Pengguna</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl">
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Username</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text">
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Password</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="password">
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Level</label>
                                    <div class="col-md-10">
                                        <select name="role" class="form-select select2">
                                            <option disabled selected>--- Pilih Level ---</option>
                                            @foreach ($role as $item)
                                                <option value="{{ $item->id }}">{{ $item->role }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div><!-- end row -->
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div>
        <div class="me-3 text-end">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="reset" class="btn btn-danger">Cancel</button>
        </div>
    </form>
@endsection
