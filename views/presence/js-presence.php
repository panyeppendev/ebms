<script>
	toastr.options = {
		"positionClass": "toast-top-center",
		"timeOut": "2000"
	}

	$(function () {
		loadData()
	})

	const loadData = () => {
		const level = $('#change-level').val()
		const grade = $('#change-grade').val()
		const rombel = $('#change-rombel').val()

		$.ajax({
			url: '<?= base_url() ?>presence/loaddata',
			method: 'post',
			data: {
				level,
				grade,
				rombel
			},
			success: res => {
				$('#show-index').html(res)
			}
		})

	}

	$('#change-level').on('change', function () {
		$('#level').val($(this).val())
		$('#form-filter').submit()
	})

	$('#change-grade').on('change', function () {
		$('#grade').val($(this).val())
		$('#form-filter').submit()
	})

	$('#change-rombel').on('change', function () {
		$('#rombel-set').val($(this).val())
	})

	const set = () => {
	  	const level = $('#change-level').val()
	  	const grade = $('#change-grade').val()
	  	const rombel = $('#change-rombel').val()
		if (level == '' || grade == '' || rombel == '') {
			toastr.error('Pastikan tingkat, kelas, dan rombel sudah dipilih')
			return false
		}
		$('#show-add').html('')
		$.ajax({
			url: '<?= base_url() ?>presence/loadadd',
			method: 'post',
			data: {
				level,
				grade,
				rombel
			},
			success: res => {
				$('#show-add').html(res)
				$('#modal-set').modal('show')
			}
		})
	}

	const nextTab = (el, e) => {
		if (e.which == 13 && $(el).val() != '') {
			e.preventDefault()
			let $next = $('[tabIndex=' + (+el.tabIndex + 1) + ']');
			if (!$next.length) {
				$next = $('[tabIndex=1]');
			}
			$next.focus().select();
		}
	}

	$('#save-set').on('click', function () {
		let month = $('#month').val()
		if (month == ''){
			toastr.error('Pastikan bulan sudah dipilih')
			return false
		}

		Swal.fire({
			title: 'Yakin, nih?',
			text: 'Pastikan data diisi dengan benar',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'OK, Lanjut!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= base_url() ?>presence/save',
					method: 'post',
					data: $('#form-set-save').serialize(),
					dataType: 'json',
					success: res => {
						if (res.status != 200) {
							toastr.error(res.message)
							return false
						}

						toastr.success(res.message)
						$('#show-add').html('')
						$('#modal-set').modal('hide')
					}
				})
			}
		})
	})
</script>
