<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b>Version</b> 1.8.15
    </div>
    <?php
    $currentYear = date('Y');
    ?>
    <strong>Copyright &copy; <?= (date('Y') != $currentYear) ? $currentYear . '-' : '' ?><?= $currentYear ?> </strong><span class="text-default">e-BMS PPMU. Panyeppen</span> All rights reserved.
</footer>

</div>
<!-- jQuery -->
<script src="<?= base_url() ?>template/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url() ?>template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>template/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="<?= base_url() ?>template/plugins/inputmask/jquery.inputmask.min.js"></script>
<script src="<?= base_url() ?>template/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>template/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="<?= base_url() ?>template/plugins/toastr/toastr.min.js"></script>
<script src="<?= base_url() ?>template/dist/js/adminlte.min.js"></script>
<script src="<?= base_url() ?>template/plugins/croppie/croppie.js"></script>
<script src="<?= base_url() ?>template/plugins/croppie/exif.js"></script>
<script>
	const switchRole = role_id => {
		$.ajax({
			url: '<?= base_url() ?>auth/switchRole',
			method: 'POST',
			data: {
				role_id
			},
			dataType: 'JSON',
			success: data => {
				let status = data.status
				if (status !== 200) {
					console.log(data.message)
					return false
				}

				window.location.href = '<?= base_url() ?>'
			}

		})
	}
</script>
