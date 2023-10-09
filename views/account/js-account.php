<script src="<?= base_url('template') ?>/plugins/autoNumeric.js"></script>
<script>
	toastr.options = {
		"positionClass": "toast-top-center",
		"timeOut": "2000"
	}

	$(function() {
		accounts()
	})

	let modalElement = $('#modal-account')
	let idElement = $('#id')
	let categoryElement = $('#category')
	let nameElement = $('#name')
	let nominalElement = $('#nominal')

	nominalElement.autoNumeric('init', {
		aSign: 'Rp.   ',
		aSep: '.',
		aDec: ',',
		mDec: '0'
	});

	modalElement.on('hidden.bs.modal', () => {
		idElement.val('')
		nameElement.val('')
		categoryElement.val('')
		nominalElement.val('')
		$('.form-control-border').removeClass('is-invalid')
		$('.errors').html('')
	})

	$('#save-account').on('click', function() {
		$.ajax({
			url: '<?= base_url() ?>account/save',
			method: 'post',
			data: {
				id: idElement.val(),
				category: categoryElement.val(),
				name: nameElement.val(),
				nominal: nominalElement.autoNumeric('get')
			},
			dataType: 'JSON',
			beforeSend: function() {
				$('#save-account').prop('disabled', true)
			},
			success: function(response) {
				$('#save-account').prop('disabled', false)
				let status = response.status
				if (status == 400) {
					let errors = response.errors
					if (errors.category) {
						$('#error-category').html(errors.category)
						$('#category').addClass('is-invalid')
					}else {
						$('#error-category').html('')
						$('#category').removeClass('is-invalid')
					}

					if (errors.name) {
						$('#error-name').html(errors.name)
						$('#name').addClass('is-invalid')
					}else {
						$('#error-name').html('')
						$('#name').removeClass('is-invalid')
					}
					return false
				}

				if (status == 500) {
					toastr.error(`Opsss.! ${response.message}`)
					return false
				}

				$('.errors').html('')
				$('.form-control-border').removeClass('is-invalid')
				categoryElement.val('')
				nameElement.val('');
				accounts()

				toastr.success(`Yeaah, satu akun berhasil ${response.message}`)
				modalElement.modal('hide')
			}
		})
	})

	const accounts = () => {
		let category = $('#change-category').val()
		$.ajax({
			url: '<?= base_url() ?>account/accounts',
			data: {
				category
			},
			method: 'POST',
			success: response => {
				$('#show-account').html(response)
			}
		})
	}

	const edit = id => {
		$.ajax({
			url: `<?= base_url() ?>account/edit`,
			data: {
				id
			},
			method: 'POST',
			dataType: 'JSON',
			success: response => {
				let status = response.status
				if (status != 200) {
					toastr.error(response.message)
					return false
				}

				idElement.val(id)
				categoryElement.val(response.data.category)
				nameElement.val(response.data.name)
				nominalElement.autoNumeric('set', response.data.nominal)
				modalElement.modal('show')
			}
		})
	}

	const setStatus = (id, status) => {
		Swal.fire({
			title: 'Yakin, nih?',
			text: 'Anda bisa mengubah status di lain waktu',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yakin dong',
			cancelButtonText: 'Gak jadi'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: `<?= base_url() ?>account/setstatus`,
					data: {
						id,
						status
					},
					method: 'POST',
					dataType: 'JSON',
					success: response => {
						let status = response.status
						if (!status) {
							toastr.error(response.message)
							return false
						}

						accounts()
						toastr.success('Status akun berhasil diperbarui')
					}
				})
			}
		})
	}

</script>
