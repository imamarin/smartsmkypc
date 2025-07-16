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
                <h4 class="mb-0">Ranking Raport Siswa</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Ranking Raport Siswa</li>
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
                        <div class="col-12">
                            <label for="kelas" class="form-label">Kelas:</label><br>
                            {{ $kelas->kelas }}<br>
                            <label for="kelas" class="form-label">Semester:</label><br>
                            {{ $aktivasi->semester}}<br>
                            <label for="kelas" class="form-label mt-2">Tahun Ajaran:</label><br>
                            {{ $aktivasi->tahunajaran->awal_tahun_ajaran }} / {{ $aktivasi->tahunajaran->akhir_tahun_ajaran }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title text-start">Ranking Raport Siswa</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped display nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nisn</th>
                                    <th>Nama Siswa</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detailnilairaport as $key => $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }} </td>
                                        <td>{{ $subject->siswa->nisn_dapodik }}</td>
                                        <td>{{ $subject->siswa->nama }}</td>
                                        <td>{{ round($subject->rata) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

@endpush
