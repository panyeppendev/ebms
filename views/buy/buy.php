<?php $this->load->view('partials/header'); ?>
<?php
if ($step[0] != 0) {
    $currentStep = $step[0];
} else {
    $currentStep = 0;
}
?>
<input type="hidden" id="current-step" value="<?= $currentStep ?>">
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
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-body py-1">
                            <i class="fas fa-info-circle"></i>
                            Pangkas rambut paket - <?= @$currentStep ?>
                        </div>
                    </div>
                </div>
                <div class="col-6" data-toggle="modal" data-target="#modal-detail" id="show-recap"></div>
                <div class="col-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="reservation" placeholder="Hari ini">
                        <input type="hidden" id="filter-date" value="">
                    </div>
                </div>
            </div>
            <hr class="mt-0">
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-body pb-0">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">NIS</label>
                                <div class="col-sm-8">
                                    <input data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" autofocus name="nis" id="nis" class="form-control">
                                </div>
                            </div>
                            <div id="wrap-type" style="display: none;">
								<div class="form-group row mb-0">
                                    <div class="col-12">
                                        <form id="form-save-buy" autocomplete="off">
                                            <input type="hidden" id="nis-save" name="nis" value="0">
                                            <input type="hidden" id="step" name="step" value="<?= $currentStep ?>">
                                            <input type="hidden" id="package" name="package" value="0">
                                            <input type="hidden" id="nominal" name="nominal" value="0">
                                            <input type="hidden" id="status" name="status" value="0">
                                        </form>
                                        <button style="display: none;" id="show-nominal" type="button" class="btn btn-block btn-default mb-4 text-primary">
                                            <h6>Tarif : <span id="nominal-rp"></span></h6>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button id="button-check" class="btn btn-primary btn-block" onclick="checkClicked()">
                                Cek NIS
                            </button>
                            <button id="button-save" style="display: none;" class="btn btn-primary btn-block btn-save" onclick="setStatusBeforeSave('POCKET')">
                                <i class="fas fa-credit-card"></i> Bayar dengan Uang Saku
                            </button>
                            <button id="button-save" style="display: none;" class="btn btn-primary btn-block btn-save" onclick="setStatusBeforeSave('CASH')">
                                <i class="fas fa-money-bill-alt"></i> Bayar dengan Uang Cash
                            </button>
						</div>
					</div>
				</div>
				<div class="col-8">
                    <div class="text-center text-primary pt-5" style="display: none;" id="show-success">
                        <i class="fas fa-check-circle fa-5x"></i>
                        <br>
                        <h6 class="mt-3">
                            Satu transaksi selesai...
                        </h6>
                    </div>
                    <div id="show-data"></div>
                </div>
			</div>
	<?php } ?>
	</section>
	<!-- /.content -->
</div>

<div class="modal fade" id="modal-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <input type="text" onkeyup="loadData()" id="changeName" class="form-control form-control-sm" placeholder="Cari nama">
            </div>
            <div class="modal-body" id="show-detail" style="min-height: 45vh; max-height: 85vh; overflow: auto"></div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/footer'); ?>
<script src="<?= base_url('template') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('template') ?>/plugins/daterangepicker/daterangepicker.js"></script>
<?php $this->load->view('buy/js-buy'); ?>
