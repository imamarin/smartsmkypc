@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Pembayaran Keuangan Siswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pembayaran Keuangan Siswa</li>
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
                            <form action="{{ route('pembayaran-lain.siswa') }}" method="get">
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <label for="nisn" class="form-label text-white">Siswa</label>
                                        <select name="nisn" id="nisn" class="form-select select2">
                                            <option value="">Pilih Siswa</option>
                                            @foreach ($data_siswa as $item)
                                                <option value="{{ encryptSmart($item->nisn) }}" {{ $item->nisn == $siswa->nisn ? 'selected':'' }}>
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
                                    <th>Keuangan</th>
                                    <th style="text-align: end">Biaya (Rp)</th>
                                    <th>Sisa Tagihan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategori as $key => $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td style="text-align: end">{{ number_format($item->biaya,'0',',','.') }}</td>
                                        <td>
                                            @php
                                                $tagihan = $item->biaya;
                                            @endphp
                                            @if(isset($keuangan_paid[$siswa->nisn][$item->id]))
                                            @php
                                                $tagihan = $item->biaya - $keuangan_paid[$siswa->nisn][$item->id][1]
                                            @endphp
                                            {{ number_format($tagihan,'0',',','.') }}
                                            @else
                                            {{ number_format($item->biaya, '0',',','.') }}
                                            @endif
                                            
                                        </td>
                                        {{-- <td style="text-align: center;">{{ isset($keuangan_paid[$siswa->nisn][$item->id]) ? $keuangan_paid[$siswa->nisn][$item->id][1] : '-' }}</td> --}}
                                        <td>
                                            @if(isset($keuangan_paid[$siswa->nisn][$item->id]))
                                                @if($tagihan == 0)
                                                <div class="btn-group w-100">
                                                    <button type="button" class="btn btn-success btn-sm w-100">Sudah Bayar</button>
                                                    <button type="button" class="btn btn-success btn-sm dropdown-toggle dropdown-icon" data-bs-toggle="dropdown"></button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="{{ route('pembayaran-lain.detail', Crypt::encrypt($siswa->nisn.'*'.$item->id.'*'.$keuangan_paid[$siswa->nisn][$item->id][0].'*'.$item->nama)) }}">Lihat Detail Pembayaran</a>
                                                        <a class="dropdown-item" href="{{ route('pembayaran-lain.destroy', Crypt::encrypt($siswa->nisn.'*'.$item->id.'*'.$keuangan_paid[$siswa->nisn][$item->id][0].'*'.$item->nama)) }}" data-confirm-delete="true">Batalkan</a>
                                                    </div>
                                                </div>
                                                @elseif($tagihan == $item->biaya)
                                                <div class="btn-group w-100">
                                                    <button class="btn btn-danger btn-sm w-100" 
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#addSubjectModal" 
                                                        data-id = "{{ Crypt::encrypt($siswa->nisn.'*'.$item->id.'*'.$item->biaya.'*'.$tagihan.'*'.$item->nama) }}"
                                                        data-nisn="{{ $siswa->nisn ?? '' }}"
                                                        data-nama="{{ $siswa->nama ?? '' }}"
                                                        data-kelas="{{ $siswa->kelas ?? '' }}"
                                                        data-tagihan="{{ $tagihan ?? '' }}"
                                                        data-biaya="{{ $item->biaya ?? '' }}"
                                                        data-nama-biaya="{{ $item->nama ?? '' }}">
                                                        Lakukan Pembayaran
                                                    </button>
                                                </div>
                                                @elseif($tagihan < $item->biaya)
                                                <div class="btn-group w-100">
                                                    <button type="button" class="btn btn-warning btn-sm w-100">Angsuran</button>
                                                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle dropdown-icon" data-bs-toggle="dropdown"></button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a type="button" class="dropdown-item"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#addSubjectModal" 
                                                            data-id = "{{ Crypt::encrypt($siswa->nisn.'*'.$item->id.'*'.$item->biaya.'*'.$tagihan.'*'.$item->nama) }}"
                                                            data-nisn="{{ $siswa->nisn ?? '' }}"
                                                            data-nama="{{ $siswa->nama ?? '' }}"
                                                            data-kelas="{{ $siswa->kelas ?? '' }}"
                                                            data-tagihan="{{ $tagihan ?? '' }}"
                                                            data-biaya="{{ $item->biaya ?? '' }}"
                                                            data-nama-biaya="{{ $item->nama ?? '' }}">Bayar Angsuran</a>
                                                        <a class="dropdown-item" href="{{ route('pembayaran-lain.detail', Crypt::encrypt($siswa->nisn.'*'.$item->id.'*'.$keuangan_paid[$siswa->nisn][$item->id][0].'*'.$item->nama)) }}">Lihat Detail Angsuran</a>
                                                    </div>
                                                </div>
                                                @endif
                                            @else
                                            <div class="btn-group w-100">
                                                <button class="btn btn-danger btn-sm w-100" data-bs-toggle="modal"
                                                    data-bs-target="#addSubjectModal" 
                                                    data-id = "{{ Crypt::encrypt($siswa->nisn.'*'.$item->id.'*'.$item->biaya.'*'.$tagihan.'*'.$item->nama) }}"
                                                    data-nisn="{{ $siswa->nisn ?? '' }}"
                                                    data-nama="{{ $siswa->nama ?? '' }}"
                                                    data-kelas="{{ $siswa->kelas ?? '' }}"
                                                    data-tagihan="{{ $tagihan ?? '' }}"
                                                    data-biaya="{{ $item->biaya ?? '' }}"
                                                    data-nama-biaya="{{ $item->nama ?? '' }}">
                                                    Lakukan Pembayaran
                                                </button>
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
                <form id="subjectForm" action="{{ route('pembayaran-lain.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="subjectId" name="id">
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="mb-3">
                            <label for="biaya" class="form-label">Biaya: </label>
                            <span id="biaya"></span>
                        </div>
                        <div class="mb-3">
                            <label for="sisa" class="form-label">Sisa Pembayaran: </label>
                            <span id="tagihan"></span>
                        </div>
                        <div class="mb-3">
                            <label for="bayar" class="form-label">Nominal Pembayaran: </label>
                            <input type="text" name="bayar" id="bayar" class="form-control">
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
            const namaBiaya = button.getAttribute('data-nama-biaya');
            const dataBiaya = button.getAttribute('data-biaya');
            const dataTagihan = button.getAttribute('data-tagihan');
            const subjectId = document.getElementById('subjectId');
            const biaya = document.getElementById('biaya');
            const tagihan = document.getElementById('tagihan');
            const title = document.getElementById('addSubjectModalLabel')
            const bayar = document.getElementById('bayar');
            bayar.value = formatRupiah(dataTagihan);
            title.innerHTML = "Pembayaran "+namaBiaya;
            biaya.innerHTML = formatRupiah(dataBiaya);
            tagihan.innerHTML = formatRupiah(dataTagihan);
            subjectId.value = id;
        });

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        const input = document.getElementById('bayar');
            input.addEventListener('input', function (e) {
            let value = this.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            this.value = rupiah ? 'Rp ' + rupiah : '';
        });
    </script>
@endpush
