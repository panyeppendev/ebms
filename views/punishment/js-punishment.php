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
        loadPunishment()
        loadCountPunishment()
    })

    const filterLoad = () => {
		loadPunishment()
		loadCountPunishment()
    }

    const loadDetail = () => {
        let name = $('#change-name').val()
        let filter = $('#filter-date').val()
        let category = $('#filter-category').val()
        $.ajax({
            url: '<?= base_url() ?>punishment/loaddetail',
            method: 'POST',
            data: {
				category,
                name,
                filter
            },
            success: function(res) {
                $('#show-detail').html(res)
				$('#modal-detail').modal('show')
            }
        })
    }

	const beforeLoadDetail = category => {
		$('#filter-category').val(category)
		loadDetail()
	}

    const loadRecap = () => {
        let filter = $('#filter-date').val()
        $.ajax({
            url: '<?= base_url() ?>punishment/loadrecap',
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
            cancelLabel: 'Semua data',
            applyLabel: 'Terapkan'
        }
    })

    $('#reservation').on('apply.daterangepicker', function(ev, picker) {
        $(this).val('').attr('placeholder', picker.startDate.format('DD/MM/YYYY'));
        $('#filter-date').val(picker.startDate.format('YYYY-MM-DD'))

        loadRecap()
    });

    $('#reservation').on('cancel.daterangepicker', function(ev, picker) {
        $(this).attr('placeholder', 'Semua waktu').val('');
        $('#filter-date').val('')

        loadRecap()
    });

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

    const loadCountPunishment = () => {
        let name = $('#changeName').val()
		let category = $('#changeCategory').val();
        $.ajax({
            url: '<?= base_url() ?>punishment/loadcountpunishment',
            method: 'POST',
            data: {
                name,
                category
            },
            success: function(res) {
                $('#show-count-punishment').html(res)
            }
        })
    }

</script>
</body>

</html>
