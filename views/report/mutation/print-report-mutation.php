<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Laporan Mutasi Kas</title>
	<style>
		* {
			font-family: 'Corbel', Courier, monospace;
			font-size: 11pt;
		}

		.container {
			/*width: 21cm;*/
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
<?php if ($mutations) : ?>
<div class="container">
	<h6 class="text-center">LAPORAN MUTASI KAS</h6>
	<h6 class="text-center mb-1"><?= $date ?></h6>
	<p>Rekening : <?= $account ?></p>
	<table border="1" class="table table-xl" style="width: 100%">
		<thead>
		<tr>
			<th>NO</th>
			<th>NAMA</th>
			<th>DOMISILI</th>
			<th>KELAS</th>
			<th>KREDIT</th>
			<th>DEBIT</th>
			<th>SALDO</th>
		</tr>
		</thead>
		<tbody>
		<?php
			$no = 1;
			$totalCredit = 0;
			$totalDebit = 0;
			$total = 0;
			foreach ($mutations as $mutation) {
				$credit = $mutation['credit'];
				$debit = $mutation['debit'];
				$residual = $credit - $debit;

				$totalCredit += $credit;
				$totalDebit += $debit;
				$total = $totalCredit - $totalDebit;
			?>
		<tr>
			<td class="text-center"><?= $no++ ?></td>
			<td>
				<?= $mutation['name'] ?>
			</td>
			<td>
				<?= $mutation['domicile'] ?>
			</td>
			<td class="">
				<?= $mutation['class'] ?>
			</td>
			<td class="text-right">
				<?= number_format($credit, 0, ',', '.') ?>
			</td>
			<td class="text-right">
				<?= number_format($debit, 0, ',', '.') ?>
			</td>
			<td class="text-right">
				<?= number_format($residual, 0, ',', '.') ?>
			</td>
		</tr>
		<?php
		}
		?>
		<tr class="text-bold">
			<td colspan="4" class="text-center">TOTAL</td>
			<td class="text-right">
				<?= number_format($totalCredit, 0, ',', '.') ?>
			</td>
			<td class="text-right">
				<?= number_format($totalDebit, 0, ',', '.') ?>
			</td>
			<td class="text-right">
				<?= number_format($total, 0, ',', '.') ?>
			</td>
		</tr>
		</tbody>
	</table>
</div>
<?php endif; ?>
</body>

</html>
