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

    $('#nominal').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        aForm: true,
        vMax: '999999999',
        vMin: '-999999999'
    });

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
            url: '<?= base_url() ?>deposit/checkid',
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
                    $('#show-check').html('')
                    return false
                }

				$('#nis').val(response.nis)
                $('#nominal').prop('readonly', false).focus().val()

                showCheck(response.nis)
            }
        })
    }

    const showCheck = nis => {
        $.ajax({
            url: '<?= base_url() ?>deposit/showCheck',
            method: 'POST',
            data: {
                nis
            },
            success: function(response) {
                $('#show-check').html(response)
            }
        })
    }

    $('#form-deposit').on('submit', function(e) {
        e.preventDefault()

        const nominal = $('#nominal').autoNumeric('get')
        if (nominal == '' || nominal == 0) {
            errorAlert('Nominal tidak boleh kosong')
            return false
        }

		$('#nominal-real').val(nominal)

        save()
    })

    const saveButton = () => {
        $('#form-deposit').submit()
    }

    const save = () => {
        Swal.fire({
            title: 'Anda yakin?',
            text: 'Tindakan ini akan disimpan permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin!',
            cancelButtonText: 'Cek lagi'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>deposit/store',
                    method: 'POST',
                    data: $('#form-deposit').serialize(),
                    dataType: 'JSON',
                    beforeSend: () => {
                        $('#loading').show()
                    },
                    success: function(res) {
                        $('#loading').hide()
                        let status = res.status
                        if (status == 400) {
                            errorAlert(res.message)
                            $('#nominal').focus().select()
                            return false
                        }

                        toastr.success(`Yeaah! ${ res.message }`)
                        $('#show-check').html('')
                        $('#nominal').prop('readonly', true).val('')
						$('#id').val('').focus()
                    }
                })
            }
        })

    }
</script>
</body>

</html>
