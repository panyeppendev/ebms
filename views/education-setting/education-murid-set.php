<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
	<form id="form-murid-set" method="post" action="<?= base_url() ?>educationsetting/setmurid">
		<input type="hidden" name="level" id="level-murid" value="<?= $level ?>">
		<input type="hidden" name="kelas" id="kelas-murid" value="<?= $kelas_filter ?>">
	</form>
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
													<option <?= ($item == $kelas_filter) ? 'selected' : '' ?> value="<?= $item ?>"><?= $item ?></option>
													<?php
												}
											}
											?>
										</select>
									</div>
									<div class="col-3">
										<select class="form-control form-control-sm" id="rombel-murid" onchange="loadRombel()">
											<option value="">.:Pilih Rombel:.</option>
											<?php
											if ($rombel) {
												foreach ($rombel as $item) {
													?>
													<option value="<?= $item->rombel ?>"><?= rombel($item->rombel) ?></option>
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
			<div class="col-12">
				<div class="card overflow-auto" style="height: 75vh">
					<div class="card-body" id="show-data-set">
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>

<div class="modal fade" id="modal-set" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header py-2">
				<h6 class="modal-title">Form Atur Murid Kelas - [<span id="count-checked"></span>]</h6>
			</div>
			<div class="modal-body overflow-auto" style="height: 80vh; ">
				<form autocomplete="off" id="form-save-set-murid">
					<input type="hidden" name="rombel" id="rombel_changed" value="">
					<div id="show-add"></div>
				</form>
			</div>
			<div class="modal-footer justify-content-between p-2">
				<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-primary btn-sm" id="save-set-murid">Simpan</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('education-setting/js-education-murid'); ?>

