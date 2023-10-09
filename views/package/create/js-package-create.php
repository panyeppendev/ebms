<script src="<?= base_url('template') ?>/plugins/autoNumeric.js"></script>
<script>
	toastr.options = {
		"positionClass": "toast-top-center",
		"timeOut": "2000"
	}

	$('.account').autoNumeric('init', {
		aSep: '.',
		aDec: ',',
		mDec: '0'
	});

	$('.form-control').keypress(function(e) {
		if (e.which == 13 && $(this).val() != '') {
			e.preventDefault()
			let $next = $('[tabIndex=' + (+this.tabIndex + 1) + ']');
			if (!$next.length) {
				$next = $('[tabIndex=0]');
			}
			$next.focus().select();
		}
	});

	nameElement = $('#name')
	errorNameElement = $('#error-name')

	const handleSubmit = (el) => {
		$.ajax({
			url: '<?= base_url() ?>package/save',
			method: 'post',
			data: {
				name: nameElement.val()
			},
			dataType: 'JSON',
			beforeSend: function() {
				$(el).prop('disabled', true)
			},
			success: function(response) {
				$(el).prop('disabled', false)
				let status = response.status
				if (!status) {
					let errors = response.errors

					if (errors.name) {
						errorNameElement.html(errors.name)
						nameElement.addClass('is-invalid')
					}else {
						errorNameElement.html('')
						nameElement.removeClass('is-invalid')
					}
					return false
				}
				errorNameElement.html('')
				nameElement.removeClass('is-invalid')
				nameElement.addClass('is-valid')

				Swal.fire({
					title: 'Yakin, nih?',
					text: 'Pastikan detil paket sudah diisi',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yakin dong',
					cancelButtonText: 'Gak jadi'
				}).then((result) => {
					if (result.isConfirmed) {
						store(el)
					}
				})
			}
		})
	}

	const store = el => {
		$.ajax({
			url: '<?= base_url() ?>package/store',
			method: 'post',
			data: $('#form-package').serialize(),
			dataType: 'JSON',
			beforeSend: function() {
				$(el).prop('disabled', true)
			},
			success: function(response) {
				$(el).prop('disabled', false)
				let status = response.status
				if (!status) {
					let message = response.message

					toastr.error(message)
					return false
				}

				Swal.fire({
					icon: 'success',
					title: 'Yeahh...',
					text: 'Satu paket berhasil dtambahkan',
					timer: 2000,
					timerProgressBar: true
				})

				setTimeout(function (){
					window.location.href = `<?= base_url() ?>package/edit/${response.message}`;
				}, 2000)
			}
		})
	}
</script>
