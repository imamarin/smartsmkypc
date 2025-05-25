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
	<title></title>
</head>

<body>
	@php
	$nis = "";
	$nama = "";
    @endphp

	@foreach ($siswa as $row)
		$nis = $row->nisn;
		$nama = $row->nama
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
						<tr>
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
						<tr style="font-weight:bold;">
							<?php
							for ($a = 0; $a < count($romawi); $a++) {
							?>
								<th align="center" valign="middle">Nilai Akhir</th>
								<!-- <th align="center" valign="middle">K</th> -->
							<?php
							}
							?>
						</tr>
					</thead>
					<tbody>
						<?php
						if (isset($_GET['hal'])) {
							$rec = $_GET['hal'];
						} else {
							$rec = 0;
						}

						$thn = array();
						$ks = array();
						$tahun = explode("/", $row->thnmasuk);
						$kelas = explode(" ", $row->kdkelas);
						$z = 0;
						$klz = "";
						for ($t = $tahun[0]; $t <= $tahun[0] + 2; $t++) {
							$tt = $t + 1;
							$th = $t . "/" . $tt;
							$queri_kelas = $this->db->query("SELECT siswakelas.kdkelas FROM siswakelas,settahunajaran WHERE siswakelas.idtahunajaran = settahunajaran.idtahunajaran AND settahunajaran.tahun='$th' AND siswakelas.nisn = '$nis'");
							if ($queri_kelas->num_rows() > 0) {
								$tk = $queri_kelas->row();
								$klz = $tk->kdkelas;
								array_push($ks, $tk->kdkelas);
							} else {
								if ($z == 0) {
									if (isset($kelas[2])) {
										$tk = "X " . $kelas[1] . " " . $kelas[2];
									} else {
										$tk = "X " . $kelas[1];
									}
								} elseif ($z == 1) {
									if (isset($kelas[2])) {
										$tk = "XI " . $kelas[1] . " " . $kelas[2];
									} else {
										$tk = "XI " . $kelas[1];
									}
								} elseif ($z == 2) {
									if (isset($kelas[2])) {
										$tk = "XII " . $kelas[1] . " " . $kelas[2];
									} else {
										$tk = "XII " . $kelas[1];
									}
								}
								array_push($ks, $klz);
							}

							$z++;
							// array_push($ks, $tk);
							array_push($thn, $th);
						}
						// Load database kedua
						// $db2 = $this->load->database('raport_db', TRUE);
						// $matpel1="SELECT DISTINCT(kelasmatpel.kdmatpel),matpel.nmmatpel,matpel.kelompok,matpel.id_matpel AS no FROM rapot2.matpel,rapot2.kelasmatpel,rapot2.siswakelas WHERE matpel.kdmatpel=kelasmatpel.kdmatpel AND kelasmatpel.kdkelas=siswakelas.kelas AND siswakelas.nisn='$row->nisn' AND ((kelasmatpel.tahun='$thn[0]' AND kelasmatpel.kdkelas='$ks[0]') OR (kelasmatpel.tahun='$thn[1]' AND kelasmatpel.kdkelas='$ks[1]') OR (kelasmatpel.tahun='$thn[2]' AND kelasmatpel.kdkelas='$ks[2]')) "; 

						$matpel2 = "SELECT DISTINCT(matpelkelas.kdmatpel),matpel.matpel as nmmatpel,matpel.kelompok,matpel.nourut AS no FROM datamaster.matpel,datamaster.matpelkelas,datamaster.siswakelas,datamaster.settahunajaran WHERE matpel.kdmatpel=matpelkelas.kdmatpel AND matpelkelas.kdkelas=siswakelas.kdkelas AND matpelkelas.idtahunajaran=settahunajaran.idtahunajaran AND siswakelas.idtahunajaran=settahunajaran.idtahunajaran AND siswakelas.nisn='$row->nisn' AND ((settahunajaran.tahun='$thn[0]' AND matpelkelas.kdkelas='$ks[0]') OR (settahunajaran.tahun='$thn[1]' AND matpelkelas.kdkelas='$ks[1]') OR (settahunajaran.tahun='$thn[2]' AND matpelkelas.kdkelas='$ks[2]')) ORDER BY matpel.nourut ASC";
						$matpel = $this->db->query($matpel2)->result_array();
						$no = 1;
						$dtn = array();
						foreach ($matpel as $key => $dtm) {
							# code...
						?>
							<tr style="height:20px;">
								<td align="center"><?php echo $no; ?></td>
								<td><?php echo $dtm['nmmatpel']; ?></td>
								<?php

								$np = 0;
								$tp = 0;
								$semes = array('ganjil', 'genap');
								for ($c = 0; $c <= 2; $c++) {
									// $nilai=$db2->query("SELECT*FROM nilai WHERE nisn='$row->nisn' AND kdmatpel='$dtm[kdmatpel]' AND tahun='$thn[$c]'");
									// if($c==0){
									//     $nilai=$this->db->query("SELECT nilairaport.semester, nilairaport.kdmatpel, settahunajaran.tahun ,pengetahuan, keterampilan FROM nilairaport,detailnilairaport,settahunajaran WHERE nilairaport.idnilairaport=detailnilairaport.idnilairaport AND nilairaport.idtahunajaran=settahunajaran.idtahunajaran AND nisn='$row->nisn' AND kdmatpel='$dtm[kdmatpel]' AND tahun='$thn[$c]'");
									// }else{
									//     $nilai=$db2->query("SELECT*FROM nilai WHERE nisn='$row->nisn' AND kdmatpel='$dtm[kdmatpel]' AND tahun='$thn[$c]'");
									// }
									// if($nilai->num_rows()<=0){
									$nilai = $this->db->query("SELECT nilairaport.semester, nilairaport.kkm, nilairaport.kdmatpel, settahunajaran.tahun ,pengetahuan, keterampilan FROM nilairaport,detailnilairaport,settahunajaran WHERE nilairaport.idnilairaport=detailnilairaport.idnilairaport AND nilairaport.idtahunajaran=settahunajaran.idtahunajaran AND nisn='$row->nisn' AND kdmatpel='$dtm[kdmatpel]' AND tahun='$thn[$c]'");
									// }

									$hasil = $nilai->num_rows();
									$queri = $nilai->result();
									foreach ($queri as $dt) {
										$s = $dt->semester;
										$th = $dt->tahun;
										$kd = $dt->kdmatpel;
										$dtn['pengetahuan'][$kd][$th][$s] = $dt->pengetahuan;
										$dtn['keterampilan'][$kd][$th][$s] = $dt->keterampilan;
										$dtn['kkm'][$kd][$th][$s] = $dt->kkm;
									}

									for ($b = 0; $b < count($semes); $b++) {


										if (!isset($dtn['pengetahuan'][$dtm['kdmatpel']][$thn[$c]][$semes[$b]])) {
								?>
											<td align="center">-</td>
										<?php
										} else {
											//
										?>
											<td align="center">
										<?php
											if ($dtn['pengetahuan'][$dtm['kdmatpel']][$thn[$c]][$semes[$b]] > 0) {
												if ($dtn['pengetahuan'][$dtm['kdmatpel']][$thn[$c]][$semes[$b]] >= $dtn['kkm'][$dtm['kdmatpel']][$thn[$c]][$semes[$b]]) {
													echo $dtn['pengetahuan'][$dtm['kdmatpel']][$thn[$c]][$semes[$b]];
												} else {
													echo "<label id='merah'>" . $dtn['pengetahuan'][$dtm['kdmatpel']][$thn[$c]][$semes[$b]] . "</labe>";
												}
												$np = $np + $dtn['pengetahuan'][$dtm['kdmatpel']][$thn[$c]][$semes[$b]];
												$tp++;
											} else {
												echo "<label id='merah'>0</label>";
											}
										}
									}
								}

								$ujian = $this->db->query("SELECT detailujian.nilai FROM ujian,detailujian,settahunajaran WHERE ujian.idujian=detailujian.idujian AND ujian.idtahunajaran=settahunajaran.idtahunajaran AND nisn='$row->nisn' AND kdmatpel='$dtm[kdmatpel]' AND ujian.semester='genap' AND tahun='$thn[0]' AND kategori='us'");
								if ($ujian->num_rows() < 0) {
										?>
											<td align="center">-</td>
											<td align="center">-</td>
										<?php
									} else {
										$dtn2 = $ujian->row_array();
										?>
											<td align="center">
												<?php
												if ($dtn2['nilai'] > 0) {
													echo $dtn2['nilai'];
												} else {
													if ($kelas[0] == "XII" && $this->session->semesterraport == "genap") {
														if ($np > 0) {
															$nil_p = $np / $tp;
															echo ceil($nil_p);
														} else {
															echo "-";
														}
													} else {
														echo "-";
													}
												}
												?>
											</td>
										<?php
									}
										?>
							</tr>
						<?php
							$no++;
						}
						for ($r = $no; $r <= 29; $r++) {
						?>
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
						<?php
						}
						?>
					</tbody>
				</table>
				<br>
				<table class="table table-bordered table-hover table-striped" cellspacing="0" style="font-size:11px;width:100%;" id="nilai">
					<tr>
						<th colspan="6" align="center" valign="middle" style="font-weight:bold;">KEHADIRAN SISWA</th>
					</tr>
					<tr>

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
					<?php
					$semes = array('ganjil', 'genap');
					for ($c = 0; $c <= 2; $c++) {
						for ($b = 0; $b < count($semes); $b++) {
							$absen = $this->db->query("SELECT sakit,izin,alfa FROM absensiraport,settahunajaran WHERE nisn='$row->nisn' AND absensiraport.idtahunajaran=settahunajaran.idtahunajaran AND absensiraport.semester='$semes[$b]' AND tahun='$thn[$c]'");
							if ($absen->num_rows() < 0) {
					?>
								<td align="center">Sakit=0, Izin=0, Tanpa Keterangan=0</td>
							<?php
							} else {
								$nabsen = $absen->row_array();
							?>
								<td align="center">
									Sakit=
									<?php
									if ($nabsen['sakit'] > 0) {
										echo $nabsen['sakit'];
									} else {
										echo "0";
									}
									echo ", Izin=";
									if ($nabsen['izin'] > 0) {
										echo $nabsen['izin'];
									} else {
										echo "0";
									}
									echo ", Tanpa Keterangan=";
									if ($nabsen['alfa'] > 0) {
										echo $nabsen['alfa'];
									} else {
										echo "0";
									}

									?>
								</td>

					<?php
							}
						}
					}
					?>
				</table>
				<br />


				<?php
				$queri = $this->db->query("SELECT*FROM nilaiujikomraport WHERE nisn='$row->nisn'");
				// if($queri->num_rows()>0){
				?>
				<!-- <table class="table table-bordered table-hover table-striped" cellspacing="0"  id="nilai" style="font-size:11px;width:100%;">
				<tr>
					<th>NO</th>
					<th>LEMBAGA/INDUSTRI YANG MENSERTIFIKASI</th>
					<th>ALAMAT</th>
					<th>NILAI</th>
				</tr>
				<?php
				$queri = $this->db->query("SELECT*FROM nilaiujikomraport WHERE nisn='$row->nisn'");
				if ($queri->num_rows() > 0) {
					$ujikom = $queri->row_array();
				?>
					<tr>
					<td >1</td>
					<td><?php echo $ujikom['industri']; ?></td>
					<td><?php echo $ujikom['alamat']; ?></td>
					<td><?php echo $ujikom['praktek']; ?></td>
					</tr>	
				<?php
				} else {
				?>
					<tr>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					</tr>
					<?php
				}

					?>
				</table> -->
				<?php
				// }
				?>
				<br />
				<?php
				$queri = $this->db->query("SELECT*FROM prakerinraport WHERE nisn='$row->nisn'");
				if ($queri->num_rows() > 0) {
				?>
					<table class="table table-bordered table-hover table-striped" cellspacing="0" id="nilai" style="font-size:11px;width:100%;">
						<tr>
							<th>NO</th>
							<th>TEMPAT PRAKERIN</th>
							<th>ALAMAT</th>
							<th>TOTAL JAM PRAKERIN</th>
							<th>NILAI</th>
						</tr>
						<?php
						$queri = $this->db->query("SELECT*FROM prakerinraport WHERE nisn='$row->nisn'");
						if ($queri->num_rows() > 0) {
							$ujikom = $queri->row_array();
						?>
							<tr>
								<td>1</td>
								<td><?php echo $ujikom['dudi']; ?></td>
								<td><?php echo $ujikom['alamat']; ?></td>
								<td><?php echo $ujikom['waktu']; ?> Jam</td>
								<td><?php echo $ujikom['nilai']; ?></td>
							</tr>
						<?php
						} else {
						?>
							<tr>
								<td rowspan="1">-</td>
								<td rowspan="1">-</td>
								<td rowspan="1">-</td>
								<td>-</td>
								<td>-</td>
							</tr>
						<?php
						}

						?>
					</table>
				<?php
				}
				?>
				<table border="0" style="font-size:12px;width:100%;">
					<tr>
						<td>
							<?php
							$kls = explode(" ", $row->kdkelas);


							if ($this->session->semesterraport == "genap" && strtolower($kls[0]) == "xii") {
							?>
								<b>Keputusan :</b><br />
								Berdasarkan hasil yang dicapai pada semester 1 dan 6, peserta didik ditetapkan
								<br />
								<b>Lulus / <strike>Tidak Lulus</strike></b>
								<?php
							} else {

								if (isset($kenaikan[$row->nisn]) and $this->session->semesterraport == "genap") {
								?>


									<?php

									if ($kenaikan[$row->nisn]['nilai'] == 1) {
										if (strtolower($kls[0]) == "x") {
									?>
											<b>Keputusan :</b><br />
											Berdasarkan hasil yang dicapai pada semester 1 dan 2, peserta didik ditetapkan
											<br />
											<br />
											Naik kelas XI ( Sebelas )<br />
											Tinggal di kelas - ( - )<br />
										<?php
										} else if (strtolower($kls[0]) == "xi") {
										?>

											<b>Keputusan :</b><br />
											Berdasarkan hasil yang dicapai pada semester 3 dan 4, peserta didik ditetapkan
											<br />
											<br />
											Naik kelas XII ( Dua belas )<br />
											Tinggal di kelas - ( - )<br />
										<?php
										}
										?>
										<?php
									} else {
										if (strtolower($kls[0]) == "x") {
										?>

											<b>Keputusan :</b><br />
											Berdasarkan hasil yang dicapai pada semester 1 dan 2, peserta didik ditetapkan
											<br />
											<br />
											Naik kelas - ( - )<br />
											Tinggal di kelas X ( Sepuluh )<br />
										<?php
										} else if (strtolower($kls[0]) == "xi") {
										?>
											<b>Keputusan :</b><br />
											Berdasarkan hasil yang dicapai pada semester 3 dan 4, peserta didik ditetapkan
											<br />
											<br />
											Naik kelas - ( - )<br />
											Tinggal di kelas XI ( Sebelas )<br />
									<?php
										}
									}
									?>
									<br />
									<br />
							<?php

								}
							}
							?>
						</td>
					</tr>
					<tr>
						<td valign="top">
							<b>*Keterangan:</b><br>
							P = Nilai pengetahuan<br>
							K = Nilai Keterampilan
						</td>
						<td align="right">
							<table style="font-size:12px;" align="right">
								<tr>
									<td>
										<?php
										$arrNamaBulan = array("01" => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April", "05" => "Mei", "06" => "Juni", "07" => "Juli", "08" => "Agustus", "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember");
										$tgl = explode("-", $dr->tglterimaraport);
										?>
										Tasikmalaya, <?= $tgl[2] . " " . $arrNamaBulan[$tgl[1]] . " " . $tgl[0] ?><br />
										<?php
										if ($this->session->semesterraport == "genap" && strtolower($kls[0]) == "xii") {
										?>
											Kepala Sekolah<br />
											<br />
											<br />
											<br />
											<br />
											<b>
												DRS. UJANG SANUSI, MM<br />
												NIP: 1968199704003
												<br />
											<?php
										} else {
											?>
												Kepala Sekolah<br />
												<br />
												<br />
												<br />
												<br />
												<b>
													DRS. UJANG SANUSI, MM<br />
													NIP: 1968199704003
													<br />
													<!-- Walikelas<br /> -->
													<!-- <br />
										<br />
										<br />
										<br />
										<b><?php
											// echo strtoupper($this->session->nama); 
											?></b><br /> -->
												<?php
											}
												?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

		</div>
		</div>
	<?php
	}

	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$finish = $time;
	$total_time = round(($finish - $start), 4);
	// echo "Selesai dalam ".$total_time." detik";

	?>