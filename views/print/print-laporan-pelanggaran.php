<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= $title; ?></title>
	<link rel="shortcut icon" href="<?= base_url() ?>/assets/images/favicon.png">
	<style>
		* {
			/*font-family: 'Courier New', Courier, monospace;*/
			font-family: 'Corbel', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
			font-size: 11pt;
		}

		.container {
			width: 1247px;
			display: relative;
		}

		.row {
			display: flex;
			flex-wrap: wrap;
		}

		.col-12 {
			flex: 0 0 100%;
			max-width: 100%;
		}

		.col-8 {
			flex: 0 0 66.666667%;
			max-width: 66.666667%;
		}

		.col-7 {
			flex: 0 0 58.333333%;
			max-width: 58.333333%;
		}

		.col-6 {
			flex: 0 0 50%;
			max-width: 50%;
		}

		.col-5 {
			flex: 0 0 41.666667%;
			max-width: 41.666667%;
		}

		.col-4 {
			flex: 0 0 33.333333%;
			max-width: 33.333333%;
		}

		.logo {
			width: 100%;
			margin-top: 8px;
		}

		.h1,
		.h2,
		.h3,
		.h4,
		.h5,
		.h6,
		h1,
		h2,
		h3,
		h4,
		h5,
		h6 {
			margin-top: 0.1rem;
			margin-bottom: 0.1rem;
			margin-block-start: 0px;
			margin-block-end: 0px;
			font-family: inherit;
			font-weight: bold;
			color: inherit;
		}

		.invoice-title {
			font-size: 3.5rem;
		}

		.text-right {
			text-align: end;
		}

		hr {
			margin-top: 0.6rem;
			margin-bottom: 0.6rem;
			border: 0;
			border-top: 1px solid rgb(0 0 0 / 82%)
		}

		table {
			border-collapse: collapse;
		}

		.table {
			width: 100%;
			margin-bottom: 1rem;
			color: #212529;
			background-color: transparent;
		}

		.tablestripped {
			width: 100%;
			margin-bottom: 1rem;
			color: #212529;
			background-color: transparent;
		}

		.tablebottom {
			width: 100%;
			margin-bottom: 1rem;
			color: #212529;
			background-color: transparent;
		}

		.mb-0 {
			margin-bottom: 0px;
		}

		.mt-2 {
			margin-top: 3rem;
		}

		.mb-1 {
			margin-bottom: 1rem;
		}

		.mb-2 {
			margin-bottom: 2rem;
		}

		.tablestripped th {
			vertical-align: top;
			border-top: 1px solid #999797;
			border-bottom: 1px solid #999797;
		}

		.tablestripped td {
			vertical-align: top;
			border-top: 1px solid #999797;
		}

		.tablebottom td,
		.tablebottom th {
			vertical-align: top;
			border-top: 1px dashed #999797;
		}

		#line-bottom {
			border-top: 1px solid #999797;
		}

		.table-xl th {
			padding: 0.5rem;
		}

		.table-xl td {
			padding: 0.3rem;
		}

		.table-sm td,
		.table-sm th {
			padding: 0.2rem;
		}

		.text-center {
			text-align: center;
		}

		.text-bold {
			font-weight: bold;
		}

		.notes {
			padding-left: 25px;
			padding-top: 10px;
		}
	</style>
</head>

<body>
<div class="container">
	<div class="row">
		<div class="col-12">
			<h6 class="text-center mb-1">
				LAPORAN PELANGGARAN BERDASARKAN ASRAMA
				<br>
				<?= $date ?>
			</h6>
			<table class="tablestripped table-xl">
				<thead>
				<tr>
					<th>NO</th>
					<th>KAMAR</th>
					<th>RINGAN</th>
					<th>SEDANG</th>
					<th>BERAT</th>
					<th>SANGAT BERAT</th>
					<th>JUMLAH</th>
					<th>%</th>
				</tr>
				</thead>
				<tbody>
				<?php
				if ($data[0]) {
					$no = 1;
					foreach ($data[0] as $d) {
						?>
						<tr>
							<td class="text-center"><?= $no++ ?></td>
							<td><?= $d['domicile'] ?></td>
							<td class="text-center"><?= $d['low'] ?></td>
							<td class="text-center"><?= $d['medium'] ?></td>
							<td class="text-center"><?= $d['high'] ?></td>
							<td class="text-center"><?= $d['top'] ?></td>
							<td class="text-center"><?= $d['total'] ?></td>
							<td class="text-center"><?= round(($d['total']/$data[1]->total) * 100, 1).' %' ?></td>
						</tr>
						<?php
					}
				}else{
					echo '<tr><td colspan="3" class="text-center">Tidak ada data untuk ditampilkan</td></tr>';
				}
				?>
				<tr style="font-weight: bold">
					<td colspan="2" class="text-center">TOTAL</td>
					<td class="text-center"><?= $data[2] ?></td>
					<td class="text-center"><?= $data[3] ?></td>
					<td class="text-center"><?= $data[4] ?></td>
					<td class="text-center"><?= $data[5] ?></td>
					<td class="text-center"><?= $data[1]->total ?></td>
					<td class="text-center">100%</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row mt-2">
		<div class="col-12">
			<h6 class="text-center mb-1">
				LAPORAN PELANGGARAN BERDASARKAN JENIS
				<br>
				<?= $date ?>
			</h6>
			<table class="tablestripped table-xl">
				<thead>
				<tr>
					<th>NO</th>
					<th>PELANGGARAN</th>
					<th>KATEGORI</th>
					<th>JUMLAH</th>
					<th>%</th>
				</tr>
				</thead>
				<tbody>
				<?php
				if ($constitution) {
					$no = 1;
					$category = [
						'LOW' => 'Ringan',
						'MEDIUM' => 'Sedang',
						'HIGH' => 'Berat',
						'TOP' => 'Sangat Berat'
					];
					foreach ($constitution as $d) {
						?>
						<tr>
							<td class="text-center"><?= $no++ ?></td>
							<td><?= $d->name ?></td>
							<td><?= $category[$d->category] ?></td>
							<td class="text-center"><?= $d->total ?></td>
							<td class="text-center"><?= round(($d->total/$data[1]->total) * 100, 1).' %' ?></td>
						</tr>
						<?php
					}
				}else{
					echo '<tr><td colspan="3" class="text-center">Tidak ada data untuk ditampilkan</td></tr>';
				}
				?>
				<tr style="font-weight: bold">
					<td colspan="3" class="text-center">TOTAL</td>
					<td class="text-center"><?= $data[1]->total ?></td>
					<td class="text-center">100%</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row mt-2">
		<div class="col-12">
			<h6 class="text-center mb-1">
				10 SANTRI DENGAN PELANGGARAN TERBANYAK
				<br>
				<?= $date ?>
			</h6>
			<table class="tablestripped table-xl">
				<thead>
				<tr>
					<th>NO</th>
					<th>NAMA</th>
					<th>DOMISILI</th>
					<th>JUMLAH</th>
				</tr>
				</thead>
				<tbody>
				<?php
				if ($santri) {
					$no = 1;
					foreach ($santri as $d) {
						?>
						<tr>
							<td class="text-center"><?= $no++ ?></td>
							<td><?= $d->name ?></td>
							<td><?= $d->domicile ?></td>
							<td class="text-center"><?= $d->total ?></td>
						</tr>
						<?php
					}
				}else{
					echo '<tr><td colspan="4" class="text-center">Tidak ada data untuk ditampilkan</td></tr>';
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<h6 class="text-center mb-1">
				LAPORAN SKORSING BERDASARKAN ASRAMA
				<br>
				<?= $date ?>
			</h6>
			<table class="tablestripped table-xl">
				<thead>
				<tr>
					<th>NO</th>
					<th>KAMAR</th>
					<th>JUMLAH</th>
					<th>%</th>
				</tr>
				</thead>
				<tbody>
				<?php
				if ($skorsing[0]) {
					$no = 1;
					foreach ($skorsing[0] as $d) {
						?>
						<tr>
							<td class="text-center"><?= $no++ ?></td>
							<td><?= $d->domicile ?></td>
							<td class="text-center"><?= $d->total ?></td>
							<td class="text-center"><?= round(($d->total/$skorsing[1]->total) * 100, 1).' %' ?></td>
						</tr>
						<?php
					}
				}else{
					echo '<tr><td colspan="3" class="text-center">Tidak ada data untuk ditampilkan</td></tr>';
				}
				?>
				<tr style="font-weight: bold">
					<td colspan="2" class="text-center">TOTAL</td>
					<td class="text-center"><?= $skorsing[1]->total ?></td>
					<td class="text-center">100%</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<h6 class="text-center mb-1">
				LAPORAN SKORSING BERDASARKAN PELANGGARAN
				<br>
				<?= $date ?>
			</h6>
			<table class="tablestripped table-xl">
				<thead>
				<tr>
					<th>NO</th>
					<th>PELANGGARAN</th>
					<th>JUMLAH</th>
					<th>%</th>
				</tr>
				</thead>
				<tbody>
				<?php
				if ($skorsing_pelanggaran[0]) {
					$no = 1;
					foreach ($skorsing_pelanggaran[0] as $d) {
						?>
						<tr>
							<td class="text-center"><?= $no++ ?></td>
							<td><?= $d->name ?></td>
							<td class="text-center"><?= $d->total ?></td>
							<td class="text-center"><?= round(($d->total/$skorsing_pelanggaran[1]->total) * 100, 1).' %' ?></td>
						</tr>
						<?php
					}
				}else{
					echo '<tr><td colspan="3" class="text-center">Tidak ada data untuk ditampilkan</td></tr>';
				}
				?>
				<tr style="font-weight: bold">
					<td colspan="2" class="text-center">TOTAL</td>
					<td class="text-center"><?= $skorsing_pelanggaran[1]->total ?></td>
					<td class="text-center">100%</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- <script>
	window.print()
	setTimeout(() => {
		window.close()
	}, 2000);
</script> -->
</body>

</html>
