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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body bg bg-light" style="border: 2px solid rgb(31, 177, 188)">
                    <div class="row">
                        <div class="col-3">
                            <label for="kelas" class="form-label">Kelas:</label><br>
                            {{ $nilairaport->kelas->kelas }}<br>
                        </div>
                        <div class="col-3">
                            <label for="kelas" class="form-label mt-2">Mata Pelajaran:</label><br>
                            {{ $nilairaport->matpel->matpel }}
                        </div>
                        <div class="col-3">
                            <label for="kelas" class="form-label">Semester:</label><br>
                            {{ $nilairaport->semester}}<br>
                        </div>
                        <div class="col-3">
                            <label for="kelas" class="form-label mt-2">Tahun Ajaran:</label><br>
                            {{ $nilairaport->tahunajaran->awal_tahun_ajaran }} / {{ $nilairaport->tahunajaran->akhir_tahun_ajaran }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between" style="background-color: rgb(31, 177, 188)">
                    <h4 class="card-title text-start text-white">Input Nilai Raport</h4>
                    <div>
                        <a href="{{ route('detail-nilai-raport.export', $id) }}" class="btn btn-sm btn-success">Eksport Excel</a>
                        <a href="#" class="btn btn-sm btn-secondary me-2"  data-bs-toggle="modal" data-bs-target="#importModal" id="importNilaiRaport">Import Nilai</a>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="{{ route('detail-nilai-raport.store', $id) }}" method="post">
                            @csrf
                            <table class="table table-striped display nowrap">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        <th rowspan="2">Nisn</th>
                                        <th rowspan="2">Nama Siswa</th>
                                        <th rowspan="2">Nilai Akhir</th>
                                        <th rowspan="2">Hasil Capaian Pembelajaran</th>
                                        {{-- <th colspan="2">Hasil Ketercapaian</th> --}}
                                    </tr>
                                    {{-- <tr>
                                        <th>Tercapai <input type="radio" name="pilih_capaian" class="btn btn-sm btn-success" onclick="checkAll('tercapai')"></th>
                                        <th>Tidak Tercapai <input type="radio" name="pilih_capaian" class="btn btn-sm btn-danger" onclick="checkAll('tidaktercapai')"></th>
                                    </tr> --}}
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $key => $subject)
                                        <tr>
                                            <td>{{ $loop->iteration }} </td>
                                            <td>{{ $subject->nisn }}</td>
                                            <td>{{ $subject->nama }}</td>
                                            <td style="width: 150px;">
                                                <input type="number" name="nilai_pengetahuan[{{ $subject->nisn }}]" id="nilai_pengetahuan_{{ $subject->nisn }}" value="{{ $nilai_pengetahuan[$subject->nisn] ?? old('nilai_pengetahuan.'.$subject->nisn) }}" class="form-control" min="0" max="100">
                                            </td>
                                            <td>
                                                @foreach ($cp as $key2 => $item)
                                                <div class="row pb-3 border border-top-0 border-left-0 border-end-0">
                                                    <div class="col-md-6">
                                                        {{ $item->capaian }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        @php
                                                            $abaikan = '';
                                                            $tercapai = '';
                                                            $tidak_tercapai = '';
                                                            if(isset($nilai_cp[$subject->nisn][$item->kode_cp])){
                                                                if($nilai_cp[$subject->nisn][$item->kode_cp] == 1){
                                                                    $tercapai = 'checked';
                                                                }else{
                                                                    $tercapai = '';
                                                                }

                                                                if($nilai_cp[$subject->nisn][$item->kode_cp] == 0){
                                                                    $tidak_tercapai = 'checked';
                                                                }else{
                                                                    $tidak_tercapai = '';
                                                                }
                                                            }else{
                                                                $abaikan = 'checked';
                                                            }
                                                        @endphp
                                                        <input type="radio" name="capaian[{{ $subject->nisn }}][{{ $item->kode_cp }}]" id="capaian['{{ $key }}']['{{ $key2 }}']" value="1" class="radio-tercapai siswa-{{ $key }} cp-{{ $key2 }}" {{ $tercapai }}> Tercapai<br>
                                                        <input type="radio" name="capaian[{{ $subject->nisn }}][{{ $item->kode_cp }}]" id="capaian['{{ $key }}']['{{ $key2 }}']" value="0" class="radio-tercapai siswa-{{ $key }} cp-{{ $key2 }}" {{ $tidak_tercapai }}> Tidak Tercapai<br>
                                                        <input type="radio" name="capaian[{{ $subject->nisn }}][{{ $item->kode_cp }}]" id="capaian['{{ $key }}']['{{ $key2 }}']" value="2" class="radio-tercapai siswa-{{ $key }} cp-{{ $key2 }}" {{ $abaikan }}> Abaikan<br>
                                                    </div>
                                                </div>
                                                @endforeach
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
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Nilai Raport Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="importForm" action="{{ route('nilai-raport.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" name="id" value="{{ Crypt::encrypt($nilairaport->id) }}">
                            <label for="file" class="form-label">Upload File (.xlsx)</label>
                            <input type="file" class="form-control" accept=".xlsx" id="file" name="file" required>
                        </div>

                        <div class="mb-3">
                            <span>Download Template .xlsx disini</span>
                            <a href="{{ route('nilairaport.template.import') }}">Template_Nilai_Raport_Siswa.xlsx</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function checkAll(type) {
        const selector = type === 'tercapai' ? '.radio-tercapai' : '.radio-tidaktercapai';
        const radios = document.querySelectorAll(selector);

        // Loop per siswa dan per cp
        const grouped = {};

        radios.forEach(radio => {
            const name = radio.name;
            if (!grouped[name]) {
                grouped[name] = radio;
            }
        });

        Object.values(grouped).forEach(radio => {
            radio.checked = true;
        });
    }
</script>
@endpush
