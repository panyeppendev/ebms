<script src="<?= base_url('template') ?>/plugins/autoNumeric.js"></script>
<script>
	$('[data-mask]').inputmask();

	toastr.options = {
		"positionClass": "toast-top-center",
		"timeOut": "2000"
	}

	const openDaily = () => {
		$('#modal-open').modal('show')
	}

	const saveOpenDaily = el => {
		const textOpen = '<?= $open ?>'
		const textConfirm = $('#text-confirm').val()

		if (textOpen != textConfirm) {
			toastr.error('Opppss..! Ketikkan dengan benar')
			return false
		}

		Swal.fire({
			title: 'Yakin, nih?',
			text: 'Tindakan ini hanya bisa dilakukan sekali',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			showLoaderOnConfirm: true,
			confirmButtonText: 'OK, Lanjut',
			cancelButtonText: 'Nggak jadi'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= base_url() ?>setdaily/open',
					method: 'POST',
					dataType: 'JSON',
					beforeSend: () => {
						$(el).prop('disabled', true)
					},
					success: function(res) {
						$(el).prop('disabled', false)
						let status = res.status

						if (status != 200) {
							toastr.error(res.message)
							return false
						}

						$('#modal-open').modal('hide')

						Swal.fire({
							icon: 'success',
							title: 'Yeahh...',
							text: 'Transaksi hari ini berhasil dibuka',
							timer: 2000,
							timerProgressBar: true
						})

						setTimeout(function (){
							location.reload()
						}, 2000)
					}
				})
			}
		})
	}

	const closeDaily = () => {
		$('#modal-close').modal('show')
	}

	const saveCloseDaily = el => {
		const textClose = '<?= $close ?>'
		const textConfirm = $('#text-confirm-close').val()

		if (textClose != textConfirm) {
			toastr.error('Opppss..! Ketikkan dengan benar')
			return false
		}

		Swal.fire({
			title: 'Yakin, nih?',
			text: 'Tindakan ini hanya bisa dilakukan sekali',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			showLoaderOnConfirm: true,
			confirmButtonText: 'OK, Lanjut',
			cancelButtonText: 'Nggak jadi'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= base_url() ?>setdaily/close',
					method: 'POST',
					data: {
						textClose
					},
					dataType: 'JSON',
					beforeSend: () => {
						$(el).prop('disabled', true)
					},
					success: function(res) {
						$(el).prop('disabled', false)
						let status = res.status

						if (status != 200) {
							toastr.error(res.message)
							return false
						}

						$('#modal-close').modal('hide')

						Swal.fire({
							icon: 'success',
							title: 'Yeahh...',
							text: 'Transaksi hari ini berhasil ditutup',
							timer: 2000,
							timerProgressBar: true
						})

						setTimeout(function (){
							location.reload()
						}, 2000)
					}
				})
			}
		})
	}


</script>
</body>

</html>
