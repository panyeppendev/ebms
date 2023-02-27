<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
		<div class="row">
			<div class="col-3">
				<div class="card">
					<div class="card-body py-1">
						<i class="fas fa-info-circle"></i>
						Ta'zir Santri Indisipliner
					</div>
				</div>
			</div>
			<div class="col-7"></div>
			<div class="col-2">
				<a href="<?= base_url() ?>punishment" class="btn btn-primary btn-sm btn-block">
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
								<label class="col-sm-4 col-form-label">NO. KARTU</label>
								<div class="col-sm-8">
									<input data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" autofocus name="nis" id="nis" class="form-control">
								</div>
							</div>
						</div>
						<form id="form-save-punishment" autocomplete="off">
							<div class="col-12">
								<input type="hidden" id="nis-save" name="nis" value="0">
								<div class="form-group">
									<label for="reason">Bentuk pelanggaran</label>
									<select name="constitution" id="constitution" class="form-control select2bs4">
										<option value="">.:Pilih pelanggaran:.</option>
										<?php
											if ($constitutions) {
												$text = [
													'LOW' => 'Ringan',
													'MEDIUM' => 'Sedang',
													'HIGH' => 'Berat',
													'TOP' => 'Sangat berat'
												];
												foreach ($constitutions as $constitution) {
										?>
											<option value="<?= $constitution->id ?>"><?= $constitution->name.' - '.$text[$constitution->category] ?></option>
										<?php
												}
											}
										?>
									</select>
								</div>
							</div>
						</form>
						<div class="col-12">
							<div class="callout callout-danger">
								<h5>Catatan!</h5>
								<p>Untuk tindakan <b>TERLAMBAT KEMBALI IZIN</b> bukan melalui menu ini melainkan di menu Jarak Jauh/Dekat</p>
							</div>
						</div>
					</div>
					<div class="card-footer px-3">
						<button id="button-check" class="btn btn-primary btn-block" onclick="checkClicked()">
							Simpan
						</button>
					</div>
				</div>
			</div>
			<div class="col-7" id="show-data"></div>
		</div>
	</section>
	<!-- /.content -->
</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('punishment/js-punishment-add'); ?>
