@extends('layouts.app')
@push('styles')
<style>
</style>
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Input Nilai Raport Siswa</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Input Nilai Raport Siswa</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body bg bg-light" style="border: 2px solid rgb(31, 177, 188)">
                    <div class="row">
                        <div class="col-6">
                            <label for="kelas" class="form-label">Kelas:</label><br>
                            {{ $nilairaport->kelas->kelas }}<br>
                            <label for="kelas" class="form-label mt-2">Mata Pelajaran:</label><br>
                            {{ $nilairaport->matpel->matpel }}
                        </div>
                        <div class="col-6">
                            <label for="kelas" class="form-label">Semester:</label><br>
                            {{ $nilairaport->semester}}<br>
                            <label for="kelas" class="form-label mt-2">Tahun Ajaran:</label><br>
                            {{ $nilairaport->tahunajaran->awal_tahun_ajaran }} / {{ $nilairaport->tahunajaran->akhir_tahun_ajaran }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between" style="background-color: rgb(31, 177, 188)">
                    <h4 class="card-title text-start text-white">Input Nilai Raport</h4>
                    <div>
                        <a href="{{ route('detail-nilai-raport.export', $id) }}" class="btn btn-sm btn-success">Eksport Excel</a>
                        <a href="#" class="btn btn-sm btn-secondary me-2"  data-bs-toggle="modal" data-bs-target="#importModal" id="importNilaiRaport">Import Nilai</a>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="{{ route('detail-nilai-raport.store', $id) }}" method="post">
                            @csrf
                            <table class="table table-striped display nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nisn</th>
                                        <th>Nama Siswa</th>
                                        <th>Nilai Akhir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $key => $subject)
                                        <tr>
                                            <td>{{ $loop->iteration }} </td>
                                            <td>{{ $subject->nisn }}</td>
                                            <td>{{ $subject->nama }}</td>
                                            <td>
                                                <input type="number" name="nilai_pengetahuan[{{ $subject->nisn }}]" id="nilai_pengetahuan_{{ $subject->nisn }}" value="{{ $nilai_pengetahuan[$subject->nisn] ?? old('nilai_pengetahuan.'.$subject->nisn) }}" class="form-control" min="0" max="100">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6">
                                            <div class="mb-3 d-flex justify-content-end">
                                                <input type="submit" value="Simpan Nilai" class="btn btn-primary">
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Nilai Raport Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="importForm" action="{{ route('nilai-raport.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" name="id" value="{{ Crypt::encrypt($nilairaport->id) }}">
                            <label for="file" class="form-label">Upload File (.xlsx)</label>
                            <input type="file" class="form-control" accept=".xlsx" id="file" name="file" required>
                        </div>

                        <div class="mb-3">
                            <span>Download Template .xlsx disini</span>
                            <a href="{{ route('nilairaport.template.import') }}">Template_Nilai_Raport_Siswa.xlsx</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
