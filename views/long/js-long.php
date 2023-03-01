<script src="<?= base_url() ?>template/plugins/select2/js/select2.full.min.js"></script>
<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

    const errorAlert = message => {
        toastr.error(`Opss.! ${ message }`)
    }

	$('.select2bs4').select2({
		theme: 'bootstrap4'
	})

	$(document).on('select2:open', () => {
		document.querySelector('.select2-search__field').focus();
	});

    $('body').on('keyup', e => {
        if (e.keyCode == 113) {
            $('#nis').focus().val('')
        }
    })

    $(function(){
        loadRecap()
        loadPermission()
        loadCountPermission()
		//$('#modal-constitution').modal('show')
    })

    const filterLoad = () => {
        loadPermission()
        loadCountPermission()
    }

    const loadData = () => {
        let name = $('#changeName').val()
        let filter = $('#filter-date').val()
        $.ajax({
            url: '<?= base_url() ?>long/loaddata',
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
            url: '<?= base_url() ?>long/loadrecap',
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

    const loadPermission = () => {
        let name = $('#changeName').val()
        let status = $('#changeStatus').val()
        $.ajax({
            url: '<?= base_url() ?>long/loadpermission',
            method: 'POST',
            data: {
                name,
                status
            },
            success: function(res) {
                $('#show-permission').html(res)
            }
        })
    }

    const loadCountPermission = () => {
        let name = $('#changeName').val()
        let status = $('#changeStatus').val()
        $.ajax({
            url: '<?= base_url() ?>long/loadcountpermission',
            method: 'POST',
            data: {
                name,
                status
            },
            success: function(res) {
                $('#show-count-permission').html(res)
            }
        })
    }

	const doBack = id => {
		Swal.fire({
			title: 'Anda yakin?',
			text: 'Tindakan ini akan disimpan permanen',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yakin',
			cancelButtonText: 'Gak jadi'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= base_url() ?>long/doback',
					method: 'POST',
					data: { id },
					dataType: 'JSON',
					success: function(res) {
						let status = res.status
						if (status != 200) {
							errorAlert(res.message)
							return false
						}
						if (res.message === 'LATE') {
							$('#id-student').val(res.nis)
							$('#id-permission').val(id)
							$('#modal-constitution').modal('show')
						}
						toastr.success('Satu izin berhasil diselesaikan')
						loadPermission()
					}
				})
			}
		})
	}

	const doPunishment = (nis, permission) => {
		$('#id-student').val(nis)
		$('#id-permission').val(permission)
		$('#modal-constitution').modal('show')
	}

	$('#modal-constitution').on('hidden.bs.modal', () => {
		$('#change-constitution').val('')
		$('#id-permission').val(0)
		$('#id-constitution').val(0)
		$('#id-student').val(0)
		$('#show-constitution').html('');
		$('#button-save-punishment').prop('disabled', true)
	})

	$('#change-constitution').on('change', function () {
		let constitution = $(this).val()
		$('#id-constitution').val(constitution)
		$('#button-save-punishment').prop('disabled', false)
		$.ajax({
			url: '<?= base_url() ?>long/loadconstitution',
			method: 'POST',
			data: { id: constitution },
			success: function (res) {
				$('#show-constitution').html(res)
			}
		})
	})

	const savePunishment = () => {
		Swal.fire({
			title: 'Anda yakin?',
			text: 'Tindakan ini akan disimpan permanen',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yakin',
			cancelButtonText: 'Gak jadi'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= base_url() ?>long/savepunishment',
					method: 'POST',
					data: $('#form-add-punishment').serialize(),
					dataType: 'JSON',
					success: function(res) {
						let status = res.status
						if (status != 200) {
							Swal.fire(
								'Oppss..!',
								res.message,
								'error'
							)
							return false
						}

						Swal.fire(
							'Yeahh..!',
							res.message,
							'success'
						)
						$('#modal-constitution').modal('hide')
						loadPermission()
					}
				})
			}
		})
	}

	const closePrint = id => {
		Swal.fire({
			title: 'Anda yakin?',
			text: 'Tindakan ini akan disimpan permanen',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yakin',
			cancelButtonText: 'Gak jadi'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= base_url() ?>long/closeprint',
					method: 'POST',
					data: { id },
					dataType: 'JSON',
					success: function(res) {
						let status = res.status
						if (status != 200) {
							errorAlert(res.message)
							return false
						}

						toastr.success('Print berhasil ditutup')
						loadPermission()
					}
				})
			}
		})
	}

	$('#nis-doback').on('keyup', function(e) {
		let nis = $(this).val()
		let key = e.which
		if (key != 13) {
			return false
		}

		if (key == 13 && nis == '') {
			errorAlert('Pastikan NIS tidak kosong')
			return false
		}

		getIdPermission(nis)
	})

	const getPermissionClicked = () => {
		let nis = $('#nis-doback').val()
		if (nis == '') {
			errorAlert('Pastikan NIS sudah diisi');
			return false
		}

		getIdPermission(nis)
	}

	const getIdPermission = nis => {
		$.ajax({
			url: '<?= base_url() ?>long/getidpermission',
			method: 'POST',
			data: {
				nis
			},
			dataType: 'JSON',
			success: function (res) {
				let status = res.status
				if (status === 400) {
					errorAlert(res.message);
					return false
				}

				$('#nis-doback').val('').focus()
				doBack(res.id)
			}
		})
	}

	$('#modal-doback').on('shown.bs.modal', function (){
		$('#nis-doback').val('').focus()
	})

</script>
</body>

</html>
