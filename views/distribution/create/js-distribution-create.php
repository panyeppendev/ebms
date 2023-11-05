<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

    $('body').on('keydown', e => {
        if (e.keyCode == 113) {
            $('#card').focus().val('')
        }
    })

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
		let date = $('#date')
		let nis = $('#nis')
		let purchase = $('#purchase')
		let account = $('#account')
		let nominal = $('#nominal')
		let status = $('#status')

        $.ajax({
            url: '<?= base_url() ?>distribution/checkCard',
            method: 'POST',
            data: {
                card
            },
            dataType: 'JSON',
            success: function(res) {

                if (res.status != 200) {
                    errorAlert(res.message)
                    $('#card').focus().val('')
                    $('#show-data').hide()
                    return false
                }

				getData(res.nis, res.package)

				if (res.nominal <= 0) {
					errorAlert('Saldo limit tidak tersedia')
					$('#card').select()
					date.val('')
					nis.val('')
					purchase.val('')
					account.val('')
					nominal.val('')
					status.val('')
					return false
				}

				date.val(res.date)
				nis.val(res.nis)
				purchase.val(res.purchase)
				account.val(res.account)
				nominal.val(res.nominal)
				status.val(res.category)

				setTimeout(function () {
					store()
				}, 100);
            }
        })
    }

	const getData = (nis, packageId) => {
		$.ajax({
			url: '<?= base_url() ?>distribution/getdata',
			method: 'POST',
			data: {
				nis,
				package: packageId
			},
			success: function(res) {
				$('#show-data').html(res)
			}
		})
	}

    const store = () => {
        $.ajax({
            url: '<?= base_url() ?>distribution/store',
            method: 'POST',
            data: $('#form-distribution').serialize(),
            dataType: 'JSON',
            beforeSend: () => {
                $('#loading').show()
            },
            success: function(res) {
                $('#loading').hide()

				$('#date').val('')
				$('#nis').val('')
				$('#purchase').val('')
				$('#account').val('')
				$('#nominal').val('')
				$('#card').val('').focus()

                let status = res.status
                if (status != 200) {
                    errorAlert(res.message)
                    return false
                }

                toastr.success('Satu data berhasil ditambahkan')
            }
        })
    }
</script>
</body>

</html>
