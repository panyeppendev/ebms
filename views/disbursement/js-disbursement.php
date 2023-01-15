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
        let name = $('#changeName').val()
        let filter = $('#filter-date').val()
        $.ajax({
            url: '<?= base_url() ?>disbursement/loaddata',
            method: 'POST',
            data: {
                name,
                filter
            },
            success: function(res) {
                $('#show-detail').html(res)
            }
        })
    }

    const loadRecap = () => {
        let filter = $('#filter-date').val()
        $.ajax({
            url: '<?= base_url() ?>disbursement/loadrecap',
            method: 'POST',
            data: {
                filter
            },
            success: function(res) {
                $('#show-recap').html(res)
            }
        })
    }

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
        $.ajax({
            url: '<?= base_url() ?>disbursement/checknis',
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
                    $('#nominal').prop('readonly', true)
                    return false
                }

                if (status == 400) {
                    $('#nominal').prop('readonly', true)
                } else {
                    $('#nominal').prop('readonly', false).focus().val('')
                }
                $('#package').val(res.package)
                $('#nis-save').val(res.nis)
                getData(res)
            }
        })
    }

    const getData = data => {
        $.ajax({
            url: '<?= base_url() ?>disbursement/getdata',
            method: 'POST',
            data: {
                nis: data.nis,
                package: data.package
            },
            success: function(res) {
                $('#show-data').html(res)
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
                getData(res)
                toastr.success(`Yeaah! ${ res.message }`)
                $('#nis').focus().val('')
                $('#package').val(0)
                $('#nis-save').val(0)
                $('#nominal').prop('readonly', true).val('')
            }
        })
    }
</script>
</body>

</html>