<?php $this->load->view('partials/header'); ?>
<input type="hidden" id="current-step" value="<?= $step ?>">
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <?php if ($setting == 'CLOSED' || !$shift) { ?>
            <div class="row mt-3">
                <div class="error-page" style="margin-top: 100px;">
                    <div class="error-content">
                        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! ada masalah nih....</h3>
                        <p>
                            <?php
                            if ($setting == 'CLOSED') {
                                echo 'Pencairan DPU belum dibuka. Hubungi bagian Admin!! ~ <br>';
                            }
                            if (!$shift) {
                                echo 'Anda tidak bisa melayani pada saat jam tutup. Hubungi bagian Admin!! ~';
                            }
                            ?>
                            <br>
                            <br>
                            <a href="<?= base_url() ?>">Kilik untuk kembali ke Beranda</a>
                        </p>

                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row">
                <div class="col-4">
                    <div class="callout callout-success py-1">
                        <i class="fas fa-info-circle"></i>
                        Pencairan DPU paket - <?= @$step ?>
                    </div>
                </div>
                <div class="col-6" id="load-data"></div>
                <div class="col-2">
                    <button class="btn btn-default btn-sm btn-block">
                        <b class="text-success">Rekapitulasi</b>
                    </button>
                </div>
            </div>
            <hr class="mt-0">
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <label for="nis">NIS</label>
                                <input autofocus data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" class="form-control" id="nis" name="nis">
                            </div>
                        </div>
                        <div class="card-footer py-1"></div>
                    </div>
                </div>
                <div class="col-8" id="show-data"></div>
            </div>
        <?php } ?>
    </section>
    <!-- /.content -->
</div>
<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('transaction/js-transaction'); ?>