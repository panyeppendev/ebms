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
            $('#changeName').focus().val('')
        }
    })

    $(function() {
        loadData()
    })

    const loadData = () => {
        $.ajax({
            url: '<?= base_url() ?>transaction/loaddata',
            method: 'POST',
            data: {
                name: $('#changeName').val()
            },
            success: function(res) {
                $('#load-data').html(res)
            }
        })
    }

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

    const checkNis = nis => {
        let step = $('#current-step').val()
        $.ajax({
            url: '<?= base_url() ?>transaction/checknis',
            method: 'POST',
            data: {
                nis,
                step
            },
            dataType: 'JSON',
            success: function(res) {

                let status = res.status
                if (status == 500) {
                    errorAlert(res.message)
                    $('#nis').focus().val('')
                    $('#show-data').html('')
                    return false
                }

                getData(res.nis, res.text, res.message, status)
                $('#nis').focus().val('')
            }
        })
    }


    const getData = (nis, text, message, status) => {
        $.ajax({
            url: '<?= base_url() ?>transaction/getdata',
            method: 'POST',
            data: {
                nis,
                text,
                message,
                status
            },
            success: function(res) {
                $('#show-data').html(res)
            }
        })
    }
</script>
</body>

</html>