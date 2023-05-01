<script src="<?= base_url('template') ?>/plugins/autoNumeric.js"></script>
<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

	$('.indonesian-currency').autoNumeric('init', {
		aSep: '.',
		aDec: ',',
		aForm: true,
		vMax: '999999999',
		vMin: '-999999999'
	});

    $(function() {
        loadData()
    })

    const loadData = () => {
        let name = $('#change-name').val()
        let domicile = $('#change-domicile').val()
        $.ajax({
            url: '<?= base_url() ?>paymentregistration/loaddata',
            method: 'POST',
            data: {
                name,
                domicile
            },
            success: function(response) {
                $('#show-data').html(response)
            }
        })
    }

    $('#modal-package').on('shown.bs.modal', () => {
        $('#id').focus()
    })

    $('#modal-package').on('hidden.bs.modal', () => {
        resetModal()
    })

    const resetModal = () => {
        $('#id').val('')
        $('#id_student').val(0)
        $('#level_of_formal').val(0)
        $('#nominal').val('').prop('readonly', true)
        $('#show-check').html('')
    }

    $('#id').on('keyup', function(e) {
        let id = $(this).val()
        let key = e.which
        if (key != 13) {
            return false
        }

        if (key == 13 && id == '') {
            return false
        }

        checkID(id)
    })

    const checkID = id => {
        $.ajax({
            url: '<?= base_url() ?>paymentregistration/checkid',
            method: 'POST',
            data: {
                id
            },
            dataType: 'JSON',
            success: function(response) {
                let status = response.status
                if (status == 400) {
                    toastr.error(`Oppss..! ${ response.message }`)
                    $('#id').focus().select()
                    $('#nominal').prop('readonly', true)
                    $('#show-check').html('')
                    $('#id-student').val(0)
                    return false
                }
                $('#id-student').val(response.id)
                $('#nominal').prop('readonly', false).val('').focus()
                $('#show-rate').html('')
                showCheck(response.id)
            }
        })
    }

    const showCheck = id => {
        $.ajax({
            url: '<?= base_url() ?>paymentregistration/showCheck',
            method: 'POST',
            data: {
                id
            },
            success: function(response) {
                $('#show-check').html(response)
            }
        })
    }

	$('#nominal').on('keyup', function(e) {
		let nominal = $(this).val()
		let key = e.which
		if (key != 13) {
			return false
		}

		if (key == 13 && nominal == '') {
			return false
		}
		Swal.fire({
			title: 'Anda yakin?',
			text: 'Pastikan nominal sudah diisi',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yakin!',
			cancelButtonText: 'Cek lagi'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= base_url() ?>paymentregistration/save',
					method: 'POST',
					data: {
						id: $('#id-student').val(),
						nominal
					},
					dataType: 'JSON',
					success: function(response) {
						console.log(response)
						let status = response.status
						if (status == 400) {
							toastr.error(`Oppss..! ${response.message}`)
							return false
						}
						$('#invoice').val(response.invoice)
						toastr.success('Yeaay..! Satu pembayaran berhasil')
						resetModal()
						$('#id').focus()
						let invoice = $('#invoice').val()
						if (invoice != 0) {
							Swal.fire({
								title: 'Pembayaran Sukses....',
								icon: 'success',
								html: 'Otomatis print invoice dalam <strong>1</strong> detik.<br/><br/>',
								timer: 1000,
								timerProgressBar: true
							})
							setTimeout(function() {
								$('#form-print').submit()
							}, 1000)
						}
					}
				})
			}
		})
	})

</script>
</body>

</html>
