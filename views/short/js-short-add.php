<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

    const errorAlert = message => {
        toastr.error(`Opss.! ${ message }`)
    }

    $('body').on('keyup', e => {
        if (e.keyCode == 113) {
            $('#nis').focus().val('')
        }
    })

    $('#nis').on('keyup', function(e) {
        let nis = $(this).val()
        let key = e.which
        if (key != 13) {
            return false
        }

        if (key == 13 && nis == '') {
            errorAlert('Pastikan NIS tidak kosong')
            return false
        }

        checkNis(nis)
    })

    const checkClicked = () => {
        let nis = $('#nis').val()
        if (nis == '') {
            errorAlert('Pastikan NIS tidak kosong')
            return false
        }

        checkNis(nis)
    }

    const checkNis = nis => {
        let step = $('#current-step').val()
        $.ajax({
            url: '<?= base_url() ?>short/checknis',
            method: 'POST',
            data: {
                nis,
                step
            },
            dataType: 'JSON',
            success: function(res) {
                
                $('#show-nominal').hide()
                let status = res.status
                if (status == 500) {
                    errorAlert(res.message)
                    $('#nis').focus().val('')
                    $('#wrap-type').hide()
                    $('#show-data').html('')
                    $('#button-check').show()
                    $('.btn-save').hide()
                    return false
                }

                if (status == 400) {
                    $('#button-check').show()
                    $('#wrap-type').hide()
                    $('.btn-save').hide()
                    getData(res)

                    return false
                }

                $('#wrap-type').show()
                $('.btn-save').show()
                $('#button-check').hide()
                $('#show-nominal').show()
                $('#nominal').val(res.nominal)
                $('#nominal-rp').html(res.rp)
                $('#package').val(res.package)
                $('#nis-save').val(res.nis)
                getData(res)
            }
        })
    }

    const getData = data => {
        $.ajax({
            url: '<?= base_url() ?>short/getdata',
            method: 'POST',
            data: {
                nis: data.nis,
                package: data.package,
				message: data.message
            },
            success: function(res) {
                $('#show-success').hide()
                $('#show-data').html(res)
            }
        })
    }

    const setStatusBeforeSave = status => {
        $('#status').val(status)

        save()
    }

    const save = () => {
        Swal.fire({
            title: 'Yakin, nih?',
            text: 'Tindakan ini akan disimpan permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin!',
            cancelButtonText: 'Nggak Jadi'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>short/save',
                    method: 'POST',
                    data: $('#form-save-short').serialize(),
                    dataType: 'JSON',
                    beforeSend: () => {
                        $('#loading').show()
                    },
                    success: function(res) {
                        $('#loading').hide()
                        let status = res.status
                        if (status == 400) {
                            errorAlert(res.message)
                            $('#nominal').focus().val('')
                            return false
                        }
                        // loadRecap()
                        toastr.success(`Yeaah! ${ res.message }`)
                        $('#nis').focus().val('')
                        $('#package').val(0)
                        $('#nis-save').val(0)
                        $('#reason').val('')
                        $('.btn-save').hide()
                        $('#wrap-type').hide()
                        $('#button-check').show()
                        $('#show-data').html('')
                        $('#show-success').show()
                    }
                })
            }
        })
        
    }
</script>
</body>

</html>
