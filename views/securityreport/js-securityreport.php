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

	$('.reservation').daterangepicker({
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

	$('#reservation').on('apply.daterangepicker', function(ev, picker) {
		$(this).val('').attr('placeholder', picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
		$('#start-date').val(picker.startDate.format('YYYY-MM-DD'))
		$('#end-date').val(picker.endDate.format('YYYY-MM-DD'))
	});

	$('#reservation').on('cancel.daterangepicker', function(ev, picker) {
		$(this).attr('placeholder', 'Semua waktu').val('');
		$('#start-date').val(0)
		$('#end-date').val(0)
	});

	$('#reservation-perizinan').on('apply.daterangepicker', function(ev, picker) {
		$(this).val('').attr('placeholder', picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
		$('#start-date-perizinan').val(picker.startDate.format('YYYY-MM-DD'))
		$('#end-date-perizinan').val(picker.endDate.format('YYYY-MM-DD'))
	});

	$('#reservation-perizinan').on('cancel.daterangepicker', function(ev, picker) {
		$(this).attr('placeholder', 'Semua waktu').val('');
		$('#start-date-perizinan').val(0)
		$('#end-date-perizinan').val(0)
	});

	$('#reservation-pelanggaran').on('apply.daterangepicker', function(ev, picker) {
		$(this).val('').attr('placeholder', picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
		$('#start-date-pelanggaran').val(picker.startDate.format('YYYY-MM-DD'))
		$('#end-date-pelanggaran').val(picker.endDate.format('YYYY-MM-DD'))
	});

	$('#reservation-pelanggaran').on('cancel.daterangepicker', function(ev, picker) {
		$(this).attr('placeholder', 'Semua waktu').val('');
		$('#start-date-pelanggaran').val(0)
		$('#end-date-pelanggaran').val(0)
	});

	const printBarber = () => {
		let date = $('#date').val()

		if (!date) {
			errorAlert('Pastikan tanggal diisi');
			return false
		}

		window.open('<?= base_url() ?>securityreport/printbarber?date=' + date)
	}

	const printPenalty = () => {
		let start = $('#start-date').val()
		let end = $('#end-date').val()

		window.open('<?= base_url() ?>securityreport/printpenalty?start=' + start + '&end=' + end)
	}

	const printPerizinan = () => {
		let start = $('#start-date-perizinan').val()
		let end = $('#end-date-perizinan').val()

		window.open('<?= base_url() ?>securityreport/laporanPerizinan?start=' + start + '&end=' + end)
	}

	const printPelanggaran = () => {
		let start = $('#start-date-pelanggaran').val()
		let end = $('#end-date-pelanggaran').val()

		window.open('<?= base_url() ?>securityreport/laporanpelanggaran?start=' + start + '&end=' + end)
	}
</script>
</body>

</html>
