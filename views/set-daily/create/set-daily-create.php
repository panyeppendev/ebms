<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content p-3">
		<div class="row justify-content-between">
			<div class="col-3">
				<h4 class="card-title mt-1">Pengaturan Pencairan</h4>
			</div>
			<div class="col-9">
				<div class="row justify-content-end">
					<div class="col-2">
						<a href="<?= base_url() ?>setdaily" class="btn btn-sm btn-default btn-block">
							<i class="fas fa-undo"></i>
							Kembali
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-5 justify-content-center">
			<div class="col-3">
				<button type="button" class="btn btn-outline-primary btn-block py-5" onclick="setAccount('account_pocket')">
					<i class="fa fa-wallet fa-3x"></i>
					<span class="d-block mt-3">Atur Rekening Uang Saku</span>
				</button>
			</div>
			<div class="col-3">
				<button type="button" class="btn btn-outline-primary btn-block py-5" onclick="setAccount('account_breakfast')">
					<i class="fas fa-coffee fa-3x"></i>
					<span class="d-block mt-3">Atur Rekening Sarapan</span>
				</button>
			</div>
			<div class="col-3">
				<button type="button" class="btn btn-outline-primary btn-block py-5" onclick="setAccount('account_dpu')">
					<i class="fas fa-utensils fa-3x"></i>
					<span class="d-block mt-3">Atur Rekening DPU</span>
				</button>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>

<div class="modal fade" id="modal-set">
	<div class="modal-dialog modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<h5>Konfirmasi</h5>
			</div>
			<div class="modal-body" id="show-detail">
				<div class="form-group">
					<input type="hidden" id="table" value="">
					<label for="account" class="font-weight-normal">Pilih Rekening</label>
					<select id="account" class="form-control">
						<option value="">:Pilih Rekening:</option>
						<?php
						if ($accounts) {
							foreach ($accounts as $account) {
								?>
								<option value="<?= $account->id ?>"><?= $account->name ?></option>
								<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary btn-block" onclick="saveSetAccount(this)">
					Simpan pengaturan
				</button>
			</div>
		</div>
	</div>
</div>


<?php $this->load->view('partials/footer'); ?>
<script src="<?= base_url('template') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('template') ?>/plugins/daterangepicker/daterangepicker.js"></script>
<?php $this->load->view('set-daily/create/js-set-daily-create'); ?>
