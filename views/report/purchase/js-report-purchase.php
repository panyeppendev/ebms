<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

    const errorAlert = message => {
        toastr.error(`Opss.! ${ message }`)
    }

	let reservation = $('#reservation')

	reservation.daterangepicker({
		ranges: {
			'Hari ini': [moment(), moment()],
			'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'7 hari terakhir': [moment().subtract(6, 'days'), moment()],
			'30 hari terakhir': [moment().subtract(29, 'days'), moment()],
			'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
			'Bulan lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		},
		autoUpdateInput: false,
		locale: {
			cancelLabel: 'Reset',
			applyLabel: 'Terapkan'
		}
	})

	reservation.on('apply.daterangepicker', function(ev, picker) {
		$(this).val('').attr('placeholder', picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
		$('#start-date').val(picker.startDate.format('YYYY-MM-DD'))
		$('#end-date').val(picker.endDate.format('YYYY-MM-DD'))
		purchases()
	});

	reservation.on('cancel.daterangepicker', function(ev, picker) {
		$(this).attr('placeholder', 'Semua waktu').val('');
		$('#start-date').val('')
		$('#end-date').val('')
		purchases()
	});

    $(function(){
		purchases()
    })

    const purchases = () => {
        let start = $('#start-date').val()
		let end = $('#end-date').val()

        $.ajax({
            url: '<?= base_url() ?>report/purchases',
            method: 'POST',
            data: {
                start,
                end
            },
            success: function(res) {
                $('#show-data').html(res)
            }
        })
    }
</script>
</body>

</html>
