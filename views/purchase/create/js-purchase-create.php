<script>
	$('[data-mask]').inputmask();

	toastr.options = {
		"positionClass": "toast-top-center",
		"timeOut": "2000"
	}

	$('#nis').on('keydown', e => {
		if (e.keyCode === 13) {
			e.preventDefault()
		}
	})

	const saveTemp = () => {
		let nis = $('#nis').val()
		let package = $('#package').val()

		if (nis == '' || package == '') {
			toastr.error('Pastikan nis dan paket sudah diisi')
			return false
		}

		$.ajax({
			url: '<?= base_url() ?>purchase/save',
			method: 'POST',
			data: $('#form-purchase').serialize(),
			success: function(res) {
				$('#show-student').html(res)
			}
		})
	}

	const destroy = () => {
		let nis = $('#nis')
		nis.val('')
		$('#package').val('')
	  	$('.check-addon').prop('checked', false)
		$('#show-student').html('')
		nis.focus()
	}

	const store = (nis, packageId, amount) => {
		Swal.fire({
			title: 'Yakin, nih?',
			text: 'Pastikan pilihan paket dan tambahan sudah benar',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yakin dong',
			cancelButtonText: 'Gak jadi'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: `<?= base_url() ?>purchase/store`,
					data: {
						nis,
						packageId,
						amount
					},
					method: 'POST',
					dataType: 'JSON',
					success: response => {
						let status = response.status
						if (!status) {
							toastr.error(response.message)
							return false
						}

						destroy()
						$('#id').val(response.message)
						toastr.success('Status permbelian berhasil')
						$('#form-invoice').submit()
					}
				})
			}
		})
	}
</script>
