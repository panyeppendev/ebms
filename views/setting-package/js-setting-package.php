<script>
	toastr.options = {
		"positionClass": "toast-top-center",
		"timeOut": "2000"
	}

	$(function() {
		$('#package-selected').val($('#change-package').val())
		settings()
	})

	let modalElement = $('#modal-package')

	modalElement.on('hidden.bs.modal', () => {
		$('#sow-detail').html('')
	})

	const settings = () => {
		let id = $('#change-package').val()
		$.ajax({
			url: '<?= base_url() ?>settingpackage/settings',
			method: 'POST',
			data: {
				package: id
			},
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

	const packageSelected = (el) => {
		$('#package-selected').val($(el).val())
		settings()
	}

	const createLimit = () => {
	  	$('#form-create').submit()
	}

</script>
