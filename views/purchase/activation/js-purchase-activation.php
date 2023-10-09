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

		if (nis == '') {
			toastr.error('Pastikan NIS sudah diisi')
			return false
		}

		$.ajax({
			url: '<?= base_url() ?>purchase/purchasebyid',
			method: 'POST',
			data: {
				nis
			},
			success: function(res) {
				$('#show-purchase').html(res)
			}
		})
	}

	const activate = id => {
		Swal.fire({
			title: 'Yakin, nih?',
			text: 'Aktivasi hanya dilakukan sekali',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yakin dong',
			cancelButtonText: 'Gak jadi'
		}).then((result) => {
			if (result.isConfirmed) {
				setActivate(id)
			}
		})
	}

	const setActivate = id => {
		$.ajax({
			url: '<?= base_url() ?>purchase/activate',
			method: 'POST',
			data: {
				id
			},
			dataType: 'JSON',
			success: function(res) {
				let status = res.status
				if (status != 200) {
					toastr.error(res.message)
					return false
				}

				toastr.success(res.message)
				saveTemp()
			}
		})
	}

	const finished = id => {
		Swal.fire({
			title: 'Yakin, nih?',
			text: 'Penutupan paket hanya dilakukan sekali',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yakin dong',
			cancelButtonText: 'Gak jadi'
		}).then((result) => {
			if (result.isConfirmed) {
				setFinished(id)
			}
		})
	}

	const setFinished = id => {
		$.ajax({
			url: '<?= base_url() ?>purchase/finished',
			method: 'POST',
			data: {
				id
			},
			success: function(res) {
				let status = res.status
				if (status != 200) {
					toastr.error(res.message)
					return false
				}

				toastr.success(res.message)
				saveTemp()
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
