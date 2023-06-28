<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

	$(function (){
		loadData()
	})

    const setKelas = () => {
		let kelas = $('#kelas').val()
		$('#kelas-set').val(kelas)
		loadData()
    }

	const set = () => {
		const kelas = $('#kelas-set').val()
		if (kelas == ''){
			toastr.error('Kelas belum dipilih')
			return false
		}

		$('#modal-set').modal('show')
	}

	$('#save-set').on('click', function (){
		const rombel = $('#rombel').val()
		const head = $('#head').val()
		if (rombel == '' || rombel == '0' || head == ''){
			toastr.error('Pastikan inputan valid')
			return false
		}

		$.ajax({
			url: '<?= base_url() ?>educationsetting/saveset',
			method: 'post',
			data: $('#form-set-save').serialize(),
			dataType: 'json',
			success: function (res){
				if (res.status != 200){
					toastr.error(res.message)
					return false
				}

				toastr.success(res.message)
				$('#rombel').val('')
				$('#head').val('')
				$('#modal-set').modal('hide')
				loadData()
			}
		})
	})

	const loadData = () => {
	  	const kelas = $('#kelas-set').val()
	  	const level = $('#level-set').val()
		$.ajax({
			url: '<?= base_url() ?>educationsetting/loaddata',
			method: 'post',
			data: {
				kelas,
				level
			},
			success: res => {
				$('#show-data-set').html(res)
			}
		})
	}
</script>
</body>

</html>
