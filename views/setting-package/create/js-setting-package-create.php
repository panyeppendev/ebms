<script src="<?= base_url('template') ?>/plugins/autoNumeric.js"></script>
<script>
	toastr.options = {
		"positionClass": "toast-top-center",
		"timeOut": "2000"
	}

	const packageSelected = (el) => {
	  	$('#package-selected').val($(el).val())
		$('#form-create').submit()
	}

	$('.limit').autoNumeric('init', {
		aSep: '.',
		aDec: ',',
		mDec: '0'
	});

	$('.form-control').keypress(function(e) {
		if (e.which == 13 && $(this).val() != '') {
			e.preventDefault()
			let $next = $('[tabIndex=' + (+this.tabIndex + 1) + ']');
			if (!$next.length) {
				$next = $('[tabIndex=1]');
			}
			$next.focus().select();
		}
	});

	nameElement = $('#name')
	errorNameElement = $('#error-name')

	const handleSubmit = (el) => {
		Swal.fire({
			title: 'Yakin, nih?',
			text: 'Pastikan data sudah valid',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yakin dong',
			cancelButtonText: 'Gak jadi'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= base_url() ?>settingpackage/store',
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
							$('#form-create').submit()
						}, 2000)
					}
				})
			}
		})
	}

	const store = el => {

	}
</script>
