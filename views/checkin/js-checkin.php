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
            return false
        }

        checkNis(nis)
    })

    const showFailed = () => {
        $('#alert-yes').removeClass('d-block')
        $('#alert-yes').addClass('d-none')
        $('#alert-no').removeClass('d-none')
        $('#alert-no').addClass('d-block')
    }

    const showSuccess = () => {
        $('#alert-no').removeClass('d-block')
        $('#alert-no').addClass('d-none')
        $('#alert-yes').removeClass('d-none')
        $('#alert-yes').addClass('d-block')
    }

    const checkNis = nis => {
        $.ajax({
            url: '<?= base_url() ?>checkin/checknis',
            method: 'POST',
            data: {
                nis
            },
            dataType: 'JSON',
            success: function(res) {
                let status = res.status
                if (status != 200) {
                    errorAlert(res.message)
                    $('#nis').focus().val('')
                    $('#show-data').html('')
                    return false
                }

                if (res.syarat > 0) {
                    showFailed()
                } else {
                    showSuccess()
                }

                $('#nis').focus().val('')
                getData(res.nis, res.requirement, res.kelas)
            }
        })
    }

    const getData = (nis, requirement, kelas) => {
        $.ajax({
            url: '<?= base_url() ?>checkin/getdata',
            method: 'POST',
            data: {
                nis,
                requirement,
                kelas
            },
            success: function(res) {
                $('#show-data').html(res)
            }
        })
    }

    const loadData = () => {
        let domicile = $('#changeDomicile').val()
        $.ajax({
            url: '<?= base_url() ?>checkin/loaddata',
            method: 'POST',
            data: {
                domicile
            },
            success: res => {
                $('#load-data').html(res)
            }
        })
    }
</script>
</body>

</html>