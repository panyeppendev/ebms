<script src="<?= base_url('template') ?>/plugins/autoNumeric.js"></script>
<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

    $(function() {
        loadRecap()
    })

    $('body').on('keydown', e => {
        $('#nominal').focusout().val()
        if (e.keyCode == 113) {
            $('#nis').focus().val('')
        } else if (e.keyCode == 115) {
            $('#modal-check').modal('show')
        } else if (e.keyCode == 27) {
            $('#modal-check').modal('hide')
        }
    })

    $('#nis').on('focusin', () => {
        $('#nis').focus().val('')
        $('#package').val(0)
        $('#pocket').val(0)
        $('#total').val(0)
        $('#show-data').html('')
        $('#nominal').prop('readonly', true).val('')
    })

    $('#nominal').on('keyup', e => {
        if (e.keyCode == 113) {
            $('#nis').focus().val('')
            $('#nominal').prop('readonly', true).val('')
        }
    })

    const loadRecap = () => {
        $.ajax({
            url: '<?= base_url() ?>purchase/loadrecap',
            method: 'POST',
            success: function(res) {
                $('#show-recap').html(res)
            }
        })
    }

    const loadData = () => {
        $.ajax({
            url: '<?= base_url() ?>purchase/loaddata',
            method: 'POST',
            success: function(res) {
                $('#show-detail').html(res)
                $('#modal-detail').modal('show')
            }
        })
    }

    $('#nominal').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        aForm: true,
        vMax: '999999999',
        vMin: '-999999999'
    });

    const errorAlert = message => {
        toastr.error(`Opss.! ${ message }`)
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
        let step = $('#current_step').val()
        let start = $('#start_step').val()
        $.ajax({
            url: '<?= base_url() ?>purchase/checknis',
            method: 'POST',
            data: {
                nis,
                step,
                start
            },
            dataType: 'JSON',
            success: function(res) {
                let status = res.status
                if (status != 200) {
                    errorAlert(res.message)
                    $('#nis').focus().val('')
                    $('#show-data').html('')
                    $('#nominal').prop('readonly', true)
                    return false
                }
                $('#package').val(res.package)
                $('#pocket').val(res.pocket)
                $('#total').val(res.total)
                $('#nominal').prop('readonly', false).focus().val('')
                getData(res)
            }
        })
    }

    const getData = data => {
        $.ajax({
            url: '<?= base_url() ?>purchase/getdata',
            method: 'POST',
            data: {
                nis: data.message,
                pocket: data.pocket,
                deposit: data.deposit,
                cash: data.cash,
                canteen: data.canteen,
                total: data.total,
                package: data.text
            },
            success: function(res) {
                $('#show-data').html(res)
            }
        })
    }

    $('#form-purchase').on('submit', function(e) {
        e.preventDefault()

        const nominal = $('#nominal').val()
        if (nominal == '' || nominal == 0) {
            errorAlert('Nominal tidak boleh kosong')
            return false
        }

        save()
    })

    const save = () => {
        $.ajax({
            url: '<?= base_url() ?>purchase/save',
            method: 'POST',
            data: $('#form-purchase').serialize(),
            dataType: 'JSON',
            success: function(res) {
                let status = res.status
                if (status == 400) {
                    errorAlert(res.message)
                    $('#nominal').val('').focus()
                    return false
                }

                toastr.success(`Yeaah! ${ res.message }`)
                $('#nis').focus().val('')
                $('#package').val(0)
                $('#total').val(0)
                $('#pocket').val(0)
                $('#show-data').html('')
                $('#nominal').prop('readonly', true).val('')
            }
        })
    }

    $('#modal-check').on('shown.bs.modal', () => {
        $('#id').focus()
    })

    $('#modal-check').on('hidden.bs.modal', () => {
        $('#id').val('')
        $('#show-check').html('')
        $('#nis').focus().val('')
    })

    $('#id').on('keyup', function(e) {
        let id = $(this).val()
        let key = e.which
        if (key != 13) {
            return false
        }

        if (key == 13 && id == '') {
            return false
        }

        checkid(id)
    })

    const checkid = id => {
        let step = $('#current_step').val()
        $.ajax({
            url: '<?= base_url() ?>purchase/checknis',
            method: 'POST',
            data: {
                nis: id,
                step
            },
            dataType: 'JSON',
            success: function(res) {
                let status = res.status
                if (status != 200) {
                    errorAlert(res.message)
                    $('#id').focus().val('')
                    $('#show-check').html('')
                    return false
                }
                getcheck(res)
            }
        })
    }

    const getcheck = data => {
        $.ajax({
            url: '<?= base_url() ?>purchase/getcheck',
            method: 'POST',
            data: {
                nis: data.message,
                pocket: data.pocket,
                deposit: data.deposit,
                cash: data.cash,
                canteen: data.canteen,
                total: data.total,
                package: data.text
            },
            success: function(res) {
                $('#show-check').html(res)
            }
        })
    }
</script>
</body>

</html>