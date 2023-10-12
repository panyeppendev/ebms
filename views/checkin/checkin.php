<?php $this->load->view('partials/header'); ?>
	<div class="content-wrapper">
		<!-- Main content -->
		<section class="content p-3">
			<?php if ($setting == 'OPEN') { ?>
				<div class="row mt-3">
					<div class="error-page" style="margin-top: 100px;">
						<div class="error-content">
							<h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! ada masalah nih....</h3>
							<p>
								Liburan belum diatur. Segera hubungi bagian admin ~<br>
								<br>
								<a href="<?= base_url() ?>">Kembali ke Beranda</a>
							</p>

						</div>
					</div>
				</div>
			<?php } ?>
			<?php if ($setting == 'CLOSE' && date('Y-m-d H:i:s') < $data[1]) { ?>
				<div class="row mt-3">
					<div class="error-page" style="margin-top: 100px;">
						<div class="error-content">
							<h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! ada masalah nih....</h3>
							<p>
								Check In liburan akan dibuka pada <b class="text-success"> <?= datetimeIDFormat($data[1]) ?></b> ~<br>
								<br>
								<a href="<?= base_url() ?>">Kilik untuk kembali ke Beranda</a>
							</p>

						</div>
					</div>
				</div>
			<?php } ?>
			<?php if ($setting == 'CLOSE' && (date('Y-m-d H:i:s') >= $data[1])) { ?>
				<div class="row">
					<div class="col-4">
						<div class="card">
							<div class="card-body">
								<div class="form-group row mb-2">
									<label for="nis" class="col-sm-4 col-form-label">ID Card</label>
									<div class="col-sm-8">
										<input data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" class="form-control" id="nis" name="nis" autofocus>
									</div>
								</div>
								<div class="p-2 d-flex text-success">
                                <span class="mr-1">
                                    <i class="fas fa-info-circle"></i>
                                </span>
									<small class="pt-1">Pastikan cursor fokus pada bidang inputan, lalu masukkan nomor kartu santri lalu tekan ENTER</small>
								</div>
								<div class="mt-3">
									<button onclick="loadData()" data-toggle="modal" data-target="#modal-data" type="button" class="btn btn-primary btn-block">Lihat Data</button>
								</div>
							</div>
						</div>
						<div id="alert-no" class="alert alert-danger d-none" role="alert">
							Santri ini tidak memenuhi syarat liburan
						</div>
						<div id="alert-yes" class="alert alert-success d-none" role="alert">
							Syarat sudah terpenuhi dan boleh berlibur
						</div>
					</div>
					<div class="col-8">
						<div class="row" id="show-data">
						</div>
					</div>
				</div>
			<?php } ?>
		</section>
		<!-- /.content -->
	</div>

	<div class="modal fade" id="modal-data">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header py-2">
					<h6 class="modal-title">Data Check In Liburan</h6>
					<select id="changeDomicile" onchange="loadData()" style="width: 200px" class="form-control form-control-sm float-right mr-2">
						<option value="">.:Semua:.</option>
						<?php
						if ($rooms) {
							foreach ($rooms as $room) {
								?>
								<option value="<?= $room->name ?>"><?= $room->name ?></option>
						<?php
							}
						}
						?>
					</select>
					<form action="<?= base_url() ?>checkin/printOut" method="post" target="_blank" id="form-print">
						<input type="hidden" name="domicile" id="id-print" value="">
					</form>
					<button class="btn btn-sm btn-primary" onclick="printOut()">Print</button>
				</div>
				<div class="modal-body pt-0" style="max-height: 80vh; overflow-y: auto">
					<div class="row" id="load-data"></div>
				</div>
				<div class="modal-footer justify-content-end p-2"></div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('checkin/js-checkin'); ?>
