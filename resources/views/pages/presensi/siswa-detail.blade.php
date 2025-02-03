@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Detail Presensi Siswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Detail Presensi Siswa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="col">
                        <h4 class="card-title">Detail Presensi Siswa</h4>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            Mata Pelajaran: {{ $matpel }}
                            <hr>
                            Kelas: {{ $kelas }}
                            <hr>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered " id="exapmle">
                            <thead>
                                <tr>
                                    <th rowspan="2">#</th>
                                    <th rowspan="2">Nisn</th>
                                    <th rowspan="2">Nama</th>
                                    <th colspan="{{ count($tanggal_presensi) }}" class="text-center">Tanggal Presensi</th>
                                    <th colspan="4" class="text-center">Total Kehadiran</th>
                                </tr>
                                <tr>
                                    {{-- <th>#</th>
                                    <th>Nisn</th>
                                    <th>Nama</th> --}}
                                    @foreach($tanggal_presensi as $item)
                                    <th>
                                        <span class="badge bg-success p-1">{{ date('j/n', strtotime($item)) }}</span>
                                    </th>
                                    @endforeach
                                    <th>H</th>
                                    <th>S</th>
                                    <th>I</th>
                                    <th>A</th>
                                </tr>
                            </thead>
                            <tbody>
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
                                        @foreach($tanggal_presensi as $item)
                                        @php
                                            $ket = $presensi_siswa[$item][$subject->nisn];
                                            if($ket == 'h'){
                                                $hadir++;
                                            }elseif($ket == 's'){
                                                $sakit++;
                                            }elseif($ket == 'i'){
                                                $izin++;
                                            }else{
                                                $alfa++;
                                            }
                                        @endphp
                                        <td>{{ $presensi_siswa[$item][$subject->nisn] }}</td>
                                        @endforeach
                                        <td>{{ $hadir }}</td>
                                        <td>{{ $sakit }}</td>
                                        <td>{{ $izin }}</td>
                                        <td>{{ $alfa }}</td>
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
<script>
    
</script>
@endpush
