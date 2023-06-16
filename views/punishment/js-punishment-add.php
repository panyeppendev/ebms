<script src="<?= base_url() ?>template/plugins/select2/js/select2.full.min.js"></script>
<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

	$('.select2bs4').select2({
		theme: 'bootstrap4'
	})

	$(document).on('select2:open', () => {
		document.querySelector('.select2-search__field').focus();
	});

    const errorAlert = message => {
        toastr.error(`Opss.! ${ message }`)
    }

    $('body').on('keyup', e => {
        if (e.keyCode == 113) {
            $('#nis').focus().val('')
        }
    })

    $('#nis').on('keyup', function(e) {
        let nis = $(this).val()
		let constitution = $('#constitution').val()
        let key = e.which
        if (key != 13) {
            return false
        }

        if (key == 13 && nis == '') {
            errorAlert('Pastikan NIS tidak kosong')
            return false
        }
		$('#button-check').show()
		$('#btn-save').hide()

		$('#constitution').select2('open');
    })

    const checkClicked = () => {
        let nis = $('#nis').val()
		let constitution = $('#constitution').val()

        if (nis == '' || constitution == '') {
            errorAlert('Pastikan NIS/pelanggaran tidak kosong')
            return false
        }
		$('#nis-save').val(nis)

        checkNis()
    }

    const checkNis = () => {
        $.ajax({
            url: '<?= base_url() ?>punishment/checknis',
            method: 'POST',
            data: $('#form-save-punishment').serialize(),
            dataType: 'JSON',
            success: function(res) {
				console.log(res)
                let status = res.status
                if (status === 500) {
                    errorAlert(res.message)
                    $('#show-data').html('')
                    return false
                }

				if (status === 400){
					Swal.fire('Opss..', res.message, 'error')
					getData(res)
					return false
				}

				$('#nis').focus().val('')
				$('#constitution').val('').trigger('change')
				$('#no').prop('checked', true)

				Swal.fire('Yeaahhh..', res.message, 'success')
                getData(res)
            }
        })
    }

    const getData = data => {
        $.ajax({
            url: '<?= base_url() ?>punishment/getdata',
            method: 'POST',
            data: {
                nis: data.nis,
                constitution: data.constitution,
				status: data.status,
				message: data.message
            },
            success: function(res) {
                $('#show-data').html(res)
            }
        })
    }
	
	const loadPunishment = () => {
		let name = $('#changeName').val();
		let category = $('#changeCategory').val();
		$.ajax({
			url: '<?= base_url() ?>punishment/loadpunishment',
			method: 'POST',
			data: {
				name,
				category
			},
			success: function (res){
				$('#show-punishment').html(res)
			}
		})
	}

	const search = () => {
		const name = $('#name-search').val()
		if (name === '') {
			errorAlert('Pastikan nama sudah diisi')
			return false
		}

		$.ajax({
			url: '<?= base_url() ?>punishment/search',
			method: 'POST',
			data: { name },
			success: res => {
				$('#show-search').html(res)
			}
		})
	}

	$('#modal-search').on('shown.bs.modal', function (){
		$('#name-search').focus()
		$('#show-search').html('')
	})

	$('#modal-search').on('hidden.bs.modal', function (){
		$('#name-search').val('')
		$('#show-search').html('')
	})


	function copyToClipboard(text) {
		var sampleTextarea = document.createElement("textarea");
		document.body.appendChild(sampleTextarea);
		sampleTextarea.value = text; //save main text in it
		sampleTextarea.select(); //select textarea contenrs
		document.execCommand("copy");
		document.body.removeChild(sampleTextarea);
		toastr.success('ID berhasil disalin ke clipboard')
		$('#nis').val(text)
	}
</script>
</body>

</html>
