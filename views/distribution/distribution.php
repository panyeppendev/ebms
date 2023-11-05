<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <?php if (!$setting) { ?>
            <div class="row mt-3">
                <div class="error-page" style="margin-top: 100px;">
                    <div class="error-content">
                        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! ada masalah nih....</h3>
                        <p>
                            Pendistribusian makan belum dibuka. Segera hubungi admin!! ~<br>
                            <br>
                            <a href="<?= base_url() ?>">Kilik untuk kembali ke Beranda</a>
                        </p>

                    </div>
                </div>
            </div>
        <?php } else { ?>
			<div class="row mt-5 justify-content-center mb-5">
				<?php
				if (!$dailySetting) {
					?>
					<div class="col-8">
						<div class="error-content text-center">
							<h3>
								<i class="fas fa-exclamation-triangle text-danger"></i>
								Oops! Ada yang salah, nih.
							</h3>
							<p>
								Jenis pendistribusian makan belum diatur. <br>
							</p>
						</div>
					</div>
				<?php
				}else{
				?>
					<input type="hidden" id="setting" value="<?= $setting->created_at ?>">
					<input type="hidden" id="setting-category" value="<?= $dailySetting->category ?>">
					<div class="col-8 text-center">
						<p class="font-weight-normal mb-2">Pengaturan saat ini:</p>
						<div class="card">
							<div class="card-body">
								<h4 class="text-primary mb-0">
									<?php
									$text = [
										'BREAKFAST' => 'Sarapan',
										'MORNING' => 'Makan Pagi',
										'AFTERNOON' => 'Makan Sore',
										'FASTING' => 'Sahur'
									];
									?>

									Distribusi <?= $text[$dailySetting->category] ?>
								</h4>
							</div>
						</div>
					</div>
				<?php
				}
				?>
			</div>
			<div class="row justify-content-center">
				<p>Anda bisa ubah jenis distribusi melalui tombil ikon di bawah ini</p>
			</div>
			<div class="row justify-content-center">
				<div class="col-2 <?= (@$dailySetting->category == 'BREAKFAST') ? 'd-none' : '' ?>">
					<button type="button" class="btn btn-outline-primary btn-block py-4" onclick="setTransaction('account_breakfast', 'BREAKFAST')">
						<i class="fa fa-cookie fa-3x"></i>
						<span class="d-block mt-3">Sarapan</span>
					</button>
				</div>
				<div class="col-2 <?= (@$dailySetting->category == 'MORNING') ? 'd-none' : '' ?>">
					<button type="button" class="btn btn-outline-primary btn-block py-4" onclick="setTransaction('account_dpu', 'MORNING')">
						<i class="fas fa-coffee fa-3x"></i>
						<span class="d-block mt-3">Makan Pagi</span>
					</button>
				</div>
				<div class="col-2 <?= (@$dailySetting->category == 'AFTERNOON') ? 'd-none' : '' ?>">
					<button type="button" class="btn btn-outline-primary btn-block py-4" onclick="setTransaction('account_dpu', 'AFTERNOON')">
						<i class="fas fa-utensils fa-3x"></i>
						<span class="d-block mt-3">Makan Sore</span>
					</button>
				</div>
				<div class="col-2 <?= (@$dailySetting->category == 'FASTING') ? 'd-none' : '' ?>">
					<button type="button" class="btn btn-outline-primary btn-block py-4" onclick="setTransaction('account_dpu', 'FASTING')">
						<i class="fas fa-moon fa-3x"></i>
						<span class="d-block mt-3">Sahur</span>
					</button>
				</div>
			</div>
			<div class="row mt-5 justify-content-center">
				<div class="col-3">
					<button class="btn btn-primary btn-block mt-5" onclick="distributions()">
						Riwayat Transaksi
					</button>
				</div>
				<div class="col-3">
					<a href="<?= base_url() ?>distribution/create" class="btn btn-primary btn-block mt-5">
						Mulai Transaksi
					</a>
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
			<div class="modal-body" id="show-distribution" style="min-height: 45vh; max-height: 85vh; overflow: auto"></div>
		</div>
	</div>
</div>


<?php $this->load->view('partials/footer'); ?>
<script src="<?= base_url('template') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('template') ?>/plugins/daterangepicker/daterangepicker.js"></script>
<?php $this->load->view('distribution/js-distribution'); ?>
