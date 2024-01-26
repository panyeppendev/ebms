<script src="<?= base_url('template') ?>/plugins/autoNumeric.js"></script>
<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

	let modalSearch = $('#modal-search-name')
    $('body').on('keydown', e => {
        if (e.keyCode == 113) {
            $('#card').focus().val('')
			return false
        }

		if (e.keyCode == 115) {
			modalSearch.modal('show')
		}
    })

	modalSearch.on('shown.bs.modal', function (){
		$('#search-name').val('').focus()
	})

	modalSearch.on('hidden.bs.modal', function (){
		$('#search-name').val('')
		$('#show-search-result').html('')
	})

	$('#search-name').on('keyup', function(e) {
		let name = $(this).val()
		let key = e.which
		if (key != 13) {
			return false
		}

		if (key == 13 && name == '') {
			return false
		}

		searchCard(name)
	})

	const searchCard = name => {
		$.ajax({
			url: `<?= base_url() ?>transaction/searchCard/${name}`,
			method: 'GET',
			success: function(res) {
				$('#show-search-result').html(res)
			}
		})
	}

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

	$('#nominal').on('keyup', function (e){
		let cardEl = $('#card')
		let nominal = $(this).autoNumeric('get')
		let key = e.which
		let card = cardEl.val()

		if (key != 13) {
			return false
		}

		if (key == 13 && card == '') {
			cardEl.val('').focus()
			$(this).val('').prop('readonly', true)
			return false
		}
		if (key == 13 && nominal == '' || nominal == 0) {
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
			confirmButtonText: 'OK, Lanjut',
			cancelButtonText: 'Nggak jadi'
		}).then((result) => {
			if (result.isConfirmed) {
				save()
			}
		})
	})

    const checkCard = card => {
		let nominalEl = $('#nominal')
        $.ajax({
            url: '<?= base_url() ?>transaction/checkCard',
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

				if (res.total <= 0) {
					nominalEl.prop('readonly', true).val('')
					errorAlert('Saldo limit tidak tersedia')
				}else {
					nominalEl.prop('readonly', false).focus()
				}


				$('#nis').val(res.nis)
				$('#purchase').val(res.purchase)
				$('#account').val(res.account)
				$('#pocket').val(res.pocket)
				$('#deposit').val(res.deposit)
				$('#total').val(res.total)

				getData(res.nis)
            }
        })
    }

	const getData = nis => {
		$.ajax({
			url: '<?= base_url() ?>transaction/getdata',
			method: 'POST',
			data: {
				nis
			},
			success: function(res) {
				$('#show-data').html(res)
			}
		})
	}

    const save = () => {
        $.ajax({
            url: '<?= base_url() ?>transaction/save',
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
				$('#show-data').html('')
				$('#purchase').val('')
				$('#account').val('')
				$('#total').val('')
				$('#nominal-real').val('')
                $('#nominal').prop('readonly', true).val('')

				dailyTotal()
				transactions()

				$('#card').val('').focus()
            }
        })
    }

	const transactions = () => {
	  	const name = $('#filter-name').val()
		$.ajax({
			url: '<?= base_url() ?>transaction/transactions',
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

	const dailyTotal = () => {
		$.ajax({
			url: '<?= base_url() ?>transaction/dailytotal',
			method: 'POST',
			data: {
				date: $('#date').val()
			},
			dataType: 'JSON',
			success: (res) => {
				$('#daily-total').text(res)
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

		transactions()
	})

	$(function (){
		transactions()
		dailyTotal()
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
					url: '<?= base_url() ?>transaction/destroy',
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
						dailyTotal()
						transactions()
					}
				})
			}
		})
	}

	function copyToClipboard(text) {
		var sampleTextarea = document.createElement("textarea");
		document.body.appendChild(sampleTextarea);
		sampleTextarea.value = text; //save main text in it
		sampleTextarea.select(); //select textarea contenrs
		document.execCommand("copy");
		document.body.removeChild(sampleTextarea);
		toastr.success('ID berhasil disalin ke clipboard')

		modalSearch.modal('hide')
		$('#card').val(text).focus()
	}
</script>
</body>

</html>
