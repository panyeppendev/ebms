<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b>Version</b> 1.6.2
    </div>
    <?php
    $currentYear = date('Y');

    ?>
    <strong>Copyright &copy; <?= (date('Y') != $currentYear) ? $currentYear . '-' : '' ?><?= $currentYear ?> </strong><span class="text-default">e-BMS PPMU. Panyeppen</span> All rights reserved.
</footer>

</div>
<!-- jQuery -->
<script src="<?= base_url() ?>template/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url() ?>template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>template/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="<?= base_url() ?>template/plugins/inputmask/jquery.inputmask.min.js"></script>
<script src="<?= base_url() ?>template/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>template/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="<?= base_url() ?>template/plugins/toastr/toastr.min.js"></script>
<script src="<?= base_url() ?>template/dist/js/adminlte.min.js"></script>
<script src="<?= base_url() ?>template/plugins/croppie/croppie.js"></script>
<script src="<?= base_url() ?>template/plugins/croppie/exif.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>template/dist/js/demo.js"></script>