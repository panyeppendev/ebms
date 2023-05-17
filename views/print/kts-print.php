<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<link rel="shortcut icon" href="<?= base_url() ?>/assets/favicon.png">
	<title>Print Out KTS</title>
	<style type="text/css">
		* {
			font-family: Montserrat;
			font-size: 62pt;
		}

		.back {
			width: 870mm;
			height: 547.81mm;
			background-image: url(<?= base_url('assets/images/front.jpg') ?>);
			background-repeat: no-repeat;
			background-size: cover;
		}

		img {
			width: 700px;
		}

		.wrapper {
			padding-top: 600px;
			padding-left: 120px;
			padding-right: 120px;
			display: flex;
		}

		.biodata {
			width: 75%;
			display: block;
			padding-top: 150px
		}

		.images {
			width: 25%;
		}

		.avatar {
			width: 100%
		}

		table {
			width: 100%
		}

		td {
			padding: 8px
		}

		.align-top {
			vertical-align: top!important;
		}

		.barcode {
			margin-top: 120px
		}

		.note {
			text-align: center;
			line-height: 50pt;
			font-size: 40pt;
			padding-top: 40px;
			font-weight: bold;
		}
	</style>
</head>
<?php
$avatarPath = FCPATH . 'assets/avatars/' . $data->id . '.jpg';

if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
	$avatar = base_url('assets/avatars/default.jpg');
} else {
	$avatar = base_url('assets/avatars/' . $data->id . '.jpg');
}
?>
<body>
	<div class="back">
		<div class="wrapper">
			<div class="biodata">
				<table>
					<tr>
						<td class="align-top">Nama</td>
						<td class="align-top">:</td>
						<td class="align-top"> <b> <?= $data->name ?> </b></td>
					</tr>
					<tr>
						<td class="align-top">NIS</td>
						<td class="align-top">:</td>
						<td class="align-top">
							<div style="widht: 50%; display: inline-block">
								<?= $data->id ?>
							</div>
							<div style="padding-right: 100px; float: right; widht: 50%; display: inline-block">
								Gol. Darah : -
							</div>
						</td>
					</tr>
					<tr>
						<td class="align-top">Tetala</td>
						<td class="align-top">:</td>
						<td class="align-top"><?= $data->place_of_birth ?>, <?= dateIDFormatShort($data->date_of_birth) ?></td>
					</tr>
					<tr>
						<td class="align-top">Alamat</td>
						<td class="align-top">:</td>
						<td class="align-top">
							<?= $data->village ?>, <br>
							<?= str_replace(['Kabupaten', 'Kota '], '', $data->city) ?>
						</td>
					</tr>
				</table>
				<div class="barcode">
					<?= $barcode ?>
				</div>
			</div>
			<div class="image">
				<div class="avatar">
					<img src="<?= $avatar ?>" alt="">
				</div>
				<div class="note">
					BERLAKU SELAMA <br> MENJADI SANTRI
				</div>
			</div>
		</div>
	</div>
	<script>
		window.print()
		setTimeout(function() {
			window.close()
		}, 2000);
	</script>
</body>

</html>