@extends('layouts.app')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Input Data Guru</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('data-guru.index') }}">Data Guru</a></li>
                        <li class="breadcrumb-item active">Form Guru</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <form action="{{ route('data-guru.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <h4 class="card-title">Identitas Guru</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Kode Guru<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="kode_guru" value="{{ old('kode_guru') }}" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Nama<span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="nama" value="{{ old('nama') }}" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Tempat Lahir<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Tanggal Lahir<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
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
                                    <label class="col-md-2 col-form-label">NUPTK</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="nuptk" value="{{ old('nuptk') }}">
                                    </div>
                                </div><!-- end row -->
                            </div><!-- end col -->

                            <div class="col-xl-6">
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">NIP<span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="nip" value="{{ old('nip') }}">
                                    </div>
                                </div><!-- end row -->
                                <div class="row mb-3 mt-3 mt-xl-0">
                                    <label class="col-md-2 col-form-label">Alamat<span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <textarea name="alamat" class="form-control" cols="30" rows="5" required>{{ old('alamat') }}</textarea>
                                    </div>
                                </div><!-- end row -->
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">No HP<span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="no_hp" value="{{ old('no_hp') }}" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="row">
                                    <label class="col-md-2 col-form-label">Status<span class="text-danger">*</span></label>
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
                        <h4 class="card-title">Validasi Pengguna</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl">
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Username</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="username" value="{{ old('username') }}">
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Password</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="password" name="password">
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Level</label>
                                    <div class="col-md-10">
                                        <select name="role[]" class="form-select select2" multiple>
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
