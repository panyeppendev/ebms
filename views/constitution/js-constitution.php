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

    const loadData = () => {
		let type = $('#change-type').val()
		let category = $('#change-category').val()
        $.ajax({
            url: '<?= base_url() ?>constitution/loaddata',
            method: 'POST',
			data: {
				type,
				category
			},
            success: function(res) {
                $('#load-data').html(res)
            }
        })
    }

    $(function(){
        loadData()
    })

	$('#modal-constitution').on('hidden.bs.modal', () => {
		$('#id').val(0)
		$('#form-add')[0].reset()
	})

	const beforeShowModal = () => {
		$('#method').val('ADD')
		$('#modal-constitution').modal('show')
	}

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
                    url: '<?= base_url() ?>constitution/save',
                    method: 'POST',
                    data: $('#form-add').serialize(),
                    dataType: 'JSON',
                    success: function(res) {
                        let status = res.status
                        if (status != 200) {
                            errorAlert(res.message)
                            return false
                        }
                        toastr.success(res.message)
						$('#clause').val('')
                        $('#name').val('')
						let method = $('#method').val()
						method === 'EDIT' && $('#modal-constitution').modal('hide')
                        loadData()
                    }
                })
            }
        })
    }

    const getById = id => {
		$.ajax({
			url: '<?= base_url() ?>constitution/getbyid',
			method: 'POST',
			data: { id },
			dataType: 'JSON',
			success: function(res) {
				let status = res.status
				if (status != 200) {
					errorAlert(res.message)
					return false
				}
				$('#id').val(res.id)
				$('#type').val(res.type)
				$('#category').val(res.category)
				$('#clause').val(res.clause)
				$('#name').val(res.name)
				$('#method').val('EDIT')
				$('#modal-constitution').modal('show')
			}
		})
    }

</script>
</body>

</html>
