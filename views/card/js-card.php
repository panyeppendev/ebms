<script src="<?= base_url('template') ?>/plugins/autoNumeric.js"></script>
<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

    $(function() {
        $('body').on('keyup', e => {
            if (e.keyCode == 113) {
                $('#nominal').focusout().val('')
                $('#id').focus().val('')
            }
        })
    })

    $('#id').on('focusin', () => {
        $('#id').focus().val('')
    })

    const errorAlert = message => {
        toastr.error(`Opss.! ${ message }`)
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

        checkid(id)
    })

    $('#button-check').on('click', function() {
        let id = $('#id').val()
        if (id == '') {
            return false
        }

        checkid(id)
    })

    const checkid = id => {
        $.ajax({
            url: '<?= base_url() ?>card/checkid',
            method: 'POST',
            data: {
                id
            },
            dataType: 'JSON',
            success: function(res) {
                console.log(res);
                let status = res.status
                if (status != 200) {
                    errorAlert(res.message)
                    $('#id').focus().val('')
                    $('#show-data').html('')
                    return false
                }
                getData(res)
            }
        })
    }

    const getData = data => {
        $.ajax({
            url: '<?= base_url() ?>card/getdata',
            method: 'POST',
            data: {
                id: data.id,
                message: data.message,
                nis: data.nis
            },
            success: function(res) {
                $('#show-data').html(res)
            }
        })
    }

    $('#form-save-card').on('submit', function(e) {
        e.preventDefault()
        save()
    })

    const save = (id, nis) => {
        Swal.fire({
            title: 'Yakin, nih?',
            text: 'Pastikan kartu sudah valid',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin!',
            cancelButtonText: 'Masih ragu'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>card/save',
                    method: 'POST',
                    data: {
                        id,
                        nis
                    },
                    dataType: 'JSON',
                    success: function(res) {
                        let status = res.status
                        if (status == 400) {
                            errorAlert(res.message)
                            $('#id').val('').focus()
                            return false
                        }

                        toastr.success(`Yeaah! ${ res.message }`)
                        $('#id').focus().val('')
                        $('#show-data').html('')
                    }
                })
            }
        })

    }
</script>
</body>

</html>