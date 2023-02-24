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
            $('#changeName').focus().val('')
        }
    })

    $(function(){
		let autoDone = $('#result-auto').val()
		if (autoDone !== '0') {
			toastr.success(`INFO! ${autoDone} skorsing berhasil diselesaikan otomatis`)
		}

        loadSuspension()
        loadCountSuspension()
    })

    const filterLoad = () => {
		loadSuspension()
		loadCountSuspension()
    }

	const loadSuspension = () => {
		let name = $('#changeName').val();
		let status = $('#changeStatus').val();
		$.ajax({
			url: '<?= base_url() ?>suspension/loadsuspension',
			method: 'POST',
			data: {
				name,
				status
			},
			success: function (res){
				$('#show-suspension').html(res)
			}
		})
	}

    const loadCountSuspension = () => {
        let name = $('#changeName').val()
		let status = $('#changeStatus').val();
        $.ajax({
            url: '<?= base_url() ?>suspension/loadcountsuspension',
            method: 'POST',
            data: {
                name,
                status
            },
            success: function(res) {
                $('#show-count-suspension').html(res)
            }
        })
    }

	const doActive = id => {
		let term = $('#term').val()
		if (term === ''){
			errorAlert('Pastikan masa skorsing sudah dipilih');
			return false
		}

		Swal.fire({
			title: 'Yakin, nih?',
			text: 'Tindakan ini akan disimpan permanen',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yakin, dong!',
			cancelButtonText: 'Nggak jadi'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= base_url() ?>suspension/doactive',
					method: 'POST',
					data: {
						id,
						term
					},
					dataType: 'JSON',
					success: function (res) {
						let status = res.status
						if (status !== 200){
							errorAlert(res.message);
							return false
						}
						toastr.success(res.message)
						loadSuspension()
					}
				})
			}
		})
	}

	const doDone = id => {
		Swal.fire({
			title: 'Yakin, nih?',
			text: 'Tindakan ini akan disimpan permanen',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yakin, dong!',
			cancelButtonText: 'Nggak jadi'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= base_url() ?>suspension/dodone',
					method: 'POST',
					data: {
						id
					},
					dataType: 'JSON',
					success: function (res) {
						let status = res.status
						if (status !== 200){
							errorAlert(res.message);
							return false
						}
						toastr.success(res.message)
						loadSuspension()
					}
				})
			}
		})
	}

	const doCustom = id => {
	  	let custom = $('#custom-day').val()
		if (custom === '' || custom === '0') {
			errorAlert('Pastikan jumlah hari diisi');
			return false
		}

		Swal.fire({
			title: 'Yakin, nih?',
			text: 'Pastikan jumlah hari diisi dengan tepat',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yakin, dong!',
			cancelButtonText: 'Nggak jadi'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= base_url() ?>suspension/doCustom',
					method: 'POST',
					data: {
						id,
						custom
					},
					dataType: 'JSON',
					success: function (res) {
						let status = res.status
						if (status !== 200){
							errorAlert(res.message);
							return false
						}
						Swal.fire(
							'Yeaahh..!',
							`Skorsing berhasil ${res.message}`,
							'success'
						)
						loadSuspension()
					}
				})
			}
		})
	}
</script>
</body>

</html>
