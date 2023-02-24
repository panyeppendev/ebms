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

    $(function(){
        loadRecap()
    })

    const loadData = () => {
        let name = $('#changeName').val()
        let filter = $('#filter-date').val()
        $.ajax({
            url: '<?= base_url() ?>buy/loaddata',
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
            url: '<?= base_url() ?>buy/loadrecap',
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
            url: '<?= base_url() ?>buy/checknis',
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
            url: '<?= base_url() ?>buy/getdata',
            method: 'POST',
            data: {
                nis: data.nis,
                package: data.package
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
                    url: '<?= base_url() ?>buy/save',
                    method: 'POST',
                    data: $('#form-save-buy').serialize(),
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