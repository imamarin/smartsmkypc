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
                        <div class="col-6">
                            <label for="kelas" class="form-label">Kelas:</label><br>
                            {{ $jadwal->kelas->kelas }}<br>
                            <label for="kelas" class="form-label mt-2">Mata Pelajaran:</label><br>
                            {{ $jadwal->matpel->matpel }}
                        </div>
                        <div class="col-6">
                            <label for="kelas" class="form-label">Jam Masuk:</label><br>
                            {{ $jadwal->jampel->mulai}}<br>
                            <label for="kelas" class="form-label mt-2">Jam Keluar:</label><br>
                            {{ $jadwal->waktu_keluar }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Catatan Pembelajaran</h4>
                    <a class="collapsed font-size-24 font-bold align-items-start" data-bs-toggle="collapse" href="#collapse-catatan">+</a>
                </div>
                <div class="card-body collapse d-md-block" id="collapse-catatan" data-bs-parent="#accordion">
                    <div class="row">
                        <div class="col overflow-auto custom-scrollbar" style="max-height: 300px;">
                            <ul class="list-group list-group-flush">
                                @if(isset($catatan))
                                    @foreach ($catatan as $item)   
                                        @if(!empty($item->catatan_pembelajaran)) 
                                        <li class="list-group-item d-flex flex-column">
                                            <i>{{ date('d F Y',strtotime($item->updated_at)) }}</i>
                                            <div class="catatan mt-2">
                                                {{ $item->catatan_pembelajaran }}
                                            </div>
                                        </li>
                                        @endif
                                    @endforeach
                                @endif
                              </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <form action="{{ route('masuk-mengajar.updateCatatan', Crypt::encrypt($jadwal->id)) }}" method="post">
                                @csrf
                                <textarea name="catatan" id="catatan" rows="3" class="form-control"></textarea>
                                <div class="d-flex justify-content-end mt-2">
                                    <input type="submit" value="SIMPAN" class="btn btn-primary">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title text-start">Presensi Siswa</h4>
                    <h4 class="card-title text-end">{{ $tanggal }}</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    @if(!isset($presensi))
                    <h4 class="card-title blink text-center">Anda belum melakukan presensi siswa</h4>
                    @endif 
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
                                    @foreach ($siswa as $key => $subject)
                                        @php
                                        if($subject)   
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }} </td>
                                            <td>{{ $subject->nisn }}</td>
                                            <td>{{ $subject->siswa->nama }}</td>
                                            <td>{{ $subject->siswa->jenis_kelamin }}</td>
                                            <td>
                                                <select name="presensi[{{ $subject->nisn }}]" id="" class="form-select">
                                                    <option value="h" {{ $subject->keterangan == 'h'?'selected':'' }}>Hadir</option>
                                                    <option value="s" {{ $subject->keterangan == 's'?'selected':'' }}>Sakit</option>
                                                    <option value="i" {{ $subject->keterangan == 'i'?'selected':'' }}>Izin</option>
                                                    <option value="a" {{ $subject->keterangan == 'a'?'selected':'' }}>Tanpa Keterangan</option>
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
                                                <textarea name="pokok_bahasan" rows="5" id="pokok_bahasan" class="form-control">{{ isset($presensi) ? $presensi->pokok_bahasan : '' }}</textarea>
                                            </div>
                                            <div class="mb-3 d-flex justify-content-end">
                                                <input type="hidden" name="idjadwalmengajar" value="{{ Crypt::encrypt($jadwal->id)}}">
                                                <input type="hidden" name="kode_matpel" value="{{ Crypt::encrypt($jadwal->kode_matpel) }}">
                                                <input type="hidden" name="nip" value="{{ Crypt::encrypt($jadwal->nip) }}">
                                                <input type="hidden" name="idkelas" value="{{ Crypt::encrypt($jadwal->idkelas) }}">
                                                <input type="hidden" name="tanggal" value="{{ Crypt::encrypt($tanggal)}}">
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
