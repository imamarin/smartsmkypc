<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Raport 1</title>
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
			padding: 8px;
		}

		table#ket tr td,
		table#ket tr th {
			border: #000000 solid 1px;
			border-right: none;
			border-bottom: none;
		}

		body {
			font-size: 12px;
		}
	</style>

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
</head>

<body>
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
			<b>A. Nilai Akademik</b>
			<br />
			<table style="width:100%;" id="nilai" cellspacing="0">
				<thead>
					<tr>
						<th align="center" style="width:4%;">No</th>
						<th valign="middle" align="center" style="width: 22%;">MATA PELAJARAN</th>
						<th align="center" style="width:4%;">Pengetahuan</th>
						<th align="center" style="width:4%;">Keterampilan</th>
						<th align="center" style="width:4%;">Nilai Akhir</th>
						<th align="center" style="width:4%;">Predikat</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="6"><b>A. Muatan Nasional</b></td>
					</tr>
					@php
					$n = 1;
					$arrmatpel = array();
                    // dd($kkm);
                    @endphp
					@foreach ($kelompok_matpel_A as $row1)
						<tr>
							<td align="center" style="width:3%;">{{ $n }}</td>
							<td>{{ Casef($row1->matpel->matpel) }}</td>
							@php
                            if(isset($kkm[$row1->kode_matpel][$row1->nip])){
                                $nilai_kkm = $kkm[$row1->kode_matpel][$row1->nip];
                            }else{
								$nilai_kkm = 0;
                            }
                            
							if (isset($pengetahuan[$row1->kode_matpel][$row->nisn][$row1->nip])) {
								$pengetahuanA = $pengetahuan[$row1->kode_matpel][$row->nisn][$row1->nip];
							} else {
								$pengetahuanA = 0;
							}

                            if (isset($keterampilan[$row1->kode_matpel][$row->nisn][$row1->nip])) {
								$keterampilanA = $keterampilan[$row1->kode_matpel][$row->nisn][$row1->nip];
							} else {
								$keterampilanA = 0;
							}

                            $nilbp = $bobot_pengetahuan[$row1->kode_matpel][$row1->nip] ?? 0;
                            $nilbk = $bobot_pengetahuan[$row1->kode_matpel][$row1->nip] ?? 0;

                            $nilaiakhir = ($pengetahuanA * $nilbp) + ($keterampilanA * $nilbk);
                            $nilaiakhir = $nilaiakhir / 100;

                            if ($nilaiakhir >= 95) {
                                $predikat = "A+";
                            } else if ($nilaiakhir >= 90) {
                                $predikat = "A";
                            } else if ($nilaiakhir >= 85) {
                                $predikat = "A-";
                            } else if ($nilaiakhir >= 80) {
                                $predikat = "B+";
                            } else if ($nilaiakhir >= 75) {
                                $predikat = "B";
                            } else if ($nilaiakhir >= 70) {
                                $predikat = "B-";
								$arrmatpel[] = Casef($row1->matpel->matpel);
                            } else if ($nilaiakhir >= 60) {
                                $predikat = "C";
								$arrmatpel[] = Casef($row1->matpel->matpel);
                            } else {
                                $predikat = "D";
                                $arrmatpel[] = Casef($row1->matpel->matpel);
                            }

							@endphp

							<td align="center">{{ $pengetahuanA }}</td>
							<td align="center">{{ $keterampilanA }}</td>
							<td align="center">{{ ceil($nilaiakhir) }}</td>
							<td align="center">{{ $predikat }}</td>
					@php
						$n++;
					@endphp
					@endforeach
                    <tr>
						<td colspan="6"><b>B. Muatan Kewilayahan</b></td>
					</tr>
                    <tr>
						<td colspan="6"><b>C. Muatan Peminatan Kejuruan</b></td>
					</tr>
                    <tr>
						<td colspan="6"><b>C1. Dasar Bidang Keahlian</b></td>
					</tr>
					@php
					$n = 1;
                    @endphp
					@foreach ($kelompok_matpel_B as $row1)
						<tr>
							<td align="center" style="width:3%;">{{ $n }}</td>
							<td>{{ Casef($row1->matpel->matpel) }}</td>
							@php
                            if(isset($kkm[$row1->kode_matpel][$row1->nip])){
                                $nilai_kkm = $kkm[$row1->kode_matpel][$row1->nip];
                            }else{
								$nilai_kkm = 0;
                            }
                            
							if (isset($pengetahuan[$row1->kode_matpel][$row->nisn][$row1->nip])) {
								$pengetahuan = $pengetahuan[$row1->kode_matpel][$row->nisn][$row1->nip];
							} else {
								$pengetahuan = 0;
							}

                            if (isset($keterampilan[$row1->kode_matpel][$row->nisn][$row1->nip])) {
								$keterampilan = $keterampilan[$row1->kode_matpel][$row->nisn][$row1->nip];
							} else {
								$keterampilan = 0;
							}

                            $nilbp = $bobot_pengetahuan[$row1->kode_matpel][$row1->nip] ?? 0;
                            $nilbk = $bobot_keterampilan[$row1->kode_matpel][$row1->nip] ?? 0;

                            $nilaiakhir = ($pengetahuan * $nilbp) + ($keterampilan * $nilbk);
                            $nilaiakhir = $nilaiakhir / 100;

                            if ($nilaiakhir >= 95) {
                                $predikat = "A+";
                            } else if ($nilaiakhir >= 90) {
                                $predikat = "A";
                            } else if ($nilaiakhir >= 85) {
                                $predikat = "A-";
                            } else if ($nilaiakhir >= 80) {
                                $predikat = "B+";
                            } else if ($nilaiakhir >= 75) {
                                $predikat = "B";
                            } else if ($nilaiakhir >= 70) {
                                $predikat = "B-";
								$arrmatpel[] = Casef($row1->matpel->matpel);
                            } else if ($nilaiakhir >= 60) {
                                $predikat = "C";
								$arrmatpel[] = Casef($row1->matpel->matpel);
                            } else {
                                $predikat = "D";
                                $arrmatpel[] = Casef($row1->matpel->matpel);
                            }

							@endphp

							<td align="center">{{ $pengetahuan }}</td>
							<td align="center">{{ $keterampilan }}</td>
							<td align="center">{{ ceil($nilaiakhir) }}</td>
							<td align="center">{{ $predikat }}</td>
					@php
						$n++;
					@endphp
					@endforeach
					
                    <tr>
						<td colspan="6"><b>C1. Dasar Bidang Keahlian</b></td>
					</tr>
					@php
					$n = 1;
                    @endphp
					@foreach ($kelompok_matpel_C as $row1)
						<tr>
							<td align="center" style="width:3%;">{{ $n }}</td>
							<td>{{ Casef($row1->matpel->matpel) }}</td>
							@php
                            if(isset($kkm[$row1->kode_matpel][$row1->nip])){
                                $nilai_kkm = $kkm[$row1->kode_matpel][$row1->nip];
                            }else{
								$nilai_kkm = 0;
                            }
                            
							if (isset($pengetahuan[$row1->kode_matpel][$row->nisn][$row1->nip])) {
								$pengetahuan = $pengetahuan[$row1->kode_matpel][$row->nisn][$row1->nip];
							} else {
								$pengetahuan = 0;
							}

                            if (isset($keterampilan[$row1->kode_matpel][$row->nisn][$row1->nip])) {
								$keterampilan = $keterampilan[$row1->kode_matpel][$row->nisn][$row1->nip];
							} else {
								$keterampilan = 0;
							}

                            $nilbp = $bobot_pengetahuan[$row1->kode_matpel][$row1->nip] ?? 0;
                            $nilbk = $bobot_keterampilan[$row1->kode_matpel][$row1->nip] ?? 0;

                            $nilaiakhir = ($pengetahuan * $nilbp) + ($keterampilan * $nilbk);
                            $nilaiakhir = $nilaiakhir / 100;

                            if ($nilaiakhir >= 95) {
                                $predikat = "A+";
                            } else if ($nilaiakhir >= 90) {
                                $predikat = "A";
                            } else if ($nilaiakhir >= 85) {
                                $predikat = "A-";
                            } else if ($nilaiakhir >= 80) {
                                $predikat = "B+";
                            } else if ($nilaiakhir >= 75) {
                                $predikat = "B";
                            } else if ($nilaiakhir >= 70) {
                                $predikat = "B-";
								$arrmatpel[] = Casef($row1->matpel->matpel);
                            } else if ($nilaiakhir >= 60) {
                                $predikat = "C";
								$arrmatpel[] = Casef($row1->matpel->matpel);
                            } else {
                                $predikat = "D";
                                $arrmatpel[] = Casef($row1->matpel->matpel);
                            }

							@endphp

							<td align="center">{{ $pengetahuan }}</td>
							<td align="center">{{ $keterampilan }}</td>
							<td align="center">{{ ceil($nilaiakhir) }}</td>
							<td align="center">{{ $predikat }}</td>
					@php
						$n++;
					@endphp
					@endforeach
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<br>
			<b>B. Catatan Akademik</b>
			<table id="nilai" style="width:100%;height:100px;">
				<tr>
					<td valign="top">
						<label>
							Ananda perlu meningkatkan kompetensi pengetahuan dan keterampilan pada mata pelajaran {{ implode(", ", $arrmatpel) }} sebagai
							bekal pembelajaran kompetensi kejuruan di tingkat berikutnya.
						</label>
					</td>
				</tr>
			</table>
		</div>
	@endforeach
</body>

</html>