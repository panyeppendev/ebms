<script src="<?= base_url('template') ?>/plugins/autoNumeric.js"></script>
<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

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
        let step = $('#current-step').val()
        let status = $('#change-status').val()
        let domicile = $('#change-domicile').val()
        $.ajax({
            url: '<?= base_url() ?>package/loaddata',
            method: 'POST',
            data: {
                name,
                step,
                status,
                domicile
            },
            success: function(response) {
                $('#show-data').html(response)
            }
        })
    }

    const packageActivation = (el, step) => {
        Swal.fire({
            title: 'Anda yakin?',
            text: 'Tindakan ini tidak otomatis menambah pencairan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin!',
            cancelButtonText: 'Masih ragu'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>package/packageactivation',
                    method: 'POST',
                    data: {
                        step
                    },
                    dataType: 'JSON',
                    beforeSend: function() {
                        $(el).prop('disabled', true)
                    },
                    success: function(res) {
                        $(el).prop('disabled', false)
                        let status = res.status
                        if (status == 400) {
                            toastr.error(`Opps..! ${res.message}`)
                            return false
                        }
                        toastr.success('Yess..! Paket tahap ini berhasil diaktifkan')
                        let currentStep = $('#current-step').val()
                        loadData(currentStep)
                    }
                })
            }
        })
    }

    $('#change-step').on('change', function() {
        let step = $(this).val()
        $('#current-step').val($(this).val())
        loadData(step)
    })

    $('#modal-package').on('shown.bs.modal', () => {
        $('#id').focus()
    })

    $('#modal-package').on('hidden.bs.modal', () => {
        resetModal()
    })

    const resetModal = () => {
        $('#id').val('')
        $('#id_student').val(0)
        $('#level_of_formal').val(0)
        $('#package').val('').prop('disabled', true).removeClass('is-invalid')
        $('#show-rate').html('')
        $('#show-check').html('')
        $('#save-package').hide()
    }

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
            url: '<?= base_url() ?>package/checkid',
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
                    $('#package').prop('disabled', true).removeClass('is-invalid')
                    $('#show-check').html('')
                    $('#save-package').hide()
                    $('#id_student').val(0)
                    $('#step').val(0)
                    return false
                }
                $('#id-student').val(response.id)
                $('#step').val(step)
                $('#level-of-formal').val(response.level)
                $('#package').prop('disabled', false).addClass('is-invalid')
                $('#package').val('')
                $('#show-rate').html('')
                showCheck(response.id)
            }
        })
    }

    const showCheck = id => {
        $.ajax({
            url: '<?= base_url() ?>package/showCheck',
            method: 'POST',
            data: {
                id
            },
            success: function(response) {
                $('#show-check').html(response)
            }
        })
    }

    $('#package').on('change', function() {
        let package = $(this).val()
        let level = $('#level-of-formal').val()
        if (package == '') {
            $('#save-package').hide()
            return false
        }
        $('#save-package').show()
        $.ajax({
            url: '<?= base_url() ?>package/showrate',
            method: 'POST',
            data: {
                package,
                level
            },
            success: function(response) {
                $('#show-rate').html(response)
            }
        })
    })

    $('#save-package').on('click', function() {
        Swal.fire({
            title: 'Anda yakin?',
            text: 'Pastikan paket sudah dipilih',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin!',
            cancelButtonText: 'Cek lagi'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>package/savepackage',
                    method: 'POST',
                    data: $('#form-save-package').serialize(),
                    dataType: 'JSON',
                    beforeSend: function() {
                        $(this).prop('disabled', true)
                    },
                    success: function(response) {
                        let status = response.status
                        if (status == 400) {
                            toastr.error(`Oppss..! ${response.message}`)
                            return false
                        }
                        $('#invoice').val(response.data)
                        toastr.success('Yeaay..! Satu pembayaran berhasil')
                        resetModal()
                        $('#id').focus()
                        loadData(response.step)
                        let invoice = $('#invoice').val()
                        if (invoice != 0) {
                            Swal.fire({
                                title: 'Pembayaran Sukses....',
                                icon: 'success',
                                html: 'Otomatis print invoice dalam <strong>1</strong> detik.<br/><br/>',
                                timer: 1000,
                                timerProgressBar: true
                            })
                            setTimeout(function() {
                                $('#form-print').submit()
                            }, 1000)
                        }
                    }
                })
            }
        })
    })

    const deletePackage = id => {
        Swal.fire({
            title: 'Anda Yakin?',
            text: 'Data akan dihapus permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin, dong!',
            cancelButtonText: 'Nggak jadi!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>package/deletepackage',
                    method: 'POST',
                    data: {
                        id
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        let status = response.status
                        if (status == 400) {
                            toastr.error('Opss! Server gagal merespon. Coba resfresh halaman')
                            return false
                        }

                        toastr.success('Yeaah! Satu data berhasil dihapus')
                        let currentStep = $('#current-step').val()
                        loadData(currentStep)
                    }
                })
            }
        })
    }

    const activePackage = id => {
        Swal.fire({
            title: 'Anda Yakin?',
            text: 'Tindakan ini otomatis menambah pencairan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin, dong!',
            cancelButtonText: 'Nggak jadi!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>package/activepackage',
                    method: 'POST',
                    data: {
                        id
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        let status = response.status
                        if (status == 400) {
                            toastr.error(`Opss! ${response.message}`)
                            return false
                        }

                        toastr.success('Yeaah! Satu data berhasil diaktifkan')
                        let currentStep = $('#current-step').val()
                        loadData(currentStep)
                    }
                })
            }
        })
    }
</script>
</body>

</html>