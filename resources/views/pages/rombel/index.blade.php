@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Data Rombel</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Rombel</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="col">
                    <h4 class="card-title">List Rombel</h4>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('data-rombel.export') }}" class="btn btn-primary me-2">Export Data</a>
                        <a href="#" class="btn btn-success me-2">Import Data</a>
                        <!-- Button to trigger modal -->
                        {{-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Tambah
                            Data</button> --}}
                    </div>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table display nowrap" id="example2">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Jumlah Siswa</th>
                                <th>Walikelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kelas as $key => $item)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $item->kelas }}</td>
                                <td>{{ $item->jurusan->jurusan }}</td>
                                <td>{{ $item->rombel->count() }}</td>
                                <td>
                                    {{ $item->walikelas[0]->staf->nama ?? '-'}}
                                </td>
                                <td>
                                    <a href="{{ route('data-rombel.showStudents', Crypt::encrypt($item->id.'*'.$item->idtahunajaran)) }}"
                                        class="btn btn-sm btn-info">Lihat Siswa</a>
                                </td>
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
                <h5 class="modal-title" id="addSubjectModalLabel">Input Rombel Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="subjectForm" action="{{ route('data-rombel.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="subjectId" name="id">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="mb-3">
                        <label for="idtahunajaran" class="form-label">Tahun Ajaran</label>
                        <select name="idtahunajaran" id="idtahunajaran" class="form-control select2">
                            @foreach ($tahunajaran as $item)
                            <option value="{{ $item->id }}">{{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kdkelas" class="form-label">Kelas</label>
                        <select name="kdkelas" id="kdkelas" class="form-control select2">
                            @foreach ($kelas as $item)
                            <option value="{{ $item->kdkelas }}">{{ $item->kdkelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nisn" class="form-label">Siswa</label>
                        <select name="nisn[]" id="nisn" class="form-control select2" multiple>
                            @foreach ($siswa as $item)
                            <option value="{{ $item->nisn }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
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
@push('scripts')

@endpush
