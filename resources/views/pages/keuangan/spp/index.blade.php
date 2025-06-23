@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Pembayaran SPP</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pembayaran SPP</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header"  style="background-color: #494f4f ;">
                    <div class="row">
                        <div class="col">
                            <form action="{{ route('pembayaran-spp.siswa') }}" method="get">
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <label for="nisn" class="form-label text-white">Siswa</label>
                                        <select name="nisn" id="nisn" class="form-select select2">
                                            <option value="">Pilih Siswa</option>
                                            @foreach ($siswa as $item)
                                                <option value="{{ encryptSmart($item->nisn) }}" {{ $item->nisn == $nisn ? 'selected':'' }}>
                                                    {{ $item->nisn.' | '.strtoupper($item->nama).' | '. $item->kelas}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 d-flex align-items-end">
                                        <input type="submit" class="btn btn-primary" value="Tampilkan">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Bulan</th>
                                    <th>Tahun</th>
                                    <th style="text-align: center;">Tanggal Bayar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bulan_spp as $key => $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['bulan'] }}</td>
                                        <td>{{ $item['tahun'] }}</td>
                                        <td style="text-align: center;">{{ isset($bulan_spp_paid[$item['bulan'].' '.$item['tahun']]) ? $bulan_spp_paid[$item['bulan'].' '.$item['tahun']] : '-' }}</td>
                                        <td>
                                            @if(isset($bulan_spp_paid[$item['bulan'].' '.$item['tahun']]))
                                            <div class="btn-group w-100">
                                                <button type="button" class="btn btn-success btn-sm w-100">Sudah Bayar</button>
                                                <button type="button" class="btn btn-success btn-sm dropdown-toggle dropdown-icon" data-bs-toggle="dropdown"></button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('pembayaran-spp.destroy', Crypt::encrypt($nisn.'*'.$item['bulan'].'*'.$item['tahun'])) }}" data-confirm-delete="true">Batalkan</a>
                                                </div>
                                            </div>
                                            @else
                                            <div class="btn-group w-100">
                                                <button class="btn btn-danger btn-sm w-100" data-bs-toggle="modal"
                                                    data-bs-target="#addSubjectModal" 
                                                    data-id = "{{ Crypt::encrypt($nisn.'*'.$item['bulan'].'*'.$item['tahun']) }}"
                                                    data-nisn="{{ $nisn }}"
                                                    data-bulan="{{ $item['bulan'] }}"
                                                    data-tahun="{{ $item['tahun'] }}">
                                                    Lakukan Pembayaran
                                                </button>
                                                {{-- <a href="{{ route('pembayaran-spp.store', ['id' => Crypt::encrypt($nisn.'*'.$item['bulan'].'*'.$item['tahun'])]) }}" type="button" class="btn btn-danger w-100">Lakukan Pembayaran</button> --}}
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                {{-- @foreach ($spp as $subject)
                                    
                                @endforeach --}}
                            </tbody>
                        </table>
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
                    <h5 class="modal-title" id="addSubjectModalLabel">Pilih Nominal Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="subjectForm" action="{{ route('pembayaran-spp.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="subjectId" name="id">
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="mb-3">
                            <select name="biaya" id="biaya" class="form-select">
                                @foreach ($biaya as $key => $item)    
                                <option value="{{ $item->biaya }}">Rp. {{ number_format($item->biaya, 0, ',','.') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="submitBtn">BAYAR</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const modal = document.getElementById('addSubjectModal');
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const subjectId = document.getElementById('subjectId');
            subjectId.value = id;
        });
    </script>
@endpush
