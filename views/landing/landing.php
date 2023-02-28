<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cek Saldo Santri</title>
    <link rel="shortcut icon" href="<?= base_url() ?>/assets/images/favicon.png">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>template/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/toastr/toastr.min.css">
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="" class="navbar-brand">
                    <img src="<?= base_url() ?>assets/images/logo.png" alt="e-bms Sistem" class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light">e-bms Panyeppen</span>
                </a>
				<span class="text-muted text-sm">Pastikan cursor aktif pada bidang inputan, masukkan NIS lalu tekan ENTER</span>
				<input data-inputmask="'mask' : '9999999'" data-mask="" id="nis" autofocus type="text" class="form-control w-25" placeholder="Masukkan NIS lalu Enter">
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <div class="content">
                <div class="container pt-3">
                    <div class="row">
                        <!-- /.col-md-6 -->
                        <div class="col-12" id="show-data">

                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>

        <!-- Main Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>
            <?php
            $currentYear = date('Y');

            ?>
            <strong>Copyright &copy; <?= (date('Y') != $currentYear) ? $currentYear . '-' : '' ?><?= $currentYear ?> </strong><span class="text-default">e-BMS PPMU. Panyeppen</span> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="<?= base_url() ?>template/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url() ?>template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url() ?>template/plugins/inputmask/jquery.inputmask.min.js"></script>
    <script src="<?= base_url() ?>template/plugins/toastr/toastr.min.js"></script>
    <script src="<?= base_url() ?>template/dist/js/adminlte.min.js"></script>
    <script>
        $('[data-mask]').inputmask();

        toastr.options = {
            "positionClass": "toast-top-center",
            "timeOut": "2000"
        }

        $('#nis').on('keyup', function(e) {
            let id = $(this).val()
            let key = e.which
            if (key != 13) {
                return false
            }

            if (key == 13 && id == '') {
                return false
            }

            checkNis(id)
        })

        const checkButton = () => {
            let id = $('#nis').val()
            if (id == '') {
                return false
            }

            checkNis(id)
        }

        const checkNis = id => {
            $.ajax({
                url: '<?= base_url() ?>landing/checkSaldo',
                method: 'POST',
                data: {
                    id
                },
                dataType: 'JSON',
                success: function(res) {
                    if (res.status == 400) {
                        toastr.error(`Opppsss.! ${res.message}`)
                        return false
                    }
                    $('#nis').val('').focus()
                    getdata(id)
                }
            })
        }

        const getdata = id => {
            $.ajax({
                url: '<?= base_url() ?>landing/getdata',
                method: 'POST',
                data: {
                    id
                },
                success: function(res) {
                    $('#show-data').html(res)
                }
            })
        }
    </script>
</body>

</html>
