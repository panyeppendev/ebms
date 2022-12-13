<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<link rel="shortcut icon" href="<?= base_url() ?>/assets/favicon.png">
	<title>Print Out KTS</title>
	<style type="text/css">
		.back {
			width: 870mm;
			height: 547.81mm;
			background-image: url(<?= base_url('assets/images/behind.jpg') ?>);
			background-repeat: no-repeat;
			background-size: cover;
		}
	</style>
</head>
<body>
	<div class="back">
	</div>
	<script>
		window.print()
		setTimeout(function() {
			window.close()
		}, 2000);
	</script>
</body>

</html>