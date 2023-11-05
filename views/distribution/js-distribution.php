<script src="<?= base_url('template') ?>/plugins/autoNumeric.js"></script>
<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

    const errorAlert = message => {
        toastr.error(`Opss.! ${ message }`)
    }

	const setTransaction = (table, category) => {
		Swal.fire({
			title: 'Yakin, nih?',
			text: 'Pastikan Anda memilih dengan teliti',
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
					url: '<?= base_url() ?>distribution/set',
					method: 'POST',
					dataType: 'JSON',
					data: {
						table,
						category
					},
					success: (res) => {
						let status = res.status
						if (status != 200) {
							toastr.error(res.message)
							return false
						}

						Swal.fire({
							icon: 'success',
							title: 'Yeahh...',
							text: 'Jenis Distribusi berhasil diatur',
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

	const distributions = () => {
	  	const name = $('#filter-name').val()
		$.ajax({
			url: '<?= base_url() ?>distribution/distributions',
			method: 'POST',
			data: {
				name,
				date: $('#setting').val(),
				category: $('#setting-category').val()
			},
			success: (res) => {
				$('#show-distribution').html(res)
				$('#modal-data').modal('show')
			}
		})
	}

	const destroy = id => {
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
					url: '<?= base_url() ?>distribution/destroy',
					method: 'POST',
					dataType: 'JSON',
					data: {
						id
					},
					success: (res) => {
						let status = res.status
						if (status != 200) {
							toastr.error(res.message)
							return false
						}

						toastr.success(res.message)
						$('#modal-data').modal('hide')
					}
				})
			}
		})
	}
</script>
</body>

</html>
