<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Laporan Pembelian Paket</title>
	<style>
		* {
			font-family: 'Corbel', Courier, monospace;
			font-size: 11pt;
		}

		.container {
			width: 19cm;
			display: block;
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

		.card-title {
			font-size: 2.5rem;
		}

		.text-right {
			text-align: right;
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

		.mt-1 {
			margin-top: 1rem;
		}

		.mb-1 {
			margin-bottom: 1rem;
		}

		.mt-2 {
			margin-top: 3rem;
		}

		.mb-2 {
			margin-bottom: 2rem;
		}

		.tablestripped td,
		.tablestripped th {
			vertical-align: top;
			border-top: 1px solid #999797;
		}

		.tablebottom td,
		.tablebottom th {
			vertical-align: top;
			border-top: 1px dashed #999797;
		}

		.table-xl td,
		.table-xl th {
			padding: 0.5rem;
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

		.logo {
			width: 450px;
		}

		.border {
			background-color: #181616;
			height: 1px;
			width: 100%;
			margin-top: 10px;
			margin-bottom: 10px;
		}

		.pl-1 {
			padding-left: 0.5rem;
		}
	</style>
</head>

<body>
<?php if ($purchases) : ?>
<div class="container">
	<h6 class="text-center">LAPORAN PEMBELIAN PAKET</h6>
	<h6 class="text-center mb-1">Tanggal : <?= @dateIDFormatShort($start).' s.d. '.@dateIDFormatShort($end) ?></h6>
	<p>1. Rekap Pembelian</p>
	<table style="width: 100%" border="1" class="table table-xl mb-2">
		<thead>
		<tr>
			<th>NO</th>
			<th>PAKET</th>
			<th>QTY</th>
			<th>JUMLAH</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$totalPurchase= 0;
		if ($purchases) {
			$no = 1;
			foreach ($purchases as $d) {
				$totalPurchase += $d->total;
				?>
				<tr>
					<td class="text-center"><?= $no++ ?></td>
					<td class="text-center"><?= $d->package ?></td>
					<td class="text-center"><?= $d->qty ?></td>
					<td class="text-right">
						<?= number_format($d->total, 0, ',', '.') ?>
					</td>
				</tr>
				<?php
			}
		}
		?>
		<tr class="text-bold">
			<td colspan="3" class="text-center">TOTAL</td>
			<td class="text-right">
				<?= number_format($totalPurchase, 0, ',', '.') ?>
			</td>
		</tr>
		</tbody>
	</table>
	<p>2. Rincian Pembelian</p>
	<table style="width: 100%" border="1" class="table table-xl">
		<thead>
		<tr>
			<th>NO</th>
			<th>PAKET</th>
			<th>QTY</th>
			<th>JUMLAH</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$totalDetail = 0;
		if ($detail) {
			$no = 1;
			foreach ($detail as $d) {
				$totalDetail += $d->total;
				?>
				<tr>
					<td class="text-center"><?= $no++ ?></td>
					<td class="text-center"><?= $d->name ?></td>
					<td class="text-center"><?= $d->qty ?></td>
					<td class="text-right">
						<?= number_format($d->total, 0, ',', '.') ?>
					</td>
				</tr>
				<?php
			}
		}
		?>
		<tr class="text-bold">
			<td colspan="3" class="text-center">TOTAL</td>
			<td class="text-right">
				<?= number_format($totalDetail, 0, ',', '.') ?>
			</td>
		</tr>
		</tbody>
	</table>
</div>
<?php endif; ?>
</body>

</html>
