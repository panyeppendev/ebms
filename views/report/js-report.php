<script src="<?= base_url('template') ?>/plugins/autoNumeric.js"></script>
<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

    $(function() {
        loadPaymentReport()
        loadDisbursementReport()
    })

    const loadPaymentReport = () => {
        $('#show-payment-report').html('<h6 class="text-center text-info"><i>Data sedang dimuat ..........</i></h6>')
        let step = $('#change-step-payment').val()
        $.ajax({
            url: '<?= base_url() ?>report/loadpaymentreport',
            method: 'POST',
            data: {
                step
            },
            beforeSend: function() {
                $('.wrap-loading__').show()
            },
            success: function(res) {
                $('#show-payment-report').html(res)
            },
            complete: function() {
                $('.wrap-loading__').hide()
            }
        })
    }

    const loadDisbursementReport = () => {
        $('#show-disbursement-report').html('<h6 class="text-center text-info"><i>Data sedang dimuat ..........</i></h6>')
        let step = $('#change-step-disbursement').val()
        let startDate = $('#start-date').val()
        let endDate = $('#end-date').val()
        $.ajax({
            url: '<?= base_url() ?>report/loaddisbursementreport',
            method: 'POST',
            data: {
                step,
                startDate,
                endDate
            },
            beforeSend: function() {
                $('.wrap-loading__').show()
            },
            success: function(res) {
                $('#show-disbursement-report').html(res)
            },
            complete: function() {
                $('.wrap-loading__').hide()
            }
        })
    }

    $('#reservation').daterangepicker({
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
        $('#start-date-download').val(picker.startDate.format('YYYY-MM-DD'))
        $('#end-date-download').val(picker.endDate.format('YYYY-MM-DD'))

        loadDisbursementReport()
    });

    $('#reservation').on('cancel.daterangepicker', function(ev, picker) {
        $(this).attr('placeholder', 'Semua waktu').val('');
        $('#start-date').val('')
        $('#end-date').val('')
        $('#start-date-download').val('')
        $('#end-date-download').val('')

        loadDisbursementReport()
    });

    $('#change-step-payment').on('change', function() {
        $('#step-payment-download').val($(this).val())
    })

    $('#change-step-disbursement').on('change', function() {
        $('#step-disbursement-download').val($(this).val())
    })
</script>
</body>

</html>