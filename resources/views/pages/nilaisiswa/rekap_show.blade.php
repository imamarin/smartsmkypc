@extends('layouts.app')
@push('styles')
@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Rekap Pengolahan Nilai Siswa</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Rekap Pengolahan Nilai Siswa</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="col">
                    <div class="row">
                        <div class="col-md-6">
                            Mata Pelajaran: {{ $matpel->matpel }}
                            <hr>
                            Kelas: {{ $kelas->kelas }}
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end mb-3">
                        <!-- Button to trigger modal -->
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Ubah Persentase Nilai</button>
                    </div>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="table-responsive">
                        <table class="table display nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Nilai Tugas <br> ({{ $persen_tugas*100 }}%)</th>
                                    <th>Nilai Ujian Harian <br> ({{ $persen_harian*100 }}%)</th>
                                    <th>Nilai UTS <br> ({{ $persen_uts*100 }}%)</th>
                                    <th>Nilai UAS <br> ({{ $persen_uas*100 }}%)</th>
                                    <th>Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nilaisiswa as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nisn_dapodik }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ round($item->nilai_tugas) }}</td>
                                    <td>{{ round($item->nilai_harian) }}</td>
                                    <td>{{ round($item->nilai_uts) }}</td>
                                    <td>{{ round($item->nilai_uas) }}</td>
                                    <td>{{ round(($item->nilai_tugas*$persen_tugas)+($item->nilai_harian*$persen_harian)+($item->nilai_uts*$persen_uts)+($item->nilai_uas*$persen_uas)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                
                </div>
                
            </div>
        </div>
    </div>
</div>
<!-- Modal untuk Input Mata Pelajaran -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">Persentase Nilai Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formNilaiSiswa" action="{{ route('nilai-siswa.persentase.store', $id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger alert-dismissible" id="alert" style="display: none;">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <strong>Invalid!</strong> Akumulasi persentase harus bernilai 100.
                    </div>
                    <div class="mb-3">
                        <label for="tugas" class="form-label">Persentase Nilai Tugas</label>
                        <input type="number" name="tugas" id="tugas" class="form-control" min="0" max="100" value="{{ $persen_tugas*100 }}">
                    </div>
                    <div class="mb-3">
                        <label for="sumatif" class="form-label">Persentase Nilai Ujian Harian</label>
                        <input type="number" name="harian" id="harian" class="form-control" min="0" max="100" value="{{ $persen_harian*100 }}">
                    </div>
                    <div class="mb-3">
                        <label for="uts" class="form-label">Persentase Nilai UTS</label>
                        <input type="number" name="uts" id="uts" class="form-control" min="0" max="100" value="{{ $persen_uts*100 }}">
                    </div>
                    <div class="mb-3">
                        <label for="uas" class="form-label">Persentase Nilai UAS</label>
                        <input type="number" name="uas" id="uas" class="form-control" min="0" max="100" value="{{ $persen_uas*100 }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="submitBtn">Simpan</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    const submit = document.querySelector('#submitBtn');
    submit.addEventListener('click', function(e){
        const tugas = document.querySelector('#tugas');
        const harian = document.querySelector('#harian');
        const uts = document.querySelector('#uts');
        const uas = document.querySelector('#uas');
        const form = document.querySelector('#formNilaiSiswa');
        const alert = document.querySelector('#alert');
        
        jumlah = parseInt(tugas.value) + parseInt(harian.value) + parseInt(uts.value) + parseInt(uas.value);
        console.log(jumlah);
        
        if(jumlah == 100){
            form.submit();
        }else{
            alert.style.display = 'block';
        }
    })
</script>
@endpush
