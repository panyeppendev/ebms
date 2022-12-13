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
            url: '<?= base_url() ?>security/loaddata',
            method: 'POST',
            data: {
                name: $('#changeName').val()
            },
            success: function(res) {
                $('#load-data').html(res)
            }
        })
    }

    $('#modal-account').on('shown.bs.modal', () => {
        $('#name').focus()
    })

    $('#modal-account').on('hidden.bs.modal', () => {
        $('#name').val('')
        $('#id').val(0)
        loadData()
    })

    $('#name').on('keyup', function(e) {
        let name = $(this).val()
        let key = e.which
        if (key != 13) {
            return false
        }

        if (key == 13 && name == '') {
            return false
        }

        saveAccount()
    })

    const saveAccount = () => {
        let id = $('#id').val()
        let name = $('#name').val()

        if (name == '') {
            errorAlert('Pastikan nama akun sudah diisi')
            return false
        }

        $.ajax({
            url: '<?= base_url() ?>security/save',
            method: 'POST',
            data: {
                id,
                name
            },
            dataType: 'JSON',
            success: function(res) {
                let status = res.status
                if (status != 200) {
                    errorAlert(res.message)
                    $('#name').focus().val('')
                    return false
                }

                toastr.success(`Yess! ${res.message}`)
                $('#name').focus().val('')
                if (res.type == 'EDIT') {
                    $('#modal-account').modal('hide')
                }
            }
        })
    }


    const deleteAccount = id => {
        Swal.fire({
            title: 'Yakin, nih?',
            text: 'Data akan dihapus permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin, dong',
            cancelButtonText: 'Nggak jadi'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>security/delete',
                    method: 'POST',
                    data: {
                        id
                    },
                    dataType: 'JSON',
                    success: res => {
                        if (res.status != 200) {
                            errorAlert('Kesalahan server')
                            return false
                        }

                        toastr.success('Yess! Satu akun berhasil dihapus')
                        loadData()
                    }
                })
            }
        })
    }

    const changeStatus = id => {
        $.ajax({
            url: '<?= base_url() ?>security/changeStatus',
            method: 'POST',
            data: {
                id
            },
            dataType: 'JSON',
            success: res => {
                if (res.status != 200) {
                    errorAlert('Kesalahan server')
                    return false
                }

                toastr.success('Yess! Satu akun berhasil diperbarui')
                loadData()
            }
        })
    }

    const edit = id => {
        $.ajax({
            url: '<?= base_url() ?>security/getdata/' + id,
            dataType: 'JSON',
            success: res => {
                if (res.status != 200) {
                    errorAlert('Data tidak valid')
                    return false
                }

                $('#id').val(id)
                $('#name').val(res.name)
                $('#modal-account').modal('show')
            }
        })
    }

    $('#modal-holiday').on('hidden.bs.modal', () => {
        $('#holiday').val('')
        $('#date_of_holiday').val('')
        $('#date_of_comeback').val('')
    })

    const saveSetting = el => {
        let holiday = $('#holiday').val()
        let gohome = $('#date_of_holiday').val()
        let comeback = $('#date_of_comeback').val()

        if (holiday == '' || gohome == '' || comeback == '') {
            errorAlert('Pastikan semua bidang inputan sudah diisi dengan lengkap')
            return false
        }

        $.ajax({
            url: '<?= base_url() ?>security/savesetting',
            method: 'POST',
            data: {
                holiday,
                gohome,
                comeback
            },
            dataType: 'JSON',
            beforeSend: () => {
                $(el).prop('disabled', true)
            },
            success: res => {

                $(el).prop('disabled', false)
                if (res.status != 200) {
                    errorAlert(res.message)
                    return false
                }

                Swal.fire({
                    title: 'Liburan berhasil diubah',
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

    const setSetting = type => {
        Swal.fire({
            title: 'Anda yakin?',
            text: 'Tindakan ini hanya boleh sekali',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin, dong',
            cancelButtonText: 'Nggak jadi'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>security/setsetting/' + type,
                    dataType: 'JSON',
                    success: res => {
                        if (res.status != 200) {
                            errorAlert(res.message)
                            return false
                        }

                        Swal.fire({
                            title: 'Pengaturan berhasil diubah',
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
</script>
</body>

</html>