<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Raport 2</title>
    <script>
        window.print();
    </script>
    <style>
        table#nilai,
        table#ket {
            border-right: #000000 solid 1px;
            border-bottom: #000000 solid 1px;
        }

        table#nilai tr td,
        table#nilai tr th {
            border: #000000 solid 1px;
            border-right: none;
            border-bottom: none;
            padding: 6px;
        }

        table#ket tr td,
        table#ket tr th {
            border: #000000 solid 1px;
            border-right: none;
            border-bottom: none;
            padding: 6px;
        }

        body {
            font-size: 12px;
        }
    </style>

    <style type="text/css" media="print">

    </style>
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

    @foreach ($siswa as $row)
    <div style="page-break-before:always;">
        <center>
            <h3 style="display:none;">LAPORAN HASIL PENILAIAN AKHIR SEMESTER {{ strtoupper($aktivasi->semester) }}</h3>
            <table style="width:100%;border-bottom:solid 1px #000000;">
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
        </center>
        <br />
        <b>C. Praktik Kerja Lapangan</b>
        <br />
        <table style="width:100%;" id="nilai" cellspacing="0">
            <thead>
                <tr>
                    <th valign="middle" align="center" style="width:5%;">No</th>
                    <th valign="middle" align="center"> Mitra DU/DI</th>
                    <th align="center">Lokasi</th>
                    <th valign="middle" align="center">Lamanya</th>
                    <th align="center">Nilai</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
        <br />
        <b>D. Ekstrakurikuler</b>
        <br />
        <table style="width:100%;" id="nilai" cellspacing="0">
            <thead>
                <tr>
                    <th valign="middle" align="center" style="width:5%;">No</th>
                    <th valign="middle" align="center" style="width:30%;"> Kegiatan Ekstrakurikuler</th>
                    <th align="center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @php
                $no = 1;
                @endphp
                @if ($row->nilaiekstrakurikuler->count() > 0) 
                    @foreach ($row->nilaiekstrakurikuler as $row3) 
                        <tr>
                            <td align="center"><?php echo $no; ?></td>
                            <td><?php echo $row3->ekstrakurikuler->nama; ?></td>
                            <td>
                                <?php
                                if ($row3->nilai == 1) {
                                    echo "Peserta didik cukup dalam mengikuti kegiatan";
                                } else if ($row3->nilai  == 2) {
                                    echo "Peserta didik baik dan aktif dalam mengikuti kegiatan";
                                } else if ($row3->nilai  == 3) {
                                    echo "Peserta didik Sangat aktif dalam mengikuti kegiatan";
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                        </tr>
                        @php
                        $no++;
                        @endphp
                    @endforeach
                @else
                    <tr>
                        <td align="center">1</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td align="center">2</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td align="center">3</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <br />
        <b>E. Ketidakhadiran</b>
        <br />
        <table id="ket" cellspacing="0" style="width:30%;">
            <tr>
                <td align="center">1</td>
                <td>Sakit</td>
                <td align="center">{{ isset($row->absensiraport[0]->sakit) ? $row->absensiraport[0]->sakit : "-" }}</td>
            </tr>
            <tr>
                <td align="center">2</td>
                <td>Izin</td>
                <td align="center">{{ isset($row->absensiraport[0]->izin) ? $row->absensiraport[0]->izin : "-" }}</td>
            </tr>
            <tr>
                <td align="center">3</td>
                <td>Tanpa Keterangan</td>
                <td align="center">{{ isset($row->absensiraport[0]->alfa) ? $row->absensiraport[0]->alfa : "-" }}</td>
            </tr>
        </table>

        @php
        $kls = explode(" ", $row->rombel[0]->kelas->kelas);
        @endphp
        @if ($aktivasi->semester == "genap" && strtolower($kls[0]) == "xii")
            <br />
            <br />
            Keputusan :<br />
            Berdasarkan hasil yang dicapai pada semester 1 sampai 6, peserta didik ditetapkan
            <br />
            <b>Lulus / <strike>Tidak Lulus</strike></b>
        @else
            @if ($aktivasi->semester == "genap" && $row->kenaikankelas->count() > 0) 
                <br>
                <b>F. Kenaikan Kelas</b>
                <table id="nilai" style="width:100%;">
                    <tr>
                        <td>
                            @if ($row->kenaikankelas[0]->keterangan == 1)
                                @if (strtolower($kls[0]) == "x")
                                        Naik ke kelas XI ( Sebelas )<br />
                                @elseif (strtolower($kls[0]) == "xi")
                                    Naik ke kelas XII ( Dua belas )<br />
                                @endif
                            @else
                                @if (strtolower($kls[0]) == "x")
                                    Tinggal di kelas X ( Sepuluh )<br />
                                @elseif (strtolower($kls[0]) == "xi")
                                    Tinggal di kelas XI ( Sebelas )<br />
                                @endif
                            @endif
                        </td>
                    </tr>
                </table>
    
            @endif
        @endif
        <br />
        <br />

        <br />
        <br />
        <table style="width:100%;" border="0">
            <tr>
                <td valign="top">
                    <table>
                        <tr>
                            <td>
                                Mengetahui: <br />
                                Orang Tua/Wali<br />
                                <br />
                                <br />
                                <br />
                                ............................
                            </td>
                        </tr>
                    </table>
                </td>
                <td valign="top">
                    <table align="right">
                        <tr>
                            <td>
                                @php
                                $arrNamaBulan = array("01" => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April", "05" => "Mei", "06" => "Juni", "07" => "Juli", "08" => "Agustus", "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember");
                                $tgl = explode("-", $aktivasi->tanggal_terima_raport);
                                @endphp
                                Tasikmalaya, {{ $tgl[2] . " " . $arrNamaBulan[$tgl[1]] . " " . $tgl[0] }}<br />
                                Wali Kelas {{ $walikelas->kelas->kelas }}
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
            @if ($aktivasi->semester == "genap")
                <tr>

                    <td colspan="2" align="center">
                        <table>
                            <tr>
                                <td>
                                    Mengetahui: <br />
                                    Kepala Sekolah<br />
                                    <br />
                                    <br />
                                    <br />
                                    {{ strtoupper($aktivasi->kepala_sekolah) }}<br />
                                    NIP:-
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>
            @endif
        </table>
    </div>
    @endforeach
</body>

</html>