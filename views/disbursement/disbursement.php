<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
		<div class="row justify-content-between">
			<div class="col-3">
				<h4 class="card-title mt-1">Transaksi Pencairan</h4>
			</div>
			<div class="col-9">
				<div class="row justify-content-end">
					<div class="col-3">
						<button class="btn btn-sm btn-primary btn-block" data-toggle="modal" data-target="#modal-data">
							Riwayat Transaksi
						</button>
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
                            Pencairan uang saku baik tunai maupun non-tunai belum dibuka. Segera atur!! ~<br>
                            <br>
                            <a href="<?= base_url() ?>">Kilik untuk kembali ke Beranda</a>
                        </p>

                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row mt-2">
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="card">NIS <small class="text-success">Tekan F2 untuk fokus inputan Kartu</small> </label>
                                <input autofocus data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" class="form-control" id="card" name="card">
                            </div>
                            <div class="form-group">
                                <form id="form-disbursement" autocomplete="off">
                                    <input type="hidden" name="date" id="date" value="<?= $setting->created_at ?>">
                                    <input type="hidden" name="nis" id="nis" value="">
                                    <input type="hidden" name="purchase" id="purchase" value="">
                                    <input type="hidden" name="account" id="account" value="">
                                    <input type="hidden" name="total" id="total" value="">
									<input type="hidden" name="nominal_real" id="nominal-real" value="">
                                    <label for="nominal">Nominal</label>
                                    <input readonly type="text" class="form-control indonesian-currency" id="nominal" name="nominal">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8" id="show-data" style="display: none">
					<b id="name"></b> <br>
					<hr class="my-2">
					<span id="address"></span>
					<br>
					<span id="domicile"></span>
					<hr class="my-2">
					<span id="diniyah"></span>
					<br>
					<span id="formal"></span>
					<hr class="my-2">
					<span id="total-text"></span>
				</div>
            </div>
        <?php } ?>
    </section>
    <!-- /.content -->
</div>

<div class="modal fade" id="modal-data">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<input autocomplete="off" type="text" id="filter-name" class="form-control form-control-sm" placeholder="Ketik nama lalu tekan enter">
			</div>
			<div class="modal-body" id="show-disbursement" style="min-height: 45vh; max-height: 85vh; overflow: auto"></div>
		</div>
	</div>
</div>


<?php $this->load->view('partials/footer'); ?>
<script src="<?= base_url('template') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('template') ?>/plugins/daterangepicker/daterangepicker.js"></script>
<?php $this->load->view('disbursement/js-disbursement'); ?>
