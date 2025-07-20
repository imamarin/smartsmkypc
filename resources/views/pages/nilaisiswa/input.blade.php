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
                <h4 class="mb-0">Input Nilai Siswa</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Input Nilai Siswa</li>
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
                    <h4 class="card-title">{{ strtoupper("Nilai ".$nilaisiswa->kategori) }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <label for="kelas" class="form-label">Kelas:</label><br>
                            {{ $nilaisiswa->kelas->kelas }}<br>
                            <label for="kelas" class="form-label mt-2">Mata Pelajaran:</label><br>
                            {{ $nilaisiswa->matpel->matpel }}
                        </div>
                        <div class="col-6">
                            <label for="kelas" class="form-label">Tanggal Pelaksanaan:</label><br>
                            {{ $nilaisiswa->tanggal_pelaksanaan}}<br>
                            <label for="kelas" class="form-label mt-2">Keterangan:</label><br>
                            {{ $nilaisiswa->keterangan }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title text-start">Input Nilai Siswa</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="{{ route('nilai-siswa.simpan',['kategori'=>$nilaisiswa->kategori,'id'=>Crypt::encrypt($nilaisiswa->id)]) }}" method="post">
                            @csrf
                            <table class="table table-striped display nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nisn</th>
                                        <th>Nama Siswa</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Nilai</th>
                                        @if($nilaisiswakurmer)
                                        <th>Capaian</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rombel as $key => $subject)
                                        <tr>
                                            <td>{{ $loop->iteration }} </td>
                                            <td>{{ $subject->siswa->nisn_dapodik }}</td>
                                            <td>{{ $subject->siswa->nama }}</td>
                                            <td>{{ $subject->siswa->jenis_kelamin }}</td>
                                            <td>
                                                <input type="number" name="nilai[{{ $subject->nisn }}]" id="nilai_{{ $subject->nisn }}" value="{{ $nilai[$subject->nisn] ?? old('nilai.'.$subject->nisn) }}" class="form-control" min="0" max="100">
                                            </td>
                                            @if($nilaisiswakurmer && isset($nilai[$subject->nisn]))
                                            <td>
                                                @if ($nilai[$subject->nisn] <= $nilaisiswakurmer->tp->bt1)
                                                    Belum Tuntas, Remedial seluruh bagian
                                                @elseif ($nilai[$subject->nisn] <= $nilaisiswakurmer->tp->bt2)
                                                    Belum Tuntas, remedial sebagian
                                                @elseif ($nilai[$subject->nisn] <= $nilaisiswakurmer->tp->bt2)
                                                    Tuntas, Perlu pengayaan
                                                @else
                                                    Tuntas
                                                @endif
                                                {{-- @if ($nilaisiswakurmer->tp->t1 <= $nilai[$subject->nisn])
                                                    Siswa sudah mencapai kompetensi: {{ $nilaisiswakurmer->tp->tujuan }}
                                                @else
                                                    Siswa belum mencapai kompetensi: {{ $nilaisiswakurmer->tp->tujuan }}
                                                @endif --}}
                                            </td>
                                            @else
                                            <td>-</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
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
