@extends('layouts.app')
@push('styles')
<style>
    .blink {
        animation: blinker 3s linear infinite;
        color: rgb(196, 61, 61);
        font-family: sans-serif;
    }
    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #6c757d; /* Warna abu-abu */
        border-radius: 10px;
    }

    .scroll-container {
        scrollbar-width: thin; /* Scrollbar lebih tipis */
        scrollbar-color: #888 transparent; /* Warna thumb dan track */
    }
</style>
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Presensi Harian Siswa</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Presensi Harian Siswa</li>
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
                    <h4 class="card-title">Presensi Harian Siswa</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <label for="kelas" class="form-label">Kelas:</label><br>
                            {{ $rombel[0]->kelas->kelas ?? '-' }}<br>
                            <label for="kelas" class="form-label mt-2">Tahun Ajaran:</label><br>
                            {{ $tahunajaran->awal_tahun_ajaran }} / {{ $tahunajaran->akhir_tahun_ajaran }}
                        </div>
                        <div class="col-6">
                            <label for="kelas" class="form-label">Tanggal:</label><br>
                            {{ date('d-m-Y', strtotime($tanggal)) }}<br>
                            <label for="kelas" class="form-label mt-2">Semester:</label><br>
                            {{ $tahunajaran->semester }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title text-start">Input Presensi Harian Siswa</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="{{ route('presensi-harian-siswa-store', Crypt::encrypt($idkelas."*".$tahunajaran->id."*".$tahunajaran->semester."*".$tanggal)) }}" method="post">
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
                                    @foreach ($rombel as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }} </td>
                                        <td>{{ $subject->nisn }}</td>
                                        <td>{{ $subject->siswa->nama }}</td>
                                        <td>{{ $subject->siswa->jenis_kelamin }}</td>
                                        <td>
                                            <select name="presensi[{{ $subject->nisn }}]" id="" class="form-select">
                                                <option value="h" {{ ($presensi[$subject->nisn] ?? '') == 'h'?'selected':'' }}>Hadir</option>
                                                <option value="s" {{ ($presensi[$subject->nisn] ?? '') == 's'?'selected':'' }}>Sakit</option>
                                                <option value="i" {{ ($presensi[$subject->nisn] ?? '') == 'i'?'selected':'' }}>Izin</option>
                                                <option value="a" {{ ($presensi[$subject->nisn] ?? '') == 'a'?'selected':'' }}>Tanpa Keterangan</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <div class="d-flex justify-content-end">
                                                <input type="submit" value="SIMPAN PRESENSI" class="btn btn-primary">
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
<script>
    const collapsed = document.querySelector(".collapsed");
    const collapse = document.querySelector("#collapse-catatan");
    
    if (collapse && collapse.classList.contains('show')){
        collapsed.innerText = "-"
    }

    collapsed.addEventListener('click', function(event){
        collapsed.innerText = collapsed.innerText === "+" ? "-" : "+";
    })
</script>
@endpush
