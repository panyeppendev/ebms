<script>
	$('[data-mask]').inputmask();

	toastr.options = {
		"positionClass": "toast-top-center",
		"timeOut": "2000"
	}

	$(function (){
		loadRombel()
	})

	const setKelas = () => {
		let kelas = $('#kelas').val()
		$('#kelas-murid').val(kelas)
		$('#form-murid-set').submit()
	}

	const getAdd = (level, kelas, rombel) => {
		$.ajax({
			url: '<?= base_url() ?>educationsetting/getadd',
			method: 'post',
			data: {
				level,
				kelas,
				rombel
			},
			success: res => {
				$('#show-add').html(res)
			}
		})
	}

	const set = () => {
		const level = $('#level-murid').val()
		const kelas = $('#kelas-murid').val()
		const rombel = $('#rombel-murid').val()
		if (kelas == '' || rombel == ''){
			toastr.error('Kelas atau rombel belum dipilih')
			return false
		}
		getAdd(level, kelas, rombel)

		$('#rombel_changed').val(rombel)
		$('#modal-set').modal('show')
	}

	const loadRombel = () => {
		const grade = $('#kelas-murid').val()
		const level = $('#level-murid').val()
		const rombel = $('#rombel-murid').val()
		$.ajax({
			url: '<?= base_url() ?>educationsetting/loadRombel',
			method: 'post',
			data: {
				grade,
				level,
				rombel
			},
			success: res => {
				$('#show-data-set').html(res)
			}
		})
	}

	const setCount = () => {
		let checked = $('.check-rombel:checked').length
		$('#count-checked').text(checked)
	}

	$('#save-set-murid').on('click', () => {
		let checked = $('.check-rombel:checked').length
		if (checked <= 0) {
			toastr.error('Pastikan sudah checklist murid yang akan ditambahkan')
			return false
		}
		Swal.fire({
			title: 'Yakin, nih?',
			text: 'Data akan disimpan permanen',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'OK, Lanjut'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= base_url() ?>educationsetting/savesetmurid',
					method: 'post',
					data: $('#form-save-set-murid').serialize(),
					dataType: 'json',
					success: function (res){
						if (res.status != 200){
							toastr.error(res.message)
							return false
						}

						toastr.success(res.message)
						loadRombel()
						$('#count-checked').text(0)
						$('#modal-set').modal('hide')
					}
				})
			}
		})

	})
</script>
</body>

</html>
