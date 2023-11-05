<script src="<?= base_url('template') ?>/plugins/autoNumeric.js"></script>
<script>
	$('[data-mask]').inputmask();

	toastr.options = {
		"positionClass": "toast-top-center",
		"timeOut": "2000"
	}

	const setAccount = table => {
		$('#table').val(table)
		$('#modal-set').modal('show')
	}

	const saveSetAccount = el => {
		const table = $('#table').val()
		const account = $('#account').val()

		if (account == '') {
			toastr.error('Opppss..! Pilih dulu rekening')
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
					url: '<?= base_url() ?>setdaily/store',
					method: 'POST',
					data: {
						table,
						account
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

						$('#modal-open').modal('hide')

						Swal.fire({
							icon: 'success',
							title: 'Yeahh...',
							text: 'Rekening berhasil diatur',
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
