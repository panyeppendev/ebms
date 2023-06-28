<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

    const setLevel = () => {
		let level = $('#change-level').val()
		if (level == ''){
			toastr.error('Pastikan tingkat pendidikan dipilih')
			return false
		}

		$('#level').val(level)
		$('#form-set').submit()
    }

	const setMurid = () => {
		let level = $('#change-level-murid').val()
		if (level == ''){
			toastr.error('Pastikan tingkat pendidikan dipilih')
			return false
		}

		$('#level-murid').val(level)
		$('#form-murid-set').submit()
	}
</script>
</body>

</html>
