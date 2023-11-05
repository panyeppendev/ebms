<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
		<div class="row justify-content-between">
			<div class="col-3">
				<h4 class="card-title mt-1">Distribusi Makan</h4>
			</div>
			<div class="col-9">
				<div class="row justify-content-end">
					<div class="col-4">
						<a href="<?= base_url() ?>distribution" class="btn btn-sm btn-primary btn-block">
							Kembali halaman pengaturan
						</a>
					</div>
				</div>
			</div>
		</div>
        <?php if (!$setting) { ?>
            <div class="row mt-3">
                <div class="error-page" style="margin-top: 100px;">
                    <div class="error-content">
                        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! ada masalah nih....</h3>
                        <p>
                            Pendistribusian makan belum dibuka. Segera hubungi bagian admin!! ~<br>
                            <br>
                            <a href="<?= base_url() ?>">Kilik untuk kembali ke Beranda</a>
                        </p>

                    </div>
                </div>
            </div>
        <?php
			} else {
			if (!$dailySetting) {
		?>
				<div class="row mt-3">
					<div class="error-page" style="margin-top: 100px;">
						<div class="error-content">
							<h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! ada masalah nih....</h3>
							<p>
								Jenis pendistribusian makan belum diatur ~<br>
								<br>
								<a href="<?= base_url() ?>distribution">Atur sekarang</a>
							</p>

						</div>
					</div>
				</div>
		<?php
			}else{
		?>
            <div class="row mt-4">
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="card">NIS <small class="text-success">Tekan F2 untuk fokus inputan Kartu</small> </label>
                                <input autofocus data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" class="form-control" id="card" name="card">
                            </div>
                            <div class="form-group">
                                <form id="form-distribution" autocomplete="off">
                                    <input type="hidden" name="date" id="date" value="">
                                    <input type="hidden" name="nis" id="nis" value="">
                                    <input type="hidden" name="purchase" id="purchase" value="">
                                    <input type="hidden" name="account" id="account" value="">
									<input type="hidden" name="nominal" id="nominal" value="">
									<input type="hidden" name="status" id="status" value="">
								</form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8" id="show-data">

				</div>
            </div>
        <?php
				}
			}
		?>
    </section>
    <!-- /.content -->
</div>

<?php $this->load->view('partials/footer'); ?>
<script src="<?= base_url('template') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('template') ?>/plugins/daterangepicker/daterangepicker.js"></script>
<?php $this->load->view('distribution/create/js-distribution-create'); ?>
