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

    $('#nominal').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        aForm: true,
        vMax: '999999999',
        vMin: '-999999999'
    });

    $(function() {
        loadData()
        let currentStep = $('#current-step').val()
        if (currentStep == 0) {
            Swal.fire({
                title: 'Tahap pembayaran belum diatur',
                icon: 'error',
                html: 'Anda akan diarahkan dalam <strong>3</strong> detik.<br/><br/>',
                timer: 3000,
                timerProgressBar: true
            })
            setTimeout(function() {
                window.location.href = '<?= base_url() ?>paymentsetting'
            }, 3000)
        }
    })

    const loadData = () => {
        let name = $('#change-name').val()
        let domicile = $('#change-domicile').val()
        $.ajax({
            url: '<?= base_url() ?>deposit/loaddata',
            method: 'POST',
            data: {
                name,
                domicile
            },
            success: function(response) {
                $('#show-data').html(response)
            }
        })
    }

    const detailDeposit = id => {
        $.ajax({
            url: '<?= base_url() ?>deposit/detail',
            method: 'POST',
            data: {
                id
            },
            success: function(response) {
                $('#show-detail').html(response)
            },
            complete: function() {
                $('#modal-detail').modal('show')
            }
        })
    }
</script>
</body>

</html>
