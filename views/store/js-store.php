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

    $('body').on('keyup', e => {
        if (e.keyCode == 113) {
            $('#changeName').focus().val('')
        }
    })

    $('.indonesian-currency').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        aForm: true,
        vMax: '999999999',
        vMin: '-999999999'
    });

    const loadData = () => {
        $.ajax({
            url: '<?= base_url() ?>store/loaddata',
            method: 'POST',
            success: function(res) {
                $('#load-data').html(res)
            }
        })
    }

    const loadReason = () => {
        let type = $('#changeType').val()
        $.ajax({
            url: '<?= base_url() ?>store/loadreason',
            method: 'POST',
            data: { type },
            success: function(res) {
                $('#load-reason').html(res)
            }
        })
    }

    $(function(){
        loadData()
        loadReason()
		loadTerm()
    })

    const save = () => {
        Swal.fire({
            title: 'Anda yakin?',
            text: 'Pastikan semua sudah diisi',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin',
            cancelButtonText: 'Gak jadi'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>store/save',
                    method: 'POST',
                    data: $('#form-store').serialize(),
                    dataType: 'JSON',
                    success: function(res) {
                        let status = res.status
                        if (status != 200) {
                            errorAlert(res.message)
                            return false
                        }
                        toastr.success('Pembelian berhasil diatur')
                        $('#form-store')[0].reset()
                        loadData()
                    }
                })
            }
        })
    }

    const saveReason = () => {
        Swal.fire({
            title: 'Anda yakin?',
            text: 'Pastikan semua sudah diisi',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin',
            cancelButtonText: 'Gak jadi'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>store/savereason',
                    method: 'POST',
                    data: $('#form-reason').serialize(),
                    dataType: 'JSON',
                    success: function(res) {
                        let status = res.status
                        if (status != 200) {
                            errorAlert(res.message)
                            return false
                        }
                        toastr.success('Pembelian berhasil diatur')
                        $('#form-reason')[0].reset()
                        loadReason()
                    }
                })
            }
        })
    }

    const deleteReason = id => {
        Swal.fire({
            title: 'Anda yakin?',
            text: 'Data akan dihapus permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin',
            cancelButtonText: 'Gak jadi'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>store/deletereason',
                    method: 'POST',
                    data: { id },
                    dataType: 'JSON',
                    success: function(res) {
                        let status = res.status
                        if (status != 200) {
                            errorAlert(res.message)
                            return false
                        }
                        toastr.success('Data berhasil dihapus')
                        loadReason()
                    }
                })
            }
        })
    }

	const saveSettingTerm = () => {
		let minute = $('#minute').val()
		Swal.fire({
			title: 'Anda yakin?',
			text: 'Pastikan inputan menit sudah diisi',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yakin',
			cancelButtonText: 'Gak jadi'
		}).then((result) => {
			if (result.isConfirmed) {
				if (minute === '') {
					errorAlert('Pastikan inputan menit sudah diisi');
					return false
				}
				$.ajax({
					url: '<?= base_url() ?>store/saveterm',
					method: 'POST',
					data: {
						minute
					},
					dataType: 'JSON',
					success: function(res) {
						let status = res.status
						if (status != 200) {
							errorAlert(res.message)
							return false
						}
						toastr.success('Pengaturan lama izin berhasil diatur')
						$('#minute').val('')
						loadTerm()
					}
				})
			}
		})
	}

	const loadTerm = () => {
		$.ajax({
			url: '<?= base_url() ?>store/loadterm',
			method: 'POST',
			success: function(res) {
				$('#show-term').html(res)
			}
		})
	}
</script>
</body>

</html>
