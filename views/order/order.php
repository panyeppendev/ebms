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
<input type="hidden" id="current_step" value="<?= $currentStep ?>">
<input type="hidden" id="start_step" value="<?= $startStep ?>">
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <?php if ($setting == 'CLOSED') { ?>
            <div class="row mt-3">
                <div class="error-page" style="margin-top: 100px;">
                    <div class="error-content">
                        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! ada masalah nih....</h3>
                        <p>
                            Pencairan uang saku baik tunai maupun non-tunai belum dibuka. Segera hubungi bagian admin ~<br>
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
                        Pencairan non tunai paket - <?= @$currentStep ?>
                    </div>
                </div>
                <div class="col-8" id="show-recap"></div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nis">NIS <small class="text-success">Tekan F2 untuk fokus inputan NIS</small> </label>
                                <input data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" class="form-control" id="nis" name="nis" autofocus>
                            </div>
                            <form id="form-order" autocomplete="off">
                                <input type="hidden" name="package" id="package" value="0">
                                <input type="hidden" name="pocket" id="pocket" value="0">
                                <input type="hidden" name="total" id="total" value="0">
                                <div class="form-group">
                                    <label for="nominal">Nominal</label>
                                    <input readonly type="text" class="form-control" id="nominal" name="nominal">
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <span class="text-info">
                                Tekan <b>F4</b> untuk cek saldo
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-8" id="show-data">

                </div>
            </div>
        <?php } ?>
    </section>
    <!-- /.content -->
</div>

<div class="modal fade" id="modal-check" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Cek Uang Saku Paket <?= $currentStep ?></h6>
                <div class="align-middle" data-dismiss="modal" title="Tutup" style="cursor: pointer">
                    <i class="fas fa-times-circle text-danger"></i>
                </div>
            </div>
            <div class="modal-body" style="background-color: #f4f6f9; min-height: 40vh">
                <div class="row">
                    <div class="col-4">
                        <div class="card mb-0">
                            <div class="card-body p-0">
                                <input data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" class="form-control" name="id" id="id">
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="mt-2 font-italic row justify-content-around">
                            <div class="text-info">
                                Masukkan ID Card lalu ENTER
                            </div>
                            <div class="text-danger">
                                Tekan <b>ESC</b> untuk tutup modal
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3" id="show-check"></div>
            </div>
            <div class="modal-footer justify-content-between p-2"></div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body" id="show-detail" style="max-height: 85vh;"></div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('order/js-order'); ?>