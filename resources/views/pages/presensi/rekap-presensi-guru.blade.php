@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Rekap Presensi Staf</h4>
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
                <div class="card-header"  style="background-color: #cbd931 ;">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <form action="{{ route('data-rekap-presensi-guru') }}" method="get">
                                {{-- @csrf --}}
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label for="idtahunajaran" class="form-label">Tahun Ajaran</label>
                                        <select name="idtahunajaran" id="idtahunajaran" class="form-select select2">
                                            @foreach ($tahunajaran as $item)
                                                <option value="{{ encryptSmart($item->id) }}" {{ $tahunajaran_selected == $item->id?'selected':'' }}>
                                                    {{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="semester" class="form-label">Semester</label>
                                        <select name="semester" id="semester" class="form-select select2">
                                            <option value="ganjil" {{ $semester_selected == 'ganjil'?'selected':'' }}>Ganjil</option>
                                            <option value="genap" {{ $semester_selected == 'genap'?'selected':'' }}>Genap</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 d-flex align-items-end mb-1">
                                        <input type="submit" class="btn btn-primary" value="Tampilkan Rekapan">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-6 d-flex align-items-end justify-content-end">
                            <a href="{{ route('data-rekap-presensi-guru.export', Crypt::encrypt($tahunajaran_selected.'*'.$semester_selected)) }}" class="btn btn-info me-2">Export Data</a>
                        </div>
                    </div>
                    
                    
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered display nowrap" id="exapmle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NIP</th>
                                    <th>Nama Staf</th>
                                    <th class="text-center">Persentase Kehadiran</th>
                                    @if(in_array('Detail Kehadiran', $fiturMenu[$view]))
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staf as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->nip }}</td>
                                        <td>{{ $subject->nama }}</td>
                                        <td>
                                            @php
                                            
                                                $jmlPertemuan = $jumlahPertemuan[$subject->nip] ?? 0;
                                                $totPertemuan = $totalPertemuan[$subject->nip] ?? 0;

                                                if( $jmlPertemuan > 0 && $totPertemuan > 0 ){
                                                    $persentasi_hadir = round($jumlahPertemuan[$subject->nip] / $totalPertemuan[$subject->nip] * 100);
                                                }else{
                                                    $persentasi_hadir = 0;
                                                }
                                            @endphp
                                            <div class="d-flex justify-content-center">
                                                <div class="progress w-50">
                                                    @php
                                                        if($persentasi_hadir > 80){
                                                            $bg = 'bg-success';
                                                        }elseif($persentasi_hadir > 60){
                                                            $bg = 'bg-warning';
                                                        }else{
                                                            $bg = 'bg-danger';
                                                        }
                                                    @endphp
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated {{ $bg }}" style="width:{{ $persentasi_hadir }}%">{{ $persentasi_hadir }}%</div>
                                                </div>
                                            </div>
                                        </td>
                                        @if(in_array('Detail Kehadiran', $fiturMenu[$view]))
                                        <td>
                                            <a href="{{ route('data-rekap-presensi-guru-detail', Crypt::encrypt($subject->nip."*".$tahunajaran_selected."*".$semester_selected)) }}" class="btn btn-sm btn-warning">Lihat Detail Kehadiran</a>
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

@endsection

@push('scripts')
@endpush
