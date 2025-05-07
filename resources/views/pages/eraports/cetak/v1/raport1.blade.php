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

	<?php
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
	?>
</head>

<body>
	<?php

	foreach ($siswa as $row) {
	?>
		<div style="page-break-before:always;">
			<center>
				<h3 style="display:none;">LAPORAN HASIL PENILAIAN AKHIR SEMESTER <?php echo strtoupper($this->session->semesterraport); ?></h3>
				<table style="width:100%;border-bottom:solid 1px #000000;">
					<tr>
						<td>Nama Sekolah</td>
						<td>: <?php echo $dr->nmsekolah; ?></td>
						<td>Kelas</td>
						<td>: <?php echo $row->kdkelas; ?></td>
					</tr>
					<tr>
						<td>Nama Peserta Didik</td>
						<td>: <?php echo casef($row->nama); ?></td>
						<td>Semester</td>
						<td>: <?php echo $this->session->semesterraport; ?></td>
					</tr>
					<tr>
						<td>No. Induk / NISN</td>
						<td>: <?php echo $row->nisn3; ?></td>
						<td>Tahun Ajaran</td>
						<td>: <?php echo $this->session->tahunraport; ?></td>
					</tr>
				</table>

			</center><br />
			<b>A. Nilai Akademik</b>
			<br />
			<table style="width:100%;" id="nilai" cellspacing="0">
				<thead>
					<tr>
						<th align="center" style="width:4%;">No</th>
						<th valign="middle" align="center" style="width: 22%;">MATA PELAJARAN</th>
						<th align="center" style="width:4%;">Nilai Akhir</th>
						<th align="center" style="width:70%;">CAPAIAN KOMPETENSI</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="6"><b>Mata Pelajaran Umum</b></td>
					</tr>
					<?php
					$n = 1;
					$arrmatpel = array();
					foreach ($matpel_A as $row1) {
					?>
						<tr>
							<td align="center" style="width:3%;"><?php echo $n; ?></td>
							<td><?php echo Casef($row1->matpel); ?></td>
							<?php
							if (isset($pengetahuan[$row1->kdmatpel][$row->nisn])) {
								$pengetahuanA = $pengetahuan[$row1->kdmatpel][$row->nisn];
								$kkm = $pengetahuan[$row1->kdmatpel]['kkm'];
							} else {
								$pengetahuanA = 0;
								$kkm = 0;
							}
							?>
							<td align="center"><?= $pengetahuanA; ?></td>
							<td>
								<?php
								$txtcapaian = "";
								if(isset($capaian[$row1->kdmatpel])){
									foreach ($capaian[$row1->kdmatpel] as $key => $value) {
										# code...

										if (isset($capai[$row->nisn][$value->kdcp])) {
											if ($capai[$row->nisn][$value->kdcp] == "1") {
												if ($txtcapaian == "") {
													# code...
													$txtcapaian = $value->capaian;
												} else {
													$txtcapaian = $txtcapaian . "," . $value->capaian;
												}
											}
										}
									}
								}
								echo "Siswa sudah mencapai kompetensi: " . $txtcapaian . "<br>";
								?>

								<?php
								$txtcapaian = "";
								if(isset($capaian[$row1->kdmatpel])){
									foreach ($capaian[$row1->kdmatpel] as $key => $value) {
										# code...

										if (isset($capai[$row->nisn][$value->kdcp])) {
											if ($capai[$row->nisn][$value->kdcp] == "0") {
												if ($txtcapaian == "") {
													# code...
													$txtcapaian = $value->capaian;
												} else {
													$txtcapaian = $txtcapaian . "," . $value->capaian;
												}
											}
										}
									}
								}
								echo "Siswa belum mencapai kompetensi: " . $txtcapaian . "<br>";
								?>
							</td>
						</tr>
					<?php
						$n++;
					}
					?>
					<tr>
						<td colspan="6"><b>Mata Pelajaran Kejuruan</b></td>
					</tr>
					<?php
					$n = 1;
					foreach ($matpel_B as $row1) {
					?>
						<tr>
							<td align="center" style="width:3%;"><?php echo $n; ?></td>
							<td><?php echo Casef($row1->matpel); ?></td>
							<?php
							if (isset($pengetahuan[$row1->kdmatpel][$row->nisn])) {
								$pengetahuanB = $pengetahuan[$row1->kdmatpel][$row->nisn];
								$kkm = $pengetahuan[$row1->kdmatpel]['kkm'];
							} else {
								$pengetahuanB = 0;
								$kkm = 0;
							}
							?>
							<td align="center"><?= $pengetahuanB; ?></td>
							<td>
								<?php
								$txtcapaian = "";
								if(isset($capaian[$row1->kdmatpel])){
									foreach ($capaian[$row1->kdmatpel] as $key => $value) {
										# code...
										if (isset($capai[$row->nisn][$value->kdcp])) {
											if ($capai[$row->nisn][$value->kdcp] == "1") {
												if ($txtcapaian == "") {
													# code...
													$txtcapaian = $value->capaian;
												} else {
													$txtcapaian = $txtcapaian . "," . $value->capaian;
												}
											}
										}
									}
								}
								echo "Siswa sudah mencapai kompetensi: " . $txtcapaian . "<br>";
								// echo print_r($capaian);
								?>

								<?php
								$txtcapaian = "";
								if(isset($capaian[$row1->kdmatpel])){
									foreach ($capaian[$row1->kdmatpel] as $key => $value) {
										# code...
										if (isset($capai[$row->nisn][$value->kdcp])) {
											if ($capai[$row->nisn][$value->kdcp] == "0") {
												if ($txtcapaian == "") {
													# code...
													$txtcapaian = $value->capaian;
												} else {
													$txtcapaian = $txtcapaian . "," . $value->capaian;
												}
											}
										}
									}
								}
								echo "Siswa belum mencapai kompetensi: " . $txtcapaian;
								?>
							</td>
						</tr>
					<?php
						$n++;
					}
					?>

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
							Ananda perlu meningkatkan kompetensi pengetahuan dan keterampilan pada mata pelajaran <?= implode(", ", $arrmatpel) ?> sebagai
							bekal pembelajaran kompetensi kejuruan di tingkat berikutnya.
						</label>
					</td>
				</tr>
			</table>
		</div>
	<?php
	}
	?>
</body>

</html>