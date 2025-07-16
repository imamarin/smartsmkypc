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
                <div class="card-body">
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
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title text-start">Input Nilai Raport</h4>
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
                                        <th>Nilai Pengetahuan</th>
                                        <th>Nilai Ketrampilan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $key => $subject)
                                        <tr>
                                            <td>{{ $loop->iteration }} </td>
                                            <td>{{ $subject->nisn_dapodik }}</td>
                                            <td>{{ $subject->nama }}</td>
                                            <td>
                                                <input type="number" name="nilai_pengetahuan[{{ $subject->nisn }}]" id="nilai_pengetahuan_{{ $subject->nisn }}" value="{{ $nilai_pengetahuan[$subject->nisn] ?? old('nilai_pengetahuan.'.$subject->nisn) }}" class="form-control" min="0" max="100">
                                            </td>
                                            <td>
                                                <input type="number" name="nilai_keterampilan[{{ $subject->nisn }}]" id="nilai_keterampilan_{{ $subject->nisn }}" value="{{ $nilai_keterampilan[$subject->nisn] ?? old('nilai_keterampilan.'.$subject->nisn) }}" class="form-control" min="0" max="100">
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

@endsection

@push('scripts')

@endpush
