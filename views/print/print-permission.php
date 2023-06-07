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
            font-size: 12pt;
        }

        .container {
            width: 148mm;
            height: 210mm;
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

        .col-10 {
            flex: 0 0 83%;
            max-width: 83%;
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

        .col-2 {
            flex: 0 0 16.6%;
            max-width: 16.6%;
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

		.mb-5 {
			margin-bottom: 40px;
		}

		.pl-5 {
			padding-left: 20px;
		}

		.align-top {
			vertical-align: top;
		}

		.pt-5 {
			padding-top: 30px;
		}

		.mb-3 {
			margin-bottom: 10px;
		}
    </style>
</head>

<body>
    <div class="container">
		<?php
			if ($data) {
		?>
				<div class="row">
                    <div class="col-12">
                        <img src="<?= base_url() ?>assets/images/kop.jpg" style="width: 100%;">
                    </div>
					<div class="col-12 text-center mb-3">
						<h6>
							<u>SURAT IZIN JARAK JAUH</u>
						</h6>
						<i>Nomor: <?= $data->reference ?></i>
					</div>
					<div class="col-12">
						<p>Yang bertanda tangan di bawah ini, Kami pengurus Pondok Pesantren Miftahul Ulum Panyeppen menerangkan bahwa anak tersebut di bawah ini:</p>
					</div>
					<div class="col-12">
						<table class="table mb-0" border="0">
							<tbody>
								<tr>
									<td class="pl-5">Nama</td>
									<td>:</td>
									<td><b><?= $data->name ?></b></td>
								</tr>
								<tr>
									<td class="pl-5">NIS</td>
									<td>:</td>
									<td><?= $data->student_id ?></td>
								</tr>
								<tr>
									<td class="pl-5">Tetala</td>
									<td>:</td>
									<td><?= $data->place_of_birth.', '.dateIDFormat($data->date_of_birth) ?></td>
								</tr>
								<tr>
									<td class="pl-5">Usia</td>
									<td>:</td>
									<td><?= ageCounter($data->date_of_birth) ?></td>
								</tr>
								<tr>
									<td class="pl-5 align-top">Alamat</td>
									<td class="align-top">:</td>
									<td>
										<?= $data->address.', '.$data->village ?> <br>
										<?= $data->district.', '.str_replace(['Kota ', 'Kabupaten '], '', $data->city) ?>
									</td>
								</tr>
								<tr>
									<td class="pl-5">Domisili</td>
									<td>:</td>
									<td><?= $data->domicile ?></td>
								</tr>
								<tr>
									<td class="pl-5">Pend. Diniyah</td>
									<td>:</td>
									<td><?= $data->class.' - '.$data->level ?></td>
								</tr>
								<tr>
									<td class="pl-5">Pend. Formal</td>
									<td>:</td>
									<td><?= $data->class_of_formal.' - '.$data->level_of_formal ?></td>
								</tr>
								<tr>
									<td class="pl-5">Nama Wali</td>
									<td>:</td>
									<td><b><?= $data->father ?></b></td>
								</tr>
								<tr>
									<td class="pl-5">Keperluan</td>
									<td>:</td>
									<td><?= $data->reason ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-12">
					<p>
						Telah diberi izin untuk pulang/bepergian selama <b><?= showDiff($data->created_at, $data->expired_at) ?></b>, berlaku sejak tanggal ditetapkan
						dan harus kembali ke pondok pesantren selambat-lambatnya <br> <?= dateDisplayWithDay($data->date_expired) ?>
					</p>
				</div>
				<div class="col-12">
					<p>Demikian mohon maklum adanya.</p>
				</div>
				<div class="row">
					<div class="col-6"></div>
					<div class="col-6">
						<table class="table">
							<tr>
								<td class="text-right">Panyeppen</td>
								<td>,</td>
								<td><u><?= dateIDFormat($data->created_at) ?></u></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td><?= dateHijriFormat(getHijriManual($data->created_at)) ?></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-12 text-center">
						<table class="table">
							<tr>
								<td>
									<img src="<?= base_url() ?>assets/images/ttd.png" alt="TTD" width="50px" style="position:absolute; margin-top: 20px">
									Ketua III,
									<br>
									PPMU. Panyeppen
									<br><br><br><br><br>
									<b><u>( UST. RUMHUL FATTAH )</u></b>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row">
                    <div class="col-10">
						<?php
						if ($data->note === 'EMERGENCY') {
							?>
						<p><b>CATATAN:</b> <br>
							Santri tersebut di atas sedang dalam masa skorsing
						<?php
							}
						?>
						</p>
					</div>
                    <div class="col-2">
                        <img src="<?= base_url() ?>assets/images/diniyah.png" style="width: 100%; bottom: 0px">
                    </div>
				</div>
		<?php
			}
		?>
    </div>
    <p style="page-break-after: always;">&nbsp;</p>
    <div class="container">
		<?php
			if ($data) {
		?>
				<div class="row">
                    <div class="col-12">
                        <img src="<?= base_url() ?>assets/images/kop.jpg" style="width: 100%;">
                    </div>
					<div class="col-12 text-center mb-3">
						<h6>
							<u>SURAT IZIN JARAK JAUH</u>
						</h6>
						<i>Nomor: <?= $data->reference ?></i>
					</div>
					<div class="col-12">
						<p>Yang bertanda tangan di bawah ini, Kami pengurus Pondok Pesantren Miftahul Ulum Panyeppen menerangkan bahwa anak tersebut di bawah ini:</p>
					</div>
					<div class="col-12">
						<table class="table mb-0" border="0">
							<tbody>
								<tr>
									<td class="pl-5">Nama</td>
									<td>:</td>
									<td><b><?= $data->name ?></b></td>
								</tr>
								<tr>
									<td class="pl-5">NIS</td>
									<td>:</td>
									<td><?= $data->student_id ?></td>
								</tr>
								<tr>
									<td class="pl-5">Tetala</td>
									<td>:</td>
									<td><?= $data->place_of_birth.', '.dateIDFormat($data->date_of_birth) ?></td>
								</tr>
								<tr>
									<td class="pl-5">Usia</td>
									<td>:</td>
									<td><?= ageCounter($data->date_of_birth) ?></td>
								</tr>
								<tr>
									<td class="pl-5 align-top">Alamat</td>
									<td class="align-top">:</td>
									<td>
										<?= $data->address.', '.$data->village ?> <br>
										<?= $data->district.', '.str_replace(['Kota ', 'Kabupaten '], '', $data->city) ?>
									</td>
								</tr>
								<tr>
									<td class="pl-5">Domisili</td>
									<td>:</td>
									<td><?= $data->domicile ?></td>
								</tr>
								<tr>
									<td class="pl-5">Pend. Diniyah</td>
									<td>:</td>
									<td><?= $data->class.' - '.$data->level ?></td>
								</tr>
								<tr>
									<td class="pl-5">Pend. Formal</td>
									<td>:</td>
									<td><?= $data->class_of_formal.' - '.$data->level_of_formal ?></td>
								</tr>
								<tr>
									<td class="pl-5">Nama Wali</td>
									<td>:</td>
									<td><b><?= $data->father ?></b></td>
								</tr>
								<tr>
									<td class="pl-5">Keperluan</td>
									<td>:</td>
									<td><?= $data->reason ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-12">
					<p>
						Telah diberi izin untuk pulang/bepergian selama <b><?= showDiff($data->created_at, $data->expired_at) ?></b>, berlaku sejak tanggal ditetapkan
						dan harus kembali ke pondok pesantren selambat-lambatnya <br> <?= dateDisplayWithDay($data->date_expired) ?>
					</p>
				</div>
				<div class="col-12">
					<p>Demikian mohon maklum adanya.</p>
				</div>
				<div class="row">
					<div class="col-6"></div>
					<div class="col-6">
						<table class="table">
							<tr>
								<td class="text-right">Panyeppen</td>
								<td>,</td>
								<td><u><?= dateIDFormat($data->created_at) ?></u></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td><?= dateHijriFormat(getHijriManual($data->created_at)) ?></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-12 text-center">
						<table class="table">
							<tr>
								<td>
									<img src="<?= base_url() ?>assets/images/ttd.png" alt="TTD" width="50px" style="position:absolute; margin-top: 20px">
									Ketua III,
									<br>
									PPMU. Panyeppen
									<br><br><br><br><br>
									<b><u>( UST. RUMHUL FATTAH )</u></b>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-10">
						<?php
						if ($data->note === 'EMERGENCY') {
							?>
						<p><b>CATATAN:</b> <br>
							Santri tersebut di atas sedang dalam masa skorsing
						<?php
							}
						?>
						</p>
					</div>
                    <div class="col-2">
                        <img src="<?= base_url() ?>assets/images/formal.png" style="width: 100%; bottom: 0px">
                    </div>
				</div>
		<?php
			}
		?>
    </div>
    <!-- <script>
        window.print()
        setTimeout(() => {
            window.close()
        }, 2000);
    </script> -->
</body>

</html>
