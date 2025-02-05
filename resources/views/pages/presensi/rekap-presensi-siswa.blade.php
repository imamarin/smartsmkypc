@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Rekap Presensi Siswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Rekap Presensi Siswa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('data-rekap-presensi-siswa') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-2">
                                <label for="idtahunajaran" class="form-label">Tahun Ajaran</label>
                                <select name="idtahunajaran" id="idtahunajaran" class="form-select select2">
                                    @foreach ($tahunajaran as $item)
                                        <option value="{{ $item->id }}" {{ $tahunajaran_selected == $item->id?'selected':'' }}>
                                            {{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="idkelas" class="form-label">Kelas</label>
                                <select name="idkelas" id="idkelas" class="form-select select2">
                                    @foreach ($kelas as $item)
                                        <option value="{{ $item->id }}" {{ $kelas_selected == $item->id?'selected':'' }}>
                                            {{ $item->kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="semester" class="form-label">Semester</label>
                                <select name="semester" id="semester" class="form-select select2">
                                    <option value="ganjil" {{ $semester_selected == 'ganjil'?'selected':'' }}>Ganjil</option>
                                    <option value="genap" {{ $semester_selected == 'genap'?'selected':'' }}>Genap</option>
                                </select>
                            </div>
                            <div class="col-2 d-flex align-items-end mb-1">
                                <input type="submit" class="btn btn-primary" value="Tampilkan Rekapan">
                            </div>
                        </div>
                    </form>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered display nowrap" id="exapmle">
                            <thead>
                                <tr>
                                    <th rowspan="3">#</th>
                                    <th rowspan="3">Nisn</th>
                                    <th rowspan="3">Nama</th>
                                    <th colspan="{{ isset($matpel_presensi)?count($matpel_presensi)*4:4 }}" class="text-center">Mata Pelajaran</th>
                                    <th rowspan="2" colspan="4" class="text-center">Total Kehadiran</th>
                                </tr>
                                <tr>
                                    @if(isset($matpel_presensi))
                                    @foreach($matpel_presensi as $value)
                                        <th colspan="4" class="text-center">{{ $value }}</th>
                                    @endforeach
                                    @else
                                        <th colspan="4" class="text-center">Mata Pelajaran</th>
                                    @endif
                                    
                                </tr>
                                <tr>
                                    @if(isset($matpel_presensi))
                                    @foreach($matpel_presensi as $value)
                                    <th>H</th>
                                    <th>S</th>
                                    <th>I</th>
                                    <th>A</th>
                                    @endforeach
                                    @else
                                    <th>H</th>
                                    <th>S</th>
                                    <th>I</th>
                                    <th>A</th>
                                    @endif
                                    <th>H</th>
                                    <th>S</th>
                                    <th>I</th>
                                    <th>A</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($siswa))
                                @foreach ($siswa as $subject)
                                    @php
                                        $hadir = 0;
                                        $sakit = 0;
                                        $izin = 0;
                                        $alfa = 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }} </td>
                                        <td>{{ $subject->nisn }}</td>
                                        <td>{{ $subject->siswa->nama }}</td>
                                        @foreach($matpel_presensi as $item)
                                        <td>{{ $presensi_siswa[$item][$subject->nisn]['h'] ?? 0 }}</td>
                                        <td>{{ $presensi_siswa[$item][$subject->nisn]['s'] ?? 0 }}</td>
                                        <td>{{ $presensi_siswa[$item][$subject->nisn]['i'] ?? 0 }}</td>
                                        <td>{{ $presensi_siswa[$item][$subject->nisn]['a'] ?? 0 }}</td>
                                        @endforeach
                                        <td>{{ $total_hadir[$subject->nisn] ?? 0 }}</td>
                                        <td>{{ $total_sakit[$subject->nisn] ?? 0 }}</td>
                                        <td>{{ $total_izin[$subject->nisn] ?? 0 }}</td>
                                        <td>{{ $total_alfa[$subject->nisn] ?? 0 }}</td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const tahunajaran = $('#idtahunajaran');
        const kelas = $('#idkelas');
        const semester = $('#semester');
        $('#idtahunajaran').on('change',function(event){
            let url = '{{ route('data-kelas.json-tahunajaran', ':id') }}'.replace(':id', tahunajaran.val());
            $.get(url, function(data,status){
                kelas.html("");
                if(data.data.length > 0){
                    semester.val(data.semester);
                    data.data.forEach(element => {
                        let option = new Option(element.kelas, element.id);
                        kelas.append(option);
                    });
                }
            })
        })
    </script>
@endpush
