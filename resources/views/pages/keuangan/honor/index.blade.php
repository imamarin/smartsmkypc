@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Honor Pegawai</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center" style="background-color: #494f4f ;">
                    <h4 class="card-title text-white">Data Honor Pengawai</h4>
                    <div class="col">
                        <div class="d-flex justify-content-end mb-3">
                            <!-- Button to trigger modal -->
                            @if(in_array('Tambah', $fiturMenu[$view]))
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Tambah
                                Data</button>
                            @endif
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Bulan</th>
                                    <th>Tahun</th>
                                    @if(in_array('Hapus', $fiturMenu[$view]))
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($honor as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->bulan }}</td>
                                        <td>{{ $subject->tahun }}</td>
                                        @if(in_array('Hapus', $fiturMenu[$view]))
                                        <td>
                                            @if(in_array('Rincian', $fiturMenu[$view]))
                                            <a href="{{ route('honorium-pegawai.rincian', Crypt::encrypt($subject->id)) }}"
                                                class="btn btn-info btn-sm">Rincian Honor Pegawai</a>
                                            @endif
                                            @if(in_array('Kelola', $fiturMenu[$view]))
                                            <a href="{{ route('honorium-pegawai.kelola', Crypt::encrypt($subject->id)) }}"
                                                class="btn btn-primary btn-sm">Kelola Honor Pegawai</a>
                                            @endif
                                            @if(in_array('Hapus', $fiturMenu[$view]))
                                            <a href="{{ route('honorium-pegawai.destroy', Crypt::encrypt($subject->id)) }}"
                                                class="btn btn-danger btn-sm" data-confirm-delete="true">Hapus</a>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Input Mata Pelajaran -->
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectModalLabel">Tambah Honorium Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="subjectForm" action="{{ route('honorium-pegawai.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select name="bulan" id="bulan" class="form-select select2" required>
                                <option value="Januari" {{ $bulan == '1' ? 'selected' : '' }}>Januari</option>
                                <option value="Februari" {{ $bulan == '2' ? 'selected' : '' }}>Februari</option>
                                <option value="Maret" {{ $bulan == '3' ? 'selected' : '' }}>Maret</option>
                                <option value="April" {{ $bulan == '4' ? 'selected' : '' }}>April</option>
                                <option value="Mei" {{ $bulan == '5' ? 'selected' : '' }}>Mei</option>
                                <option value="Juni" {{ $bulan == '6' ? 'selected' : '' }}>Juni</option>
                                <option value="Juli" {{ $bulan == '7' ? 'selected' : '' }}>Juli</option>
                                <option value="Agustus" {{ $bulan == '8' ? 'selected' : '' }}>Agustus</option>
                                <option value="September" {{ $bulan == '9' ? 'selected' : '' }}>September</option>
                                <option value="Oktober" {{ $bulan == '10' ? 'selected' : '' }}>Oktober</option>
                                <option value="November" {{ $bulan == '11' ? 'selected' : '' }}>November</option>
                                <option value="Desember" {{ $bulan == '12' ? 'selected' : '' }}>Desember</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <input type="number" class="form-control" id="tahun" name="tahun" min="1900" max="2099" step="1" value="{{ date('Y') }}" required placeholder="YYYY">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
