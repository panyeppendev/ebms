<script>
	toastr.options = {
		"positionClass": "toast-top-center",
		"timeOut": "2000"
	}

	$(function() {
		packages()
	})

	let modalElement = $('#modal-package')

	modalElement.on('hidden.bs.modal', () => {
		$('#sow-detail').html('')
	})

	const packages = () => {
		$.ajax({
			url: '<?= base_url() ?>package/packages',
			method: 'POST',
			success: response => {
				$('#show-package').html(response)
			}
		})
	}

	const detail = id => {
		$.ajax({
			url: '<?= base_url() ?>package/packagebyid',
			method: 'POST',
			data: {
				id
			},
			success: response => {
				$('#show-detail').html(response)
				modalElement.modal('show')
			}
		})
	}

</script>
