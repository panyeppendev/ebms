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

    const loadData = () => {
        $.ajax({
            url: '<?= base_url() ?>fasting/loaddata',
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
        $.ajax({
            url: '<?= base_url() ?>requirement/checknis',
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
                    $('#id-nis').val(0)
                    $('#id-requirement').val(0)
                    $('#kelas').val(0)
                    hideButton()
                    return false
                }
                $('#nis').focus().val('')
                showButton()
                getData(res.nis, res.requirement, res.kelas)
            }
        })
    }

    const showButton = () => {
        $('#tombol-cek').removeClass('d-none')
        $('#tombol-cek').addClass('d-inline-block')

        $('#tombol-batal').removeClass('d-none')
        $('#tombol-batal').addClass('d-inline-block')
    }

    const hideButton = () => {
        $('#tombol-cek').addClass('d-none')
        $('#tombol-cek').removeClass('d-inline-block')

        $('#tombol-batal').addClass('d-none')
        $('#tombol-batal').removeClass('d-inline-block')
        $('#show-data').html('')
        $('#id-nis').val(0)
        $('#id-requirement').val(0)
        $('#kelas').val(0)
    }


    const getData = (nis, requirement, kelas) => {
        $.ajax({
            url: '<?= base_url() ?>requirement/getdata',
            method: 'POST',
            data: {
                nis,
                requirement
            },
            success: function(res) {
                $('#id-nis').val(nis)
                $('#id-requirement').val(requirement)
                $('#kelas').val(kelas)
                $('#show-data').html(res)
            }
        })
    }

    const checkIn = () => {
        let nis = $('#id-nis').val()
        let requirement = $('#id-requirement').val()
        let kelas = $('#kelas').val()
        $.ajax({
            url: '<?= base_url() ?>requirement/checkin',
            method: 'POST',
            data: {
                nis,
                requirement,
                kelas
            },
            success: function(res) {
                $('#show-checkin').html(res)
            }
        })
    }

    const saveCheckin = () => {
        $.ajax({
            url: '<?= base_url() ?>requirement/savecheckin',
            method: 'POST',
            data: $('#form-checkin').serialize(),
            dataType: 'JSON',
            success: res => {
                if (res.status != 200) {
                    errorAlert(res.message)
                    return false
                }

                toastr.success(`Yeaahh! ${res.message} data berhasil diperbarui`)
                $('#modal-checkin').modal('hide')

                let nis = $('#id-nis').val()
                let requirement = $('#id-requirement').val()
                let kelas = $('#kelas').val()
                getData(nis, requirement, kelas)
            }
        })
    }
</script>
</body>

</html>