<?php $this->load->view('partials/header'); ?>
	<div class="content-wrapper">
		<!-- Main content -->
		<section class="content p-3">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body py-3">
							<div class="row">
								<div class="col-4">
									<h6 class="mb-0 mt-1">Atur Murid Kelas</h6>
								</div>
								<div class="col-8">
									<div class="row justify-content-end">
										<div class="col-3">
											<select onchange="setKelas()" id="kelas" class="form-control form-control-sm">
												<option value="">.:Pilih Kelas:.</option>
												<?php
												if ($kelas) {
													foreach ($kelas as $item) {
														?>
														<option value="<?= $item ?>"><?= $item ?></option>
														<?php
													}
												}
												?>
											</select>
										</div>
										<div class="col-3">
											<button class="btn btn-sm btn-default btn-block" onclick="set()">
												Atur
											</button>
										</div>
										<div class="col-2">
											<a href="<?= base_url() ?>educationsetting" class="btn btn-sm btn-primary btn-block">
												Kembali
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12" id="show-data-set"></div>
			</div>
		</section>
		<!-- /.content -->
	</div>

	<div class="modal fade" id="modal-set" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-default">
			<div class="modal-content">
				<div class="modal-header py-2">
					<h6 class="modal-title">Form Atur Murid Kelas</h6>
				</div>
				<div class="modal-body">
					<form autocomplete="off" id="form-set-save">
						<input type="hidden" name="kelas" id="kelas-set" value="">
						<input type="hidden" name="level" id="level-set" value="<?= $level ?>">
						<div class="form-group row">
							<label for="name" class="col-sm-3 col-form-label">Rombel</label>
							<div class="col-sm-3">
								<input type="number" min="1" id="rombel" name="rombel" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label for="head" class="col-sm-3 col-form-label">Nama</label>
							<div class="col-sm-8">
								<input type="text" class="form-control text-uppercase" name="head" id="head">
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer justify-content-between p-2">
					<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Tutup</button>
					<button type="button" class="btn btn-primary btn-sm" id="save-set">Simpan</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('education-setting/js-education-set'); ?>
