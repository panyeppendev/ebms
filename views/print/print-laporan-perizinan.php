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
                    LAPORAN PERIZINAN BERDASARKAN ASRAMA
                    <br>
                    <?= $date ?>
                </h6>
                <table class="tablestripped table-xl">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>KAMAR</th>
							<th>PENDEK</th>
							<th>JAUH</th>
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
							<td class="text-center"><?= $d['short'] ?></td>
							<td class="text-center"><?= $d['long'] ?></td>
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
					LAPORAN PERIZINAN JARAK PENDEK BERDASARKAN ALASAN
					<br>
					<?= $date ?>
				</h6>
				<table class="tablestripped table-xl">
					<thead>
					<tr>
						<th>NO</th>
						<th>ALASAN</th>
						<th>JUMLAH</th>
						<th>%</th>
					</tr>
					</thead>
					<tbody>
					<?php
					if ($short[0]) {
						$no = 1;
						foreach ($short[0] as $d) {
							?>
							<tr>
								<td class="text-center"><?= $no++ ?></td>
								<td><?= $d->reason ?></td>
								<td class="text-center"><?= $d->total ?></td>
								<td class="text-center"><?= round(($d->total/$short[1]) * 100, 1).' %' ?></td>
							</tr>
							<?php
						}
					}else{
						echo '<tr><td colspan="3" class="text-center">Tidak ada data untuk ditampilkan</td></tr>';
					}
					?>
					<tr style="font-weight: bold">
						<td colspan="2" class="text-center">TOTAL</td>
						<td class="text-center"><?= $short[1] ?></td>
						<td class="text-center">100%</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12">
				<h6 class="text-center mb-1">
					LAPORAN PERIZINAN JARAK JAUH BERDASARKAN ALASAN
					<br>
					<?= $date ?>
				</h6>
				<table class="tablestripped table-xl">
					<thead>
					<tr>
						<th>NO</th>
						<th>ALASAN</th>
						<th>JUMLAH</th>
						<th>%</th>
					</tr>
					</thead>
					<tbody>
					<?php
					if ($long[0]) {
						$no = 1;
						foreach ($long[0] as $d) {
							?>
							<tr>
								<td class="text-center"><?= $no++ ?></td>
								<td><?= $d->reason ?></td>
								<td class="text-center"><?= $d->total ?></td>
								<td class="text-center"><?= round(($d->total/$long[1]) * 100, 1).' %' ?></td>
							</tr>
							<?php
						}
					}else{
						echo '<tr><td colspan="3" class="text-center">Tidak ada data untuk ditampilkan</td></tr>';
					}
					?>
					<tr style="font-weight: bold">
						<td colspan="2" class="text-center">TOTAL</td>
						<td class="text-center"><?= $long[1] ?></td>
						<td class="text-center">100%</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12">
				<h6 class="text-center mb-1">
					10 SANTRI DENGAN IZIN JARAK JAUH TERBANYAK
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
					if ($ten[0]) {
						$no = 1;
						foreach ($ten[0] as $d) {
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
		<div class="row mt-2">
			<div class="col-12">
				<h6 class="text-center mb-1">
					10 SANTRI DENGAN IZIN JARAK DEKAT TERBANYAK
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
					if ($ten[1]) {
						$no = 1;
						foreach ($ten[1] as $d) {
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
		<div class="row mt-2">
			<div class="col-12">
				<h6 class="text-center mb-1">
					REKAPITULASI KEUANGAN PERIZINAN
					<br>
					<?= $date ?>
				</h6>
				<table class="tablestripped table-xl">
					<thead>
					<tr>
						<th>NO</th>
						<th>TIPE</th>
						<th>JUMLAH</th>
					</tr>
					</thead>
					<tbody>
					<?php
					if ($payment) {
						$no = 1;
						$grand = 0;
						foreach ($payment as $d) {
							$grand += $d->total;
							?>
							<tr>
								<td class="text-center"><?= $no++ ?></td>
								<td><?= ($d->type == 'LONG') ? 'Jarak Jauk' : 'Jarak Dekat' ?></td>
								<td class="text-right"><?= number_format($d->total, 0, ',', '.') ?></td>
							</tr>
							<?php
						}
						?>
						<tr style="font-weight: bold">
							<td colspan="2" class="text-center">TOTAL</td>
							<td class="text-right"><?= number_format($grand, 0, ',', '.') ?></td>
						</tr>
					<?php
					}else{
						echo '<tr><td colspan="3" class="text-center">Tidak ada data untuk ditampilkan</td></tr>';
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12">
				<h6 class="text-center mb-1">
					LAPORAN TERLAMBAT BERDASARKAN ASRAMA
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
					if ($late[0]) {
						$no = 1;
						foreach ($late[0] as $d) {
							?>
							<tr>
								<td class="text-center"><?= $no++ ?></td>
								<td><?= $d->domicile ?></td>
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
						<td colspan="2" class="text-center">TOTAL</td>
						<td class="text-center"><?= $late[1]->total ?></td>
						<td class="text-center">100%</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12">
				<h6 class="text-center mb-1">
					LAPORAN PERIZINAN JARAK DEKAT BELUM KEMBALI
					<br>
					<?= $date ?>
				</h6>
				<table class="tablestripped table-xl">
					<thead>
					<tr>
						<th>NO</th>
						<th>NAMA</th>
						<th>KAMAR</th>
						<th>ALASAN</th>
						<th>TANGGAL</th>
					</tr>
					</thead>
					<tbody>
					<?php
					if ($short_late) {
						$no = 1;
						foreach ($short_late as $d) {
							?>
							<tr>
								<td class="text-center"><?= $no++ ?></td>
								<td><?= $d->name ?></td>
								<td><?= $d->domicile ?></td>
								<td><?= $d->reason ?></td>
								<td><?= datetimeIDFormat($d->expired_at) ?></td>
							</tr>
							<?php
						}
					}else{
						echo '<tr><td colspan="5" class="text-center">Tidak ada data untuk ditampilkan</td></tr>';
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12">
				<h6 class="text-center mb-1">
					LAPORAN PERIZINAN JARAK JAUH BELUM KEMBALI
					<br>
					<?= $date ?>
				</h6>
				<table class="tablestripped table-xl">
					<thead>
					<tr>
						<th>NO</th>
						<th>NAMA</th>
						<th>KAMAR</th>
						<th>ALASAN</th>
						<th>TANGGAL</th>
					</tr>
					</thead>
					<tbody>
					<?php
					if ($long_late) {
						$no = 1;
						foreach ($long_late as $d) {
							?>
							<tr>
								<td class="text-center"><?= $no++ ?></td>
								<td><?= $d->name ?></td>
								<td><?= $d->domicile ?></td>
								<td><?= $d->reason ?></td>
								<td><?= datetimeIDFormat($d->expired_at) ?></td>
							</tr>
							<?php
						}
					}else{
						echo '<tr><td colspan="5" class="text-center">Tidak ada data untuk ditampilkan</td></tr>';
					}
					?>
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
