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
			width: 800px;
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
			<h6 class="text-center mb-2">
				DATA SANTRI TIDAK CHECKIN LIBURAN MAULID 1445 H
				<br>
				<?= ($domicile != '') ? 'DOMISILI '.strtoupper($domicile) : 'SEMUA DOMISILI' ?>
			</h6>
			<table class="tablestripped table-xl">
				<thead>
				<tr>
					<th>NO</th>
					<th>NIS</th>
					<th>NAMA</th>
					<th>ALAMAT</th>
					<th>STATUS</th>
				</tr>
				</thead>
				<tbody>
				<?php
				if ($datas) {
					$no = 1;
					$key_values = array_column($datas, 'status');
					array_multisort($key_values, SORT_ASC, $datas);

					foreach ($datas as $data) {
						$status = $data['status'];
						if ($status) {
							$status = '<span class="badge badge-success">Checkin</span>';
						}else{
							$status = '<span class="badge badge-danger">Tidak</span>';
						}
						?>
						<tr>
							<td><?= $no++ ?></td>
							<td><?= $data['id'] ?></td>
							<td>
								<b><?= $data['name'] ?></b>
							</td>
							<td><?= $data['address'] ?></td>
							<td><?= $status ?></td>
						</tr>
						<?php
					}
				} else {
					echo '<tr class="text-center"><td colspan="6"><h6 class="text-danger">Tak ada data untuk ditampilkan</h6></td></tr>';
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!--    <script>-->
<!--        window.print()-->
<!--        setTimeout(() => {-->
<!--            window.close()-->
<!--        }, 2000);-->
<!--    </script>-->
</body>

</html>
