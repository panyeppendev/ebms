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

    $(function() {
        loadData()
        let currentStep = $('#current-step').val()
        if (currentStep == 0) {
            Swal.fire({
                title: 'Tahap pembayaran belum diatur',
                icon: 'error',
                html: 'Anda akan diarahkan dalam <strong>3</strong> detik.<br/><br/>',
                timer: 3000,
                timerProgressBar: true
            })
            setTimeout(function() {
                window.location.href = '<?= base_url() ?>paymentsetting'
            }, 3000)
        }
    })

    const loadData = () => {
        let name = $('#change-name').val()
        let domicile = $('#change-domicile').val()
        $.ajax({
            url: '<?= base_url() ?>deposit/loaddata',
            method: 'POST',
            data: {
                name,
                domicile
            },
            success: function(response) {
                $('#show-data').html(response)
            }
        })
    }

    const detailDeposit = id => {
        $.ajax({
            url: '<?= base_url() ?>deposit/detail',
            method: 'POST',
            data: {
                id
            },
            success: function(response) {
                $('#show-detail').html(response)
            },
            complete: function() {
                $('#modal-detail').modal('show')
            }
        })
    }


    $('#modal-deposit').on('shown.bs.modal', () => {
        $('#id').focus()
    })

    $('#modal-deposit').on('hidden.bs.modal', () => {
        $('#id').val('')
        $('#package').val(0)
        $('#total').val(0)
        $('#nominal').val('')
        $('#show-check').html('')
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

        checkID(id)
    })

    const checkID = id => {
        let step = $('#current-step').val()
        $.ajax({
            url: '<?= base_url() ?>deposit/checkid',
            method: 'POST',
            data: {
                id,
                step
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
                $('#package').val(response.package)
                $('#total').val(response.total)
                $('#nominal').prop('readonly', false).focus().val()

                showCheck(response)
            }
        })
    }

    const showCheck = res => {
        $.ajax({
            url: '<?= base_url() ?>deposit/showCheck',
            method: 'POST',
            data: {
                nis: res.message,
                kredit: res.kredit,
                debet: res.debet,
                total: res.total
            },
            success: function(response) {
                $('#show-check').html(response)
            }
        })
    }

    $('#form-deposit').on('submit', function(e) {
        e.preventDefault()

        const nominal = $('#nominal').val()
        if (nominal == '' || nominal == 0) {
            errorAlert('Nominal tidak boleh kosong')
            return false
        }

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
                    url: '<?= base_url() ?>deposit/save',
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
                        $('#modal-deposit').modal('hide')
                        loadData()
                    }
                })
            }
        })

    }
</script>
</body>

</html>