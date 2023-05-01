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
        let status = $('#change-status').val()
        let domicile = $('#change-domicile').val()
        $.ajax({
            url: '<?= base_url() ?>payment/loaddata',
            method: 'POST',
            data: {
                name,
                status,
                domicile
            },
            success: function(response) {
                $('#show-data').html(response)
            }
        })
    }

    const packageActivation = (el, step) => {
        Swal.fire({
            title: 'Anda yakin?',
            text: 'Tindakan ini tidak otomatis menambah pencairan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin!',
            cancelButtonText: 'Masih ragu'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>package/packageactivation',
                    method: 'POST',
                    data: {
                        step
                    },
                    dataType: 'JSON',
                    beforeSend: function() {
                        $(el).prop('disabled', true)
                    },
                    success: function(res) {
                        $(el).prop('disabled', false)
                        let status = res.status
                        if (status == 400) {
                            toastr.error(`Opps..! ${res.message}`)
                            return false
                        }
                        toastr.success('Yess..! Paket tahap ini berhasil diaktifkan')
                        let currentStep = $('#current-step').val()
                        loadData(currentStep)
                    }
                })
            }
        })
    }

    $('#change-step').on('change', function() {
        let step = $(this).val()
        $('#current-step').val($(this).val())
        loadData(step)
    })

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
            url: '<?= base_url() ?>payment/checkid',
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
            url: '<?= base_url() ?>payment/showCheck',
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
					url: '<?= base_url() ?>payment/save',
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

    const deletePackage = id => {
        Swal.fire({
            title: 'Anda Yakin?',
            text: 'Data akan dihapus permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin, dong!',
            cancelButtonText: 'Nggak jadi!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>package/deletepackage',
                    method: 'POST',
                    data: {
                        id
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        let status = response.status
                        if (status == 400) {
                            toastr.error('Opss! Server gagal merespon. Coba resfresh halaman')
                            return false
                        }

                        toastr.success('Yeaah! Satu data berhasil dihapus')
                        let currentStep = $('#current-step').val()
                        loadData(currentStep)
                    }
                })
            }
        })
    }

</script>
</body>

</html>
