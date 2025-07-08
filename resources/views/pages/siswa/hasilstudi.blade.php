@extends('layouts.app')
@section('content')
    @push('styles')
        <style>
            /* .card {
                border: 1px solid #ccc;
                margin: 10px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            } */
            .card-header {
                cursor: pointer;
            }
            .card-body,
            .card-footer {
                padding: 10px;
                display: none;
            }
    </style>
    @endpush
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Hasil Studi Siswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('hasil-studi-siswa') }}">Hasil Studi Siswa</a></li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    
    <!-- end page title -->
    @php
    function Casef($n)
    {
        $kalimat = explode(" ", $n);
        $kalimatbaru = array();
        foreach ($kalimat as $kal) {
            $kata1 = ucfirst(strtolower($kal));
            $kalimatbaru[] = $kata1;
        }

        $newtext = implode(" ", $kalimatbaru);
        return $newtext;
    }
    @endphp
    @php
    $nis = "";
    $nama = "";
    @endphp
    <div class="row">
        <div class="col-12">
            @foreach ($siswa as $row)
            <div class="table-responsive">
                <table class="table table-striped table-bordered display nowrap">
                    <thead>
                        <tr style="height:20px;">
                            <th rowspan="2" colspan="2" style="text-align: center; vertical-align: middle">MATA PELAJARAN</th>
                            <?php


                            $romawi = array('I', 'II', 'III', 'IV', 'V', 'VI');
                            $a = 0;
                            for ($a = 0; $a < count($romawi); $a++) {
                            ?>
                                <th style="text-align: center; vertical-align: middle;">SEMESTER <?php echo $romawi[$a]; ?> </th>
                            <?php
                            }
                            ?>
                            <th rowspan="2" style="text-align: center; vertical-align: middle;">NILAI UJIAN SEKOLAH</th>
                        </tr>
                        <tr style="font-weight:bold;height:20px;">
                            <?php
                            for ($a = 0; $a < count($romawi); $a++) {
                            ?>
                                <th valign="middle" style="text-align:center;">Nilai Akhir</th>
                            <?php
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach($row->matpel as $matpel)
                        <tr style="height:20px;">
                            <td align="center">{{ $loop->iteration }}</td>
                            <td>{{ $matpel->matpel }}</td>
                            @for ($tahun = $row->masuk_tahun; $tahun <= $row->masuk_tahun + 2; $tahun++)
                                @php
                                    $semester = ['ganjil','genap'];
                                @endphp
                                @foreach ($semester as $semester)
                                <td style="text-align: center; vertical-align: middle;">
                                    @php
                                        $filtered = $matpel->hasil->first(function ($item) use($tahun, $semester) {
                                            return $item->tahun_ajaran == $tahun && $item->semester == $semester;
                                        });
                                        if($filtered){
                                            echo $filtered->nilai;
                                        }else{
                                            echo "-";
                                        }
                                    @endphp
                                </td>
                                @endforeach
                            @endfor
                            <td style="text-align: center; vertical-align: middle;">{{ $matpel->us->nilai ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <table class="table table-striped table-bordered display nowrap">
                    <thead>
                        <tr style="height:20px;">
                            <th colspan="6" style="text-align: center; vertical-align: middle;">KEHADIRAN SISWA</th>
                        </tr>
                        <tr style="height:20px;">
                            <?php
                            $romawi = array('I', 'II', 'III', 'IV', 'V', 'VI');
                            $a = 0;
                            for ($a = 0; $a < count($romawi); $a++) {
                            ?>
                                <th style="text-align: center; vertical-align: middle;">SEMESTER <?php echo $romawi[$a]; ?> </th>
                            <?php
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @for ($tahun = $row->masuk_tahun; $tahun <= $row->masuk_tahun + 2; $tahun++)
                                @php
                                    $semester = ['ganjil','genap'];
                                @endphp
                                @foreach ($semester as $semester)
                                <td align="center">
                                    @php
                                        $filtered = $row->absensi->first(function ($item) use($tahun, $semester) {
                                            return $item->tahun_ajaran == $tahun && $item->semester == $semester;
                                        });
                                        if($filtered){
                                            echo "Sakit=".$filtered->sakit.", Izin=".$filtered->izin.", Tanpa Keterangan=".$filtered->alfa;
                                        }else{
                                            echo "Sakit=0, Izin=0, Tanpa Keterangan=0";
                                        }
                                    @endphp
                                </td>
                                @endforeach
                            @endfor
                        </tr>
                    </tbody>
                </table>
                <br>
                @if($row->nilaiprakerin)
                <table class="table table-bordered table-hover table-striped" cellspacing="0">
                    <tr>
                        <th style="text-align: center; vertical-align: middle;">TEMPAT PRAKERIN</th>
                        <th style="text-align: center; vertical-align: middle;">ALAMAT</th>
                        <th style="text-align: center; vertical-align: middle;">TOTAL JAM PRAKERIN</th>
                        <th style="text-align: center; vertical-align: middle;">NILAI</th>
                    </tr>
                    <tr>
                        <td style="text-align: center; vertical-align: middle;">{{ $row->nilaiprakerin->dudi }}</td>
                        <td style="text-align: center; vertical-align: middle;">{{ $row->nilaiprakerin->alamat }}</td>
                        <td style="text-align: center; vertical-align: middle;">{{ $row->nilaiprakerin->waktu }} Jam</td>
                        <td style="text-align: center; vertical-align: middle;">{{ $row->nilaiprakerin->nilai }}</td>
                    </tr>
                </table>
                @endif
            </div>
            @endforeach
        </div>
    </div>
@endsection
