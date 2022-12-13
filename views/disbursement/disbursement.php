<?php $this->load->view('partials/header'); ?>
<?php
if ($step[0] != 0) {
    $currentStep = $step[0];
    $startStep = $step[1];
} else {
    $currentStep = 0;
    $startStep = 0;
}
?>
<input type="hidden" id="current-step" value="<?= $currentStep ?>">
<input type="hidden" id="start-step" value="<?= $startStep ?>">
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <?php if ($setting == 'CLOSED') { ?>
            <div class="row mt-3">
                <div class="error-page" style="margin-top: 100px;">
                    <div class="error-content">
                        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! ada masalah nih....</h3>
                        <p>
                            Pencairan uang saku baik tunai maupun non-tunai belum dibuka. Segera atur!! ~<br>
                            <br>
                            <a href="<?= base_url() ?>">Kilik untuk kembali ke Beranda</a>
                        </p>

                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row mb-2">
                <div class="col-4">
                    <div class="callout callout-success py-1">
                        <i class="fas fa-info-circle"></i>
                        Pencairan uang saku paket - <?= @$currentStep ?>
                    </div>
                </div>
                <div class="col-6" id="show-recap"></div>
                <div class="col-2">
                    <button class="btn btn-default btn-sm btn-block">
                        <b class="text-success">Rekapitulasi</b>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nis">NIS</label>
                                <input autofocus data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" class="form-control" id="nis" name="nis">
                            </div>
                            <div class="form-group">
                                <form id="form-disbursement">
                                    <input type="hidden" name="package_save" id="package-save">
                                    <input type="hidden" name="pocket_save" id="pocket-save">
                                    <input type="hidden" name="total_save" id="total-save">
                                    <label for="nominal">Nominal</label>
                                    <input readonly type="text" class="form-control indonesian-currency" id="nominal" name="nominal">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8" id="show-data"></div>
            </div>
        <?php } ?>
    </section>
    <!-- /.content -->
</div>


<div class="modal fade" id="modal-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body" id="show-detail"></div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('disbursement/js-disbursement'); ?>