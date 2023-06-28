<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content p-3">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body py-3">
						<div class="row justify-content-end">
							<div class="col-2">
								<select id="change-level" class="form-control form-control-sm">
									<option value="">.:Pilih Tingkat:.</option>
									<option <?= ($level === 'I\'dadiyah') ? 'selected' : '' ?> value="I'dadiyah">I'dadiyah</option>
									<option <?= ($level === 'Ula') ? 'selected' : '' ?> value="Ula">Ula</option>
									<option <?= ($level === 'Wustho') ? 'selected' : '' ?> value="Wustho">Wustho</option>
									<option <?= ($level === 'Ulya') ? 'selected' : '' ?> value="Ulya">Ulya</option>
								</select>
							</div>
							<div class="col-2">
								<select id="change-grade" class="form-control form-control-sm">
									<option value="">.:Pilih Kelas:.</option>
									<?php
									if ($grades) {
										foreach ($grades as $item) {
											?>
											<option <?= ($item === $grade) ? 'selected' : '' ?> value="<?= $item ?>"><?= $item ?></option>
											<?php
										}
									}
									?>
								</select>
							</div>
							<div class="col-2">
								<select id="change-rombel" class="form-control form-control-sm" onchange="loadData()">
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
							<form id="form-filter" method="post" action="<?= base_url() ?>presence">
								<input type="hidden" name="level" id="level" value="<?= $level ?>">
								<input type="hidden" name="grade" id="grade" value="<?= $grade ?>">
							</form>
							<div class="col-2">
								<button type="button" class="btn btn-sm btn-primary btn-block" onclick="set()">
									Atur
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body overflow-auto" style="height: 75vh;" id="show-index">

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
				<h6 class="modal-title">Form Tambah Absensi</h6>
			</div>
			<div class="modal-body">
				<form autocomplete="off" id="form-set-save">
					<div class="row justify-content-end mb-3">
						<div class="col-2">
							<select name="month" id="month" class="form-control form-control-sm">
								<option value="">.:Pilih Bulan:.</option>
								<option value="10">Syawal</option>
								<option value="11">Dzul Qo'dah</option>
								<option value="12">Dzul Hijjah</option>
								<option value="1">Muharram</option>
								<option value="2">Shafar</option>
								<option value="3">Rabiul Awal</option>
								<option value="4">Rabiul Tsani</option>
								<option value="5">Jumadal Ula</option>
								<option value="6">Jumadal Tsaniyah</option>
								<option value="7">Rajab</option>
								<option value="8">Sya'ban</option>
							</select>
						</div>
					</div>
					<input type="hidden" name="level" id="level-set" value="<?= $level ?>">
					<input type="hidden" name="grade" id="kelas-set" value="<?= $grade ?>">
					<input type="hidden" name="rombel" id="rombel-set" value="">
					<div id="show-add"></div>
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
<?php $this->load->view('presence/js-presence'); ?>
