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
			background-image: url(<?= base_url('assets/images/ktws-front.jpg') ?>);
			background-repeat: no-repeat;
			background-size: cover;
		}

		img {
			width: 700px;
		}

		.wrapper {
			padding-top: 600px;
			padding-left: 150px;
			padding-right: 150px;
			display: flex;
		}

		.biodata {
			width: 75%;
			display: block;
			padding-top: 150px
		}

		.image {
			width: 25%;
			padding-top: 150px
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
$avatarPath = FCPATH . 'assets/fathers/' . $data->id . '.jpg';

if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
	$avatar = base_url('assets/fathers/default.jpg');
} else {
	$avatar = base_url('assets/fathers/' . $data->id . '.jpg');
}
?>
<body>
	<div class="back">
		<div class="wrapper">
			<div class="biodata">
				<table>
					<tr>
						<td class="align-top">Ayah</td>
						<td class="align-top">:</td>
						<td class="align-top"> <b> <?= $data->father ?> </b></td>
					</tr>
					<tr>
						<td class="align-top">Ibu</td>
						<td class="align-top">:</td>
						<td class="align-top"> <b> <?= $data->mother ?> </b></td>
					</tr>
					<tr>
						<td class="align-top">Wali dari</td>
						<td class="align-top">:</td>
						<td class="align-top"><?= $data->name ?></td>
					</tr>
					<tr>
						<td class="align-top">NIS</td>
						<td class="align-top">:</td>
						<td class="align-top"><?= $data->id ?></td>
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
					BERLAKU SELAMA <br> MENJADI WALI SANTRI
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