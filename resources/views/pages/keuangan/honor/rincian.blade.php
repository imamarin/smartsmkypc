@extends('layouts.app')
@push('styles')
<style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      background-color: rgba(255, 255, 255, 0.1);
      border: none;
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 20px rgba(0,0,0,0.2);
      color: #fff;
    }
    .card-header {
      background: linear-gradient(90deg, #263788, #6a11cb);
      color: #fff;
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    .table td, .table th {
      color: #000000;
      vertical-align: middle;
    }

    .table td:nth-child(2), .table th:nth-child(2) {
        text-align: right;
    }
    .table-striped > tbody > tr:nth-of-type(odd) {
      background-color: rgba(255, 255, 255, 0.05);
    }
    .total-card {
      background: linear-gradient(to right, #11998e, #38ef7d);
      color: white;
    }

    h2  {
        text-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }

    h5 {
        color: #fff;
        text-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
  </style>
@endpush

@section('content')
    <div class="container-fluid py-5">
        <div class="row mb-4">
            <div class="col">
            <h2 class="text-center">Rincian Honor Bulan {{ $honor->bulan }} Tahun {{ $honor->tahun }}</h2>
            </div>
        </div>

        <!-- Daftar Honor -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">Daftar Honor</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 50%">Jenis Honor</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Mengajar ({{ $detail->jml_jam }} Jam)</td>
                            <td>Rp. {{ number_format($detail->honor ?? '0',0,",",".") }}</td>
                        </tr>
                        <tr>
                            <td>Bonus Kehadiran</td>
                            <td>Rp. {{ number_format($detail->bonus_hdr ?? '0',0,",",".")  }}</td>
                        </tr>
                        <tr>
                            <td>Insentif Pengabdian</td>
                            <td>Rp. {{ number_format($detail->tun_jab_bak ?? '0',0,",",".") }}</td>
                        </tr>
                        <tr>
                            <td>Tunjangan Jabatan</td>
                            <td>Rp. {{ number_format($detail->tunjab ?? '0',0,",",".") }}</td>
                        </tr>
                        <tr>
                            <td>Subsidi Oprs/Sertifikasi</td>
                            <td>Rp. {{ number_format($detail->sub_non_ser ?? '0',0,",",".") }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total Honor</th>
                            <th>
                                @php
                                    $totalHonor = $detail->honor + $detail->bonus_hdr + $detail->tunj_jab_bakti + $detail->tunjab + $detail->sub_non_ser;   
                                @endphp
                                Rp. {{ number_format($totalHonor, 0, ",", ".") }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Potongan Honor -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Potongan Honor</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th style="width: 50%">Keterangan</th>
                        <th>Jumlah</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tabungan</td>
                            <td>Rp. {{ number_format($detail->tabungan ?? '0',0,",",".") }}</td>
                        </tr>
                        <tr>
                            <td>Arisan</td>
                            <td>Rp. {{ number_format($detail->arisan ?? '0',0,",",".") }}</td>
                        </tr>
                        <tr>
                            <td>Qurban</td>
                            <td>Rp. {{ number_format($detail->qurban ?? '0',0,",",".") }}</td>
                        </tr>
                        <tr>
                            <td>Bon Kas 1</td>
                            <td>Rp. {{ number_format($detail->kas_1 ?? '0',0,",",".") }}</td>
                        </tr>
                        <tr>
                            <td>Bon Kas 2</td>
                            <td>Rp. {{ number_format($detail->kas_2 ?? '0',0,",",".") }}</td>
                        </tr>
                        <tr>
                            <td>Lain-Lain</td>
                            <td>Rp. {{ number_format($detail->lainnya ?? '0',0,",",".") }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total Potongan</th>
                            <th>
                                @php
                                    $totalPotongan = $detail->tabungan + $detail->arisan + $detail->qurban + $detail->kas_1 + $detail->kas_2 + $detail->lainnya;
                                @endphp
                                Rp. {{ number_format($totalPotongan, 0, ",", ".") }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Total Honor Diterima -->
        <div class="card shadow-sm total-card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Total Honor Diterima</h5>
                <h4 class="mb-0">Rp. {{ number_format($totalHonor - $totalPotongan ?? '0',0,",",".") }}</h4>
            </div>
        </div>
    </div>

@endsection