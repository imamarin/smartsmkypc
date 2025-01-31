@extends('layouts.app')
@push('styles')
<style>
    .blink {
        animation: blinker 3s linear infinite;
        color: rgb(0, 0, 0);
        font-family: sans-serif;
    }
    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }
</style>
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Kehadiran Siswa</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kehadiran Siswa</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Selamat Mengajar</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="kelas" class="form-label">Kelas:</label><br>
                            {{ $jadwal->kelas->kelas }}<br>
                            <label for="kelas" class="form-label mt-2">Mata Pelajaran:</label><br>
                            {{ $jadwal->matpel->matpel }}
                        </div>
                        <div class="col-md-6">
                            <label for="kelas" class="form-label">Jam Masuk:</label><br>
                            {{ $jadwal->jampel->mulai}}<br>
                            <label for="kelas" class="form-label mt-2">Jam Keluar:</label><br>
                            {{ $jadwal->waktu_keluar }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Catatan Pembelajaran</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Presensi Siswa</h4>
                    <h4 class="card-title">Tanggal: {{ date('d-m-Y') }} Jam: {{ date('H:i:s') }}</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="{{ route('presensi.store') }}" method="post">
                            @csrf
                            <table class="table table-striped display nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nisn</th>
                                        <th>Nama Siswa</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Kehadiran</th>
                                        {{-- <th>Aksi</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jadwal->kelas->rombel as $subject)
                                        <tr>
                                            <td>{{ $loop->iteration }} </td>
                                            <td>{{ $subject->nisn }}</td>
                                            <td>{{ $subject->siswa->nama }}</td>
                                            <td>{{ $subject->siswa->jenis_kelamin }}</td>
                                            <td>
                                                <select name="presensi[{{ $subject->nisn }}]" id="" class="form-select">
                                                    <option value="h">Hadir</option>
                                                    <option value="s">Sakit</option>
                                                    <option value="i">Izin</option>
                                                    <option value="a">Tanpa Keterangan</option>
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <div class="mb-3">
                                                <label for="pokok_bahasan" class="form-label">Pokok Bahasan</label>
                                                <textarea name="pokok_bahasan" rows="5" id="pokok_bahasan" class="form-control"></textarea>
                                            </div>
                                            <div class="mb-3 d-flex justify-content-end">
                                                <input type="hidden" name="idjadwalmengajar" value="{{ Crypt::encrypt($jadwal->id)}}">
                                                <input type="hidden" name="kode_matpel" value="{{ Crypt::encrypt($jadwal->kode_matpel) }}">
                                                <input type="hidden" name="kode_guru" value="{{ Crypt::encrypt($jadwal->kode_guru) }}">
                                                <input type="hidden" name="idkelas" value="{{ Crypt::encrypt($jadwal->idkelas) }}">
                                                <input type="submit" value="Simpan Presensi" class="btn btn-primary">
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
