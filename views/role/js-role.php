<script>
	toastr.options = {
		"positionClass": "toast-top-center",
		"timeOut": "2000"
	}

	$(function() {
		roles()
	})

	let modalElement = $('#modal-role')
	let nameElement = $('#name')

	modalElement.on('hidden.bs.modal', () => {
		nameElement.val('')
		$('.errors').html('')
	})

	modalElement.on('shown.bs.modal', () => {
		nameElement.focus().val('')
	})

	$('#save-role').on('click', function() {
		$.ajax({
			url: '<?= base_url() ?>role/save',
			method: 'post',
			data: {
				name: nameElement.val()
			},
			dataType: 'JSON',
			beforeSend: function() {
				$('#save-role').prop('disabled', true)
			},
			success: function(response) {
				$('#save-role').prop('disabled', false)
				let status = response.status
				if (status == 400) {
					let message = response.message
					$('#error-name').html(message)
					nameElement.addClass('is-invalid')
					return false
				}

				if (status == 500) {
					toastr.error('Opsss.! Kesalahan server nih. Coba Refresh page')
					return false
				}

				$('.errors').html('')
				$('.form-control-border').removeClass('is-invalid')
				nameElement.val('');
				roles()

				toastr.success('Yeaah, satu role berhasil ditambahkan')
			}
		})
	})

	const roles = () => {
		$.ajax({
			url: '<?= base_url() ?>role/roles',
			method: 'POST',
			success: response => {
				$('#show-role').html(response)
			}
		})
	}

</script>
