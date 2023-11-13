<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
		<div class="row justify-content-between mb-5">
			<div class="col-3">
				<h4 class="card-title mt-1">Pengaturan Pencairan</h4>
			</div>
			<div class="col-9">
				<div class="row justify-content-end">
					<div class="col-3">
						<a href="<?= base_url() ?>setdaily/reset" class="btn btn-sm btn-danger btn-block">
							<i class="fa fa-plus-circle"></i>
							Reset Cadangan
						</a>
					</div>
					<div class="col-3">
						<a href="<?= base_url() ?>setdaily/create" class="btn btn-sm btn-default btn-block">
							<i class="fa fa-plus-circle"></i>
							Atur Rekening Pencairan
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-5 justify-content-center">
			<div class="col-4">
				<?php if (!$setting) { ?>
					<button type="button" class="btn btn-outline-primary btn-block py-5" onclick="openDaily()">
						<i class="fas fa-unlock fa-3x"></i>
						<span class="d-block mt-3">Buka Transaksi Harian</span>
					</button>
				<?php } else { ?>
					<button type="button" class="btn btn-outline-danger btn-block py-5" onclick="closeDaily()">
						<i class="fas fa-lock fa-3x"></i>
						<span class="d-block mt-3">Tutup Transaksi Harian</span>
					</button>
				<?php } ?>
			</div>
		</div>
		<div class="row mt-5 justify-content-center">
			<div class="col-6">
				<h6 class="text-danger font-italic text-center">
					Buka tutup transaksi harian hanya dilakukan sekali dalam satu hari. <br>
					Mohon hati-hati dan teliti sebelum menyimpan pengaturan!
				</h6>
			</div>
		</div>
    </section>
    <!-- /.content -->
</div>

<div class="modal fade" id="modal-open">
	<div class="modal-dialog modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<h5>Konfirmasi</h5>
			</div>
			<div class="modal-body" id="show-detail">
				<div class="form-group">
					<label style="user-select: none;" for="text-confirm" class="font-weight-normal">Ketik <span class="text-primary">"<?= $open ?>"</span></label>
					<input type="text" class="form-control" id="text-confirm" autocomplete="off">
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary btn-block" onclick="saveOpenDaily(this)">
					Buka Transaksi Hari Ini
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-close">
	<div class="modal-dialog modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<h5>Konfirmasi</h5>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label style="user-select: none;" for="text-confirm" class="font-weight-normal">Ketik <span class="text-primary">"<?= $close ?>"</span></label>
					<input type="text" class="form-control" id="text-confirm-close" autocomplete="off">
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary btn-block" onclick="saveCloseDaily(this)">
					Tutup Transaksi Hari Ini
				</button>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('partials/footer'); ?>
<script src="<?= base_url('template') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('template') ?>/plugins/daterangepicker/daterangepicker.js"></script>
<?php $this->load->view('set-daily/js-set-daily'); ?>
