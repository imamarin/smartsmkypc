<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
?>


<style>
	table#nilai {
		border-right: #000000 solid 1px;
		border-bottom: #000000 solid 1px;
	}

	table#nilai tr td,
	table#nilai tr th {
		border: #000000 solid 1px;
		border-right: none;
		border-bottom: none;
		font-family: Arial, Helvetica, sans-serif;
	}

	body {
		font-family: Arial, Helvetica, sans-serif;
	}

	label#merah {
		font-weight: bold;
		color: darkred;
	}
</style>
<html>

<head>
	<title>Transkrip Nilai</title>
</head>

<body>
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

@foreach ($siswa as $row)
    <div style="page-break-after:always;background-image:url('');background-repeat:no-repeat;background-size:100% 100%;margin-top:1%; padding-top:2%; padding-left:4%;padding-bottom:3%; padding-right:3%;">
        <br />
        <center>
        <h3>TRANSKRIP NILAI HASIL BELAJAR</h3>
        <br />
        <br />
        </center>
        <table style="width:100%;font-size:10px;">
            <tr>
                <td>Nama Sekolah</td>
                <td>: {{ $aktivasi->nama_sekolah }}</td>
                <td>Kelas</td>
                <td>: {{ $row->rombel[0]->kelas->kelas }}</td>
            </tr>
            <tr>
                <td>Nama Peserta Didik</td>
                <td>: {{ casef($row->nama) }}</td>
                <td>Semester</td>
                <td>: {{ $aktivasi->semester }}</td>
            </tr>
            <tr>
                <td>No. Induk / NISN</td>
                <td>: {{ $row->nisn }}</td>
                <td>Tahun Ajaran</td>
                <td>: {{ $aktivasi->tahunajaran->awal_tahun_ajaran }}/{{ $aktivasi->tahunajaran->akhir_tahun_ajaran }}</td>
            </tr>
        </table>
        <center>
            <table class="table table-bordered table-hover table-striped" cellspacing="0" style="font-size:11px;width:100%;" id="nilai">
                <thead>
                    <tr style="height:20px;">
                        <th rowspan="2" colspan="2" align="center" valign="middle" style="font-weight:bold;">MATA PELAJARAN</th>
                        <?php


                        $romawi = array('I', 'II', 'III', 'IV', 'V', 'VI');
                        $a = 0;
                        for ($a = 0; $a < count($romawi); $a++) {
                        ?>
                            <th align="center" style="font-weight:bold;">SEMESTER <?php echo $romawi[$a]; ?> </th>
                        <?php
                        }
                        ?>
                        <th align="center" rowspan="2" style="font-weight:bold;">NILAI UJIAN SEKOLAH</th>
                    </tr>
                    <tr style="font-weight:bold;height:20px;">
                        <?php
                        for ($a = 0; $a < count($romawi); $a++) {
                        ?>
                            <th align="center" valign="middle">Nilai Akhir</th>
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
                            <td align="center">
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
                        <td align="center">{{ $matpel->us->nilai ?? '-' }}</td>
                    </tr>
                    @endforeach
                    @for ($i = $row->matpel->count(); $i <= 29; $i++)
                        <tr style="height:20px;">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
            <br>
            <table class="table table-bordered table-hover table-striped" cellspacing="0" style="font-size:11px;width:100%;" id="nilai">
                <thead>
                    <tr style="height:20px;">
                        <td colspan="6" align="center"><b>KEHADIRAN SISWA</b></td>
                    </tr>
                    <tr style="height:20px;">
                        <?php
                        $romawi = array('I', 'II', 'III', 'IV', 'V', 'VI');
                        $a = 0;
                        for ($a = 0; $a < count($romawi); $a++) {
                        ?>
                            <th align="center" style="font-weight:bold;">SEMESTER <?php echo $romawi[$a]; ?> </th>
                        <?php
                        }
                        ?>
                    </tr>
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
                </thead>
                <tbody>
                </tbody>
            </table>
            <br>
            @if($row->nilaiprakerin)
            <table class="table table-bordered table-hover table-striped" cellspacing="0" id="nilai" style="font-size:11px;width:100%;">
                <tr>
                    <th>TEMPAT PRAKERIN</th>
                    <th>ALAMAT</th>
                    <th>TOTAL JAM PRAKERIN</th>
                    <th>NILAI</th>
                </tr>
                <tr>
                    <td>{{ $row->nilaiprakerin->dudi }}</td>
                    <td>{{ $row->nilaiprakerin->alamat }}</td>
                    <td align="center">{{ $row->nilaiprakerin->waktu }} Jam</td>
                    <td align="center">{{ $row->nilaiprakerin->nilai }}</td>
                </tr>
            </table>
            @endif
        </center>
        <br>

        @php
        $arrNamaBulan = array("01" => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April", "05" => "Mei", "06" => "Juni", "07" => "Juli", "08" => "Agustus", "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember");
        $tgl = explode("-", $aktivasi->tanggal_terima_raport);
        @endphp
        <table style="width:100%;font-size:11px" border="0">
            @if ($aktivasi->semester == "genap" AND $row->rombel[0]->kelas->tingkat == 'XII')
            <tr>
                <td valign="top">
                    <b>Keputusan :</b><br />
                    Berdasarkan hasil yang dicapai pada semester 1 dan 6, peserta didik ditetapkan
                    <br />
                    <b>Lulus / <strike>Tidak Lulus</strike></b>
                </td>
                <td valign="top">
                    <table align="right">
                        <tr>
                            <td style="font-size: 12px">
                                Tasikmalaya, {{ $tgl[2] . " " . $arrNamaBulan[$tgl[1]] . " " . $tgl[0] }}<br />
                                Kepala Sekolah<br />
                                <br />
                                <br />
                                <br />
                                <br />
                                {{ strtoupper($aktivasi->kepala_sekolah) }}<br />
                                NIP: {{ $aktivasi->nip_kepala_sekolah }}

                            </td>
                        </tr>
                    </table>
                </td>

            </tr>
            @else
            <tr>
                @if($aktivasi->semester == "genap" AND $row->kenaikankelas)
                <td valign="top">
                    @if($row->kenaikankelas->keterangan == 1)
                        @if($row->rombel[0]->kelas->tingkat == 'X')
                        <b>Keputusan :</b><br />
                        Berdasarkan hasil yang dicapai pada semester 1 dan 2, peserta didik ditetapkan
                        <br />
                        <br />
                        Naik kelas XI ( Sebelas )<br />
                        @elseif($row->rombel[0]->kelas->tingkat == 'XI')
                        <b>Keputusan :</b><br />
                        Berdasarkan hasil yang dicapai pada semester 3 dan 4, peserta didik ditetapkan
                        <br />
                        <br />
                        Naik kelas XII ( Dua Belas )<br />
                        @endif
                    @else
                        @if($row->rombel[0]->kelas->tingkat == 'X')
                        <b>Keputusan :</b><br />
                        Berdasarkan hasil yang dicapai pada semester 1 dan 2, peserta didik ditetapkan
                        <br />
                        <br />
                        Tinggal di kelas X ( Sepuluh )<br />
                        @elseif($row->rombel[0]->kelas->tingkat == 'XI')
                        <b>Keputusan :</b><br />
                        Berdasarkan hasil yang dicapai pada semester 3 dan 4, peserta didik ditetapkan
                        <br />
                        <br />

                        Tinggal di kelas XI ( Sebelas )<br />
                        @endif
                    @endif
                </td>
                @else
                <td valign="top">{{ $row->kenaikankelas  }}</td>
                @endif
                <td valign="top">
                    <table align="right">
                        <tr>
                            <td style="font-size: 12px">
                                Tasikmalaya, {{ $tgl[2] . " " . $arrNamaBulan[$tgl[1]] . " " . $tgl[0] }}<br />
                                Wali Kelas {{ $walikelas->kelas->kelas }}
                                <br />
                                <br />
                                <br />
                                <br />
                                <u>{{ $walikelas->staf->nama }}</u><br />
                                NIP: {{ $walikelas->staf->nip }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @endif
        </table>
    </div>
@endforeach