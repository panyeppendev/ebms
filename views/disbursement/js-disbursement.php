<script src="<?= base_url('template') ?>/plugins/autoNumeric.js"></script>
<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

    $(function() {
        loadRecap()
        let currentStep = $('#current-step').val()
        if (currentStep == 0) {
            Swal.fire({
                title: 'Tahap pencairan belum diatur',
                icon: 'error',
                html: 'Anda akan diarahkan dalam <strong>2</strong> detik.<br/><br/>',
                timer: 2000,
                timerProgressBar: true
            })
            setTimeout(function() {
                window.location.href = '<?= base_url() ?>paymentsetting'
            }, 2000)
        }
    })

    const loadData = () => {
        $.ajax({
            url: '<?= base_url() ?>disbursement/loaddata',
            method: 'POST',
            success: function(res) {
                $('#show-detail').html(res)
                $('#modal-detail').modal('show')
            }
        })
    }

    const loadRecap = () => {
        $.ajax({
            url: '<?= base_url() ?>disbursement/loadrecap',
            method: 'POST',
            success: function(res) {
                $('#show-recap').html(res)
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

    $('#form-disbursement').on('submit', function(e) {
        e.preventDefault()

        const nominal = $('#nominal').val()
        if (nominal == '' || nominal == 0) {
            errorAlert('Nominal tidak boleh kosong')
            return false
        }

        save()
    })

    const checkNis = nis => {
        let step = $('#current-step').val()
        let start = $('#start-step').val()

        $.ajax({
            url: '<?= base_url() ?>disbursement/checknis',
            method: 'POST',
            data: {
                nis,
                step,
                start
            },
            dataType: 'JSON',
            success: function(res) {
                console.log(res);
                let status = res.status
                if (status != 200) {
                    errorAlert(res.message)
                    $('#nis').focus().val('')
                    $('#show-data').html('')
                    $('#nominal').prop('readonly', true)
                    return false
                }
                getData(res)
                $('#package-save').val(res.package)
                $('#pocket-save').val(res.pocket)
                $('#total-save').val(res.total)
                $('#nominal').prop('readonly', false).focus().val('')
            }
        })
    }

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
                if (status == 400) {
                    errorAlert(res.message)
                    $('#nominal').focus().val('')
                    return false
                }
                loadRecap()
                toastr.success(`Yeaah! ${ res.message }`)
                $('#nis').focus().val('')
                $('#show-data').html('')
                $('#nominal').prop('readonly', true).val('')
            }
        })
    }

    const getData = data => {
        $.ajax({
            url: '<?= base_url() ?>disbursement/getdata',
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
</script>
</body>

</html>