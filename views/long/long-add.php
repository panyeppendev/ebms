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
                            Izin jarak dekat paket - <?= @$currentStep ?>
                        </div>
                    </div>
                </div>
                <div class="col-7"></div>
                <div class="col-2">
                    <a href="<?= base_url() ?>long" class="btn btn-primary btn-sm btn-block">
                        <i class="fas fa-list-ul"></i> Lihat data
                    </a>
                </div>
            </div>
            <hr class="mt-0">
            <div class="row">
                <div class="col-5">
                    <div class="card">
                        <div class="card-body pb-0 px-2">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">NO. KARTU WALI</label>
                                    <div class="col-sm-7">
                                        <input data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" autofocus name="nis" id="nis" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div id="wrap-type" style="display: none;">
                                <form id="form-save-long" autocomplete="off">
                                    <div class="col-12">
                                            <input type="hidden" id="nis-save" name="nis" value="0">
                                            <input type="hidden" id="step" name="step" value="<?= $currentStep ?>">
                                            <input type="hidden" id="nominal" name="nominal" value="0">
                                            <input type="hidden" id="note" name="note" value="REGULER">
                                        <div class="form-group">
                                            <label for="reason">Alasan</label>
                                            <select name="reason" id="reason" class="form-control">
                                                <option value="">.:Pilih Alasan:.</option>
                                                <?php
                                                    if ($reasons) {
                                                        foreach ($reasons as $reason) {
                                                ?>
                                                    <option value="<?= $reason->name ?>"><?= $reason->name ?></option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
										<div class="row">
											<div class="col-sm-7">
												<!-- text input -->
												<div class="form-group">
													<label>Tanggal Kembali</label>
													<input type="date" class="form-control" name="date" id="date">
												</div>
											</div>
											<div class="col-sm-5">
												<div class="form-group">
													<label>Waktu</label>
													<select name="time" id="time" class="form-control">
														<option value="">.:Waktu:.</option>
														<option value="06:00:00">Pagi</option>
														<option value="11:30:00">Siang</option>
														<option value="17:00:00">Sore</option>
														<option value="08:00:00">Malam</option>
													</select>
												</div>
											</div>
										</div>
                                        <button style="display: none;" id="show-nominal" type="button" class="btn btn-block btn-default mb-4 text-primary">
                                            <h6>Tarif : <span id="nominal-rp"></span></h6>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-footer px-3">
                            <button id="button-check" class="btn btn-primary btn-block" onclick="checkClicked()">
                                Cek NIS
                            </button>
							<div class="row justify-content-between btn-save" style="display: none;">
								<div class="col-6">
									<button class="btn btn-danger btn-block" onclick="cancelSave()">
										<i class="fas fa-times-circle"></i> Batalkan
									</button>
								</div>
								<div class="col-6">
									<button class="btn btn-primary btn-block" id="button-save" onclick="save()">
										<i class="fas fa-money-bill-alt"></i> Bayar dan simpan izin
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-7">
                    <div class="text-center text-success pt-5" style="display: none;" id="show-success">
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
<?php $this->load->view('long/js-long-add'); ?>
