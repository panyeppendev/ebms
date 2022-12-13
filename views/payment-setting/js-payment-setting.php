<script src="<?= base_url('template') ?>/plugins/autoNumeric.js"></script>
<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

    $(function() {
        loadData()
        loadPackage()
        loadShift()
    })

    const loadData = () => {
        let type = $('#change-type').val()
        $.ajax({
            url: '<?= base_url() ?>paymentsetting/loaddata',
            method: 'POST',
            data: {
                type
            },
            success: function(response) {
                $('#show-rate').html(response)
            }
        })
    }

    const loadPackage = () => {
        let package = $('#change-package').val()
        $.ajax({
            url: '<?= base_url() ?>paymentsetting/loadpackage',
            method: 'POST',
            data: {
                package
            },
            success: function(response) {
                $('#show-package').html(response)
            }
        })
    }

    const loadShift = () => {
        $.ajax({
            url: '<?= base_url() ?>paymentsetting/loadshift',
            method: 'POST',
            success: function(response) {
                $('#show-shift').html(response)
            }
        })
    }

    $('#change-type').on('change', function() {
        let setting = '<?= $setting ?>'
        if (setting != 1) {
            let type = $(this).val()
            if (type != '') {
                $.ajax({
                    url: '<?= base_url() ?>paymentsetting/setrate',
                    method: 'POST',
                    data: {
                        type
                    },
                    beforeSend: function() {
                        $('#set-rate').prop('disabled', true)
                    },
                    success: function(response) {
                        $('#set-rate').prop('disabled', false)
                    }
                })
            }
        }
    })

    $('#set-rate').on('click', function() {
        let type = $('#change-type').val()
        if (type == '') {
            toastr.error('Opss..! Anda belum menentukan tipe tarif')
            return false
        }

        if (type == 'NEW') {
            $('#show-form-new').removeClass('d-none')
            $('#show-form-new').addClass('d-block')
            $('#save-form-rate-new').removeClass('d-none')
            $('#save-form-rate-new').addClass('d-inline-block')

            $('#show-form-old').addClass('d-none')
            $('#show-form-old').removeClass('d-block')
            $('#save-form-rate-old').addClass('d-none')
            $('#save-form-rate-old').removeClass('d-inline-block')
        } else {
            $('#show-form-old').removeClass('d-none')
            $('#show-form-old').addClass('d-block')
            $('#save-form-rate-old').removeClass('d-none')
            $('#save-form-rate-old').addClass('d-inline-block')

            $('#show-form-new').addClass('d-none')
            $('#show-form-new').removeClass('d-block')
            $('#save-form-rate-new').addClass('d-none')
            $('#save-form-rate-new').removeClass('d-inline-block')
        }
        $('#modal-set-rate').modal('show')
    })

    $('.indonesian-currency').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        aForm: true,
        vMax: '999999999',
        vMin: '-999999999'
    });

    $('.form-control').keypress(function(e) {
        if (e.which == 13 && $(this).val() != '') {
            e.preventDefault()
            let $next = $('[tabIndex=' + (+this.tabIndex + 1) + ']');
            if (!$next.length) {
                $next = $('[tabIndex=1]');
            }
            $next.focus().select();
        }
    });

    $('#save-form-rate-new').on('click', function() {
        let dataForm = $('#form-set-rate-new').serialize()
        saveRate(dataForm, $(this))
    })

    $('#save-form-rate-old').on('click', function() {
        let dataForm = $('#form-set-rate-old').serialize()
        saveRate(dataForm, $(this))
    })

    const saveRate = (dataForm, el) => {
        Swal.fire({
            title: 'Anda Yakin?',
            text: 'Pastikan semua bidang inputan sudah valid',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin dong',
            cancelButtonText: 'Nggak yakin'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>paymentsetting/saverate',
                    method: 'POST',
                    data: dataForm,
                    beforeSend: function() {
                        el.prop('disabled', true)
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        el.prop('disabled', false)
                        let status = response.status
                        if (status == 400) {
                            toastr.error('Oppsss.! Ada yang nggak beres dengan server')
                            return false
                        }

                        loadData()
                        toastr.success('Yeaah..! Tarif berhasil diatur')
                        $('#modal-set-rate').modal('hide')
                    }
                })
            }
        })
    }

    const setPayment = () => {
        Swal.fire({
            title: 'Yakin, nih?',
            text: 'Tindakan ini tidak bisa diubah lagi selama setahun',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin dong',
            cancelButtonText: 'Belum yakin'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>paymentsetting/setpayment',
                    method: 'POST',
                    dataType: 'JSON',
                    beforeSend: function() {
                        $('#set-payment').prop('disabled', true)
                    },
                    success: function(response) {
                        let status = response.status
                        if (status == 400) {
                            toastr.error(`Oppss..! ${response.message}`)
                            $('#set-payment').prop('disabled', false)
                            return false
                        }

                        Swal.fire({
                            title: 'Tarif pembayaran berhasil diatur',
                            icon: 'success',
                            html: 'Halaman akan dimuat ulang dalam <strong>2</strong> detik.<br/><br/>',
                            timer: 2000,
                            timerProgressBar: true
                        })
                        setTimeout(function() {
                            location.reload()
                        }, 2000)
                    }
                })
            }
        })
    }

    $('#set-package').on('click', function() {
        let package = $('#change-package').val()
        if (package == '') {
            toastr.error('Oppss..! Anda belum memilih paket')
            return false
        }

        if (package == 'GENERAL') {
            $('#show-form-general').removeClass('d-none')
            $('#show-form-general').addClass('d-block')
            $('#save-form-general').removeClass('d-none')
            $('#save-form-general').addClass('d-inline-block')

            $('#show-form-ab').addClass('d-none')
            $('#show-form-ab').removeClass('d-block')
            $('#save-form-ab').addClass('d-none')
            $('#save-form-ab').removeClass('d-inline-block')

            $('#show-form-cd').addClass('d-none')
            $('#show-form-cd').removeClass('d-block')
            $('#save-form-cd').addClass('d-none')
            $('#save-form-cd').removeClass('d-inline-block')
        } else if (package == 'AB') {
            $('#show-form-ab').removeClass('d-none')
            $('#show-form-ab').addClass('d-block')
            $('#save-form-ab').removeClass('d-none')
            $('#save-form-ab').addClass('d-inline-block')

            $('#show-form-general').addClass('d-none')
            $('#show-form-general').removeClass('d-block')
            $('#save-form-general').addClass('d-none')
            $('#save-form-general').removeClass('d-inline-block')

            $('#show-form-cd').addClass('d-none')
            $('#show-form-cd').removeClass('d-block')
            $('#save-form-cd').addClass('d-none')
            $('#save-form-cd').removeClass('d-inline-block')
        } else {
            $('#show-form-cd').removeClass('d-none')
            $('#show-form-cd').addClass('d-block')
            $('#save-form-cd').removeClass('d-none')
            $('#save-form-cd').addClass('d-inline-block')

            $('#show-form-general').addClass('d-none')
            $('#show-form-general').removeClass('d-block')
            $('#save-form-general').addClass('d-none')
            $('#save-form-general').removeClass('d-inline-block')

            $('#show-form-ab').addClass('d-none')
            $('#show-form-ab').removeClass('d-block')
            $('#save-form-ab').addClass('d-none')
            $('#save-form-ab').removeClass('d-inline-block')
        }
        $('#modal-set-package').modal('show')
    })

    $('#change-package').on('change', function() {
        let setting = '<?= $setting ?>'
        let package = $(this).val()
        if (setting != 1) {
            if (package == '') {
                $('#set-package').prop('disabled', true)
                return false
            }
            $('#set-package').prop('disabled', false)
            $.ajax({
                url: '<?= base_url() ?>paymentsetting/setpackage',
                method: 'POST',
                data: {
                    package
                },
                dataType: 'JSON',
                beforeSend: function() {
                    $('#set-package').prop('disabled', true)
                },
                success: function(response) {
                    $('#set-package').prop('disabled', false)
                }
            })
        }
    })

    $('#save-form-general').on('click', function() {
        let dataForm = $('#form-set-general').serialize()
        savePackage(dataForm, $(this), 'savepackages')
    })

    $('#save-form-ab').on('click', function() {
        let dataForm = $('#form-set-ab').serialize()
        savePackage(dataForm, $(this), 'savepackage')
    })

    $('#save-form-cd').on('click', function() {
        let dataForm = $('#form-set-cd').serialize()
        savePackage(dataForm, $(this), 'savepackage')
    })

    const savePackage = (dataForm, el, url) => {
        Swal.fire({
            title: 'Anda Yakin?',
            text: 'Pastikan semua bidang inputan sudah valid',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin dong',
            cancelButtonText: 'Nggak yakin'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>paymentsetting/' + url,
                    method: 'POST',
                    data: dataForm,
                    beforeSend: function() {
                        el.prop('disabled', true)
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        el.prop('disabled', false)
                        let status = response.status
                        if (status == 400) {
                            toastr.error('Oppsss.! Ada yang nggak beres dengan server')
                            return false
                        }

                        loadPackage()
                        toastr.success('Yeaah..! Tarif berhasil diatur')
                        $('#modal-set-package').modal('hide')
                    }
                })
            }
        })
    }

    $('#save-shift').on('click', function() {
        Swal.fire({
            title: 'Anda Yakin?',
            text: 'Pastikan semua bidang inputan sudah valid',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin dong',
            cancelButtonText: 'Nggak yakin'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>paymentsetting/saveshift',
                    method: 'POST',
                    data: $('#form-set-shift').serialize(),
                    beforeSend: function() {
                        $(this).prop('disabled', true)
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        $(this).prop('disabled', false)
                        let status = response.status
                        if (status == 400) {
                            toastr.error('Oppsss.! Ada yang nggak beres dengan server')
                            return false
                        }

                        loadShift()
                        toastr.success('Yeaah..! Tarif berhasil diatur')
                        $('#modal-set-shift').modal('hide')
                    }
                })
            }
        })
    })

    $('#set-step').on('click', function() {
        let step = $('#change-step').val()
        if (step == '') {
            toastr.error('Oppss..! Anda belum memilih tahapan')
            return false
        }

        Swal.fire({
            title: 'Anda Yakin?',
            text: 'Tindakan ini berpengaruh pada pembayaran paket',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin dong',
            cancelButtonText: 'Nggak yakin'
        }).then((result) => {
            if (result.isConfirmed) {
                saveStep(step, 'PAYMENT')
            }
        })
    })

    $('#set-step-disbursement').on('click', function() {
        let step = $('#change-step-disbursement').val()
        if (step == '') {
            toastr.error('Oppss..! Anda belum memilih tahapan')
            return false
        }

        Swal.fire({
            title: 'Anda Yakin?',
            text: 'Tindakan ini berpengaruh pada pembayaran paket',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin dong',
            cancelButtonText: 'Nggak yakin'
        }).then((result) => {
            if (result.isConfirmed) {
                saveStep(step, 'DISBURSEMENT')
            }
        })
    })

    const saveStep = (step, status) => {
        $.ajax({
            url: '<?= base_url() ?>paymentsetting/savestep',
            method: 'POST',
            data: {
                step,
                status
            },
            dataType: 'JSON',
            success: function(response) {
                let status = response.status
                if (status == 400) {
                    toastr.error('Oppsss.! Ada yang nggak beres dengan server')
                    return false
                }

                Swal.fire({
                    title: 'Tahapan berhasil diubah',
                    icon: 'success',
                    html: 'Halaman akan dimuat ulang dalam <strong>2</strong> detik.<br/><br/>',
                    timer: 2000,
                    timerProgressBar: true
                })
                setTimeout(function() {
                    location.reload()
                }, 2000)
            }
        })
    }
</script>
</body>

</html>