<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= $title; ?></title>
	<link rel="shortcut icon" href="<?= base_url() ?>/assets/logo.png">
	<style>
		* {
			font-family: 'Courier New', Courier, monospace;
			/* font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; */
			font-size: 10pt;
		}

		.container {
			width: 302px;
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
			border-top: 1px dashed #999797;
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

		.text-left {
			text-align: start;
		}
	</style>
</head>

<body>
<div class="container">
	<div class="row">
		<div class="col-12">
			<img class="logo" src="<?= base_url() ?>assets/images/header-print.png" alt="">
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-12">
			<?php
			if ($data) : ?>
			<table class="table">
				<tr>
					<td>Nama</td>
					<td> <b> <?= $data->name; ?></b></td>
				</tr>
				<tr>
					<td>Domisili</td>
					<td><?= $data->domicile ?></td>
				</tr>
				<tr>
					<td>Diniyah</td>
					<td>
						<?= $data->class . ' - ' . $data->level ?>
					</td>
				</tr>
				<tr>
					<td>Formal</td>
					<td>
						<?= $data->class_of_formal . ' - ' . $data->level_of_formal ?>
					</td>
				</tr>
				<tr>
					<td>Alamat</td>
					<td><?= $data->village . '<br>' . str_replace(['Kabupaten', 'Kota '], '', $data->city) ?></td>
				</tr>
				<tr>
					<td>Paket</td>
					<td><b><?= $data->package ?></b></td>
				</tr>
			</table>
			<?php endif; ?>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<table class="tablestripped table-xl">
				<thead>
				<tr>
					<th style="width: 70%">URAIAN</th>
					<th style="width: 30%">NOMINAL</th>
				</tr>
				</thead>
				<tbody>
				<tr><td colspan="2" class="text-center">DETAIL PAKET</td></tr>
				<?php
				$total = 0;
				if ($package) {
					foreach ($package as $detail) {
						$total += $detail->nominal;
						?>
						<tr>
							<td><?= $detail->name ?></td>
							<td class="text-right">
								<?= number_format($detail->nominal, 0, ',', '.') ?>
							</td>
						</tr>
						<?php
					}
				}
				?>
				<?php
				$totalAddon = 0;
				if ($addon) {
					?>
					<tr>
						<td><b>SUB TOTAL</b></td>
						<td class="text-right">
							<?= number_format($total, 0, ',', '.') ?>
						</td>
					</tr>
					<tr><td colspan="3" class="text-center">DETAIL TAMBAHAN</td></tr>
				<?php
					foreach ($addon as $detail) {
						$totalAddon += $detail->nominal;
						?>
						<tr>
							<td><?= $detail->name ?></td>
							<td class="text-right">
								<?= number_format($detail->nominal, 0, ',', '.') ?>
							</td>
						</tr>
				<?php
					}
					?>
					<tr>
						<td><b>SUB TOTAL</b></td>
						<td class="text-right">
							<?= number_format($totalAddon, 0, ',', '.') ?>
						</td>
					</tr>
				<?php
				}
				?>

				</tbody>
				<tfoot>
				<tr>
					<th class="text-left"> TOTAL</th>
					<th class="text-right"><?= number_format($total + $totalAddon, 0, ',', '.') ?></th>
				</tr>
				</tfoot>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<div class="text-center">
				Panyeppen
				<br>
				<?= datetimeIDFormat(@$data->created_at) ?><br>
				Kasir <br>
				<p style="margin-top: 40px;">
					<u><b><?= @$data->user ?></b></u>
				</p>
			</div>
			<div class="notes">
				<i>
					PERHATIAN! <br>
					Nota ini adalah bukti pembayaran yang sah.
				</i>
			</div>
		</div>
	</div>

</div>
<script>
	window.print()
	setTimeout(() => {
		window.close()
	}, 2000);
</script>
</body>

</html>
