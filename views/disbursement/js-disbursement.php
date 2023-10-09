<script src="<?= base_url('template') ?>/plugins/autoNumeric.js"></script>
<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

    $('body').on('keydown', e => {
        if (e.keyCode == 113) {
            $('#nis').focus().val('')
        }
    })

    $('#reservation').daterangepicker({
        singleDatePicker: true,
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Reset',
            applyLabel: 'Terapkan'
        }
    })

    $('#reservation').on('apply.daterangepicker', function(ev, picker) {
        $(this).val('').attr('placeholder', picker.startDate.format('DD/MM/YYYY'));
        $('#filter-date').val(picker.startDate.format('YYYY-MM-DD'))

        loadRecap()
    });

    $('#reservation').on('cancel.daterangepicker', function(ev, picker) {
        $(this).attr('placeholder', 'Hari ini').val('');
        $('#filter-date').val('')

        loadRecap()
    });

    $('.indonesian-currency').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        aForm: true,
        vMax: '999999999',
        vMin: '-999999999'
    });

    const errorAlert = message => {
        toastr.error(`Opss.! ${ message }`)
    }

    $('#card').on('keyup', function(e) {
        let card = $(this).val()
        let key = e.which
        if (key != 13) {
            return false
        }

        if (key == 13 && card == '') {
            return false
        }

        checkCard(card)
    })

    const checkCard = card => {
		let nominalEl = $('#nominal')
        $.ajax({
            url: '<?= base_url() ?>disbursement/checkCard',
            method: 'POST',
            data: {
                card
            },
            dataType: 'JSON',
            success: function(res) {
                let status = res.status
                if (status != 200) {
                    errorAlert(res.message)
                    $('#card').focus().val('')
                    $('#show-data').hide()
                    nominalEl.prop('readonly', true)
                    return false
                }

				nominalEl.prop('readonly', false).focus()
				$('#nominal').autoNumeric('set', res.total)
				$('#nis').val(res.nis)
				$('#purchase').val(res.purchase)
				$('#account').val(res.account)
				$('#total').val(res.total)
				$('#name').text(res.name)
				$('#address').text(res.address)
				$('#domicile').text(res.domicile)
				$('#diniyah').text(res.diniyah)
				$('#formal').text(res.formal)
				$('#total-text').text(res.total)
				$('#show-data').show()
            }
        })
    }

	$('#form-disbursement').on('submit', function(e) {
		e.preventDefault()

		const nominal = $('#nominal').autoNumeric('get')

		if (nominal == '' || nominal == 0) {
			errorAlert('Nominal tidak boleh kosong')
			return false
		}

		$('#nominal-real').val(nominal)
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
				save()
			}
		})
	})

    const save = () => {
        $.ajax({
            url: '<?= base_url() ?>disbursement/save',
            method: 'POST',
            data: $('#form-disbursement').serialize(),
            dataType: 'JSON',
            beforeSend: () => {
                $('#loading').show()
            },
            success: function(res) {
                $('#loading').hide()
                let status = res.status
                if (status != 200) {
                    errorAlert(res.message)
                    $('#nominal').focus().val('')
                    return false
                }

                toastr.success('Satu data berhasil ditambahkan')
                $('#card').focus().val('')
				$('#purchase').val('')
				$('#account').val('')
				$('#total').val('')
				$('#nominal-real').val('')
                $('#nominal').prop('readonly', true).val('')
				$('#show-data').hide()
				disbursements()
            }
        })
    }

	const disbursements = () => {
	  	const name = $('#filter-name').val()
		$.ajax({
			url: '<?= base_url() ?>disbursement/disbursements',
			method: 'POST',
			data: {
				name,
				date: $('#date').val()
			},
			success: (res) => {
				$('#show-disbursement').html(res)
			}
		})
	}

	$('#filter-name').on('keyup', function(e) {
		let key = e.which
		if (key != 13) {
			return false
		}

		if (key == 13 && card == '') {
			return false
		}

		disbursements()
	})

	$(function (){
		disbursements()
	})

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
					url: '<?= base_url() ?>disbursement/destroy',
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
						disbursements()
					}
				})
			}
		})
	}
</script>
</body>

</html>
